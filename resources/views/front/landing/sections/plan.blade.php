{{-- Project plan: phase bars drawn to scale on a shared grid (no week numbers, by design). --}}
@php
    $phases = $d['phases'];
    $cols = max(1, ...array_map(fn ($p) => $p['end'], $phases ?: [['end' => 1]]));
@endphp
<section class="lp-section lp-plan" id="plan">
    <div class="lp-wrap">
        <div class="lp-split-head">
            <h2>{{ $d['heading'] }}</h2>
            @if($d['text'] !== '')<p class="lp-lede">{{ $d['text'] }}</p>@endif
        </div>
        @if(count($phases))
        <div class="lp-gantt" id="lp-gantt" style="--cols: {{ $cols }}">
            @foreach($phases as $i => $phase)
            <button class="lp-phase" type="button" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                    data-label="Phase {{ $i + 1 }}" data-title="{{ $phase['title'] }}" data-text="{{ $phase['text'] }}">
                <span class="lp-phase-name"><span class="lp-n">{{ $i + 1 }}</span><span class="lp-t">{{ $phase['title'] }}</span></span>
                <span class="lp-bar" style="--s: {{ $phase['start'] + 1 }}; --e: {{ $phase['end'] + 1 }}"></span>
            </button>
            @endforeach
        </div>
        <div class="lp-detail" aria-live="polite">
            <div class="lp-wk" id="lp-d-wk">Phase 1</div>
            <div><h3 id="lp-d-t">{{ $phases[0]['title'] }}</h3><p id="lp-d-p">{{ $phases[0]['text'] }}</p></div>
        </div>
        @endif
        @if($d['foot_note'] !== '' || $d['foot_link'] !== '')
        <div class="lp-plan-foot"><span>{{ $d['foot_note'] }}</span>@if($d['foot_link'] !== '')<a href="#contact">{{ $d['foot_link'] }}</a>@endif</div>
        @endif
    </div>
</section>
