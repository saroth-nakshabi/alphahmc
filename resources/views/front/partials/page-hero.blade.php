{{--
    Standardized static-page hero (cinematic image band).
    Shared by all static pages EXCEPT home.

    Data source: $pageMeta (Dashboard → Pages & SEO). Values below act as
    per-page DEFAULTS — whatever is set in Pages & SEO always wins.

    Optional include variables:
      $heroEyebrow   string  fallback eyebrow text
      $heroTitle     string  fallback title (may contain HTML, e.g. <br>)
      $heroSubtitle  string  fallback subtitle (rendered under the title)
      $heroDesc      string  fallback description (may contain HTML)
      $heroBadge     array   ['icon' => 'fas fa-…', 'text' => '…'] small pill
      $heroCtaText   string  CTA label (omit to hide CTA)
      $heroCtaUrl    string  CTA href (default '#')
      $heroCtaModal  string  Bootstrap modal target (e.g. '#inquiryModal') — opens a modal instead of navigating
      $breadcrumb    array   ['Home' => route('home'), 'Current' => null]  (null url = current page)
--}}
@once
    @push('meta')
        <link rel="stylesheet" href="{{ asset('public/front/assets/css/page-hero.css') }}?v=3">
    @endpush
@endonce

@php
    $ph        = $pageMeta ?? null;
    $phEyebrow = ($ph?->hero_eyebrow) ?: ($heroEyebrow ?? null);
    $phTitle   = ($ph?->hero_title) ?: ($heroTitle ?? null);
    $phSub     = ($ph?->hero_subtitle) ?: ($heroSubtitle ?? null);
    $phDesc    = ($ph?->hero_description) ?: ($heroDesc ?? null);
    $phImg     = ($ph && $ph->hero_image) ? asset('public/uploads/page_images/' . $ph->hero_image) : null;
@endphp

<section class="page-hero"
    @if($phImg) style="background-image: linear-gradient(rgba(11,31,58,0.62), rgba(11,31,58,0.85)), url('{{ $phImg }}');" @endif>
    <div class="page-hero__inner">
        @if($phEyebrow)
            <div class="page-hero__eyebrow">{{ $phEyebrow }}</div>
        @endif

        <h1 class="page-hero__title">
            {!! $phTitle !!}@if($phSub)<span class="ph-sub">{{ $phSub }}</span>@endif
        </h1>

        @if($phDesc)
            <p class="page-hero__desc">{!! $phDesc !!}</p>
        @endif

        @if(!empty($heroBadge['text']))
            <div class="page-hero__badge">
                @if(!empty($heroBadge['icon']))<i class="{{ $heroBadge['icon'] }}"></i>@endif
                {{ $heroBadge['text'] }}
            </div>
        @endif

        @if(!empty($heroCtaText))
            <a href="{{ $heroCtaUrl ?? '#' }}" class="page-hero__cta"
                @if(!empty($heroCtaModal)) data-bs-toggle="modal" data-bs-target="{{ $heroCtaModal }}" @endif>
                {{ $heroCtaText }}
            </a>
        @endif
    </div>

    @if(!empty($breadcrumb))
        <nav class="page-hero__breadcrumb" aria-label="Breadcrumb">
            @foreach($breadcrumb as $label => $url)
                @if(!$loop->first)<span class="sep">›</span>@endif
                @if($url)<a href="{{ $url }}">{{ $label }}</a>@else<span class="current">{{ $label }}</span>@endif
            @endforeach
        </nav>
    @endif
</section>
