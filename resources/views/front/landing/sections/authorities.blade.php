{{-- Authorities strip under the hero. --}}
<div class="lp-hero-foot">
    <div class="lp-wrap">
        <div class="lp-authorities">
            @if($d['label'] !== '')<span>{{ $d['label'] }}</span>@endif
            @foreach($d['items'] as $a)<strong>{{ $a['name'] }}</strong>@endforeach
        </div>
    </div>
</div>
