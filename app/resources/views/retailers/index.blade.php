<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BenefitGuard NYC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">

    {{-- ── Header ─────────────────────────────────────────────────── --}}
    <header class="border-b border-slate-800 bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold tracking-tight text-white">BenefitGuard NYC</h1>
                    <p class="text-xs text-slate-400">SNAP Retailer Fraud Risk Intelligence &mdash; New York City</p>
                </div>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- ── Stats bar ───────────────────────────────────────────── --}}
        <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-5">
            <div class="rounded-lg border border-slate-700 bg-slate-800/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Stores</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ number_format($totalCount) }}</p>
            </div>
            <div class="rounded-lg border border-red-900/60 bg-red-950/30 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-red-400">High Risk</p>
                <p class="mt-1 text-2xl font-bold text-red-300">{{ number_format($highCount) }}</p>
            </div>
            <div class="rounded-lg border border-orange-900/60 bg-orange-950/30 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-orange-400">Elevated</p>
                <p class="mt-1 text-2xl font-bold text-orange-300">{{ number_format($elevatedCount) }}</p>
            </div>
            <div class="rounded-lg border border-yellow-900/60 bg-yellow-950/30 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-yellow-400">Moderate</p>
                <p class="mt-1 text-2xl font-bold text-yellow-300">{{ number_format($moderateCount) }}</p>
            </div>
            <div class="rounded-lg border border-green-900/60 bg-green-950/30 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-green-400">Low</p>
                <p class="mt-1 text-2xl font-bold text-green-300">{{ number_format($lowCount) }}</p>
            </div>
        </div>

        {{-- ── Filters ─────────────────────────────────────────────── --}}
        <form method="GET" action="{{ route('retailers.index') }}"
              class="mb-4 rounded-lg border border-slate-700 bg-slate-800/60 p-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs font-medium text-slate-400">Store Name</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search store name…"
                        class="w-full rounded-md border border-slate-600 bg-slate-700 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-400">Borough</label>
                    <select name="borough"
                        class="w-full rounded-md border border-slate-600 bg-slate-700 px-3 py-2 text-sm text-slate-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">All boroughs</option>
                        @foreach ($boroughs as $b)
                            <option value="{{ $b }}" @selected($borough === $b)>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-400">Store Type</label>
                    <select name="store_type"
                        class="w-full rounded-md border border-slate-600 bg-slate-700 px-3 py-2 text-sm text-slate-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">All types</option>
                        @foreach ($storeTypes as $type)
                            <option value="{{ $type }}" @selected($storeType === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-400">Risk Tier</label>
                    <select name="risk_tier"
                        class="w-full rounded-md border border-slate-600 bg-slate-700 px-3 py-2 text-sm text-slate-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">All tiers</option>
                        @foreach ($riskTiers as $tier)
                            <option value="{{ $tier }}" @selected($riskTier === $tier)>{{ $tier }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-400">Zip Code</label>
                    <input type="text" name="zip_code" value="{{ $zipCode }}" placeholder="e.g. 10001"
                        class="w-full rounded-md border border-slate-600 bg-slate-700 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

            </div>

            <div class="mt-3 flex items-center gap-2">
                <button type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-800">
                    Apply Filters
                </button>
                @if ($search || $borough || $storeType || $riskTier || $zipCode)
                    <a href="{{ route('retailers.index') }}"
                        class="rounded-md border border-slate-600 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">
                        Clear All
                    </a>
                @endif
            </div>
        </form>

        {{-- ── Result count ─────────────────────────────────────────── --}}
        <div class="mb-3 flex items-center justify-between">
            <p class="text-sm text-slate-400">
                <span class="font-semibold text-slate-200">{{ number_format($retailers->total()) }}</span>
                {{ Str::plural('retailer', $retailers->total()) }}
                @if ($search || $borough || $storeType || $riskTier || $zipCode)
                    <span class="text-slate-500">matching current filters</span>
                @endif
            </p>
            <p class="text-xs text-slate-500">Sorted by highest risk score</p>
        </div>

        {{-- ── Table ───────────────────────────────────────────────── --}}
        <div class="overflow-x-auto rounded-lg border border-slate-700">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-700 bg-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Store Name</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Borough</th>
                        <th class="px-4 py-3">Zip</th>
                        <th class="px-4 py-3 text-right">Score</th>
                        <th class="px-4 py-3">Risk Tier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 bg-slate-900">
                    @forelse ($retailers as $retailer)
                        <tr class="transition-colors hover:bg-slate-800/50">
                            <td class="px-4 py-3 font-medium text-slate-100">
<a href="{{ route('retailers.show', $retailer->id) }}" class=hover:text-blue-400 transition-colors">
{{ $retailer->store_name }} 
</a> 
</td> 
                            <td class="px-4 py-3 text-slate-400">{{ $retailer->store_type }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $retailer->borough }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $retailer->zip_code }}</td>
                            <td class="px-4 py-3 text-right font-mono font-semibold text-slate-200">{{ $retailer->risk_score }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match ($retailer->risk_tier) {
                                        'High'     => 'border border-red-800 bg-red-950 text-red-400',
                                        'Elevated' => 'border border-orange-800 bg-orange-950 text-orange-400',
                                        'Moderate' => 'border border-yellow-800 bg-yellow-950 text-yellow-400',
                                        'Low'      => 'border border-green-800 bg-green-950 text-green-400',
                                        default    => 'border border-slate-600 bg-slate-700 text-slate-400',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge }}">
                                    {{ $retailer->risk_tier }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                No retailers match the current filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ───────────────────────────────────────────── --}}
        @if ($retailers->hasPages())
            <div class="mt-4">
                {{ $retailers->links() }}
            </div>
        @endif

    </div>

</body>
</html>
