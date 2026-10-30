@extends('front.layout-2')

{{-- Landing page built in the admin (Landing pages). Layout, typography and tracking stay here and in
     landing-facility.css/js; every word comes from $content (App\Support\LandingPageSchema).
     Design: prototype version 5 (Saroth, 30 Oct 2026), D:/Gads-agent/docs/facility-landing-prototype.html. --}}
@php
    use App\Support\LandingPageSchema;

    $pg   = $content['page'];
    $hero = $content['hero'];
    // "before <em>highlight</em> after", with a space only where the next part does not start with punctuation.
    $highlight = function (string $before, string $mark, string $after): string {
        $html = e($before);
        if ($mark !== '') {
            $html .= ($html !== '' ? ' ' : '') . '<em>' . e($mark) . '</em>';
        }
        if ($after !== '') {
            $html .= (preg_match('/^[,.;:!?]/', $after) ? '' : ' ') . e($after);
        }

        return $html;
    };
    $waHref = 'https://wa.me/' . preg_replace('/\D/', '', $pg['wa_number'])
        . ($pg['wa_text'] !== '' ? '?text=' . rawurlencode($pg['wa_text']) : '');
    $enabled = array_values(array_filter($content['sections'], fn ($s) => $s['enabled']));
    $navLinks = [];
    foreach ($enabled as $s) {
        if (!empty(LandingPageSchema::SECTIONS[$s['key']]['nav']) && ($s['data']['nav_label'] ?? '') !== '') {
            $navLinks[$s['key']] = $s['data']['nav_label'];
        }
    }
    $faq = LandingPageSchema::section($content, 'faq');
    $canonical = $page->canonical ?: $page->url();
    $pic = \App\Support\Img::heroPicture($page->hero_image, 'uploads/landing_pages/nhs-medical-center-hero.jpg');
@endphp

@section('bare_layout', '1')
{{-- Ads landing pages stay out of search by default (SEO brief 30 Oct: they would compete with the /services pages
     and read as doorway pages). Links are still followed; robots.txt must not block /facility-setup/. --}}
@section('robots', ($page->indexable && !$preview) ? 'index, follow' : 'noindex, follow')
@section('canonical', $canonical)
@section('meta_description', (string) $page->meta_description)

@push('page_title'){{ $page->seo_title ?: $page->name }}@endpush

@push('meta')
    <link rel="preload" as="image" type="image/webp" imagesrcset="{{ $pic['desktop']['webp'] }}" imagesizes="(min-width: 1344px) 1320px, 100vw" media="(min-width: 768px)">
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/landing-facility.css') }}?v={{ substr(md5_file(public_path('front/assets/css/landing-facility.css')), 0, 10) }}">
    @if($faq && count($faq['items']))
    <script type="application/ld+json">{!! json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(fn ($q) => [
            '@type' => 'Question', 'name' => $q['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q['answer']],
        ], $faq['items']),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endif
@endpush

@section('content')
<div class="lp" id="lp-top">
    @if($preview)
        <div class="lp-preview" role="status">Draft preview. Only signed-in admins can see this page until it is published.</div>
    @endif

    <header class="lp-top" id="lp-header">
        <div class="lp-wrap">
            <a class="lp-pill lp-pill-logo" href="{{ route('home') }}" aria-label="Alpha Health Group home">
                <img src="{{ asset('public/front-new/assets/images/ahg-logo-header.png') }}" alt="Alpha Health Group" width="123" height="40">
            </a>
            @if($navLinks)
            <nav class="lp-pill lp-pill-nav" aria-label="On this page">
                @foreach($navLinks as $key => $label)<a href="#{{ $key }}">{{ $label }}</a>@endforeach
            </nav>
            @endif
            <div class="lp-pill lp-pill-act">
                @if($pg['phone_display'] !== '')<span class="lp-top-call">{{ $pg['phone_display'] }}</span>@endif
                <a class="lp-btn lp-btn-primary" href="{{ $waHref }}" target="_blank" rel="noopener">{{ $pg['wa_header'] ?: 'WhatsApp us' }}</a>
            </div>
        </div>
    </header>

    <main>
        @include('front.landing.sections.hero')

        @foreach($enabled as $section)
            @include('front.landing.sections.' . $section['key'], ['d' => $section['data']])
        @endforeach
    </main>

    <footer class="lp-footer">
        <div class="lp-wrap">
            <span>Alpha Health Group. Dubai, Abu Dhabi, Al Ain and Colombo.</span>
            <a href="{{ route('home') }}">alphahmc.com</a>
        </div>
    </footer>

    <div class="lp-dock" id="lp-dock">
        <a class="lp-btn lp-btn-primary" href="{{ $waHref }}" target="_blank" rel="noopener">{{ $pg['dock_wa'] ?: 'WhatsApp' }}</a>
        <a class="lp-btn lp-btn-ghost" href="#contact">{{ $pg['dock_proposal'] ?: 'Request a proposal' }}</a>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Page context for the conversion tracker: lead_type picks the Ads labels (App\Support\LeadType). --}}
    <script>
        window._ahgPage = {
            service_name: @json($page->name),
            service_slug: @json($page->slug),
            page_type:    'landing_page',
            lead_type:    @json(\App\Support\LeadType::for($page)),
        };
    </script>
    <script src="{{ asset('public/front/assets/js/landing-facility.js') }}?v={{ substr(md5_file(public_path('front/assets/js/landing-facility.js')), 0, 10) }}" defer></script>
@endpush
