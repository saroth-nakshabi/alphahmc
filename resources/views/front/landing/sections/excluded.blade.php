{{-- What is not in our fee. --}}
<section class="lp-section">
    <div class="lp-wrap lp-excl">
        <div><h2>{{ $d['heading'] }}</h2>@if($d['text'] !== '')<p class="lp-lede lp-mt">{{ $d['text'] }}</p>@endif</div>
        <ul>@foreach($d['items'] as $item)<li>{{ $item['text'] }}</li>@endforeach</ul>
    </div>
</section>
