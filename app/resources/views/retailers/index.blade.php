<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NYC SNAP Retailers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">NYC SNAP Retailers</h1>
            <p class="mt-1 text-sm text-gray-500">Authorized SNAP retailers in New York City</p>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('retailers.index') }}" class="mb-4 flex flex-wrap items-end gap-3">

            <div class="flex-1 min-w-48">
                <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Search store name</label>
                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="e.g. Associated…"
                    class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-200"
                >
            </div>

            <div>
                <label for="borough" class="block text-xs font-medium text-gray-600 mb-1">Borough</label>
                <select
                    id="borough"
                    name="borough"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-200"
                >
                    <option value="">All boroughs</option>
                    @foreach ($boroughs as $b)
                        <option value="{{ $b }}" @selected($borough === $b)>{{ $b }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="store_type" class="block text-xs font-medium text-gray-600 mb-1">Store type</label>
                <select
                    id="store_type"
                    name="store_type"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-200"
                >
                    <option value="">All types</option>
                    @foreach ($storeTypes as $type)
                        <option value="{{ $type }}" @selected($storeType === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 active:bg-gray-900"
            >
                Filter
            </button>

            @if ($search || $borough || $storeType)
                <a
                    href="{{ route('retailers.index') }}"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-600 hover:bg-gray-50"
                >
                    Clear
                </a>
            @endif

        </form>

        {{-- Result count --}}
        <p class="mb-3 text-sm text-gray-500">
            Showing <span class="font-medium text-gray-800">{{ number_format($retailers->total()) }}</span>
            {{ Str::plural('retailer', $retailers->total()) }}
            @if ($search || $borough || $storeType)
                matching current filters
            @endif
        </p>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Store Name</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Address</th>
                        <th class="px-4 py-3">Borough</th>
                        <th class="px-4 py-3">Zip</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($retailers as $retailer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $retailer->store_name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $retailer->store_type }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $retailer->street_address }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $retailer->borough }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $retailer->zip_code }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-400">No retailers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($retailers->hasPages())
            <div class="mt-4">
                {{ $retailers->links() }}
            </div>
        @endif

    </div>

</body>
</html>
