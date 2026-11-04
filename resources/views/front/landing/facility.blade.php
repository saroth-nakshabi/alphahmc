@extends('front.layout-2')

{{-- Landing page built in the admin (Landing pages). Layout, typography and tracking stay here and in
     landing-facility.css/js; every word comes from $content (App\Support\LandingPageSchema).
     Design: prototype version 5 (Saroth, 30 Oct 2026), D:/Gads-agent/docs/facility-landing-prototype.html. --}}
@php
    use App\Support\LandingPageSchema;

    $pg   = $content['page'];
    $hero = $content['hero'];
    // Language of the page (Arabic pages read right to left) and its interface text (App\Support\LandingText).
    $lang = $page->lang();
    $t = fn (string $key, array $replace = []) => \App\Support\LandingText::get($lang, $key, $replace);
    // "before <em>highlight</em> after", with a space only where the next part does not start with punctuation.
    $highlight = function (string $before, string $mark, string $after): string {
        $html = e($before);
        if ($mark !== '') {
            $html .= ($html !== '' ? ' ' : '') . '<em>' . e($mark) . '</em>';
        }
        if ($after !== '') {
            $html .= (preg_match('/^[,.;:!?\x{060C}\x{061B}\x{061F}]/u', $after) ? '' : ' ') . e($after);
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
    // Shared WhatsApp chat window (front/partials/wa-chat, in the layout): this page's number, text and copy; it
    // opens by itself after 5 seconds on landing pages (Saroth, 30 Oct 2026).
    $waChat = [
        'link'         => $waHref,
        'title'        => $pg['wa_window_title'] ?: 'Alpha Health Group',
        'subtitle'     => $pg['wa_window_subtitle'] ?: 'Facility setup team',
        'welcome'      => $pg['wa_window_message'] ?: 'Hello. Tell us what you plan to open and where, and the facility team will reply on WhatsApp.',
        'send_label'   => $pg['wa_window_send'] ?: 'Send on WhatsApp',
        'auto_open_ms' => 5000,
        'fallback'     => 'landing',
    ];
@endphp

@section('bare_layout', '1')
@if($page->isRtl())
    @section('html_lang', 'ar')
    @section('html_dir', 'rtl')
    @section('og_locale', 'ar_AE')
    @section('no_wa_chat', '1')
@endif
{{-- Ads landing pages stay out of search by default (SEO brief 30 Oct: they would compete with the /services pages
     and read as doorway pages). Links are still followed; robots.txt must not block /facility-setup/. --}}
@section('robots', ($page->indexable && !$preview) ? 'index, follow' : 'noindex, follow')
@section('canonical', $canonical)
@section('meta_description', (string) $page->meta_description)

@push('page_title'){{ $page->seo_title ?: $page->name }}@endpush

@push('meta')
    <link rel="preload" as="image" type="image/webp" imagesrcset="{{ $pic['desktop']['webp'] }}" imagesizes="(min-width: 1344px) 1320px, 100vw" media="(min-width: 768px)">
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/landing-facility.css') }}?v={{ substr(md5_file(public_path('front/assets/css/landing-facility.css')), 0, 10) }}">
    {{-- Reviewed page: WebPage with reviewedBy + lastReviewed, as on the service pages. --}}
    @if($lpReviewer = \App\Models\Reviewer::forPage($page))
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'WebPage', '@id' => $canonical, 'url' => $canonical,
        'name' => $page->seo_title ?: $page->name,
        'inLanguage' => $lang,
        'reviewedBy' => ['@type' => 'Person', '@id' => $lpReviewer->personId(), 'name' => $lpReviewer->name,
                         'jobTitle' => $lpReviewer->title, 'url' => $lpReviewer->url()],
        'lastReviewed' => $page->reviewed_at->toDateString(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endif
    @if($page->isRtl())
    {{-- Arabic pages: the Arabic font, Service (provider by @id, as on the service pages) and the breadcrumb; no FAQPage
         (brief "alphahmc-arabic-facility-pages-build", 4 Nov 2026). --}}
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('public/front/assets/fonts/ibm-plex-arabic-400.woff2') }}?v=4fa3d81d41" crossorigin>
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'Service', 'name' => $page->seo_title ?: $page->name,
        'description' => (string) $page->meta_description, 'url' => $canonical, 'inLanguage' => 'ar',
        'serviceType' => $page->seo_title ?: $page->name,
        'provider' => ['@id' => url('/') . '/#organization'],
        'areaServed' => \App\Support\AreaServed::schema($page->emirate ? $page->emirate . ', UAE' : 'UAE'),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => $t('home'), 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $hero['crumb_parent'] ?: $t('crumb_parent')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $hero['crumb_current'] ?: $page->name, 'item' => $canonical],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endif
    @if($faq && count($faq['items']) && !$page->isRtl())
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

    {{-- Header as the site's floating island (Saroth, 30 Oct 2026): logo left, this page's sections in the middle
         (no mega menu), and a Contact button that offers Call or WhatsApp. --}}
    <header class="lp-top" id="lp-header">
        <div class="lp-wrap">
            <div class="lp-island">
                <a class="lp-island-logo" href="{{ route('home') }}" aria-label="{{ $t('home_aria') }}">
                    <img src="{{ asset('public/front-new/assets/images/ahg-logo-header.png') }}" alt="Alpha Health Group" width="185" height="60">
                </a>
                @if($navLinks)
                <nav class="lp-island-nav" aria-label="{{ $t('nav_aria') }}">
                    @foreach($navLinks as $key => $label)<a href="#{{ $key }}">{{ $label }}</a>@endforeach
                </nav>
                @endif
                <div class="lp-contact-menu">
                    <button type="button" class="lp-contact-btn" id="lp-contact-btn" aria-expanded="false" aria-controls="lp-contact-list">{{ $t('contact') }}</button>
                    <div class="lp-contact-list" id="lp-contact-list" hidden>
                        @if($pg['phone_tel'] !== '' || $pg['phone_display'] !== '')
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $pg['phone_tel'] ?: $pg['phone_display']) }}"><i class="fas fa-phone" aria-hidden="true"></i><span><strong>{{ $t('menu_call') }}</strong><small dir="ltr">{{ $pg['phone_display'] }}</small></span></a>
                        @endif
                        <a href="{{ $waHref }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp" aria-hidden="true"></i><span><strong>{{ $pg['wa_header'] ?: $t('menu_wa') }}</strong><small>{{ $t('menu_wa_sub') }}</small></span></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        @include('front.landing.sections.hero')

        @foreach($enabled as $section)
            @include('front.landing.sections.' . $section['key'], ['d' => $section['data']])
        @endforeach
    </main>

    {{-- Footer: the site footer's bottom section (logo, policies, social, email and phone, legal line) on a plain colour. --}}
    <footer class="lp-footer">
        <div class="lp-wrap">
            <div class="lp-footer-row">
                <a class="lp-footer-logo" href="{{ route('home') }}" aria-label="{{ $t('home_aria') }}">
                    <picture>
                        <source type="image/webp" srcset="{{ asset('public/front-new/assets/images/about/alpha-logo5-200.webp') }}">
                        <img src="{{ asset('public/front-new/assets/images/about/alpha-logo5-200.png') }}" alt="Alpha Health Group" width="200" height="215" loading="lazy" decoding="async">
                    </picture>
                </a>
                <nav class="lp-footer-policies" aria-label="{{ $t('policies_aria') }}">
                    <a href="{{ url('/alpha-privacy-policy') }}">{{ $t('privacy') }}</a>
                    <a href="{{ route('front.terms-of-service') }}">{{ $t('terms') }}</a>
                    <a href="{{ route('front.cookie-policy') }}">{{ $t('cookies') }}</a>
                    <a href="{{ route('front.privacy-policy') }}">{{ $t('gdpr') }}</a>
                </nav>
                <div class="lp-footer-social">
                    <span>{{ $t('connect') }}</span>
                    <a href="https://www.linkedin.com/company/alphatsm" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                    <a href="https://x.com/alphatsm_" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                    <a href="https://www.facebook.com/alphatsm" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="https://www.instagram.com/alphahealthgroup/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                </div>
                <div class="lp-footer-contact">
                    <a class="lp-footer-email" href="mailto:info@alphatsm.com" dir="ltr"><i class="fas fa-envelope" aria-hidden="true"></i>info@alphatsm.com</a>
                    <a class="lp-footer-phone" href="tel:+97142724064" dir="ltr"><i class="fas fa-phone" aria-hidden="true"></i>+971 4 272 4064</a>
                </div>
            </div>
            <div class="lp-footer-legal">
                <p>{{ $t('legal') }}</p>
                @if($page->isRtl())
                <p>&copy; {{ date('Y') }} <span dir="ltr">Alpha Health Group.</span> {{ $t('rights') }}</p>
                @else
                <p>&copy; {{ date('Y') }} Alpha Health Group. {{ $t('rights') }}</p>
                @endif
            </div>
        </div>
    </footer>

    <div class="lp-dock" id="lp-dock">
        <a class="lp-btn lp-btn-primary" href="{{ $waHref }}" target="_blank" rel="noopener">{{ $pg['dock_wa'] ?: $t('dock_wa') }}</a>
        <a class="lp-btn lp-btn-ghost" href="#contact">{{ $pg['dock_proposal'] ?: $t('dock_proposal') }}</a>
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
            lang:         @json($lang),
        };
        window._lpText = @json(\App\Support\LandingText::script($lang));
    </script>
    <script src="{{ asset('public/front/assets/js/landing-facility.js') }}?v={{ substr(md5_file(public_path('front/assets/js/landing-facility.js')), 0, 10) }}" defer></script>
@endpush
