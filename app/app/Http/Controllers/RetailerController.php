<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RetailerController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $borough = $request->string('borough')->trim()->value();
        $storeType = $request->string('store_type')->trim()->value();

        $retailers = Retailer::query()
            ->when($search, fn ($q) => $q->where('store_name', 'like', "%{$search}%"))
            ->when($borough, fn ($q) => $q->where('borough', $borough))
            ->when($storeType, fn ($q) => $q->where('store_type', $storeType))
            ->orderBy('store_name')
            ->paginate(50)
            ->withQueryString();

        return view('retailers.index', [
            'retailers' => $retailers,
            'boroughs' => ['Bronx', 'Brooklyn', 'Manhattan', 'Queens', 'Staten Island'],
            'storeTypes' => ['Convenience Store', 'Grocery Store', 'Other', 'Specialty Store'],
            'search' => $search,
            'borough' => $borough,
            'storeType' => $storeType,
        ]);
    }
}
