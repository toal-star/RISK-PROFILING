@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- Mobile: prev / next only --}}
        <div class="flex items-center justify-between gap-2 sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex cursor-not-allowed items-center rounded-md border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium leading-5 text-slate-500">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center rounded-md border border-slate-600 bg-slate-800 px-4 py-2 text-sm font-medium leading-5 text-slate-300 transition duration-150 ease-in-out hover:bg-slate-700 hover:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500 active:bg-slate-700">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center rounded-md border border-slate-600 bg-slate-800 px-4 py-2 text-sm font-medium leading-5 text-slate-300 transition duration-150 ease-in-out hover:bg-slate-700 hover:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500 active:bg-slate-700">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex cursor-not-allowed items-center rounded-md border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium leading-5 text-slate-500">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        {{-- Desktop: full page list --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between sm:gap-2">

            <div>
                <p class="text-sm leading-5 text-slate-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-slate-200">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-semibold text-slate-200">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-semibold text-slate-200">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex rtl:flex-row-reverse rounded-md shadow-sm">

                    {{-- Previous page --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex cursor-not-allowed items-center rounded-l-md border border-slate-700 bg-slate-800 px-2 py-2 text-sm font-medium leading-5 text-slate-500" aria-hidden="true">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           class="-mr-px inline-flex items-center rounded-l-md border border-slate-600 bg-slate-800 px-2 py-2 text-sm font-medium leading-5 text-slate-400 transition duration-150 ease-in-out hover:bg-slate-700 hover:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 active:bg-slate-700"
                           aria-label="{{ __('pagination.previous') }}">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach ($elements as $element)

                        {{-- "…" separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="-ml-px inline-flex cursor-default items-center border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium leading-5 text-slate-500">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Page links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="-ml-px inline-flex cursor-default items-center border border-blue-700 bg-blue-900/50 px-4 py-2 text-sm font-semibold leading-5 text-blue-300">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       class="-ml-px inline-flex items-center border border-slate-600 bg-slate-800 px-4 py-2 text-sm font-medium leading-5 text-slate-300 transition duration-150 ease-in-out hover:bg-slate-700 hover:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500 active:bg-slate-700"
                                       aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif

                    @endforeach

                    {{-- Next page --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                           class="-ml-px inline-flex items-center rounded-r-md border border-slate-600 bg-slate-800 px-2 py-2 text-sm font-medium leading-5 text-slate-400 transition duration-150 ease-in-out hover:bg-slate-700 hover:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 active:bg-slate-700"
                           aria-label="{{ __('pagination.next') }}">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="-ml-px inline-flex cursor-not-allowed items-center rounded-r-md border border-slate-700 bg-slate-800 px-2 py-2 text-sm font-medium leading-5 text-slate-500" aria-hidden="true">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif

                </span>
            </div>

        </div>
    </nav>
@endif
