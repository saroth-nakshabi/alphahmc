<!DOCTYPE html>
<html lang="en">

<head>
    {{-- One robots tag per page: search results, previews and removed pages set their own --}}
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- SEO: dashboard-managed PageSetting takes precedence on standard pages;
         detail pages (services etc.) fall back to their own @push/@yield values. --}}
    @php
        $pmTitle = (isset($pageMeta) && $pageMeta && $pageMeta->meta_title) ? $pageMeta->meta_title : null;
        $pmDesc  = (isset($pageMeta) && $pageMeta && $pageMeta->meta_description) ? $pageMeta->meta_description : null;
        $pmKeys  = (isset($pageMeta) && $pageMeta && $pageMeta->meta_keywords) ? $pageMeta->meta_keywords : null;
        $pmOg    = (isset($pageMeta) && $pageMeta && $pageMeta->og_image) ? asset('public/uploads/page_images/' . $pageMeta->og_image) : null;
        // Page's own description section (already escaped), else the site-wide one from Settings.
        $siteDesc = trim((string) \App\Models\AppSetting::get('site_meta_description', ''));
        $metaDesc = trim($__env->yieldContent('meta_description')) ?: e($siteDesc);
    @endphp

    @if($pmTitle)<title>{{ $pmTitle }}</title>@else<title>@stack('page_title')</title>@endif
    @if($pmDesc)
        <meta name="description" content="{{ $pmDesc }}">
    @elseif($metaDesc !== '')
        <meta name="description" content="{!! $metaDesc !!}">
    @endif
    @if($pmKeys)<meta name="keywords" content="{{ $pmKeys }}">@endif
    @stack('meta')

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Default Open Graph tags — pages can override via @push('og_tags') --}}
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $pmTitle ?? '' }}@unless($pmTitle)@stack('page_title')@endunless" />
    @if($pmDesc)
        <meta property="og:description" content="{{ $pmDesc }}" />
    @elseif($metaDesc !== '')
        <meta property="og:description" content="{!! $metaDesc !!}" />
    @endif
    @if($pmOg)<meta property="og:image" content="{{ $pmOg }}" /><meta name="twitter:image" content="{{ $pmOg }}" />@endif
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@AlphaHealthGrp" />
    @stack('og_tags')

    <link rel="icon" type="image/svg+xml" href="{{ asset('public/favicon.svg') }}">
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

    // Countries where visitors must opt in before any cookie: EU and EEA, UK, Switzerland.
    var OPT_IN_REGIONS = [
        'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE',
        'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE',
        'IS', 'LI', 'NO',
        'GB', 'CH'
    ];
    var GRANTED = { analytics_storage: 'granted', ad_storage: 'granted', ad_user_data: 'granted', ad_personalization: 'granted' };
    var DENIED  = { analytics_storage: 'denied',  ad_storage: 'denied',  ad_user_data: 'denied',  ad_personalization: 'denied' };

    if (_savedConsent === 'accepted') {
        gtag('consent', 'default', GRANTED);
    } else if (_savedConsent === 'rejected') {
        gtag('consent', 'default', DENIED);
        gtag('set', 'ads_data_redaction', true);
    } else {
        // No choice yet. Opt-in regions start denied; everywhere else starts granted.
        // The banner still shows, and Reject switches everything to denied.
        gtag('consent', 'default', Object.assign({}, DENIED, { region: OPT_IN_REGIONS, wait_for_update: 500 }));
        gtag('consent', 'default', GRANTED);
        gtag('set', 'ads_data_redaction', true);
    }

    // Keep the Google click ID across pages when cookies are denied, so an inquiry on a
    // second page still credits the ad. Off today because it adds "?_gl=" to internal
    // links; turn on only if that is acceptable.
    // gtag('set', 'url_passthrough', true);
</script>

@include('front.partials.whatsapp-ref')
@include('front.partials.recaptcha-loader')

{{-- Global Tags from database (GA4, GTM, etc.) — fire AFTER consent default above --}}
@foreach($globaltags as $tag)
    {!! $tag->tags !!}
@endforeach


{{-- page specific tag: only on the page that describes the service, package or category.
     A bare @isset($service) also fired on home and all-services, where a @foreach over
     services leaves $service set (home carried the Telehealth schema). --}}
@php
    $schemaOwnPage = isset($service) && in_array(optional(request()->route())->getName(),
        ['front.service', 'service-packages', 'front.service-category'], true);
@endphp
@if($schemaOwnPage)
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
    // Package (service group) pages share this block: their URL is /service-packages/{slug}
    // and their FAQs are the `faqs` relation, not a service's `faq`.
    $isServiceGroup = $service instanceof \App\Models\ServiceGroup;
    // Category pages pass the category as $service; its page is /service-category/{slug}.
    $schemaPageUrl  = match (true) {
        $isServiceGroup                             => route('service-packages', $service->slug),
        $service instanceof \App\Models\Category    => route('front.service-category', $service->slug),
        default                                     => route('front.service', $service->slug),
    };
    $schemaFaqs     = $isServiceGroup ? $service->faqs : $service->faq;

    $faqSchema = [];
    if ($schemaFaqs && $schemaFaqs->count()) {
        foreach ($schemaFaqs as $faq) {
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
        // The page's own meta description (what search shows), else the hero text.
        'description' => trim((string) $service->meta_description) !== ''
            ? trim((string) $service->meta_description)
            : trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $service->content), ENT_QUOTES | ENT_HTML5, 'UTF-8'))),
        'url'         => $schemaPageUrl,
        'provider'    => [
            '@type' => 'Organization',
            'name'  => 'Alpha Health Group',
            'url'   => config('app.url'),
        ],
    ];

    // Only emit these when set — avoids null properties in the JSON-LD.
    if (!empty($service->areaServed)) {
        $professionalService['areaServed'] = $service->areaServed;
    }
    // serviceType is not a ProfessionalService property (schema.org warns); the
    // service is already named in 'name'.

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
                'item'     => $schemaPageUrl,
            ],
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
!!}
</script>

@endif


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
        json_encode(array_filter([
            '@context'     => 'https://schema.org',
            '@type'        => 'Organization',
            'name'         => 'Alpha Health Group',
            'alternateName'=> 'Alpha Health Consultancies',
            'url'          => config('app.url'),
            'logo'         => [
                '@type' => 'ImageObject',
                'url'   => asset('public/front-new/assets/images/alpha-logo.svg'),
            ],
            'description'  => $siteDesc !== '' ? $siteDesc : null,
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
        ], fn ($v) => $v !== null), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    !!}
    </script>





    <!-- DNS prefetch for external origins -->
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    @include('front.partials.fonts')

    {{-- Bootstrap 5.2.3 (unused rules removed) + style.min.css + slide-menu.min.css as one file
         (scripts/css/build.mjs). Templates with @section('critical', ...) inline their first-screen
         rules and load the full files without blocking (scripts/css/critical.mjs). --}}
    @php $criticalTemplate = trim($__env->yieldContent('critical')) ?: null; @endphp
    @include('front.partials.stylesheet', ['href' => \App\Support\CriticalCss::bundleUrl('base'), 'bundle' => 'base'])

    <!-- Icon font + animation libs — deferred (not needed for first paint).
         Font Awesome is the only icon set loaded site-wide: self-hosted, fonts subset to the icons
         in use (~16 KB instead of ~300 KB) with the full files as fallback (scripts/fontawesome/build.py).
         Bootstrap Icons is loaded only by the pages that use it; Line Awesome was unused. -->
    <link rel="preload" as="style"
          href="{{ asset('public/front/assets/vendor/fontawesome-6.5.1/css/fontawesome.min.css') }}?v=2"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('public/front/assets/vendor/fontawesome-6.5.1/css/fontawesome.min.css') }}?v=2"></noscript>

    @php
        // Swiper (CSS + about 150 KB of JS) only on pages that render a slider (swiper-wrapper markup).
        // Child sections and stacks are already rendered when the layout runs, so check them.
        $needsSwiper = stripos($__env->yieldContent('content') . $__env->yieldContent('custom_js')
            . $__env->yieldPushContent('scripts') . $__env->yieldPushContent('meta'), 'swiper-wrapper') !== false;
    @endphp
    @if($needsSwiper)
    <link rel="preload" as="style"
          href="{{ asset('public/front/assets/vendor/swiper-11.2.10/swiper-bundle.min.css') }}?v=11.2.10"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('public/front/assets/vendor/swiper-11.2.10/swiper-bundle.min.css') }}?v=11.2.10"></noscript>
    @endif

    <link rel="preload" as="style"
          href="{{ asset('public/front/assets/vendor/aos-2.3.4/aos.css') }}?v=2.3.4"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('public/front/assets/vendor/aos-2.3.4/aos.css') }}?v=2.3.4"></noscript>

    @stack('meta_scripts')

    @yield('custom_css')

</head>

<body>

@foreach($googletags as $tag)
        @if($tag->noscript_tags)
            {!! $tag->noscript_tags !!}
        @endif
    @endforeach

    @include('front.partials.stylesheet', ['href' => asset('public/front/assets/css/front-global.min.css') . '?v=13', 'bundle' => 'front-global'])

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
                    {{-- 13 KB WebP / 28 KB PNG rendered from AHGlogo.svg (225 KB: embedded raster masks) --}}
                    <picture>
                        <source type="image/webp" srcset="{{ asset('public/front-new/assets/images/ahg-logo-header.webp') }}">
                        <img src="{{ asset('public/front-new/assets/images/ahg-logo-header.png') }}" alt="Alpha Health Group" width="460" height="150" />
                    </picture>
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
                    /* Mobile: hide Contact in the header (kept in the slide menu); search owns the right side. Shows on desktop/web. */
                    @media (max-width:991px){.nav-contact-cta{display:none !important;}}
                </style>
            </div>
        </div>

        <!-- Expanded Content Area -->
        <div id="sidebar-menu">
            {{-- Mobile-only bottom-left close (easy one-handed reach) --}}
            <button type="button" class="menu-mobile-close" id="menuMobileClose" aria-label="Close menu">
                <i class="fas fa-xmark"></i> Close
            </button>
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
                        <div class="service-content"><a href="{{ route('front.new-about') }}" class="group-link" id="group_about">Who We Are</a></div>
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


    {{-- jQuery and the menu script load after the page content (just before the footer scripts) so they
         do not stop the parser before the hero image; see the block above ui.js. --}}


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
                    <picture>
                        <source type="image/webp" srcset="{{ asset('public/front-new/assets/images/about/alpha-logo5-200.webp') }}">
                        <img src="{{ asset('public/front-new/assets/images/about/alpha-logo5-200.png') }}" alt="AlphaHealth"
                            width="200" height="215" loading="lazy" decoding="async"
                            style="width:100px;height:auto;object-fit:contain;display:block;" />
                    </picture>

                </div>


                <!-- Policies -->
                <div class="footer-policies" style="text-align: center">
                    <a href="{{ url('/alpha-privacy-policy') }}" class="policy-link">Privacy Policy</a>
                    <a href="{{ route('front.terms-of-service') }}" class="policy-link">Terms of Service</a>
                    <a href="{{ route('front.cookie-policy') }}" class="policy-link">Cookie Policy</a>
                    <a href="{{ route('front.privacy-policy') }}" class="policy-link">GDPR &amp; Data Protection</a>
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


    


    {{-- jQuery 3.6.0, self-hosted (byte-identical to code.jquery.com, sha256 /xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=),
         and the menu script. Kept synchronous and before every script that uses jQuery. --}}
    <script src="{{ asset('public/front/assets/js/vendor/jquery-3.6.0.min.js') }}?v=3.6.0"></script>

    <!-- JavaScript for Unified Menu (Fixed & Cleaned) -->
    <script>
        window.AHG_NAV = {
            searchLive: @json(route('front.search.live')),
            search: @json(route('front.search')),
            blog: @json(route('front.new_blog')),
            project: @json(route('front.project')),
            allServices: @json(route('front.all-services')),
        };
    </script>
    <script src="{{ asset('public/front/assets/js/layout/nav.js') }}?v=1"></script>

    <script src="{{ asset('public/front/assets/js/layout/ui.js') }}?v=1"></script>

    {{-- Bootstrap, Swiper and AOS are self-hosted (byte-identical to jsDelivr: Bootstrap sha384 kenU1KFd...,
         Swiper 11.2.10 = what swiper@11 resolved to, AOS 2.3.4), so pages no longer connect to cdn.jsdelivr.net. --}}
    <script src="{{ asset('public/front/assets/vendor/bootstrap-5.2.3/bootstrap.bundle.min.js') }}?v=5.2.3"></script>
    <!-- Swiper JS (only on pages with a slider, see $needsSwiper) -->
    @if($needsSwiper)
    <script src="{{ asset('public/front/assets/vendor/swiper-11.2.10/swiper-bundle.min.js') }}?v=11.2.10"></script>

    <script src="{{ asset('public/front/assets/js/layout/swiper.js') }}?v=1"></script>
    @else
    {{-- Safety net: a stray new Swiper() on a page without a slider must not stop its other scripts --}}
    <script>window.Swiper = window.Swiper || function () { var noop = new Proxy(function () {}, { get: function () { return noop; }, apply: function () { return noop; } }); return noop; };</script>
    @endif





    <script></script>

    <script src="{{ asset('public/front/assets/vendor/aos-2.3.4/aos.js') }}?v=2.3.4"></script>
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
    <script src="{{ asset('public/front/assets/js/layout/tracking.js') }}?v=75ed55c1ff"></script>

    {{-- "Chat with Alpha" AI widget stays only on Contact + About Us — everywhere
         else it was high-click/low-inquiry friction, so the dwell popup + the
         direct WhatsApp floating button (below) carry the CTA instead. --}}
    @if(request()->routeIs('contact', 'front.new-about'))
        @include('front.partials.ai-assistant')
    @else
        @include('front.partials.wa-dwell-popup')
    @endif

    {{-- Reusable global inquiry modal: pages opt in via @push('inquiry_modal') --}}
    @stack('inquiry_modal')

    {{-- Meta Pixel (was in Tag Manager; starts on the load event) --}}
    @include('front.partials.meta-pixel')
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

@php
    // ── Unified page-level WhatsApp number + context-aware message ──────────
    // Service, ServiceGroup and Category pages all pass their entity as $service.
    // See app/Support/WaContext.php (shared with the AI widget + dwell popup).
    $pageWaLink = \App\Support\WaContext::link(null, $service ?? null);
@endphp

{{-- ── Floating WhatsApp button ── --}}
<a href="{{ $pageWaLink }}"
   class="ahg-float-wa"
   target="_blank"
   rel="noopener"
   title="Chat with us on WhatsApp"
   onclick="window.dataLayer=window.dataLayer||[];window.dataLayer.push({event:'ahg_whatsapp_click',source:'floating_button'});">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="#fff">
        <path d="M12 2C6.477 2 2 6.477 2 12c0 1.895.523 3.665 1.432 5.18L2 22l4.981-1.398A9.944 9.944 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 01-4.073-1.112l-.292-.17-3.017.847.848-3.073-.186-.303A7.955 7.955 0 014 12c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8zm4.29-5.835c-.232-.116-1.373-.677-1.585-.754-.213-.077-.367-.116-.522.116-.155.232-.598.754-.733.91-.135.154-.27.174-.502.058-.232-.116-.98-.361-1.867-1.15-.69-.615-1.156-1.375-1.292-1.607-.135-.232-.015-.358.101-.474.104-.104.232-.27.348-.406.116-.135.154-.232.232-.386.077-.155.038-.29-.019-.406-.058-.116-.522-1.259-.715-1.722-.188-.45-.38-.389-.522-.396l-.444-.008a.852.852 0 00-.618.29c-.212.232-.81.79-.81 1.926 0 1.136.83 2.233.945 2.388.116.155 1.634 2.494 3.96 3.498.553.24 1.03.382 1.382.49.58.18 1.107.154 1.524.093.465-.069 1.373-.56 1.567-1.1.193-.54.193-1.004.135-1.1-.058-.096-.213-.155-.445-.27z"/>
    </svg>
</a>
<style>
    .ahg-float-wa {
        position: fixed; bottom: 28px; right: 28px;
        width: 56px; height: 56px;
        background: #25D366; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 20px rgba(37,211,102,.45);
        z-index: 9990;
        transition: transform .25s ease, box-shadow .25s ease;
        text-decoration: none;
    }
    .ahg-float-wa:hover { transform: scale(1.12) translateY(-2px); box-shadow: 0 8px 28px rgba(37,211,102,.55); }
    @media (max-width: 768px) {
        .ahg-float-wa { bottom: 20px; right: 18px; width: 50px; height: 50px; }
        .ahg-float-wa svg { width: 24px; height: 24px; }
    }
</style>

</html>