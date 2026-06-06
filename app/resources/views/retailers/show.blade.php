<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $retailer->store_name }} — BenefitGuard NYC</title>
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

        {{-- ── Back link ───────────────────────────────────────────── --}}
        <a href="{{ route('retailers.index') }}"
           class="mb-6 inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
            </svg>
            Back to all retailers
        </a>

        {{-- ── Store header ─────────────────────────────────────────── --}}
        <div class="mb-6 rounded-lg border border-slate-700 bg-slate-800/60 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $retailer->store_name }}</h2>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $retailer->store_type }} &middot; {{ $retailer->street_address }}, {{ $retailer->borough }}, NY {{ $retailer->zip_code }}
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Risk Score</p>
                        <p class="font-mono text-3xl font-bold text-white">{{ $retailer->risk_score }}</p>
                    </div>
                    @php
                        $badge = match ($retailer->risk_tier) {
                            'High'     => 'border border-red-800 bg-red-950 text-red-400',
                            'Elevated' => 'border border-orange-800 bg-orange-950 text-orange-400',
                            'Moderate' => 'border border-yellow-800 bg-yellow-950 text-yellow-400',
                            'Low'      => 'border border-green-800 bg-green-950 text-green-400',
                            default    => 'border border-slate-600 bg-slate-700 text-slate-400',
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $badge }}">
                        {{ $retailer->risk_tier }} Risk
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Signal cards ─────────────────────────────────────────── --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-lg border border-slate-700 bg-slate-800/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Poverty Tier</p>
                <p class="mt-2 text-lg font-bold text-slate-100">{{ $zipData?->poverty_tier ?? '—' }}</p>
                <p class="mt-1 text-xs text-slate-500">ZIP {{ $retailer->zip_code }}</p>
            </div>

            <div class="rounded-lg border border-slate-700 bg-slate-800/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Income Bracket</p>
                <p class="mt-2 text-lg font-bold text-slate-100">{{ $zipData?->income_bracket ?? '—' }}</p>
                <p class="mt-1 text-xs text-slate-500">Median household, ZIP {{ $retailer->zip_code }}</p>
            </div>

            <div class="rounded-lg border border-slate-700 bg-slate-800/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Address Churn</p>
                <p class="mt-2 text-lg font-bold text-slate-100">{{ $addressChurn?->churn_tier ?? '—' }}</p>
                <p class="mt-1 text-xs text-slate-500">
                    @if ($addressChurn)
                        {{ number_format($addressChurn->total_auth_count) }} auth events over 20 yrs
                    @else
                        No address history found
                    @endif
                </p>
            </div>

            <div class="rounded-lg border border-slate-700 bg-slate-800/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Disqualified in ZIP</p>
                <p class="mt-2 text-lg font-bold text-slate-100">{{ number_format($disqualifiedCount) }}</p>
                <p class="mt-1 text-xs text-slate-500">Confirmed trafficking stores nearby</p>
            </div>

        </div>

        {{-- ── AI explanation ───────────────────────────────────────── --}}
        <div class="rounded-lg border border-blue-900/50 bg-blue-950/20 p-6">
            <div class="mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-blue-400">
                    <path fill-rule="evenodd" d="M8.25 2.25A.75.75 0 0 1 9 3v.75h2.25V3a.75.75 0 0 1 1.5 0v.75H15V3a.75.75 0 0 1 1.5 0v.75h.75a3 3 0 0 1 3 3v.75H21A.75.75 0 0 1 21 9h-.75v2.25H21a.75.75 0 0 1 0 1.5h-.75V15H21a.75.75 0 0 1 0 1.5h-.75v.75a3 3 0 0 1-3 3h-.75V21a.75.75 0 0 1-1.5 0v-.75h-2.25V21a.75.75 0 0 1-1.5 0v-.75H9V21a.75.75 0 0 1-1.5 0v-.75h-.75a3 3 0 0 1-3-3v-.75H3A.75.75 0 0 1 3 15h.75v-2.25H3a.75.75 0 0 1 0-1.5h.75V9H3a.75.75 0 0 1 0-1.5h.75v-.75a3 3 0 0 1 3-3h.75V3a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                </svg>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-400">AI Risk Explanation</p>
                <span class="ml-auto text-xs text-slate-500">claude-sonnet-4-6</span>
            </div>
            <p class="leading-relaxed text-slate-200">{{ $explanation }}</p>
        </div>

    </div>

</body>
</html>
