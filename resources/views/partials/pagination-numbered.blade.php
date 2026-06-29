{{-- Always-numbered Bootstrap 5 pagination (numbers + prev/next), no responsive hiding.
     Laravel passes $paginator and $elements when this view is used via ->links('partials.pagination-numbered'). --}}
@if ($paginator->hasPages())
<nav aria-label="Pagination">
    <ul class="pagination justify-content-center flex-wrap mb-0">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">&laquo; Prev</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Prev</a></li>
        @endif

        {{-- Page numbers (with "..." separators) --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &raquo;</a></li>
        @else
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">Next &raquo;</span></li>
        @endif
    </ul>
</nav>
@endif
