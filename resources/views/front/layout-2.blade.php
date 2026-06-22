<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="robots" content="index, follow">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- SEO: dashboard-managed PageSetting takes precedence on standard pages;
         detail pages (services etc.) fall back to their own @push/@yield values. --}}
    @php
        $pmTitle = (isset($pageMeta) && $pageMeta && $pageMeta->meta_title) ? $pageMeta->meta_title : null;
        $pmDesc  = (isset($pageMeta) && $pageMeta && $pageMeta->meta_description) ? $pageMeta->meta_description : null;
        $pmKeys  = (isset($pageMeta) && $pageMeta && $pageMeta->meta_keywords) ? $pageMeta->meta_keywords : null;
        $pmOg    = (isset($pageMeta) && $pageMeta && $pageMeta->og_image) ? asset('public/uploads/page_images/' . $pageMeta->og_image) : null;
    @endphp

    @if($pmTitle)<title>{{ $pmTitle }}</title>@else<title>@stack('page_title')</title>@endif
    @if($pmDesc)
        <meta name="description" content="{{ $pmDesc }}">
    @else
        <meta name="description" content="@yield('meta_description', 'Alpha Health Group delivers expert healthcare consultancy, DOH compliance, quality assurance, and operational excellence for healthcare facilities in the UAE.')">
    @endif
    @if($pmKeys)<meta name="keywords" content="{{ $pmKeys }}">@endif
    @stack('meta')

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Default Open Graph tags — pages can override via @push('og_tags') --}}
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $pmTitle ?? '' }}@unless($pmTitle)@stack('page_title')@endunless" />
    @if($pmDesc)
        <meta property="og:description" content="{{ $pmDesc }}" />
    @else
        <meta property="og:description" content="@yield('meta_description', 'Alpha Health Group delivers expert healthcare consultancy, DOH compliance, quality assurance, and operational excellence for healthcare facilities in the UAE.')" />
    @endif
    @if($pmOg)<meta property="og:image" content="{{ $pmOg }}" /><meta name="twitter:image" content="{{ $pmOg }}" />@endif
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@AlphaHealthGrp" />
    @stack('og_tags')

    <link rel="shortcut icon" href="{{ asset('public/favicon.png') }}">
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}" />
    <link rel="alternate" type="text/plain" title="LLMs.txt" href="{{ asset('llms.txt') }}" />

{{-- ═══════════════════════════════════════════════════════════════
     CONSENT MODE V2 — MUST be the very first script, before any
     tracking tags (GA4, GTM, Meta Pixel) so they receive the
     consent signal on initialization, not retroactively.
════════════════════════════════════════════════════════════════ --}}
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){ dataLayer.push(arguments); }

    // ── Expiry helper ──────────────────────────────────────────────
    var CONSENT_EXPIRY_DAYS = 30;

    function setConsentWithExpiry(value) {
        var item = {
            value:  value,
            expiry: new Date().getTime() + (CONSENT_EXPIRY_DAYS * 24 * 60 * 60 * 1000),
        };
        localStorage.setItem('cookie_consent', JSON.stringify(item));
    }

    function getConsentWithExpiry() {
        var raw = localStorage.getItem('cookie_consent');
        if (!raw) return null;
        try {
            var item = JSON.parse(raw);
            if (typeof item === 'string') { localStorage.removeItem('cookie_consent'); return null; }
            if (new Date().getTime() > item.expiry) { localStorage.removeItem('cookie_consent'); return null; }
            return item.value; // "accepted" | "rejected"
        } catch(e) {
            localStorage.removeItem('cookie_consent');
            return null;
        }
    }

    // ── Set consent default BEFORE any tracking scripts load ──────
    var _savedConsent = getConsentWithExpiry();

    if (_savedConsent === 'accepted') {
        gtag('consent', 'default', {
            analytics_storage:  'granted',
            ad_storage:         'granted',
            ad_user_data:       'granted',
            ad_personalization: 'granted',
        });
    } else {
        gtag('consent', 'default', {
            analytics_storage:  'denied',
            ad_storage:         'denied',
            ad_user_data:       'denied',
            ad_personalization: 'denied',
            wait_for_update:    500,
        });
        // Consent Mode V2: model conversions even when ads consent denied
        // url_passthrough disabled: it was appending the GA linker "?_gl=..." string to
        // every internal link while consent was denied. Off = clean URLs (slightly less
        // accurate cookieless attribution). ads_data_redaction stays on (no URL impact).
        gtag('set', 'url_passthrough', false);
        gtag('set', 'ads_data_redaction', true);
    }
</script>

{{-- Global Tags from database (GA4, GTM, etc.) — fire AFTER consent default above --}}
@foreach($globaltags as $tag)
    {!! $tag->tags !!}
@endforeach


{{-- page specific tag --}}
@isset($service)
@php
    // Build areaServed array - stored as JSON string or comma separated in DB
    $areaServed = [];
    if ($service->areaServed) {
        $areas = is_array($service->areaServed)
            ? $service->areaServed
            : json_decode($service->areaServed, true);

        if (is_array($areas)) {
            foreach ($areas as $area) {
                $areaServed[] = [
                    '@type' => $area['type'] ?? 'City',
                    'name'  => $area['name'] ?? $area,
                ];
            }
        }
    }

    // Build FAQ schema
    $faqSchema = [];
    if ($service->faq && $service->faq->count()) {
        foreach ($service->faq as $faq) {
            $faqSchema[] = [
                '@type'          => 'Question',
                'name'           => $faq->faq_question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => strip_tags($faq->faq_answer),
                ],
            ];
        }
    }

    $schema = [];

    // ProfessionalService schema
    $professionalService = [
        '@context'    => 'https://schema.org',
        '@type'       => 'ProfessionalService',
        'name'        => $service->name,
        'description' => strip_tags($service->content),
        'provider'    => [
            '@type' => 'Organization',
            'name'  => 'Alpha Health Consultancies',  /*config('app.name')*/
        ],
    ];

    // Only emit these when set — avoids null properties in the JSON-LD.
    if (!empty($service->areaServed)) {
        $professionalService['areaServed'] = $service->areaServed;
    }
    if (!empty($service->serviceType)) {
        $professionalService['serviceType'] = $service->serviceType;
    }

    $schema[] = $professionalService;

    // FAQPage schema - only add if FAQs exist
    if (!empty($faqSchema)) {
        $schema[] = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $faqSchema,
        ];
    }



@endphp

{{-- Output the schema only if it was built --}}
@if(!empty($schema))
<script type="application/ld+json">
{!! json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif

{{-- Breadcrumb --}}
<script type="application/ld+json">
{!!
    json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => 'Home',
                'item'     => route('home'),
            ],
            [
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => 'Services',
                'item'     => route('front.all-services'),
            ],
            [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => $service->name,
                'item'     => route('front.service', $service->slug),
            ],
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
!!}
</script>

@endisset


{{-- Consent initialization moved above $globaltags — see top of head --}}


    {{-- Google Tags (GA4 / Google Ads / GTM head) — rendered RAW so the <script>
         elements actually execute. Sits after the Consent Mode V2 defaults above,
         so tags initialise with the correct consent signal. --}}
@foreach($googletags as $tag)
    {!! $tag->tags !!}
@endforeach

    {{-- Global WebSite Schema (enables Google Sitelinks Search Box) --}}
    <script type="application/ld+json">
    {!!
        json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => 'Alpha Health Group',
            'alternateName' => 'Alpha Health Consultancies',
            'url'      => config('app.url'),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => url('/search') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    !!}
    </script>

    {{-- Global Organization Schema --}}
    <script type="application/ld+json">
    {!!
        json_encode([
            '@context'     => 'https://schema.org',
            '@type'        => 'Organization',
            'name'         => 'Alpha Health Group',
            'alternateName'=> 'Alpha Health Consultancies',
            'url'          => config('app.url'),
            'logo'         => [
                '@type' => 'ImageObject',
                'url'   => asset('public/front-new/assets/images/alpha-logo.svg'),
            ],
            'description'  => 'Alpha Health Group delivers expert healthcare consultancy, DOH compliance, accreditation support, quality assurance, and operational excellence for healthcare facilities across the UAE.',
            'areaServed'   => [
                ['@type' => 'Country', 'name' => 'United Arab Emirates'],
            ],
            'contactPoint' => [
                '@type'             => 'ContactPoint',
                'contactType'       => 'customer service',
                'areaServed'        => 'AE',
                'availableLanguage' => ['English', 'Arabic'],
            ],
            'sameAs' => [
                'https://www.linkedin.com/company/alphatsm/',
                'https://www.instagram.com/alphahealthgroup/',
                'https://www.facebook.com/alphatsm',
                'https://x.com/alphatsm_',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    !!}
    </script>





    <!-- Preconnect & DNS prefetch for external origins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://code.jquery.com">

    <!-- Fonts — non-render-blocking, reduced weights (300/500/700 dropped — not used in critical path) -->
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap">
    </noscript>

    <!-- Bootstrap CSS — blocking (needed for above-fold grid layout) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />

    <!-- Site CSS — blocking (critical nav + layout styles) -->
    <link rel="stylesheet" href="{{ asset('public/front-new/assets/css/style.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('public/front-new/assets/css/slide-menu.css') }}?v=12">

    <!-- Icon fonts + animation libs — deferred (not needed for first paint) -->
    <link rel="preload" as="style"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <link rel="preload" as="style"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"></noscript>

    <link rel="preload" as="style"
          href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css"></noscript>

    <link rel="preload" as="style"
          href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></noscript>

    <link rel="preload" as="style"
          href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"></noscript>

    @stack('meta_scripts')

    @yield('custom_css')

    <!-- <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXX');</script> -->
</head>

<body>

@foreach($googletags as $tag)
        @if($tag->noscript_tags)
            {!! $tag->noscript_tags !!}
        @endif
    @endforeach

    <link rel="stylesheet" href="{{ asset('public/front/assets/css/front-global.css') }}?v=8">

{{-- Consent update functions (called by banner buttons) --}}
    <script>
         // ── Accept ─────────────────────────────────────────────
    function acceptAllCookies() {
        gtag('consent', 'update', {
            analytics_storage:  'granted',
            ad_storage:         'granted',
            ad_user_data:       'granted',
            ad_personalization: 'granted',
        });
        setConsentWithExpiry('accepted');
        hideCookieBanner();
    }

    // ── Reject ─────────────────────────────────────────────
    function rejectAllCookies() {
        gtag('consent', 'update', {
            analytics_storage:  'denied',
            ad_storage:         'denied',
            ad_user_data:       'denied',
            ad_personalization: 'denied',
        });
        // Keep conversion modeling active even after rejection
        // url_passthrough disabled: it was appending the GA linker "?_gl=..." string to
        // every internal link while consent was denied. Off = clean URLs (slightly less
        // accurate cookieless attribution). ads_data_redaction stays on (no URL impact).
        gtag('set', 'url_passthrough', false);
        gtag('set', 'ads_data_redaction', true);
        setConsentWithExpiry('rejected');
        hideCookieBanner();
    }

    // ── Hide banner ────────────────────────────────────────
    function hideCookieBanner() {
        var banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.animation = 'slideDownOut 0.4s ease forwards';
            setTimeout(function() {
                banner.style.display = 'none';
            }, 400);
        }
    }

    // ── Show banner if no valid consent ───────────────────
    document.addEventListener('DOMContentLoaded', function() {
        var consent = getConsentWithExpiry(); //expiry check
        var banner  = document.getElementById('cookie-consent-banner');
        if (!consent && banner) {
            setTimeout(function() {
                banner.style.display  = 'flex';
                banner.style.animation = 'slideUpIn 0.5s ease forwards';
            }, 1500);
        }
    });
    </script>


     {{-- ✅ COOKIE CONSENT BANNER --}}
    <div id="cookie-consent-banner" style="display:none;">
        {{-- <div class="cookie-icon">
            🍪
        </div> --}}
        <div class="cookie-content">
            <h6 class="cookie-title">We use cookies</h6>
            <p class="cookie-text">
                We use cookies to analyse traffic, personalise content, and improve your experience. You can manage your preferences at any time.
                <a href="{{ route('front.cookie-policy') }}" class="cookie-policy-link">Cookie Policy</a>
            </p>
        </div>
        <div class="cookie-actions">
            <button class="cookie-btn cookie-btn-reject" onclick="rejectAllCookies()">
                Reject All
            </button>
            <button class="cookie-btn cookie-btn-accept" onclick="acceptAllCookies()">
                Accept All
            </button>
        </div>
        <button class="cookie-close" onclick="rejectAllCookies()" aria-label="Close">
            ✕
        </button>
    </div>

    {{-- ✅ LOGIN BANNER (top notification bar) --}}
    {{-- @guest
    <div id="login-banner" class="login-banner">
        <div class="login-banner-content">
            <i class="fas fa-user-circle"></i>
            <span>Sign in for a personalized experience and exclusive health insights</span>
        </div>
        <div class="login-banner-actions">
            <button class="login-banner-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                Sign In
            </button>
            <button class="login-banner-close" onclick="dismissLoginBanner()" aria-label="Close">
                ✕
            </button>
        </div>
    </div>
    @endguest --}}

<!-- <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript> -->
    <!-- Unified Navbar Container -->
    <div class="navbar-content" id="main-navbar">

        <!-- Top row: Premium Glashmorphism Layout -->
        <div class="navbar-top" style="padding:0 25px">
            <!-- 1. Left: Premium Explore Button -->
            <div class="nav-column nav-left">
                <button id="explore-search-toggle" class="explore-btn-premium">
                    <div class="burger-icon">
                        <span></span>
                        <span></span>
                    </div>
                    <div class="btn-label">MENU</div>
                </button>
            </div>

            <!-- 2. Center: Logo (The Anchor) -->
            <div class="nav-column nav-center">
                <a href="{{ route('home') }}" class="navbar-logo">
                    <img src="{{ asset('public/front-new/assets/images/AHGlogo.svg') }}" alt="Alpha Health Group" />
                </a>
            </div>

            <!-- 3. Right: Premium Search Bar -->
            {{-- <div class="nav-column nav-right">
                <div class="glass-search-container">
                    <input type="text" class="glass-search-input" placeholder="Search anything..."
                        id="main-search-input">
                    <button class="glass-search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div> --}}

            <div class="nav-column nav-right">
                <div class="glass-search-container">
                    <input type="text" class="glass-search-input" placeholder="Search anything..."
                        id="main-search-input" autocomplete="off">
                    <button class="glass-search-btn">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Live Dropdown -->
                    <div id="live-search-dropdown" style="
                        display: none;
                        position: absolute;
                        top: calc(100% + 8px);
                        left: 0;
                        right: 0;
                        background: #fff;
                        border-radius: 12px;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
                        z-index: 999999;
                        overflow: hidden;
                        border: 1px solid #e2e8f0;
                        max-height: 400px;
                        overflow-y: auto;
                    ">
                        <!-- Results go here -->
                        <div id="live-search-results"></div>

                        <!-- View all results link -->
                        <div id="live-search-footer" style="
                            padding: 10px 16px;
                            border-top: 1px solid #f1f5f9;
                            background: #f8fafc;
                            display: none;
                        ">
                            <a id="view-all-link" href="#" style="
                                font-size: 0.85rem;
                                color: #009095;
                                font-weight: 600;
                                text-decoration: none;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                            ">
                                <i class="fas fa-search"></i>
                                View all results
                            </a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="nav-contact-cta">Contact</a>
                <style>
                    .nav-contact-cta{display:inline-flex;align-items:center;justify-content:center;height:46px;box-sizing:border-box;margin-left:16px;padding:0 22px;background:rgba(255,255,255,0.20);backdrop-filter:blur(15px);color:inherit;border:1px solid rgba(255,255,255,0.4);border-radius:50px;font-weight:600;font-size:.85rem;text-decoration:none;white-space:nowrap;transition:background .2s,border-color .2s,color .2s;}
                    .nav-contact-cta:hover{background:rgba(255,255,255,0.4);border-color:#009095;color:#009095;}
                    /* Mobile: shrink Contact so the search circle keeps its shape (nav-right gap handles spacing) */
                    @media (max-width:991px){.nav-contact-cta{height:44px;padding:0 16px;margin-left:0;font-size:.8rem;}}
                    @media (max-width:600px){.nav-contact-cta{height:40px;padding:0 13px;margin-left:0;font-size:.72rem;}}
                </style>
            </div>
        </div>

        <!-- Expanded Content Area -->
        <div id="sidebar-menu">
            <div class="sidebar-inner-content">

                <!-- Left Column: Menu Groups -->
                <div class="sidebar-content">
                    <div class="menu-group">
                        <h6 class="sidebar-menu-heading">OUR SERVICES</h6>
                        @if (isset($main_categories) && count($main_categories) > 0)
                            @foreach ($main_categories as $main_category)
                                <div class="service-content">
                                    <a href="#" class="main-category-link" id="main_{{ $main_category->id }}">
                                        {{ $main_category->name }}
                                    </a>
                                </div>
                            @endforeach
                        @endif
                        <!-- Our Packages Link -->
                        <div class="service-content">
                            <a href="#" class="packages-link" id="packages_link">
                                Our Packages
                            </a>
                        </div>
                    </div>

                    <div class="menu-group">
                        <h6 class="sidebar-menu-heading">Discover Alpha</h6>
                        {{-- Opens its sub-links in the right panel (like the categories / Our Packages) --}}
                        <div class="service-content"><a href="javascript:void(0);" class="group-link" id="group_about">Who We Are</a></div>
                        <div class="service-content"><a href="{{ route('front.new_blog') }}" class="kb-link" id="kb_trigger">Knowledge Base</a></div>
                        <div class="service-content"><a href="{{ route('front.project') }}" class="cs-link" id="cs_trigger">Case Studies</a></div>
                    </div>
                </div>

                <!-- Right Column: Results -->
                <div class="sidebar-content-1">
                    <div class="sidebar-heading">
                        <h2 id="dynamic-heading">Our Expertise</h2>
                        <p id="dynamic-desc">Discover our comprehensive healthcare solutions tailored for your needs.
                        </p>
                    </div>

                    {{-- Curated default panel: shown when the menu first opens (desktop); hidden once a trigger is used.
                         Featured services are admin-curated via the service `featured` flag + sort order. --}}
                    <div id="menu-default-panel" class="menu-default-panel {{ (isset($nav_menu_promos) && count($nav_menu_promos)) ? '' : 'no-promo' }}">
                        <div class="mdp-featured">
                            <div class="mdp-label">Featured Services</div>
                            <div class="mdp-grid">
                                @if (isset($nav_featured_services) && count($nav_featured_services))
                                    @foreach ($nav_featured_services as $fs)
                                        <a href="{{ route('front.service', $fs->slug) }}" class="mdp-card">
                                            <span class="mdp-card-title">{{ $fs->name }}</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            <a href="{{ route('front.all-services') }}" class="mdp-viewall">View all services <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                        @if (isset($nav_menu_promos) && count($nav_menu_promos))
                            <aside class="mdp-promo-slider" id="mdp-promo-slider">
                                <div class="mdp-promo-stack">
                                    @foreach ($nav_menu_promos as $promo)
                                        <div class="mdp-promo-slide {{ $loop->first ? 'active' : '' }}">
                                            @if($promo->eyebrow)
                                                <span class="mdp-promo-eyebrow">{{ $promo->eyebrow }}</span>
                                            @endif
                                            <h4 class="mdp-promo-title">{{ $promo->title }}</h4>
                                            @if($promo->text)
                                                <p class="mdp-promo-text">{{ $promo->text }}</p>
                                            @endif
                                            @if($promo->cta_label && $promo->cta_url)
                                                <a href="{{ $promo->cta_url }}" class="mdp-promo-cta">{{ $promo->cta_label }} <i class="fas fa-arrow-right ms-1"></i></a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if (count($nav_menu_promos) > 1)
                                    <div class="mdp-promo-dots">
                                        @foreach ($nav_menu_promos as $promo)
                                            <button type="button" class="mdp-promo-dot {{ $loop->first ? 'active' : '' }}" data-idx="{{ $loop->index }}" aria-label="Show promo {{ $loop->iteration }}"></button>
                                        @endforeach
                                    </div>
                                @endif
                            </aside>
                        @endif
                    </div>

                    <div class="categories_service">
                        <!-- Sub Categories -->
                        <div class="sub-col">
                            <ul class="sub_categories list-unstyled">
                                @foreach ($main_categories as $main_category)
                                    @foreach ($main_category->mergedCategories as $category)
                                        <li class="service-content-category filter-main_{{ $main_category->id }}"
                                            style="display: none;">
                                            <a href="{{ !empty($category->slug) ? route('front.service-category', $category->slug) : '#' }}"
                                                class="main-category-link-sub {{ empty($category->slug) ? 'disabled' : '' }}"
                                                id="sub_{{ $category->id }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endforeach
                                <!-- Service Groups for Our Packages -->
                                @if (isset($service_groups) && count($service_groups) > 0)
                                    @foreach ($service_groups as $service_group)
                                        <li class="service-group-item filter-packages"
                                            style="display: none;">
                                            <a href="{{ route('service-packages', $service_group->slug) }}"
                                                class="main-category-link-sub service-group-link"
                                                id="group_{{ $service_group->id }}">
                                                {{ $service_group->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif

                                <!-- About Alpha Health Group sub-links -->
                                <li class="service-group-item filter-group" style="display: none;">
                                    <a href="{{ route('front.new-about') }}" class="main-category-link-sub service-group-link">About Alpha Health Group</a>
                                </li>
                                <li class="service-group-item filter-group" style="display: none;">
                                    <a href="{{ route('front.brands') }}" class="main-category-link-sub service-group-link">Our Brands</a>
                                </li>
                                <li class="service-group-item filter-group" style="display: none;">
                                    <a href="{{ route('front.ahg-updates') }}" class="main-category-link-sub service-group-link">AHG Updates</a>
                                </li>
                                <li class="service-group-item filter-group" style="display: none;">
                                    <a href="{{ route('front.testimonials') }}" class="main-category-link-sub service-group-link">Testimonials</a>
                                </li>
                                <li class="service-group-item filter-group" style="display: none;">
                                    <a href="{{ route('front.our-clients') }}" class="main-category-link-sub service-group-link">Our Clients</a>
                                </li>
                                <li class="service-group-item filter-group" style="display: none;">
                                    <a href="{{ route('contact') }}" class="main-category-link-sub service-group-link">Contact Us</a>
                                </li>

                                <!-- Knowledge Base: blog categories (tags) — each opens the insights page filtered to that tag -->
                                @if (isset($nav_blog_tags))
                                    @foreach ($nav_blog_tags as $tag)
                                        <li class="service-content-category filter-kb" style="display: none;">
                                            <a href="{{ route('front.new_blog') }}?{{ rawurlencode(strtolower($tag->name)) }}" class="main-category-link-sub kb-flat-link" id="tag_{{ $tag->id }}">{{ $tag->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                                <li class="service-content-category filter-kb" style="display: none;">
                                    <a href="{{ route('front.new_blog') }}" class="main-category-link-sub kb-flat-link"><strong>View All Insights</strong></a>
                                </li>

                                <!-- Case Studies: project categories + View All at the bottom (all link to the Case Studies main page) -->
                                @if (isset($nav_project_categories))
                                    @foreach ($nav_project_categories as $pcat)
                                        <li class="service-content-category filter-cs" style="display: none;">
                                            <a href="{{ route('front.project') }}" class="main-category-link-sub cs-flat-link" id="pcat_{{ $pcat->id }}">{{ $pcat->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                                <li class="service-content-category filter-cs" style="display: none;">
                                    <a href="{{ route('front.project') }}" class="main-category-link-sub cs-flat-link"><strong>View All Case Studies</strong></a>
                                </li>
                            </ul>
                        </div>

                        <!-- Services -->
                        <div class="serv-col">
                            <ul class="service-content-2 list-unstyled">
                                @php $rendered_category_ids = []; @endphp
                                @foreach ($main_categories as $main_category)
                                    @foreach ($main_category->mergedCategories as $category)
                                        @if (!in_array($category->id, $rendered_category_ids))
                                            @php $rendered_category_ids[] = $category->id; @endphp
                                            @foreach ($category->services as $service)
                                                <li class="service-item filter-sub_{{ $category->id }}" style="display: none;">
                                                    <a href="{{ route('front.service', $service->slug) }}">
                                                        {{ $service->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                            @foreach ($category->serviceGroups as $group)
                                                <li class="service-item filter-sub_{{ $category->id }}" style="display: none;">
                                                    <a href="{{ route('service-packages', $group->slug) }}">
                                                        {{ $group->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endforeach

                                {{-- Knowledge Base: blogs under each tag --}}
                                @if (isset($nav_blog_tags))
                                    @foreach ($nav_blog_tags as $tag)
                                        @foreach ($tag->blogs as $blog)
                                            <li class="service-item filter-tag_{{ $tag->id }}" style="display: none;">
                                                <a href="{{ route('front.singleBlog', $blog->slug) }}">{{ $blog->title }}</a>
                                            </li>
                                        @endforeach
                                    @endforeach
                                @endif

                                {{-- Case Studies: projects under each category --}}
                                @if (isset($nav_project_categories))
                                    @foreach ($nav_project_categories as $pcat)
                                        @foreach ($pcat->projects as $project)
                                            <li class="service-item filter-pcat_{{ $pcat->id }}" style="display: none;">
                                                <a href="{{ route('front.project_details', $project->slug) }}">{{ $project->name }}</a>
                                            </li>
                                        @endforeach
                                    @endforeach
                                @endif
                            </ul>

                            <!-- View All Button -->
                            <div class="view-all-services-wrapper mt-4" style="display: none;">
                                <a href="{{ route('front.all-services') }}" class="view-all-services-btn">
                                    View All Services <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    

    <!-- Login Modal (Kept as is, just hidden trigger for now unless requested) -->
    <!-- ... (Modal code remains if needed, but removed from view to match image) ... -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <!-- ... existing modal content ... -->
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mt-2 login-heading">Log In To Your Account</h2>
                <form id="login_form" method="post" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3 text-start px-4 mt-3">
                        <label for="email" class="form-label">E-Mail Address</label>
                        <input type="email" class="form-control mt-2" id="email" name="email"
                            placeholder="E-Mail Address" required />
                    </div>

                    <div class="mb-3 text-start px-4 mt-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control mt-2" id="password" name="password"
                            placeholder="password" required />
                    </div>
                    <div class="px-4">
                        <button type="submit" class="btn btn-signin w-100 py-2 mt-2">
                            SIGN IN
                        </button>
                        <button type="button" class="btn btn-outline-dark w-100 mt-3 py-2">
                            SIGN UP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript for Unified Menu (Fixed & Cleaned) -->
    <script>
     $(document).ready(function () {

    console.log("Navbar Initialized (With Mobile Description)");

    function isMobile() {
        return window.innerWidth <= 768;
    }

    function navIsOpen() {
        return $("#main-navbar").hasClass("menu-is-open");
    }

    /* =========================
       TOGGLE MENU
    ========================= */
    function closeMegaMenu() {
        const nav = $("#main-navbar");
        if (!nav.hasClass("menu-is-open")) return;
        nav.removeClass("menu-is-open");
        $("#explore-search-toggle").removeClass("is-active").find(".btn-label").text("MENU");
        $(".mobile-sub-list").remove();
        $(".mobile-service-list").remove();
        stopPromoSlider();
    }

    $("#explore-search-toggle").on("click", function () {
        const nav = $("#main-navbar");
        nav.toggleClass("menu-is-open");

        if (nav.hasClass("menu-is-open")) {
            $(this).find(".btn-label").text("CLOSE");
            $(this).addClass("is-active");

            // Desktop: show the curated default panel (featured services + promo) on open —
            // editorial first impression instead of jumping into the first category.
            if (!isMobile()) {
                showMenuDefaultPanel();
            }
        } else {
            $(this).find(".btn-label").text("MENU");
            $(this).removeClass("is-active");

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();
        }
    });

    // Close the mega-menu when clicking OUTSIDE it (not on hover-out).
    $(document).on("click", function (e) {
        if ($("#main-navbar").hasClass("menu-is-open") && !$(e.target).closest("#main-navbar").length) {
            closeMegaMenu();
        }
    });

    // Close on Escape key too.
    $(document).on("keydown", function (e) {
        if (e.key === "Escape") closeMegaMenu();
    });

    //search button click




    $(document).ready(function () {

        var searchTimer;
        var currentQuery = '';

        // ── Typing: live dropdown ──────────────────────────────
        $("#main-search-input").on("input", function () {
            var query = $(this).val().trim();
            currentQuery = query;

            clearTimeout(searchTimer);

            if (query.length < 2) {
                hideDropdown();
                return;
            }

            // Show loading state
            showLoading();

            // Debounce 300ms
            searchTimer = setTimeout(function () {
                fetchResults(query);
            }, 300);
        });

        // ── Enter key: go to results page ─────────────────────
        $("#main-search-input").on("keydown", function (e) {
            if (e.key === "Enter") {
                performSearch();
            }
            if (e.key === "Escape") {
                hideDropdown();
                $(".glass-search-container").removeClass("search-open");
            }
        });

        // ── Button click: mobile = expand the bar first, then search ──
        $(".glass-search-btn").on("click", function (e) {
            var $c = $(".glass-search-container");
            var isMobileNav = window.matchMedia("(max-width: 991px)").matches;
            if (isMobileNav && !$c.hasClass("search-open")) {
                e.preventDefault();
                e.stopPropagation();
                $c.addClass("search-open");
                setTimeout(function () {
                    var el = document.getElementById("main-search-input");
                    if (el) el.focus();
                }, 60);
                return;
            }
            performSearch();
        });

        // ── Click outside: close dropdown + collapse mobile search ──
        $(document).on("click", function (e) {
            if (!$(e.target).closest(".glass-search-container").length) {
                hideDropdown();
                $(".glass-search-container").removeClass("search-open");
            }
        });

        // ── Fetch live results ─────────────────────────────────
        function fetchResults(query) {
            $.ajax({
                url: "{{ route('front.search.live') }}",
                method: "GET",
                data: { s: query },
                success: function (data) {
                    renderResults(data, query);
                },
                error: function () {
                    hideDropdown();
                }
            });
        }

        // ── Render dropdown results ────────────────────────────
        function renderResults(data, query) {
            var container = $("#live-search-results");
            container.empty();

            if (data.length === 0) {
                container.html(
                    '<div style="padding: 20px 16px; text-align:center; color:#94a3b8;">' +
                    '<i class="fas fa-search" style="font-size:1.5rem; display:block; margin-bottom:8px;"></i>' +
                    'No results for <strong>"' + escapeHtml(query) + '"</strong>' +
                    '</div>'
                );
                $("#live-search-footer").hide();
            } else {
                var currentType = '';

                $.each(data, function (i, item) {

                    // Type group header
                    if (item.type !== currentType) {
                        currentType = item.type;
                        container.append(
                            '<div style="padding: 8px 16px 4px; font-size:0.7rem; font-weight:700; ' +
                            'text-transform:uppercase; letter-spacing:1px; color:#94a3b8; ' +
                            'background:#f8fafc; border-top: 1px solid #f1f5f9;">' +
                            (item.type_plural || item.type + 's') +
                            '</div>'
                        );
                    }

                    // Highlight matched text
                    var highlighted = highlightMatch(item.title, query);

                    container.append(
                        '<a href="' + item.url + '" style="' +
                        'display:flex; align-items:center; justify-content:space-between; ' +
                        'padding: 10px 16px; text-decoration:none; color:#1e293b; ' +
                        'transition: background 0.2s ease; border-bottom: 1px solid #f8fafc;' +
                        '" ' +
                        'onmouseover="this.style.background=\'#f0fdfa\'" ' +
                        'onmouseout="this.style.background=\'transparent\'">' +

                        '<span style="font-size:0.92rem; font-weight:500; flex:1;">' +
                        highlighted +
                        '</span>' +

                        '<i class="fas fa-arrow-right" style="font-size:0.7rem; color:#cbd5e1;"></i>' +
                        '</a>'
                    );
                });

                // Update footer link
                $("#view-all-link").attr(
                    "href",
                    "{{ route('front.search') }}?s=" + encodeURIComponent(query)
                );
                $("#live-search-footer").show();
            }

            showDropdown();
        }

        // ── Show loading state ─────────────────────────────────
        function showLoading() {
            $("#live-search-results").html(
                '<div style="padding: 16px; text-align:center; color:#94a3b8;">' +
                '<i class="fas fa-spinner fa-spin"></i> Searching...' +
                '</div>'
            );
            $("#live-search-footer").hide();
            showDropdown();
        }

        // ── Show / hide dropdown ───────────────────────────────
        function showDropdown() {
            $("#live-search-dropdown").fadeIn(150);
        }

        function hideDropdown() {
            $("#live-search-dropdown").fadeOut(150);
        }

        // ── Go to full results page ────────────────────────────
        function performSearch() {
            var query = $("#main-search-input").val().trim();
            if (query.length >= 2) {
                window.location.href = "{{ route('front.search') }}?s=" + encodeURIComponent(query);
            } else {
                $("#main-search-input").css({ "border": "1px solid red" });
                setTimeout(function () {
                    $("#main-search-input").css("border", "");
                }, 1500);
            }
        }

        // ── Highlight matched text ─────────────────────────────
        function highlightMatch(text, query) {
            var escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            var regex = new RegExp('(' + escaped + ')', 'gi');
            return escapeHtml(text).replace(regex,
                '<strong style="color:#009095;">$1</strong>'
            );
        }

        // ── Escape HTML ────────────────────────────────────────
        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }

        // ── Hex to rgba helper ─────────────────────────────────
        function hexToRgba(hex, alpha) {
            var r = parseInt(hex.slice(1, 3), 16);
            var g = parseInt(hex.slice(3, 5), 16);
            var b = parseInt(hex.slice(5, 7), 16);
            return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
        }

    });

    /* =========================
       DESKTOP
    ========================= */
    $(".main-category-link").on("mouseenter click", function (e) {
        if (!isMobile()) {
            if (e.type === "click") e.preventDefault();
            handleMainCategory($(this));
        }
    });

    // Handle Our Packages link
    $(".packages-link").on("mouseenter click", function (e) {
        if (!isMobile()) {
            if (e.type === "click") e.preventDefault();
            handlePackages($(this));
        }
    });

    // Handle About Alpha Health Group link (shows its sub-links in the right panel)
    $(".group-link").on("mouseenter click", function (e) {
        if (!isMobile()) {
            if (e.type === "click") e.preventDefault();
            handleGroup($(this));
        }
    });

    // Handle Knowledge Base / Case Studies (3-level like the categories)
    // Knowledge Base: hover reveals blog categories; CLICK navigates to the insights main page (default href).
    $(".kb-link").on("mouseenter", function (e) {
        if (!isMobile()) { handleKB($(this)); }
    });
    // Case Studies: hover reveals project categories; CLICK navigates to the Case Studies main page (default href).
    $(".cs-link").on("mouseenter", function (e) {
        if (!isMobile()) { handleCS($(this)); }
    });

    $(document).on("mouseenter click", ".main-category-link-sub:not(.service-group-link):not(.cs-flat-link):not(.kb-flat-link)", function (e) {
        if (!isMobile()) {
            if (e.type === "mouseenter") {
                handleSubCategory($(this));
            }
        }
    });

    /* =========================
       MOBILE: MAIN CATEGORY
    ========================= */
    $(".main-category-link").on("click", function (e) {
        if (isMobile()) {
            e.preventDefault();

            let mainId = $(this).attr("id");
            let title = $(this).text().trim();

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();

            $(".main-category-link").removeClass("active-category");
            $(".packages-link").removeClass("active-category");
            $(this).addClass("active-category");

            // 🔥 UPDATE HEADING + DESCRIPTION
            $("#dynamic-heading").text(title);
            $("#dynamic-desc").text("Explore our specialized " + title + " categories.");

            let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');

            $(".filter-" + mainId).each(function () {
                let clone = $(this).clone();
                clone.show();
                // Add expand chevron to expandable sub-categories (skip service-group links and disabled)
                clone.find(".main-category-link-sub:not(.service-group-link):not(.disabled)").each(function () {
                    if (!$(this).find(".mobile-expand-chevron").length) {
                        $(this).append('<span class="mobile-expand-chevron" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>');
                    }
                });
                subList.append(clone);
            });

            $(this).parent().append(subList);
        }
    });

    // Handle Our Packages on Mobile
    $(".packages-link").on("click", function (e) {
        if (isMobile()) {
            e.preventDefault();

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();

            $(".main-category-link").removeClass("active-category");
            $(".packages-link").addClass("active-category");

            // UPDATE HEADING + DESCRIPTION
            $("#dynamic-heading").text("Our Packages");
            $("#dynamic-desc").text("Explore our service packages and solutions.");

            let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');

            $(".filter-packages").each(function () {
                let clone = $(this).clone();
                clone.show();
                subList.append(clone);
            });

            $(this).parent().append(subList);
        }
    });

    // Handle About Alpha Health Group on Mobile
    $(".group-link").on("click", function (e) {
        if (isMobile()) {
            e.preventDefault();

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();

            $(".main-category-link").removeClass("active-category");
            $(".packages-link").removeClass("active-category");
            $(".group-link").addClass("active-category");

            $("#dynamic-heading").text("Who We Are");
            $("#dynamic-desc").text("Alpha Health Group delivers integrated consulting, compliance, operational, and engineering solutions that help healthcare organisations build, operate, and grow with confidence.");

            let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');
            $(".filter-group").each(function () {
                let clone = $(this).clone();
                clone.show();
                subList.append(clone);
            });

            $(this).parent().append(subList);
        }
    });

    // Handle Knowledge Base / Case Studies on Mobile (tap a topic → its articles/projects)
    function mobileGroupedDrilldown($trigger, filterClass, heading, desc) {
        $(".mobile-sub-list").remove();
        $(".mobile-service-list").remove();
        $(".main-category-link, .packages-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        $trigger.addClass("active-category");
        $("#dynamic-heading").text(heading);
        $("#dynamic-desc").text(desc);

        let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');
        $('.' + filterClass).each(function () {
            let clone = $(this).clone();
            clone.show();
            clone.find(".main-category-link-sub:not(.service-group-link):not(.disabled)").each(function () {
                if (!$(this).find(".mobile-expand-chevron").length) {
                    $(this).append('<span class="mobile-expand-chevron" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>');
                }
            });
            subList.append(clone);
        });
        $trigger.parent().append(subList);
    }
    // Knowledge Base on mobile: tap navigates straight to the insights main page (no drilldown).
    // Case Studies on mobile: tap navigates straight to the Case Studies main page (no drilldown).

    /* =========================
       MOBILE: SUB CATEGORY (L2 → accordion reveals L3 services)
    ========================= */

    // Disabled links: always block navigation
    $(document).on("click", ".main-category-link-sub.disabled", function (e) {
        e.preventDefault();
    });

    // Expandable sub-categories: accordion on mobile, navigate on desktop (handled by hover above)
    $(document).on("click", ".main-category-link-sub:not(.service-group-link):not(.disabled)", function (e) {
        if (!isMobile()) return; // desktop uses hover — no change needed there

        e.preventDefault(); // block page nav; user navigates via the "Visit →" link inside L3

        var subId    = $(this).attr("id") || "";              // "sub_5"
        var catId    = subId.replace("sub_", "");             // "5"
        var catName  = $(this).clone().children().remove().end().text().trim();
        var catUrl   = $(this).attr("href");
        var parentLi = $(this).closest("li");

        // ── Toggle: collapse if already open ──────────────────
        if (parentLi.hasClass("mobile-sub-expanded")) {
            parentLi.removeClass("mobile-sub-expanded");
            parentLi.find(".mobile-service-list").slideUp(180, function () { $(this).remove(); });
            return;
        }

        // ── Collapse any other open L3 ────────────────────────
        $(".mobile-sub-expanded").each(function () {
            $(this).removeClass("mobile-sub-expanded");
            $(this).find(".mobile-service-list").slideUp(180, function () { $(this).remove(); });
        });

        parentLi.addClass("mobile-sub-expanded");

        // ── Build L3 panel ────────────────────────────────────
        var $panel = $('<ul class="mobile-service-list list-unstyled"></ul>');

        // Actual services under this sub-category
        var $services = $(".filter-sub_" + catId);
        if ($services.length > 0) {
            $services.each(function () {
                $panel.append($(this).clone().show());
            });
        } else {
            $panel.append('<li class="mobile-no-services"><span>No services listed yet</span></li>');
        }

        // "Visit [Category] page →" — last item so users can navigate to the full page
        if (catUrl && catUrl !== "#") {
            $panel.append(
                '<li class="mobile-visit-category">' +
                '<a href="' + catUrl + '">' +
                '<i class="fas fa-arrow-right"></i>&nbsp;Visit ' + catName + ' page' +
                '</a></li>'
            );
        }

        $panel.hide();
        parentLi.append($panel);
        $panel.slideDown(250); // enter slower than exit — feels responsive (MD motion rule)
    });

    /* =========================
       SHARED FUNCTIONS
    ========================= */
    // Curated default panel (open state) ⇄ real menu content (after a trigger).
    function showMenuDefaultPanel() {
        // Use explicit display values — both containers are CSS grid, so jQuery .show() (which
        // forces display:block) would break their layout.
        $("#menu-default-panel").css("display", "grid");
        $(".categories_service").css("display", "none");
        $(".main-category-link, .packages-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        $("#dynamic-heading").text("Our Expertise");
        $("#dynamic-desc").text("Explore our most-requested services, or choose an area from the left.");
        startPromoSlider();
    }
    function revealMenuContent() {
        $("#menu-default-panel").css("display", "none");
        $(".categories_service").css("display", "grid");
        stopPromoSlider();
    }

    /* Mega-menu promo auto-slider (dashboard-managed, max 3) */
    var mpTimer = null, mpIdx = 0;
    function mpSlides() { return $("#mdp-promo-slider .mdp-promo-slide"); }
    function mpGo(i) {
        var $s = mpSlides();
        if (!$s.length) return;
        mpIdx = (i + $s.length) % $s.length;
        $s.removeClass("active").eq(mpIdx).addClass("active");
        $("#mdp-promo-slider .mdp-promo-dot").removeClass("active").eq(mpIdx).addClass("active");
    }
    function startPromoSlider() {
        stopPromoSlider();
        if (mpSlides().length <= 1) return;
        mpTimer = setInterval(function () { mpGo(mpIdx + 1); }, 9000);
    }
    function stopPromoSlider() { if (mpTimer) { clearInterval(mpTimer); mpTimer = null; } }
    // Clickable dots (jump + restart), pause on hover — interruptible, premium feel.
    $(document).on("click", "#mdp-promo-slider .mdp-promo-dot", function () {
        mpGo(parseInt($(this).attr("data-idx"), 10) || 0);
        startPromoSlider();
    });
    $(document).on("mouseenter", "#mdp-promo-slider", stopPromoSlider);
    $(document).on("mouseleave", "#mdp-promo-slider", startPromoSlider);

    function handleGroup(el) {
        revealMenuContent();
        const title = "Who We Are";

        $(".main-category-link, .packages-link, .kb-link, .cs-link").removeClass("active-category");
        $(".group-link").addClass("active-category");

        // Show only the group's sub-links in the right panel.
        $(".service-content-category").hide();
        $(".service-group-item").hide();
        $(".filter-group").show();
        $(".serv-col").hide();

        $("#dynamic-heading").text(title);
        $("#dynamic-desc").text("Alpha Health Group delivers integrated consulting, compliance, operational, and engineering solutions that help healthcare organisations build, operate, and grow with confidence.");
    }

    // Knowledge Base / Case Studies: level-1 trigger → show its sub-list (tags / project
    // categories). Hovering a sub-item then reveals level-3 (blogs / projects) via handleSubCategory.
    function handleGroupedDrilldown(filterClass, heading, desc) {
        revealMenuContent();
        $(".service-content-category").hide();
        $(".service-group-item").hide();
        $('.' + filterClass).show();
        $(".serv-col").show();
        $(".service-item").hide();
        $(".view-all-services-wrapper").hide();
        $("#dynamic-heading").text(heading);
        $("#dynamic-desc").text(desc);
    }

    function handleKB(el) {
        $(".main-category-link, .packages-link, .group-link, .cs-link").removeClass("active-category");
        $(".kb-link").addClass("active-category");
        handleGroupedDrilldown("filter-kb", "Knowledge Base", "Browse our insights by topic, then pick an article.");
    }

    function handleCS(el) {
        $(".main-category-link, .packages-link, .group-link, .kb-link").removeClass("active-category");
        $(".cs-link").addClass("active-category");
        handleGroupedDrilldown("filter-cs", "Case Studies", "Explore our projects by category, then open a case study.");
    }

    function handleMainCategory(el) {
        revealMenuContent();
        const mainId = el.attr("id");
        const title = el.text().trim();

        $(".main-category-link, .packages-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        el.addClass("active-category");

        $(".service-content-category").hide();
        $(".service-group-item").hide();
        $(".filter-" + mainId).show();

        // Show services column and hide items
        $(".serv-col").show();
        $(".service-item").hide();
        $(".view-all-services-wrapper").hide();

        $("#dynamic-heading").text(title);
        $("#dynamic-desc").text("Explore our specialized " + title + " categories.");
    }

    function handleSubCategory(el) {
        const subId = el.attr("id");
        const title = el.text().trim();
        const subCategoryUrl = el.attr("href");

        $(".main-category-link-sub").removeClass("active-category");
        el.addClass("active-category");

        // Show services column and display filtered items
        $(".serv-col").show();
        $(".service-item").hide();
        $(".filter-" + subId).show();
        $(".view-all-services-wrapper").show();

        // Context-aware "View All" button: Knowledge Base / Case Studies → "View All",
        // service categories → "View All Services".
        var $btn = $(".view-all-services-btn");
        if (subId && subId.indexOf("tag_") === 0) {
            $btn.attr("href", "{{ route('front.new_blog') }}").html('View All <i class="fas fa-arrow-right ms-2"></i>');
            $("#dynamic-desc").text("Articles under " + title + ".");
        } else if (subId && subId.indexOf("pcat_") === 0) {
            $btn.attr("href", "{{ route('front.project') }}").html('View All <i class="fas fa-arrow-right ms-2"></i>');
            $("#dynamic-desc").text("Case studies under " + title + ".");
        } else {
            $btn.attr("href", subCategoryUrl && subCategoryUrl !== "#" ? subCategoryUrl : "{{ route('front.all-services') }}")
                .html('View All Services <i class="fas fa-arrow-right ms-2"></i>');
            $("#dynamic-desc").text("Specialized services available under " + title + ".");
        }

        $("#dynamic-heading").text(title);
    }

    function handlePackages(el) {
        revealMenuContent();
        const title = "Our Packages";

        $(".main-category-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        $(".packages-link").addClass("active-category");

        $(".service-content-category").hide();
        $(".service-group-item").hide();   // clear group + other items first
        $(".filter-packages").show();

        // Hide the entire services column
        $(".serv-col").hide();

        $("#dynamic-heading").text(title);
        $("#dynamic-desc").text("Explore our service packages and solutions.");
    }

});

    </script>


    <!-- FOOTER SECTION -->

    @yield('content')


    <footer class="premium-footer" style="">
        <!-- Animated Background Elements -->
        <div class="footer-bg-elements">
            <div class="bg-circle"></div>
            <div class="bg-circle"></div>
            <div class="bg-circle"></div>
        </div>

        <!-- Particles -->
        <div class="particles" id="particles"></div>

        <div class="footer-content">
            <!-- Top Section -->
            <div class="footer-top">
                <!-- Mission Statement -->
                <div class="mission-statement">
                    <h3>Our Mission</h3>
                    <p class="mission-text">
                        We provide world-class healthcare management consultancy with a focus on
                        safety, compassion, and excellence. Partnering with facilities, health systems,
                        and government entities across the UAE to deliver exceptional care.
                    </p>
                    <div class="mission-highlight">
                        <span>Committed to innovating healthcare excellence since 2015</span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="quick-links-grid">
                    <div class="links-column">
                        <h4>About AHG</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('front.new-about') }}"><i class="fas fa-chevron-right"></i> About Alpha Health Group</a></li>
                            <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                            <li><a href="{{ route('front.ahg-updates') }}"><i class="fas fa-chevron-right"></i> AHG Updates</a></li>
                            <li><a href="{{ route('front.our-clients') }}"><i class="fas fa-chevron-right"></i> Our Clients</a></li>
                        </ul>
                    </div>

                    <div class="links-column">
                        <h4>Resources</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('front.all-services') }}"><i class="fas fa-chevron-right"></i> All Services</a></li>
                            <li><a href="{{ route('front.new_blog') }}"><i class="fas fa-chevron-right"></i> Knowledge Base</a></li>
                            <li><a href="{{ route('front.project') }}"><i class="fas fa-chevron-right"></i> Case Studies</a></li>
                            <li><a href="{{ route('front.facility-types') }}"><i class="fas fa-chevron-right"></i> Services by Facility Type</a></li>
                        </ul>
                    </div>
                </div>
            </div>


            <!-- Middle Section -->
            <div class="footer-middle">
                <!-- Logo -->
                <div class="footer-logo">
                    <img src="{{ asset('public/front-new/assets/images/about/alpha-logo5.png') }}" alt="AlphaHealth"
                        style="width:100px;height:auto;object-fit:contain;display:block;" />

                </div>


                <!-- Policies -->
                <div class="footer-policies" style="text-align: center">
                    <a href="{{ url('/alpha-privacy-policy') }}" class="policy-link">Privacy Policy</a>
                    <a href="{{ route('front.terms-of-service') }}" class="policy-link">Terms of Service</a>
                    <a href="{{ route('front.cookie-policy') }}" class="policy-link">Cookie Policy</a>
                    <a href="{{ route('front.gdpr-terms') }}" class="policy-link">GDPR &amp; Data Protection</a>
                </div>


                <!-- Social Media -->
                <div class="social-section">
                    <span class="social-label">Connect With Us:</span>
                    <div class="social-icons">
                        <a href="https://www.linkedin.com/company/alphatsm" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://x.com/alphatsm_" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/alphatsm" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/alphahealthgroup/" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="contact-info">
                    <a href="mailto:info@alphatsm.com" class="contact-email">
                        <i class="fas fa-envelope"></i>
                        info@alphatsm.com
                    </a>
                    <a href="tel:+97142724064" class="contact-phone">
                        <i class="fas fa-phone"></i>
                        +971 4 272 4064
                    </a>
                </div>
            </div>
        </div>





        <!-- Bottom Section -->
        <div class="footer-bottom" style="font-family: sans-serif">
            <div class="legal-text" style="max-width: 90%; margin-left: auto; margin-right: auto;">
                Alpha Health Group provides healthcare management consultancy, accreditation support, and advisory services to healthcare organisations across the GCC and Middle East.
                All consultancy services are provided for organisational and management purposes only and do not constitute clinical medical advice or treatment.
                <div style="text-align: center"> <br>© {{ date('Y') }} Alpha Health Group. All rights reserved.</div>
            </div>

            {{-- <div class="contact-info">
                <a href="mailto:info@alphatsm.com" class="contact-email">
                    <i class="fas fa-envelope"></i>
                    info@alphatsm.com
                </a>
                <div class="contact-phone">
                    <i class="fas fa-phone"></i>
                    +971 4 272 4064
                </div>
            </div>
        </div>
        </div> --}}

        <!-- Back to Top Button INSIDE Footer -->
        <div class="footer-go-to-top" id="footerGoToTop">
            <button class="go-to-top-btn" id="goToTopBtn" aria-label="Scroll to top">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>


    </footer>


    


    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 10;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Random properties
                const size = Math.random() * 4 + 1;
                const posX = Math.random() * 100;
                const delay = Math.random() * 15;
                const duration = Math.random() * 10 + 15;

                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;

                particlesContainer.appendChild(particle);
            }
        }

        // Back to top functionality for footer button
        const footerGoToTopBtn = document.getElementById('footerGoToTop');
        const goToTopBtn = document.getElementById('goToTopBtn');

        if (footerGoToTopBtn && goToTopBtn) {
            // Show/hide button based on scroll position
            window.addEventListener('scroll', () => {
                const scrollPosition = window.pageYOffset;
                const windowHeight = window.innerHeight;
                const documentHeight = document.documentElement.scrollHeight;

                // Show button when scrolled down a bit
                if (scrollPosition > 300) {
                    footerGoToTopBtn.classList.add('show');

                    // Add pulse animation when near bottom
                    if (documentHeight - (scrollPosition + windowHeight) < 100) {
                        goToTopBtn.classList.add('pulse');
                    } else {
                        goToTopBtn.classList.remove('pulse');
                    }
                } else {
                    footerGoToTopBtn.classList.remove('show');
                    goToTopBtn.classList.remove('pulse');
                }
            });

            // Scroll to top when button is clicked
            goToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                // Add click animation
                goToTopBtn.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    goToTopBtn.style.transform = '';
                }, 200);
            });
        }

        // Animate links on hover
        const links = document.querySelectorAll('.footer-links a, .policy-link');
        links.forEach(link => {
            link.addEventListener('mouseenter', (e) => {
                const icon = e.target.querySelector('i');
                if (icon) {
                    icon.style.transform = 'translateX(3px)';
                    icon.style.transition = 'transform 0.3s ease';
                }
            });

            link.addEventListener('mouseleave', (e) => {
                const icon = e.target.querySelector('i');
                if (icon) {
                    icon.style.transform = 'translateX(0)';
                }
            });
        });

        // Social icon hover effects
        const socialIcons = document.querySelectorAll('.social-icon');
        socialIcons.forEach(icon => {
            icon.addEventListener('mouseenter', () => {
                icon.style.transform = 'translateY(-8px) rotate(8deg) scale(1.1)';
            });

            icon.addEventListener('mouseleave', () => {
                icon.style.transform = 'translateY(0) rotate(0) scale(1)';
            });
        });

        // Logo rotation
        const logoIcon = document.querySelector('.logo-icon');
        if (logoIcon) {
            logoIcon.addEventListener('mouseenter', () => {
                logoIcon.style.transform = 'rotateY(180deg) scale(1.1)';
            });

            logoIcon.addEventListener('mouseleave', () => {
                logoIcon.style.transform = 'rotateY(0) scale(1)';
            });
        }

        // Mission highlight animation
        const missionHighlight = document.querySelector('.mission-highlight');
        if (missionHighlight) {
            missionHighlight.addEventListener('mouseenter', () => {
                missionHighlight.style.transform = 'translateX(15px)';
            });

            missionHighlight.addEventListener('mouseleave', () => {
                missionHighlight.style.transform = 'translateX(0)';
            });
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();

            // Animate links on page load
            const linkItems = document.querySelectorAll('.footer-links li');
            linkItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.animation = `slideIn 0.5s forwards`;
                }, index * 100 + 300);
            });

            // Add scroll reveal animation
            const footerElements = document.querySelectorAll('.footer-top, .footer-middle, .footer-bottom');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            footerElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                observer.observe(el);
            });
        });

        // Add subtle parallax effect to background elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const bgCircles = document.querySelectorAll('.bg-circle');

            bgCircles.forEach((circle, index) => {
                const speed = 0.1 + (index * 0.05);
                const yPos = -(scrolled * speed);
                circle.style.transform = `translateY(${yPos}px)`;
            });
        });
    </script>



    <script>
        $(document).ready(function () {
            $("#menu-btn").click(function () {
                $("#sidebar-menu").toggleClass("show");

                if ($("#sidebar-menu").hasClass("show")) {
                    $("#menu-icon").removeClass("la-bars las").addClass("la-times las");
                    $("#main-container").removeClass("p-md-5 p-2").addClass("p-0");
                    $(".btn-menu").removeClass("btn-menu").addClass("extend-btn");
                    $(".btn-menu-1").removeClass("btn-menu-1").addClass("extend-btn-1");

                    $("#search-input").addClass("search-input-extend");
                    $("#search").css({
                        flex: "1"
                    });
                    $("#btn-menu-border").addClass("extend-btn-menu-border");
                } else {
                    $("#menu-icon").removeClass("la-times las").addClass("la-bars las");
                    $("#main-container").removeClass("p-0").addClass("p-md-5 p-2");
                    $(".extend-btn").removeClass("extend-btn").addClass("btn-menu");
                    $(".extend-btn-1")
                        .removeClass("extend-btn-1")
                        .addClass("btn-menu-1");
                    $("#search-input").removeClass("search-input-extend");
                    $("#search").css({
                        flex: "unset"
                    });
                    $("#btn-menu-border").removeClass("extend-btn-menu-border");
                }
            });



            $(document).on('click', '.main-category-link', function (e) {
                e.preventDefault();

                const clickedId = $(this).attr('id');
                const parentId = $(this).data('parent-id-main');

                // If it's a main category
                if (typeof parentId === 'undefined') {
                    // Remove all current highlights
                    $('.main-category-link').removeClass('active-category');

                    // Highlight this main category
                    $(this).addClass('active-category');
                }
                // If it's a sub-category
                else {
                    // Remove only other sub-category selections
                    $('.main-category-link[data-parent-id-main]').removeClass('active-category');

                    // Highlight the clicked sub-category
                    $(this).addClass('active-category');

                    // Also highlight the parent main category
                    $('.main-category-link#' + parentId).addClass('active-category');
                }
            });

        });

        $(document).ready(function () {
            if ($(window).width() < 576) {
                $("#menu-btn").click(function () {
                    if ($("#sidebar-menu").hasClass("show")) {
                        $("#search").addClass("d-none");
                        $("#new-menu-icon").addClass("d-none");
                        $(".log-btn-1").addClass("ms-auto");
                    } else {
                        $("#search").removeClass("d-none");
                        $("#new-menu-icon").removeClass("d-none");
                        $(".log-btn-1").removeClass("ms-auto");
                    }

                    $(".main-category-link").click(function () {
                        $(".sidebar-content-2").removeClass("d-none").addClass(
                            "d-block");

                        $("#menu-service").addClass("d-none");
                    });

                    $(".sub-category-link").click(function () {
                        // $(".sub_categories").addClass("d-none");
                        // $("#back-btn").addClass("d-none");
                        // $("#back-btn-1").addClass("d-block").removeClass("d-none");
                        // $("#menu-service").addClass("d-none");
                    });

                    $("#back-btn").click(function () {
                        $(".sidebar-content-2").addClass("d-none").removeClass(
                            "d-block");
                        $("#menu-service").removeClass("d-none").addClass("d-block");
                    });

                    // $("#back-btn-1").click(function() {
                    //     $("#back-btn").addClass("d-block").removeClass("d-none");
                    // });
                });
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Main Categories (top level, no data-parent-id-main)
            const mainCategoryLinks = Array.from(document.querySelectorAll('a.main-category-link'))
                .filter(link => !link.hasAttribute('data-parent-id-main'));

            // 2. Sub Categories (have data-parent-id-main)
            const subCategoryLinks = Array.from(document.querySelectorAll('a[data-parent-id-main]'));

            // 3. Services (have data-parent-id)
            const serviceLinks = Array.from(document.querySelectorAll('a[data-parent-id]'));

            // Click on Main Category => show matching Sub Categories
            mainCategoryLinks.forEach(mainLink => {
                mainLink.addEventListener('click', function (e) {
                    e.preventDefault();

                    const mainCategoryId = this.id;

                    subCategoryLinks.forEach(subLink => {
                        if (subLink.getAttribute('data-parent-id-main') ===
                            mainCategoryId) {
                            subLink.parentElement.style.display =
                                'block'; // show matching subcategories
                        } else {
                            subLink.parentElement.style.display = 'none'; // hide others
                        }
                    });

                    // Hide all services when main category is clicked
                    serviceLinks.forEach(serviceLink => {
                        serviceLink.parentElement.style.display = 'none';
                    });
                });
            });

            // Click on Sub Category => show matching Services
            subCategoryLinks.forEach(subLink => {
                subLink.addEventListener('click', function (e) {
                    e.preventDefault();

                    const subCategoryId = this.id;

                    serviceLinks.forEach(serviceLink => {
                        if (serviceLink.getAttribute('data-parent-id') === subCategoryId) {
                            serviceLink.parentElement.style.display =
                                'block'; // show matching services
                        } else {
                            serviceLink.parentElement.style.display = 'none'; // hide others
                        }
                    });
                });
            });
        });
    </script>

    <!-- jQuery is loaded at the top -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
        </script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            loop: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            spaceBetween: 0,
            speed: 600,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {

                1024: {
                    slidesPerView: "auto",
                    spaceBetween: 60,
                    coverflowEffect: {
                        depth: 0,
                    },
                },
            },

            on: {
                init: function () {
                    this.slides.forEach((slide) => {
                        slide.classList.remove("active-slide");
                        slide.style.transform = "scale(0.85)";
                        slide.style.opacity = "0.7";
                        slide.querySelector(".img-content")?.classList.remove("show");
                        slide.querySelector(".category-1")?.classList.remove("show");
                    });
                    const activeSlide = this.slides[this.activeIndex];
                    activeSlide.classList.add("active-slide");
                    activeSlide.style.transform = "scale(1.2)";
                    activeSlide.style.opacity = "1";
                    activeSlide.querySelector(".img-content")?.classList.add("show");
                    activeSlide.querySelector(".category-1")?.classList.add("show");
                },
                slideChange: function () {
                    this.slides.forEach((slide) => {
                        slide.classList.remove("active-slide");
                        slide.style.transform = "scale(0.85)";
                        slide.style.opacity = "0.7";
                        slide.querySelector(".img-content")?.classList.remove("show");
                        slide.querySelector(".category-1")?.classList.remove("show");
                    });
                    const activeSlide = this.slides[this.activeIndex];
                    activeSlide.classList.add("active-slide");
                    activeSlide.style.transform = "scale(1)";
                    activeSlide.style.opacity = "1";
                    activeSlide.querySelector(".img-content")?.classList.add("show");
                    activeSlide.querySelector(".category-1")?.classList.add("show");
                },
            },
        });
    </script>





    <script></script>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true, // Animation runs only once
            mirror: false, // Prevents animation when scrolling back up
        });
    </script>

    @stack('scripts')
    @yield('custom_js')

    {{-- ═══════════════════════════════════════════════════════════════
         CONVERSION TRACKING — GTM-READY
         Pushes dataLayer events for every conversion intent.
         When you add your GTM ID, create triggers in GTM matching:
           event = 'ahg_phone_call'
           event = 'ahg_whatsapp_click'
           event = 'ahg_email_click'
           event = 'ahg_inquiry_opened'
           event = 'ahg_inquiry_submitted'
           event = 'ahg_contact_submitted'
         Then attach Google Ads conversion tags to those triggers.
    ════════════════════════════════════════════════════════════════ --}}
    <script>
    (function () {
        'use strict';

        // ── Helper: push event to dataLayer + fire gtag directly ──────
        function trackConversion(eventName, params) {
            var payload = Object.assign({ event: eventName, page_url: window.location.href }, params || {});

            // 1. dataLayer push (GTM picks this up automatically)
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push(payload);

            // 2. gtag event (for GA4 / Google Ads when configured via GTM)
            if (typeof gtag === 'function') {
                gtag('event', eventName, params || {});
            }
        }

        // ── Global click tracker (event delegation, works on all pages) ─
        document.addEventListener('click', function (e) {
            var el = e.target.closest('a, button');
            if (!el) return;

            var href    = el.getAttribute('href') || '';
            var label   = el.getAttribute('data-track-label') || el.innerText.trim().substring(0, 60);
            var ctx = window._ahgPage || {};

            // ── Phone call ────────────────────────────────────────────
            if (href.indexOf('tel:') === 0) {
                trackConversion('ahg_phone_call', {
                    phone_number: href.replace('tel:', ''),
                    service_name: ctx.service_name || '',
                    element_label: label,
                });
                return;
            }

            // ── Email (mailto:) ───────────────────────────────────────
            if (href.indexOf('mailto:') === 0) {
                trackConversion('ahg_email_click', {
                    email_address: href.replace('mailto:', ''),
                    service_name: ctx.service_name || '',
                    element_label: label,
                });
                return;
            }

            // ── WhatsApp ──────────────────────────────────────────────
            if (href.indexOf('wa.me') !== -1 || href.indexOf('whatsapp') !== -1) {
                trackConversion('ahg_whatsapp_click', {
                    service_name: ctx.service_name || '',
                    element_label: label,
                });
                return;
            }

            // ── Inquiry / Contact modal triggers ──────────────────────
            var modalTarget = el.getAttribute('data-bs-target') || '';
            if (modalTarget === '#inquiryModal') {
                trackConversion('ahg_inquiry_opened', {
                    trigger_element: label || el.className,
                    service_name: ctx.service_name || '',
                });
                return;
            }
        });

        // ── Form submits ───────────────────────────────────────────────
        document.addEventListener('submit', function (e) {
            var form = e.target;
            var ctx  = window._ahgPage || {};

            if (form.id === 'inquiryForm') {
                trackConversion('ahg_inquiry_submitted', {
                    service_name: ctx.service_name || '',
                    form_id: 'inquiryForm',
                });
            }

            // ── Contact page form ──────────────────────────────────────
            if (form.id === 'contactForm' || form.classList.contains('contact-form')) {
                trackConversion('ahg_contact_submitted', {
                    form_id: form.id || 'contact_form',
                });
            }
        });

    })();
    </script>

    @include('front.partials.ai-assistant')

    {{-- Reusable global inquiry modal: pages opt in via @push('inquiry_modal') --}}
    @stack('inquiry_modal')
</body>

<script>
    function dismissLoginBanner() {
        var banner = document.getElementById('login-banner');
        if (banner) {
            banner.style.transition = 'opacity 0.3s ease, max-height 0.4s ease';
            banner.style.opacity   = '0';
            banner.style.maxHeight = '0';
            banner.style.overflow  = 'hidden';
            banner.style.padding   = '0';
            setTimeout(function() {
                banner.style.display = 'none';
            }, 400);
            // Remember dismissal for session
            sessionStorage.setItem('login_banner_dismissed', 'true');
        }
    }

    // Auto-dismiss if already dismissed this session
    document.addEventListener('DOMContentLoaded', function() {
        if (sessionStorage.getItem('login_banner_dismissed') === 'true') {
            var banner = document.getElementById('login-banner');
            if (banner) banner.style.display = 'none';
        }
    });
</script>

</html>