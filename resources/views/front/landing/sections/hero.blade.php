{{-- Hero: breadcrumb, H1, intro, buttons, facility links (or a facilities list), photo band. --}}
<section class="lp-hero" id="hero">
    <div class="lp-wrap">
        @if($hero['crumb_parent'] !== '' || $hero['crumb_current'] !== '')
        <p class="lp-crumb">
            @if($hero['crumb_parent'] !== '')<span>{{ $hero['crumb_parent'] }}</span>@endif
            @if($hero['crumb_current'] !== '')<span><b>{{ $hero['crumb_current'] }}</b></span>@endif
        </p>
        @endif
        <h1>{!! $highlight($hero['h1_before'], $hero['h1_highlight'], $hero['h1_after']) !!}</h1>
        <div class="lp-hero-intro">
            <p class="lp-lede">{{ $hero['intro'] }}</p>
            <div class="lp-hero-cta" id="lp-hero-cta">
                <a class="lp-btn lp-btn-primary" href="{{ $waHref }}" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.4a.5.5 0 0 0 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .1-1.3c0-.1-.2-.2-.4-.3Z"/></svg>
                    {{ $hero['wa_button'] }}
                </a>
                <a class="lp-btn lp-btn-ghost" href="#contact">{{ $hero['proposal_button'] }}</a>
            </div>
        </div>

        @if($related->count() > 1)
            <div class="lp-switch">
                @if($hero['chips_label'] !== '')<p class="lp-switch-label" id="lp-switch-label">{{ $hero['chips_label'] }}</p>@endif
                <nav class="lp-chips" @if($hero['chips_label'] !== '') aria-labelledby="lp-switch-label" @else aria-label="Facility types" @endif>
                    @foreach($related as $other)
                        @if($other->id === $page->id)
                            <a class="lp-chip" href="{{ $other->url() }}" aria-current="page">{{ $other->facility_type ?: $other->name }}</a>
                        @else
                            <a class="lp-chip" href="{{ $other->url() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">{{ $other->facility_type ?: $other->name }}</a>
                        @endif
                    @endforeach
                </nav>
            </div>
        @elseif(count($hero['facilities']))
            <div class="lp-switch">
                @if($hero['list_label'] !== '')<p class="lp-switch-label">{{ $hero['list_label'] }}</p>@endif
                <ul class="lp-chips lp-chips-static">
                    @foreach($hero['facilities'] as $f)<li class="lp-chip">{{ $f['name'] }}</li>@endforeach
                </ul>
            </div>
        @endif

        <figure class="lp-band">
            <picture>
                @if($pic['phone']['webp'] !== '')<source type="image/webp" media="(max-width: 767px)" srcset="{{ $pic['phone']['webp'] }}" sizes="100vw">@endif
                @if($pic['phone']['img'] !== '')<source media="(max-width: 767px)" srcset="{{ $pic['phone']['img'] }}" sizes="100vw">@endif
                @if($pic['desktop']['webp'] !== '')<source type="image/webp" srcset="{{ $pic['desktop']['webp'] }}" sizes="(min-width: 1344px) 1320px, 100vw">@endif
                <img src="{{ $pic['src'] }}" alt="{{ $page->hero_image_alt }}"
                     @if($pic['desktop']['img'] !== '') srcset="{{ $pic['desktop']['img'] }}" sizes="(min-width: 1344px) 1320px, 100vw" @endif
                     @if($pic['width']) width="{{ $pic['width'] }}" height="{{ $pic['height'] }}" @endif
                     fetchpriority="high" decoding="async">
            </picture>
            @if($hero['caption_bold'] !== '' || $hero['caption'] !== '')
                <figcaption>@if($hero['caption_bold'] !== '')<b>{{ $hero['caption_bold'] }}</b>@endif {{ $hero['caption'] }}</figcaption>
            @endif
        </figure>
    </div>
</section>
