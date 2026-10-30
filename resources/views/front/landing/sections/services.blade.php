{{-- Related services for this facility type (SEO brief "alphahmc-facility-types" B4, 30 Oct 2026): picked per page in
     the admin from indexable pages only; a page that is unpublished or deleted later drops out by itself. --}}
@php
    $pageTargets = \App\Models\FacilityTypeCard::targets();
    $serviceLinks = collect($d['items'])->filter(fn ($i) => isset($pageTargets[$i['target']]))
        ->map(fn ($i) => ['url' => $pageTargets[$i['target']]['url'],
                          'label' => $i['label'] !== '' ? $i['label'] : preg_replace('/^\w+: /', '', $pageTargets[$i['target']]['label'])]);
@endphp
@if($serviceLinks->isNotEmpty())
<section class="lp-section">
    <div class="lp-wrap lp-others">
        <div>
            <h2>{{ $d['heading'] }}</h2>
            @if($d['text'] !== '')<p class="lp-lede lp-mt">{{ $d['text'] }}</p>@endif
        </div>
        <ul class="lp-others-pills">
            @foreach($serviceLinks as $link)
                <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
            @endforeach
        </ul>
    </div>
</section>
@endif
