<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="robots" content="index, follow">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@stack('page_title')</title>
    <meta name="description" content="@yield('meta_description', 'Alpha Health Group delivers expert healthcare consultancy, DOH compliance, quality assurance, and operational excellence for healthcare facilities in the UAE.')">
    @stack('meta')

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Default Open Graph tags — pages can override via @push('og_tags') --}}
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@stack('page_title')" />
    <meta property="og:description" content="@yield('meta_description', 'Alpha Health Group delivers expert healthcare consultancy, DOH compliance, quality assurance, and operational excellence for healthcare facilities in the UAE.')" />
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
        gtag('set', 'url_passthrough', true);
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
                'Question'           => $faq->faq_question,
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
        'areaServed' => $service->areaServed,
        'serviceType' => $service->serviceType,
        'provider'    => [
            '@type' => 'Organization',
            'name'  => 'Alpha Health Consultancies',  /*config('app.name')*/
        ],
    ];

    if (!empty($areaServed)) {
        $professionalService['areaServed'] = $areaServed;
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


    {{-- Google Tag --}}
@foreach($googletags as $tag)
    <script type="application/ld+json">
        {!!  strip_tags( $tag->tags) !!}
    </script>
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
            'sameAs' => [],
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
          href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap">
    </noscript>

    <!-- Bootstrap CSS — blocking (needed for above-fold grid layout) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />

    <!-- Site CSS — blocking (critical nav + layout styles) -->
    <link rel="stylesheet" href="{{ asset('public/front-new/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/front-new/assets/css/slide-menu.css') }}">

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

    <style>
    /* ============================================
       COOKIE CONSENT BANNER
    ============================================ */
    #cookie-consent-banner {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 40px);
        max-width: 860px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 4px 20px rgba(0, 144, 149, 0.1);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        z-index: 99999;
        border: 1px solid rgba(0, 144, 149, 0.1);
        flex-wrap: wrap;
    }

    .cookie-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .cookie-content {
        flex: 1;
        min-width: 200px;
    }

    .cookie-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
        font-size: 1rem;
    }

    .cookie-text {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }

    .cookie-policy-link {
        color: #009095;
        font-weight: 600;
        text-decoration: underline;
    }

    .cookie-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    .cookie-btn {
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .cookie-btn-reject {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .cookie-btn-reject:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .cookie-btn-accept {
        background: linear-gradient(135deg, #009095, #006e72);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 144, 149, 0.3);
    }

    .cookie-btn-accept:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 144, 149, 0.4);
    }

    .cookie-close {
        position: absolute;
        top: 10px;
        right: 14px;
        background: none;
        border: none;
        font-size: 0.85rem;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: color 0.2s ease;
    }

    .cookie-close:hover {
        color: #1e293b;
        background: #f1f5f9;
    }

    /* ============================================
       LOGIN BANNER
    ============================================ */
    .login-banner {
        width: 100%;
        background: linear-gradient(135deg, #009095 0%, #006e72 100%);
        color: #ffffff;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 0.875rem;
        position: relative;
        z-index: 9999;
        flex-wrap: wrap;
    }

    .login-banner-content {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .login-banner-content i {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .login-banner-content span {
        opacity: 0.95;
        font-weight: 400;
    }

    .login-banner-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .login-banner-btn {
        padding: 6px 20px;
        background: #ffffff;
        color: #009095;
        border: none;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }

    .login-banner-btn:hover {
        background: #f0fdfa;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .login-banner-close {
        background: none;
        border: none;
        color: rgba(255,255,255,0.7);
        font-size: 1rem;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s ease;
        line-height: 1;
    }

    .login-banner-close:hover {
        color: #ffffff;
        background: rgba(255,255,255,0.15);
    }

    /* ============================================
       ANIMATIONS
    ============================================ */
    @keyframes slideUpIn {
        from { opacity: 0; transform: translate(-50%, 40px); }
        to   { opacity: 1; transform: translate(-50%, 0);    }
    }

    @keyframes slideDownOut {
        from { opacity: 1; transform: translate(-50%, 0);    }
        to   { opacity: 0; transform: translate(-50%, 40px); }
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 600px) {
        #cookie-consent-banner {
            bottom: 0;
            left: 0;
            right: 0;
            transform: none;
            width: 100%;
            border-radius: 16px 16px 0 0;
            flex-direction: column;
            align-items: flex-start;
        }

        @keyframes slideUpIn {
            from { opacity: 0; transform: translateY(100%); }
            to   { opacity: 1; transform: translateY(0);    }
        }

        .cookie-actions {
            width: 100%;
        }

        .cookie-btn {
            flex: 1;
            text-align: center;
        }

        .login-banner {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }
    }
</style>

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
        gtag('set', 'url_passthrough', true);
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
                    <div class="btn-label">EXPLORE</div>
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

                    <div class="menu-group mt-5">
                        <h6 class="sidebar-menu-heading">OUR GROUP</h6>
                        <div class="service-content"><a href="{{ route('front.new-about') }}">About Alpha Health</a></div>
                        <div class="service-content"><a href="{{ route('front.brands') }}">Our Brands</a></div>
                        <div class="service-content"><a href="{{ route('front.new_blog') }}">Knowledge Base</a></div>
                        <div class="service-content"><a href="{{ route('front.project') }}">Case Studies</a></div>
                        <div class="service-content"><a href="{{ route('contact') }}">Contact Us</a></div>
                        <div class="service-content"><a href="{{ route('front.ahg-updates') }}">AHG Updates</a></div>
                    </div>
                </div>

                <!-- Right Column: Results -->
                <div class="sidebar-content-1">
                    <div class="sidebar-heading">
                        <h2 id="dynamic-heading">Our Expertise</h2>
                        <p id="dynamic-desc">Discover our comprehensive healthcare solutions tailored for your needs.
                        </p>
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

    <style>
@media (max-width: 768px) {
    .sidebar-content-1 {
        display: none;
    }

    .sidebar-content {
        width: 100%;
    }

    .mobile-sub-list,
    .mobile-service-list {
        margin-left: 15px;
        padding-top: 10px;
    }

    .mobile-sub-list li,
    .mobile-service-list li {
        display: block !important;
    }
    .nav-column .nav-right {
        display: none;

    }

    .view-all-services-wrapper {
        margin-left: 15px;
        margin-top: 20px;
    }

    /* ── Mobile L2: expandable row with chevron ── */
    .mobile-sub-list .main-category-link-sub:not(.service-group-link):not(.disabled) {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        min-height: 48px;
        touch-action: manipulation;
        padding-right: 4px;
    }

    .mobile-expand-chevron {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 50%;
        background: rgba(0, 144, 149, 0.08);
        color: #009095;
        font-size: 0.65rem;
        transition: transform 0.22s cubic-bezier(0.165, 0.84, 0.44, 1),
                    background 0.22s ease;
    }

    .mobile-sub-expanded > a .mobile-expand-chevron {
        transform: rotate(90deg);
        background: rgba(0, 144, 149, 0.18);
    }

    /* ── Mobile L3: compact modern panel ── */
    .mobile-service-list {
        background: #ffffff !important;
        border: 1px solid #eef2f7 !important;
        margin: 4px 0 8px 0 !important;
        padding: 5px 6px !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .mobile-service-list li {
        display: block !important;
    }

    /* "Visit page →" — slim inline teal link at bottom of panel */
    .mobile-visit-category {
        border-top: 1px solid #f1f5f9;
        margin-top: 4px;
        padding-top: 4px;
    }

    .mobile-visit-category a {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 8px 10px !important;
        font-size: 0.67rem !important;
        font-weight: 700 !important;
        color: #009095 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.8px !important;
        text-decoration: none !important;
        border-radius: 8px !important;
        min-height: 36px !important;
        touch-action: manipulation;
        transition: background 0.14s ease !important;
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    .mobile-visit-category a:active {
        background: rgba(0, 144, 149, 0.07) !important;
    }

    .mobile-visit-category a i {
        font-size: 0.58rem !important;
    }

    /* Service item rows — slim, clean, no heavy card */
    .mobile-service-list .service-item {
        position: relative;
        margin-bottom: 2px;
    }

    .mobile-service-list .service-item > a {
        display: block !important;
        padding: 9px 30px 9px 10px !important;
        font-size: 0.82rem !important;
        font-weight: 500 !important;
        color: #1e293b !important;
        text-decoration: none !important;
        min-height: 40px !important;
        background: transparent !important;
        border-radius: 9px !important;
        border: none !important;
        line-height: 1.35 !important;
        touch-action: manipulation;
        transition: background 0.14s ease !important;
    }

    .mobile-service-list .service-item > a:active {
        background: rgba(0, 144, 149, 0.07) !important;
    }

    /* Subtle chevron */
    .mobile-service-list .service-item::after {
        content: '›';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1rem;
        line-height: 1;
        color: #009095;
        opacity: 0.3;
        pointer-events: none;
    }

    /* Hairline divider between service items */
    .mobile-service-list .service-item + .service-item > a {
        border-top: 1px solid #f8fafc !important;
        border-radius: 0 0 9px 9px !important;
    }

    /* Empty state */
    .mobile-no-services span {
        display: block;
        padding: 10px 8px;
        font-size: 0.78rem;
        color: #94a3b8;
        font-style: italic;
        text-align: center;
    }
}

/* View All Services Button Styling */
.view-all-services-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 22px;
    background: transparent;
    color: var(--alpha-teal) !important;
    border: 1.5px solid var(--alpha-teal);
    border-radius: 30px;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: 0.82rem;
    letter-spacing: 0.2px;
    text-decoration: none;
    transition: background 0.22s ease, color 0.22s ease,
                transform 0.22s ease, box-shadow 0.22s ease;
}

.view-all-services-btn:hover {
    background: var(--alpha-teal);
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(6, 109, 119, 0.18);
}

.view-all-services-btn i {
    font-size: 0.75rem;
    transition: transform 0.22s ease;
}

.view-all-services-btn:hover i {
    transform: translateX(5px);
}
        </style>

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
    $("#explore-search-toggle").on("click", function () {
        const nav = $("#main-navbar");
        nav.toggleClass("menu-is-open");

        if (nav.hasClass("menu-is-open")) {
            $(this).find(".btn-label").text("CLOSE");
            $(this).addClass("is-active");
        } else {
            $(this).find(".btn-label").text("EXPLORE");
            $(this).removeClass("is-active");

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();
        }
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
            }
        });

        // ── Button click: go to results page ──────────────────
        $(".glass-search-btn").on("click", function () {
            performSearch();
        });

        // ── Click outside: close dropdown ─────────────────────
        $(document).on("click", function (e) {
            if (!$(e.target).closest(".glass-search-container").length) {
                hideDropdown();
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
                            item.type + 's' +
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

    $(document).on("mouseenter click", ".main-category-link-sub:not(.service-group-link)", function (e) {
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
    function handleMainCategory(el) {
        const mainId = el.attr("id");
        const title = el.text().trim();

        $(".main-category-link").removeClass("active-category");
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

        // Point the button to this sub-category's page
        $(".view-all-services-btn").attr("href", subCategoryUrl && subCategoryUrl !== "#" ? subCategoryUrl : "{{ route('front.all-services') }}");

        $("#dynamic-heading").text(title);
        $("#dynamic-desc").text("Specialized services available under " + title + ".");
    }

    function handlePackages(el) {
        const title = "Our Packages";

        $(".main-category-link").removeClass("active-category");
        $(".packages-link").addClass("active-category");

        $(".service-content-category").hide();
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
                    <h3>Our Healthcare Mission</h3>
                    <p class="mission-text">
                        We provide world-class healthcare management consultancy with a focus on
                        safety, compassion, and excellence. Partnering with facilities, health systems,
                        and government entities across the UAE to deliver exceptional care.
                    </p>
                    <div class="mission-highlight">
                        <i class="fas fa-heartbeat"></i>
                        <span>Committed to healthcare excellence since 2010</span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="quick-links-grid">
                    <div class="links-column">
                        <h4>Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('front.new-about') }}"><i class="fas fa-chevron-right"></i> About Alpha Health Group</a></li>
                            <li><a href="{{ route('front.all-services') }}"><i class="fas fa-chevron-right"></i> All Services</a></li>
                            <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                            <li><a href="{{ route('front.ahg-updates') }}"><i class="fas fa-chevron-right"></i> AHG Updates</a></li>
                            <li><a href="{{ route('front.our-clients') }}"><i class="fas fa-chevron-right"></i> Our Clients</a></li>
                        </ul>
                    </div>

                    <div class="links-column">
                        <h4>Resources</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('front.new_blog') }}"><i class="fas fa-chevron-right"></i> Knowledge Base</a></li>
                            <li><a href="{{ route('front.project') }}"><i class="fas fa-chevron-right"></i> Case Studies</a></li>
                            <li><a href="{{ route('front.all-services') }}"><i class="fas fa-chevron-right"></i> Services by Facility Type</a></li>
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
                    <div class="contact-phone">
                        <i class="fas fa-phone"></i>
                        +971 4 272 4064
                    </div>
                </div>
            </div>
        </div>





        <!-- Bottom Section -->
        <div class="footer-bottom" style="font-family: sans-serif">
            <div class="legal-text">
                Alpha Health Group provides healthcare management consultancy, accreditation support, and advisory services to healthcare organisations across the GCC and Middle East.
                All consultancy services are provided for organisational and management purposes only and do not constitute clinical medical advice or treatment.
                <a href="{{ route('front.terms-of-service') }}" class="legal-link">Terms of Service</a> &middot;
                <a href="{{ route('front.gdpr-terms') }}" class="legal-link">Data Protection Policy</a>.
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


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'roboto';
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .main-content {
            flex: 1;
            padding: 40px 20px;
            text-align: center;
            color: #334155;
        }

        .main-content h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #0056a6 0%, #4CAF50 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Premium Footer Styles */
        .premium-footer {
            background: linear-gradient(180deg, #009095 0%, #000000 100%);
            color: #fff;
            font-family: 'Roboto', sans-serif;
            position: relative;
            overflow: hidden;
            padding-top: 60px;
            box-shadow: 0 -10px 30px rgba(0, 86, 166, 0.1);
            margin-right: 0%;

        }

        /* Animated Background Elements */
        .footer-bg-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(76, 175, 80, 0.05);
            animation: float 20s infinite linear;
        }

        .bg-circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -100px;
            animation-delay: 0s;
        }

        .bg-circle:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: 100px;
            left: -50px;
            animation-delay: -5s;
            animation-duration: 15s;
        }

        .bg-circle:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 30%;
            right: 20%;
            animation-delay: -10s;
            animation-duration: 25s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, 30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 40px) rotate(240deg);
            }
        }

        /* Footer Content */
        .footer-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0 8%;
        }

        /* Top Section */
        .footer-top {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 100px;
            margin-bottom: 50px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Mission Statement */
        .mission-statement h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }

        .mission-statement h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #4CAF50, #0056a6);
            border-radius: 2px;
        }

        .mission-text {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #cbd5e1;
            margin-bottom: 25px;
        }

        .mission-highlight {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: rgba(76, 175, 80, 0.1);
            border-left: 4px solid #4CAF50;
            border-radius: 0 8px 8px 0;
            font-weight: 500;
            transition: transform 0.3s ease;
            background: 0.3s ease;
        }

        .mission-highlight:hover {
            transform: translateX(10px);
            background: rgba(76, 175, 80, 0.2);
        }

        .mission-highlight i {
            color: #4CAF50;
            font-size: 1.2rem;
        }

        /* Quick Links Grid */
        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .links-column h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
            position: relative;
            padding-bottom: 8px;
        }

        .links-column h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: #4CAF50;
            transition: width 0.3s ease;
        }

        .links-column:hover h4::after {
            width: 40px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
            opacity: 0;
            transform: translateX(-10px);
            animation: slideIn 0.5s forwards;
        }

        .footer-links li:nth-child(1) {
            animation-delay: 0.1s;
        }

        .footer-links li:nth-child(2) {
            animation-delay: 0.2s;
        }

        .footer-links li:nth-child(3) {
            animation-delay: 0.3s;
        }

        .footer-links li:nth-child(4) {
            animation-delay: 0.4s;
        }

        .footer-links li:nth-child(5) {
            animation-delay: 0.5s;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            padding: 4px 0;
        }

        .footer-links a::before {
            content: '→';
            color: #4CAF50;
            font-weight: bold;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 5px;
        }

        .footer-links a:hover::before {
            opacity: 1;
            transform: translateX(0);
        }

        /* Middle Section */
        .footer-middle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-wrap: wrap;
            gap: 30px;
        }

        /* Logo */
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0056a6, #4CAF50);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 86, 166, 0.3);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .logo-icon:hover {
            transform: rotateY(180deg);
            box-shadow: 0 8px 25px rgba(0, 86, 166, 0.4);
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Policies */
        .footer-policies {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .policy-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            position: relative;
            padding: 5px 0;
            transition: color 0.3s ease;
        }

        .policy-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #4CAF50, #0056a6);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .policy-link:hover {
            color: #fff;
        }

        .policy-link:hover::after {
            width: 100%;
        }

        /* Social Media */
        .social-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .social-label {
            font-size: 0.9rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .social-icons {
            display: flex;
            gap: 12px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .social-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.7s ease;
        }

        .social-icon:hover::before {
            left: 100%;
        }

        .social-icon:hover {
            transform: translateY(-5px) rotate(5deg);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .social-icon:nth-child(1):hover {
            background: #0077b5;
        }

        /* X / Twitter */
        .social-icon:nth-child(2):hover {
            background: #1da1f2;
        }

        /* Facebook */
        .social-icon:nth-child(3):hover {
            background: #4267B2;
        }

        /* Instagram */
        .social-icon:nth-child(4):hover {
            background: #E4405F;
        }

        /*

        /* Bottom Section */
        .footer-bottom {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 0;
            flex-direction: column;
            flex-wrap: wrap;
            gap: 20px;
            position: relative;
            padding-bottom: 100px !important;
        }

        .legal-text {
            font-size: 0.85rem;
            color: #94a3b8;
            line-height: 1.6;
            width: 100%;
            text-align: center;
            margin: 0 auto;
        }

        .legal-link {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
            position: relative;
            transition: color 0.3s ease;
        }

        .legal-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #4CAF50;
            transition: width 0.3s ease;
        }

        .legal-link:hover {
            color: #fff;
        }

        .legal-link:hover::after {
            width: 100%;
        }

        .contact-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .contact-email {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-email:hover {
            background: rgba(76, 175, 80, 0.2);
            transform: translateY(-2px);
        }

        .contact-phone {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(0, 86, 166, 0.1);
            border-radius: 6px;
            font-weight: 500;
        }

        .contact-phone i {
            color: #0056a6;
        }

        /* Back to Top Button INSIDE Footer */
        .footer-go-to-top {
            position: absolute;
            bottom: 30px;
            right: 30px;
            z-index: 100;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .footer-go-to-top.show {
            opacity: 1;
            transform: translateY(0);
        }

        .go-to-top-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0056a6 0%, #4CAF50 100%);
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 86, 166, 0.4);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .go-to-top-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #4CAF50 0%, #0056a6 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            border-radius: 50%;
        }

        .go-to-top-btn:hover {
            transform: translateY(-8px) scale(1.1);
            box-shadow: 0 15px 35px rgba(0, 86, 166, 0.6);
        }

        .go-to-top-btn:hover::before {
            opacity: 1;
        }

        .go-to-top-btn i {
            position: relative;
            z-index: 2;
            transition: transform 0.4s ease;
        }

        .go-to-top-btn:hover i {
            transform: translateY(-3px);
        }

        /* Pulse animation */
        @keyframes pulse {
            0% {
                box-shadow: 0 8px 25px rgba(0, 86, 166, 0.4);
            }

            50% {
                box-shadow: 0 8px 30px rgba(0, 86, 166, 0.7);
            }

            100% {
                box-shadow: 0 8px 25px rgba(0, 86, 166, 0.4);
            }
        }

        .go-to-top-btn.pulse {
            animation: pulse 2s infinite;
        }

        /* Tooltip */
        .go-to-top-btn::after {
            content: 'Scroll to Top';
            position: absolute;
            bottom: 100%;
            right: 50%;
            transform: translateX(50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
            margin-bottom: 10px;
        }

        .go-to-top-btn:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .footer-top {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .mission-statement h3 {
                font-size: 1.6rem;
            }

            .footer-go-to-top {
                bottom: 20px;
                right: 20px;
            }
        }

        @media (max-width: 768px) {
            .footer-middle {
                flex-direction: column;
                text-align: center;
                gap: 25px;
            }

            .footer-policies {
                justify-content: center;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 25px;
                padding-bottom: 90px !important;
            }

            .contact-info {
                justify-content: center;
            }

            .quick-links-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .go-to-top-btn {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .footer-policies {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }

            .social-section {
                flex-direction: column;
                gap: 15px;
            }

            .mission-statement h3 {
                font-size: 1.4rem;
            }

            .logo-text {
                font-size: 1.5rem;
            }

            .footer-go-to-top {
                bottom: 15px;
                right: 15px;
            }
        }

        /* Particle Animation */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }
    </style>


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
        logoIcon.addEventListener('mouseenter', () => {
            logoIcon.style.transform = 'rotateY(180deg) scale(1.1)';
        });

        logoIcon.addEventListener('mouseleave', () => {
            logoIcon.style.transform = 'rotateY(0) scale(1)';
        });

        // Mission highlight animation
        const missionHighlight = document.querySelector('.mission-highlight');
        missionHighlight.addEventListener('mouseenter', () => {
            missionHighlight.style.transform = 'translateX(15px)';
        });

        missionHighlight.addEventListener('mouseleave', () => {
            missionHighlight.style.transform = 'translateX(0)';
        });

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