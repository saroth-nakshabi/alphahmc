@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between mt-5">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination d-flex justify-content-between w-100">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="disabled" aria-disabled="true">
                        <a href="javascript:void(0);" class="w-auto px-4">@lang('pagination.previous')</a>
                    </li>
                @else
                    <li class="">
                        <a class="w-auto px-4" href="{{ $paginator->previousPageUrl() }}"
                            rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="">
                        <a class="w-auto px-4" href="{{ $paginator->nextPageUrl() }}"
                            rel="next">@lang('pagination.next')</a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true">
                        <a href="javascript:void(0);" class="w-auto px-4">@lang('pagination.next')</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small text-muted">
                    {!! __('Showing') !!}
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <a href="javascript:void(0);" class="" aria-hidden="true"><i
                                    class="fa-solid fa-arrow-left"></i></a>
                        </li>
                    @else
                        <li class="">
                            <a class="" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                                aria-label="@lang('pagination.previous')"><i class="fa-solid fa-arrow-left"></i></a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="disabled" aria-disabled="true"><a href="javascript:void(0);"
                                    class="">{{ $element }}</a>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active" aria-current="page"><a href="javascript:void(0);"
                                            class="">{{ $page }}</a></li>
                                @else
                                    <li class=""><a class=""
                                            href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="">
                            <a class="" href="{{ $paginator->nextPageUrl() }}" rel="next"
                                aria-label="@lang('pagination.next')"><i class="fa-solid fa-arrow-right"></i></a>
                        </li>
                    @else
                        <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <a href="javascript:void(0);" class="" aria-hidden="true"><i
                                    class="fa-solid fa-arrow-right"></i></a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
