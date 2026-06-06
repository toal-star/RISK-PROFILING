<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class RetailerController extends Controller
{
    public function show(int $id): View
    {
        $retailer = Retailer::findOrFail($id);

        $zipData = DB::table('zip_code_data')
            ->where('zip_code', $retailer->zip_code)
            ->first();

        $addressChurn = DB::table('address_churn')
            ->where('street_address', $retailer->street_address)
            ->where('zip_code', $retailer->zip_code)
            ->first();

        $disqualifiedCount = DB::table('disqualified_retailers')
            ->where('zip_code', $retailer->zip_code)
            ->count();

        $povertyTier = $zipData?->poverty_tier ?? 'unknown';
        $incomeBracket = $zipData?->income_bracket ?? 'unknown';
        $churnTier = $addressChurn?->churn_tier ?? 'unknown';
        $totalAuthCount = $addressChurn?->total_auth_count ?? 'unknown';

        $prompt = <<<PROMPT
        You are a risk analyst writing for a nonprofit researcher investigating SNAP retailer fraud in New York City. Based on the following signals for a specific retailer, write a 3-4 sentence plain English explanation of its fraud risk profile. Be factual and specific; do not use bullet points or jargon.

        Retailer: {$retailer->store_name} ({$retailer->store_type})
        Location: {$retailer->street_address}, {$retailer->borough}, NY {$retailer->zip_code}
        Risk Score: {$retailer->risk_score}/100 - {$retailer->risk_tier} Risk

        Neighborhood (ZIP {$retailer->zip_code}):
        - Poverty Tier: {$povertyTier}
        - Income Bracket: {$incomeBracket}
        - Confirmed disqualified retailers in this ZIP: {$disqualifiedCount}

        Address History (20-year SNAP data):
        - Churn Tier: {$churnTier}
        - Total SNAP authorizations at this address: {$totalAuthCount}
        PROMPT;

        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => 'claude-sonnet-4-6',
            'max_tokens' => 500,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $explanation = $response->successful()
            ? ($response->json('content.0.text') ?? 'Risk explanation unavailable.')
            : 'Risk explanation unavailable.';

        return view('retailers.show', [
            'retailer' => $retailer,
            'zipData' => $zipData,
            'addressChurn' => $addressChurn,
            'disqualifiedCount' => $disqualifiedCount,
            'explanation' => $explanation,
        ]);
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $borough = $request->string('borough')->trim()->value();
        $storeType = $request->string('store_type')->trim()->value();
        $riskTier = $request->string('risk_tier')->trim()->value();
        $zipCode = $request->string('zip_code')->trim()->value();

        $retailers = Retailer::query()
            ->when($search, fn ($q) => $q->where('store_name', 'like', "%{$search}%"))
            ->when($borough, fn ($q) => $q->where('borough', $borough))
            ->when($storeType, fn ($q) => $q->where('store_type', $storeType))
            ->when($riskTier, fn ($q) => $q->where('risk_tier', $riskTier))
            ->when($zipCode, fn ($q) => $q->where('zip_code', $zipCode))
            ->orderBy('risk_score', 'desc')
            ->paginate(50)
            ->withQueryString();

        $tierCounts = Retailer::query()
            ->selectRaw('risk_tier, count(*) as count')
            ->groupBy('risk_tier')
            ->pluck('count', 'risk_tier');

        return view('retailers.index', [
            'retailers' => $retailers,
            'boroughs' => ['Bronx', 'Brooklyn', 'Manhattan', 'Queens', 'Staten Island'],
            'storeTypes' => ['Convenience Store', 'Grocery Store', 'Other', 'Specialty Store'],
            'riskTiers' => ['High', 'Elevated', 'Moderate', 'Low'],
            'search' => $search,
            'borough' => $borough,
            'storeType' => $storeType,
            'riskTier' => $riskTier,
            'zipCode' => $zipCode,
            'totalCount' => $tierCounts->sum(),
            'highCount' => $tierCounts->get('High', 0),
            'elevatedCount' => $tierCounts->get('Elevated', 0),
            'moderateCount' => $tierCounts->get('Moderate', 0),
            'lowCount' => $tierCounts->get('Low', 0),
        ]);
    }
}
