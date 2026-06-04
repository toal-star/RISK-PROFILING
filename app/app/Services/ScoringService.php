<?php

namespace App\Services;

use App\Models\Retailer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ScoringService
{
    /** @var array<string, int> */
    private const STORE_TYPE_POINTS = [
        'Convenience Store' => 22,
        'Grocery Store' => 16,
        'Specialty Store' => 13,
        'Other' => 10,
    ];

    /** @var array<string, int> */
    private const POVERTY_TIER_POINTS = [
        'Very High (>30%)' => 17,
        'High (21-30%)' => 13,
        'Moderate (11-20%)' => 7,
        'Low (0-10%)' => 2,
    ];

    /** @var array<string, int> */
    private const CHURN_TIER_POINTS = [
        'Very High' => 28,
        'High' => 20,
        'Moderate' => 11,
        'Low' => 4,
        'None' => 0,
    ];

    /** @var array<string, int> */
    private const INCOME_BRACKET_POINTS = [
        'Under $30k' => 15,
        '$30k-$50k' => 10,
        '$50k-$75k' => 5,
        '$75k-$100k' => 2,
        'Over $100k' => 0,
    ];

    private Collection $zipData;

    private Collection $churnData;

    /** @var array<string, int> */
    private array $disqualifiedCounts;

    public function scoreAll(): void
    {
        $this->loadLookupData();

        Retailer::query()->chunkById(100, function (Collection $retailers): void {
            $updates = $retailers->map(fn (Retailer $retailer) => [
                'id' => $retailer->id,
                'risk_score' => $score = $this->calculateScore($retailer),
                'risk_tier' => $this->scoreTier($score),
            ]);

            foreach ($updates as $update) {
                DB::table('retailers')
                    ->where('id', $update['id'])
                    ->update([
                        'risk_score' => $update['risk_score'],
                        'risk_tier' => $update['risk_tier'],
                    ]);
            }
        });
    }

    private function loadLookupData(): void
    {
        $this->zipData = DB::table('zip_code_data')
            ->select('zip_code', 'poverty_tier', 'income_bracket')
            ->get()
            ->keyBy('zip_code');

        // Deduplicate address_churn keeping highest total_auth_count per normalized address + zip_code.
        $this->churnData = DB::table('address_churn')
            ->select('street_address', 'zip_code', 'churn_tier', 'total_auth_count')
            ->get()
            ->map(function (object $row): object {
                $row->normalized_address = $this->normalizeAddress($row->street_address);

                return $row;
            })
            ->groupBy(fn (object $row) => $row->normalized_address.'|'.$row->zip_code)
            ->map(fn (Collection $group) => $group->sortByDesc('total_auth_count')->first())
            ->keyBy(fn (object $row) => $row->normalized_address.'|'.$row->zip_code);

        $this->disqualifiedCounts = DB::table('disqualified_retailers')
            ->select('zip_code', DB::raw('COUNT(*) as cnt'))
            ->groupBy('zip_code')
            ->pluck('cnt', 'zip_code')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    private function calculateScore(Retailer $retailer): int
    {
        return $this->storeTypePoints($retailer->store_type)
            + $this->povertyTierPoints($retailer->zip_code)
            + $this->churnTierPoints($retailer->street_address, $retailer->zip_code)
            + $this->disqualifiedZipPoints($retailer->zip_code)
            + $this->incomeBracketPoints($retailer->zip_code);
    }

    private function storeTypePoints(string $storeType): int
    {
        return self::STORE_TYPE_POINTS[$storeType] ?? 4;
    }

    private function povertyTierPoints(string $zipCode): int
    {
        $tier = $this->zipData->get($zipCode)?->poverty_tier;

        return self::POVERTY_TIER_POINTS[$tier] ?? 2;
    }

    private function churnTierPoints(string $streetAddress, string $zipCode): int
    {
        $key = $this->normalizeAddress($streetAddress).'|'.$zipCode;
        $tier = $this->churnData->get($key)?->churn_tier;

        return self::CHURN_TIER_POINTS[$tier] ?? 0;
    }

    private function disqualifiedZipPoints(string $zipCode): int
    {
        $count = $this->disqualifiedCounts[$zipCode] ?? 0;

        return match (true) {
            $count >= 5 => 18,
            $count >= 3 => 12,
            $count >= 1 => 6,
            default => 0,
        };
    }

    private function incomeBracketPoints(string $zipCode): int
    {
        $bracket = $this->zipData->get($zipCode)?->income_bracket;

        return self::INCOME_BRACKET_POINTS[$bracket] ?? 5;
    }

    private function scoreTier(int $score): string
    {
        return match (true) {
            $score >= 76 => 'High',
            $score >= 56 => 'Elevated',
            $score >= 31 => 'Moderate',
            default => 'Low',
        };
    }

    private function normalizeAddress(string $address): string
    {
        $address = strtoupper(trim($address));
        $address = rtrim($address, '.,;');

        $replacements = [
            '/\bAVENUE\b/' => 'AVE',
            '/\bSTREET\b/' => 'ST',
            '/\bBOULEVARD\b/' => 'BLVD',
            '/\bROAD\b/' => 'RD',
            '/\bPLACE\b/' => 'PL',
            '/\bDRIVE\b/' => 'DR',
        ];

        return preg_replace(array_keys($replacements), array_values($replacements), $address);
    }
}
