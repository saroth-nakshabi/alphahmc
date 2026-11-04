{{-- Other facility types: a pill for every other published landing page (Saroth, 30 Oct 2026). Replaces the
     "What is not in our fee" section; hidden when there is no other published page. --}}
@php
    $chosenLinks = $d['links'] ?? [];
    $otherPages = $chosenLinks ? collect() : \App\Models\LandingPage::published()->locale($page->lang())->where('id', '!=', $page->id)
        ->orderBy('id')->get(['id', 'slug', 'locale', 'name', 'facility_type', 'emirate']);
@endphp
@if($chosenLinks || $otherPages->isNotEmpty())
<section class="lp-section">
    <div class="lp-wrap lp-others">
        <div>
            <h2>{{ $d['heading'] }}</h2>
            @if($d['text'] !== '')<p class="lp-lede lp-mt">{{ $d['text'] }}</p>@endif
        </div>
        <ul class="lp-others-pills">
            @foreach($chosenLinks as $link)
                <li><a href="{{ url($link['url']) }}">{{ $link['label'] }}</a></li>
            @endforeach
            @foreach($otherPages as $other)
                <li><a href="{{ $other->url() }}">{{ $other->facility_type ? $other->facility_type . ', ' . $other->emirate : $other->name }}</a></li>
            @endforeach
        </ul>
    </div>
</section>
@endif
