@if ($paginator->hasPages())
    <nav aria-label="Arrow pagination only">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link border border-danger" aria-hidden="true">
                        <i class="fas fa-chevron-left text-black"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link border border-danger" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="@lang('pagination.previous')">
                        <i class="fas fa-chevron-left text-black"></i>
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link border border-danger" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="@lang('pagination.next')">
                        <i class="fas fa-chevron-right text-black"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link border border-danger" aria-hidden="true">
                        <i class="fas fa-chevron-right text-black"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
