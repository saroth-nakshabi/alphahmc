{{-- "Reviewed by" line (brief "alphahmc-reviewers-and-linking"): shows only when the page has a reviewer
     AND a review date. $page: Service, Category, ServiceGroup or Blog. $class: optional extra class. --}}
@php $pageReviewer = \App\Models\Reviewer::forPage($page ?? null); @endphp
@if($pageReviewer)
    <p class="ahg-reviewed-by {{ $class ?? '' }}">
        Reviewed by <a href="{{ $pageReviewer->url() }}">{{ $pageReviewer->name }}</a>, {{ $pageReviewer->title }}
        on <time datetime="{{ $page->reviewed_at->toDateString() }}">{{ $page->reviewed_at->format('j F Y') }}</time>
    </p>
@endif
@once
<style>
    .ahg-reviewed-by { margin: 10px 0 14px; font-size: .9rem; line-height: 1.5; opacity: .9; }
    .ahg-reviewed-by a { color: inherit; text-decoration: underline; text-underline-offset: 3px; }
    .ahg-reviewed-by-hero { color: inherit; }
</style>
@endonce
