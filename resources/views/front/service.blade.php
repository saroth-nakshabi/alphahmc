@extends('front/layout-2')
@section('critical', 'service')
@section('custom_css')
    {{-- service-pages-shared + service-detail + service-category (Project Process section, shared with
         the category page), merged by scripts/css/build.mjs --}}
    @include('front.partials.stylesheet', ['href' => \App\Support\CriticalCss::bundleUrl('service'), 'bundle' => 'service', 'criticalTemplate' => 'service'])
    <style>
        /* Some service content was pasted from MS Word, which injects the
           "MsoNormal" class and inline near-black colour / Word fonts that
           override the site theme (text looks black & differently styled).
           Force any Word-pasted markup to inherit the site's typography. */
        .MsoNormal,
        .MsoNormal span,
        [class^="Mso"],
        [class^="Mso"] span {
            color: inherit !important;
            font-family: inherit !important;
            background: transparent !important;
            line-height: inherit !important;
        }
        .MsoNormal { margin-bottom: 1rem !important; }

        /* Internal/content links blend with body text — no color change, no underline. */
        .hero-desc-wrapper a, .lead-text a, .quote-text a,
        .sdb-header a, .sdb-desc a, .large-info a, .transformation-desc a,
        .help-text-block a, .process-header a, .feature-card p a,
        .process-card p a, .faq-body a, .mag-desc a,
        .hero-desc-wrapper a:hover, .lead-text a:hover, .quote-text a:hover,
        .sdb-header a:hover, .sdb-desc a:hover, .large-info a:hover, .transformation-desc a:hover,
        .help-text-block a:hover, .process-header a:hover, .feature-card p a:hover,
        .process-card p a:hover, .faq-body a:hover, .mag-desc a:hover {
            color: inherit;
            text-decoration: none;
        }
        /* The hero's in-page link to the requirements (nurse ads) reads as a link, unlike the blended content links. */
        .hero-desc-wrapper a.hero-anchor-link, .hero-desc-wrapper a.hero-anchor-link:hover {
            text-decoration: underline; text-underline-offset: 4px; text-decoration-thickness: 1px; }
        .transformation-desc [id] { scroll-margin-top: 110px; }
        /* Related articles side by side (pages without case studies): the single-card design scaled to a third or
           half of the width, so titles are not cut off (layout fix, 5 Nov 2026). */
        .ey-related-grid .ey-related-card { height: 100%; min-height: 440px; }
        .ey-related-grid .ey-related-content { padding: 32px 26px; }
        .ey-related-grid .ey-related-card .rc-title { font-size: clamp(1.3rem, 1.05rem + .7vw, 1.6rem); line-height: 1.25;
            display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
        .ey-related-grid .rc-desc { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        @media (max-width: 767px) { .ey-related-grid .ey-related-card { min-height: 380px; } }

        /* Heading levels (SEO brief 4 Nov 2026): the long-description and CTA headings render as h2 and the card
           titles as h3, with the look they had as h3 and span. */
        .transformation-section .large-info h2.section-lead-heading {
            font-size: clamp(1.4rem, 2.5vw, 2rem); font-weight: 600; line-height: 1.2; letter-spacing: normal;
            margin: 0 0 15px; color: #e0e0e0; }
        .help-info .help-text-block h2.section-lead-heading {
            font-size: 1.4rem; font-weight: 600; line-height: 1.35; letter-spacing: normal;
            font-family: inherit; margin: 0 0 12px; color: #2e2e2e; }
        h3.mag-card-eyebrow { margin-top: 0; line-height: inherit;
            font-family: 'Inter', 'Inter Fallback', 'Inter Fallback Android', sans-serif !important; }
        /* Optional slider heading above the five cards. */
        .magazine-section .mag-section-heading {
            font-size: clamp(1.6rem, 2.6vw, 2.1rem); font-weight: 600; line-height: 1.25; letter-spacing: -0.01em;
            color: #1a1a1a; margin: 0 0 32px; max-width: 820px; }

        /* Comparison tables in the long description (clinic setup: mainland or DHCC). Thin rules, no coloured cells. */
        .transformation-desc table { width: 100%; border-collapse: collapse; margin: 0 0 1.25rem; font: inherit; color: inherit; }
        .transformation-desc th, .transformation-desc td { padding: .7rem .75rem .7rem 0; text-align: left; vertical-align: top;
            border-bottom: 1px solid rgba(127, 127, 127, .35); }
        .transformation-desc thead th { font-weight: 600; border-bottom-width: 2px; }
        .transformation-desc tbody th { font-weight: 500; }
        @media (max-width: 400px) { .transformation-desc th, .transformation-desc td { padding-right: .5rem; font-size: .9rem; } }
    </style>
@endsection
@php
    // One title and one description per page: meta_title (cut to 60 at a word) else the
    // name; meta_description feeds the layout's description and og:description.
    $seoTitle = \App\Support\Seo::title($service->meta_title, $service->name);
    $seoDesc  = trim((string) $service->meta_description);
    // Hero image (the LCP element): the same candidates for the preload and the <picture>.
    $pic = \App\Support\Img::heroPicture(
        $service->hero_image
            ? 'uploads/service_images/' . $service->hero_image
            : (isset($service->images[0]) ? 'uploads/service_images/' . $service->images[0]->image : null),
        'front/assets/img/hero/service-details-bg.jpg'
    );
@endphp
@push('page_title', e($seoTitle))
@if($seoDesc !== '')
    @section('meta_description', $seoDesc)
@endif
@push('meta')
    @if(!empty($service->meta_keywords))<meta name="keywords" content="{{ $service->meta_keywords }}">@endif
    @include('front.partials.hero-preload', ['pic' => $pic])
@endpush
@if($service->hero_image)@section('og_image', asset('public/uploads/service_images/' . $service->hero_image))@endif
@push('og_tags')
    {{-- og:site_name/title/description/url/type and twitter:card come from the layout. --}}
    <meta name="author" content="Alpha Health Group">
    <meta name="twitter:title" content="{{ $seoTitle }}" />
    @if($seoDesc !== '')<meta name="twitter:description" content="{{ $seoDesc }}" />@endif
@endpush
@section('content')
    

    <div class="service-page-wrapper">
        <!-- Hero Section -->
        <section class="service-hero">
            <div class="hero-background">
                @include('front.partials.hero-picture', ['pic' => $pic, 'class' => 'hero-background-img'])
                <div class="hero-background-overlay" aria-hidden="true"></div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $service->name }}</h1>
                        @include('front.partials.reviewed-by', ['page' => $service, 'class' => 'ahg-reviewed-by-hero'])
                        <div class="hero-desc-wrapper">
                            {!! \App\Support\Html::fixLinks($service->content) !!}
                        </div>
                        {{-- First-screen actions: the page's WhatsApp link (same number and message as
                             the floating button) and the inquiry modal. The site's click tracking picks
                             both up by their wa.me href and #inquiryModal target. --}}
                        <div class="hero-actions">
                            <a href="{{ \App\Support\WaContext::link(null, $service) }}" class="hero-cta-primary"
                                target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                <span>Message us on WhatsApp</span>
                            </a>
                            <button type="button" class="glass-btn" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                <span>Book a consultation</span>
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="hero-breadcrumb" aria-label="Breadcrumb">
                <div class="container">
                    <a href="{{ route('home') }}">Home</a>
                    <span class="bc-sep">›</span>
                    <a href="{{ route('front.all-services') }}">Services</a>
                    <span class="bc-sep">›</span>
                    <span class="bc-current">{{ $service->name }}</span>
                </div>
            </nav>
        </section>

        



        <!-- Quote Section -->
       <section class="quote-section" data-aos="fade-up">
    <div class="container">
        <div class="quote-inner">
            {{-- <span class="quote-eyebrow">Our Approach</span> --}}
            <div class="quote-text">
                {!! \App\Support\Html::fixLinks($service->overview) !!}
            </div>
            <div class="quote-divider"></div>
        </div>
    </div>
</section>

        <!-- Transformation Section (Dark) -->
        <section class="transformation-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4" data-aos="fade-right">
                        <div class="large-info">
                            {!! \App\Support\Html::fixLinks(\App\Support\Html::leadHeadingToH2($service->info_one, 'section-lead-heading')) !!}
                        </div>
                        {{-- Left Read more removed as requested --}}
                        <p class="content-updated-meta" style="margin-top:18px;font-size:0.85rem;color:#6b7280;">
                            Updated: {{ ($service->updated_date ?? $service->updated_at)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="col-lg-7 offset-lg-1" data-aos="fade-left">
                        <div class="transformation-desc-wrapper" id="transformation-desc-wrapper">
                            <div class="transformation-desc">
                                {!! \App\Support\Html::fixLinks($service->info_two) !!}
                            </div>
                        </div>
                        <button type="button" class="transformation-read-more" id="transformation-toggle" aria-expanded="false" aria-controls="transformation-desc-wrapper"
                            onclick="toggleTransformationDesc()">
                            Read more <i class="fa-solid fa-arrow-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
        {{-- In-page links into the long description (for example #requirements from the hero, for nurse ads; SEO brief
             5 Nov 2026): the section sits behind Read more, so open it first, then scroll to the heading. --}}
        <script>
        (function () {
            function openAndScroll(id, smooth) {
                var target = id && document.getElementById(id);
                var wrapper = document.getElementById('transformation-desc-wrapper');
                if (!target || !wrapper || !wrapper.contains(target)) return false;
                var button = document.getElementById('transformation-toggle');
                var open = wrapper.classList.contains('active') || wrapper.classList.contains('expanded');
                if (!open && button) button.click();
                setTimeout(function () { target.scrollIntoView({ behavior: smooth ? 'smooth' : 'auto', block: 'start' }); }, open ? 0 : 450);
                return true;
            }
            document.addEventListener('click', function (e) {
                var a = e.target.closest && e.target.closest('a[href^="#"]');
                if (a && openAndScroll(a.getAttribute('href').slice(1), true)) {
                    e.preventDefault();
                    history.replaceState(null, '', a.getAttribute('href'));
                }
            });
            window.addEventListener('load', function () { if (location.hash) openAndScroll(location.hash.slice(1), false); });
        })();
        </script>

        <script>
            function toggleTransformationDesc() {
    const wrapper = document.getElementById('transformation-desc-wrapper');
    const button = document.getElementById('transformation-toggle');

    wrapper.classList.toggle('active');

    if (wrapper.classList.contains('active')) {
        button.innerHTML = 'Read less <i class="fa-solid fa-arrow-up"></i>';
    } else {
        button.innerHTML = 'Read more <i class="fa-solid fa-arrow-down"></i>';
    }
}
        </script>

        <!-- Dynamic Magazine / Insights Section (only when the service has magazine rows) -->
        @php $displayMagazines = $service->magazines; @endphp
        @if ($displayMagazines->count())
        <section class="magazine-section" id="magazine-insights">
            <div class="container">
                @if (trim((string) $service->slider_heading) !== '')
                    <h2 class="mag-section-heading" data-aos="fade-up">{{ $service->slider_heading }}</h2>
                @endif
                @php
                    $firstMag = $displayMagazines->first();
                    $hasFirstImg = $firstMag && $firstMag->image;
                @endphp

                <div class="mag-container {{ !$hasFirstImg ? 'no-img-active' : '' }}">

                    <!-- Left Side: Large Image -->
                    <div class="mag-image-side" data-aos="fade-right">
                        <div class="mag-image-container">
                            @php
                                $firstMag = $displayMagazines->first();
                                $firstImg = ($firstMag && $firstMag->image)
                                    ? ((strpos($firstMag->image, 'http') === 0)
                                        ? $firstMag->image
                                        : \App\Support\Img::thumb('uploads/magazines/' . $firstMag->image, 720))
                                    : '';
                            @endphp
                            <img src="{{ $firstImg }}" alt="{{ strip_tags($firstMag->title ?? '') }}" id="mag-main-image" @if($_sz = \App\Support\Img::size($firstImg)) width="{{ $_sz[0] }}" height="{{ $_sz[1] }}" @endif loading="lazy" decoding="async">

                            <!-- Manual Nav Buttons -->
                            <div class="mag-img-nav">
                                <button class="mag-img-btn" id="mag-img-prev" aria-label="Previous image">
                                    &#8249;
                                </button>
                                <button class="mag-img-btn" id="mag-img-next" aria-label="Next image">
                                    &#8250;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Teal Slider Container -->
                    <div class="mag-content-side" data-aos="fade-left">
                        <div class="swiper mag-swiper-container">
                           <div class="swiper-wrapper">
    {{-- Original slides --}}

    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : \App\Support\Img::thumb('uploads/magazines/' . $mag->image, 720)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <h3 class="mag-card-eyebrow">{{ $mag->title }}</h3>
                <div class="mag-desc">{!! \App\Support\Html::fixLinks(strip_tags($mag->description, '<a><strong><em>')) !!}</div>
            </div>
        </div>
    @endforeach

</div>
                        </div>
                        <div class="swiper-pagination mag-pagination"></div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Slider Script (Screenshot Style) -->
  <script>
// Started when the slider nears the screen (speed brief, 5 Nov 2026): it sits below the first screen, and starting it
// on load ran its autoplay and layout work while the page was still loading.
window.ahgWhenNear('.mag-swiper-container', function () {
    const mainImage = document.getElementById('mag-main-image');

    // Each magazine is rendered once; Swiper clones slides itself for the loop.
    const allCards = document.querySelectorAll('.mag-card');
    if (!allCards.length) return;
    const magazineImages = [...allCards].map(card => card.getAttribute('data-img') || '');

    function syncImage(realIndex) {
        if (!mainImage) return;
        const idx = ((realIndex % magazineImages.length) + magazineImages.length) % magazineImages.length;
        const newImg = magazineImages[idx];
        if (!newImg) return;

        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.src = newImg;
            mainImage.style.opacity = '1';
        }, 400);
    }

    const magSwiper = new Swiper('.mag-swiper-container', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: allCards.length > 2,
        rewind: allCards.length <= 2,
        speed: 800,
        grabCursor: true,
        allowTouchMove: true,
        centeredSlides: true,
        watchSlidesProgress: true,
        pagination: {
            el: '.mag-pagination',
            clickable: true,
        },
        breakpoints: {
            0: {
                centeredSlides: false,
                spaceBetween: 20,
            },
            992: {
                centeredSlides: true,
                spaceBetween: 8,
            }
        },
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
            stopOnLastSlide: false,
        },
        on: {
            realIndexChange: function () {
                syncImage(this.realIndex);
            }
        }
    });

    // Keep autoplay alive after manual nav
    const imgPrevBtn = document.getElementById('mag-img-prev');
    const imgNextBtn = document.getElementById('mag-img-next');

    if (imgNextBtn) imgNextBtn.addEventListener('click', () => {
        magSwiper.slideNext(800);
        magSwiper.autoplay.start(); // restart autoplay after manual click
    });
    if (imgPrevBtn) imgPrevBtn.addEventListener('click', () => {
        magSwiper.slidePrev(800);
        magSwiper.autoplay.start();
    });

    if (mainImage) mainImage.style.transition = 'opacity 0.4s ease';
});

function toggleTransformationDesc() {
    const wrapper = document.getElementById('transformation-desc-wrapper');
    const btn = document.getElementById('transformation-toggle');
    const isExpanded = wrapper.classList.contains('expanded');
    if (isExpanded) {
        wrapper.classList.remove('expanded');
        wrapper.style.maxHeight = '';
        btn.innerHTML = 'Read more <i class="fa-solid fa-arrow-down"></i>';
    } else {
        wrapper.classList.add('expanded');
        // Open to the real content height; the CSS cap (800px) cut off longer content.
        wrapper.style.maxHeight = wrapper.scrollHeight + 'px';
        btn.innerHTML = 'Read less <i class="fa-solid fa-arrow-up"></i>';
    }
    btn.setAttribute('aria-expanded', String(!isExpanded));
}
</script>


<!-- How Alpha Can Help Section - EY Style Refined -->
        <section class="help-section" id="how-alpha-can-help">
            <div class="container">
                <div class="help-flex">
                    <div class="help-info" data-aos="fade-up">
                        {{-- <h2>How Alpha can help</h2> --}}

                        <div class="help-text-block">
                            {{-- <h4>{{ $service->name }} solution</h4> --}}
                            @if($service->info_three && trim($service->info_three) != '')
                                {!! \App\Support\Html::fixLinks(\App\Support\Html::leadHeadingToH2($service->info_three, 'section-lead-heading')) !!}
                            @else
                                <p>Our multidisciplinary team combines architectural excellence with deep clinical insights to
                                    deliver projects that are not just buildings, but platforms for healing.</p>
                            @endif

                            @if($service->info_four && trim($service->info_four) != '')
                                <div class="mt-4">
                                    {!! \App\Support\Html::fixLinks($service->info_four) !!}
                                </div>
                            @endif
                            <button type="button" class="help-enquiry-btn" data-bs-toggle="modal"
                                data-bs-target="#inquiryModal">
                                <i class="fas fa-envelope me-2"></i> Send Enquiry
                            </button>
                        </div>
                    </div>

                    <div class="leader-sidebar" data-aos="fade-left">
                        <span class="leader-label">Service Leader</span>
                        <hr class="leader-hr">
                        <div class="leader-profile">
                            @php $leaderImg = isset($service->agent) && $service->agent->image ? \App\Support\Img::thumb('uploads/agent_images/' . $service->agent->image, 250) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp'); @endphp
                            <img src="{{ $leaderImg }}"
                                alt="{{ isset($service->agent) ? trim($service->agent->user->first_name . ' ' . $service->agent->user->last_name) : 'Service leader' }}" class="leader-circle-img" @if($_sz = \App\Support\Img::size($leaderImg)) width="{{ $_sz[0] }}" height="{{ $_sz[1] }}" @endif loading="lazy" decoding="async">
                            <div class="leader-meta">
                                <div class="leader-name-bold">
                                    {{ isset($service->agent) ? ($service->agent->user->first_name . ' ' . $service->agent->user->last_name) : 'Dr. Vikram Singh' }}
                                </div>
                                <div class="leader-job-title">
                                    {{ isset($service->agent) ? $service->agent->title : 'Global Healthcare Leader, Alpha' }}
                                </div>

                                <div class="leader-contact-links">
                                    @php
                                        $lAgent    = $service->agent ?? null;
                                        $agentUser = $lAgent?->user;
                                        $lPhone    = $agentUser?->phone ?? '+971 4 272 4064';
                                        $showCall  = !$lAgent || $lAgent->hasMode('call');
                                        $showEmail = !$lAgent || $lAgent->hasMode('email');
                                        $_waNum    = ($lAgent && !empty($lAgent->whatsapp))
                                            ? preg_replace('/[^0-9]/', '', $lAgent->whatsapp)
                                            : (\App\Models\AppSetting::where('key','whatsapp_default_number')->value('value') ?? '97142724064');
                                        $_waService = trim(strip_tags($service->name ?? ''));
                                        $_waMsg     = $_waService !== ''
                                            ? "Hi, I'd like to enquire about \"{$_waService}\".\n" . url()->current()
                                            : "Hi, I'd like to enquire about Alpha Health Group's services.\n" . url()->current();
                                        $pageWaLink = $_waNum ? 'https://wa.me/' . $_waNum . '?text=' . rawurlencode($_waMsg) : null;
                                        $showWa    = (!$lAgent || $lAgent->hasMode('whatsapp')) && !empty($pageWaLink);
                                    @endphp

                                    @if($showCall)
                                    <a href="tel:{{ $lPhone }}" class="contact-link-icon" title="Call">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    @endif
                                    @if($showEmail)
                                    <a href="{{ route('contact') }}" class="contact-link-icon" title="Email"
                                        data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    @endif
                                    @if($showWa)
                                    <a href="{{ $pageWaLink }}" target="_blank"
                                        class="contact-link-icon whatsapp-color" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- ── Project Process (assigned from the Project Process Manager) ── --}}
        @php
          $processHeaders      = (array) $service->process_header;
          $processDescriptions = (array) $service->process_description;
          $processCount        = max(count($processHeaders), count($processDescriptions));
          $processServiceIds   = (array) $service->process_service_ids;
          $linkedIds           = array_values(array_filter($processServiceIds));
          $processServiceMap   = count($linkedIds)
              ? \App\Models\Service::whereIn('id', $linkedIds)->get()->keyBy('id')
              : collect();
        @endphp
        @if ($processCount > 0)
          <section class="process-section" id="process-journey">
            <div class="blob-container">
              <div class="blob blob-1"></div>
              <div class="blob blob-2"></div>
            </div>
            <div class="container">
              <div class="process-header" data-aos="fade-up">
                <span class="process-eyebrow">Our Process</span>
                @if(!empty($service->process_intro))
                    {!! \App\Support\Html::fixLinks($service->process_intro) !!}
                @else
                    <h2 class="process-title">How we deliver <em>{{ $service->name }}</em></h2>
                    <p class="process-subtitle">A structured {{ $processCount }}-phase engagement built for precision and speed.</p>
                @endif
              </div>
              <div class="process-grid">
                @for ($i = 0; $i < $processCount; $i++)
                  <div class="process-card" data-aos="fade-up" data-aos-delay="{{ min(($i + 1) * 100, 500) }}">
                    <div class="card-shine"></div>
                    <div class="step-num-wrapper">
                      <span class="step-num">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                      <div class="step-icon"></div>
                    </div>
                    <h3>{{ $processHeaders[$i] ?? 'Process Step' }}</h3>
                    <p>{!! $processDescriptions[$i] ?? '' !!}</p>
                    @php $stepService = !empty($processServiceIds[$i]) ? ($processServiceMap[$processServiceIds[$i]] ?? null) : null; @endphp
                    @if ($stepService)
                      <a href="{{ route('front.service', $stepService->slug) }}" class="process-svc">
                        <span class="process-svc-name">{{ $stepService->name }} →</span>
                      </a>
                    @endif
                  </div>
                @endfor
              </div>
            </div>
          </section>
        @endif

        <!--FAQ Section-->
        <section class="help-list-section" id="faq-section">
            <div class="container">
                <header class="mb-5" data-aos="fade-up">
                    <h2 class="mb-3">Frequently Asked Questions</h2>
                    <p class="text-muted">Common questions about {{ $service->name }} and our approach.</p>
                </header>

                <div class="faq-accordion">
                    @php
                        $displayFaqs = $service->faq ?? collect();
                        if ($displayFaqs->count() == 0) {
                            $displayFaqs = collect([
                                (object) [
                                    'faq_question' => 'What is the primary focus of ' . $service->name . '?',
                                    'faq_answer' => 'Our ' . $service->name . ' service focuses on delivering comprehensive, state-of-the-art solutions tailored to unique healthcare environments, ensuring operational efficiency and patient-centric care.'
                                ],
                                (object) [
                                    'faq_question' => 'How does Alpha Healthcare approach project timelines?',
                                    'faq_answer' => 'We utilize agile methodologies and deep clinical expertise to ensure projects are delivered on time without compromising on the high standards of safety and quality expected in healthcare.'
                                ],
                                (object) [
                                    'faq_question' => 'Can these services be customized for specific facility needs?',
                                    'faq_answer' => 'Absolutely. Every healthcare facility is different. Our multidisciplinary team works closely with stakeholders to adapt our strategies to your specific operational goals and site constraints.'
                                ]
                            ]);
                        }
                    @endphp

                    @foreach ($displayFaqs as $index => $faq)
                        <div class="faq-item {{ $index === 0 ? 'active' : '' }}" data-aos="fade-up"
                            data-aos-delay="{{ min($index * 100, 500) }}">
                            <button class="faq-header" onclick="toggleFaq(this)">
                                <h3 class="faq-question">{{ $faq->faq_question }}</h3>
                                <span class="faq-icon"><i class="fa-solid fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-content">
                                <div class="faq-body">
                                    {!! \App\Support\Html::fixLinks($faq->faq_answer) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <script>
            function toggleFaq(el) {
                const item = el.closest('.faq-item');
                item.classList.toggle('active');
                const content = item.querySelector('.faq-content');
                if (item.classList.contains('active')) {
                    content.style.maxHeight = content.scrollHeight + "px";
                } else {
                    content.style.maxHeight = null;
                }
            }

            // Initialize active FAQ content height
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.faq-item.active .faq-content').forEach(el => {
                    el.style.maxHeight = el.scrollHeight + "px";
                });
            });
        </script>

        <!-- Related services you might be interested in -->
        @php
            $displayServices = (isset($relatedServices) ? $relatedServices : collect())->take(6);
            if ($displayServices->isEmpty()) {
                $displayServices = isset($featuredServices) ? $featuredServices->take(3) : collect();
            }
        @endphp
        @if ($displayServices->count())
            <section class="bottom-interests">
                <div class="container">
                    <h2 class="mb-5 text-center" style="font-size: 2.8rem; font-weight: 700; color: #1a1a1a;">Related services you might be interested in</h2>
                    <div class="row g-4">
                        @foreach ($displayServices as $related)
                            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <a href="{{ route('front.service', $related->slug) }}" class="interest-card-link">
                                    <div class="interest-card">
                                        <div class="interest-img-wrapper">
                                            @php $relImg = $related->hero_image ? \App\Support\Img::thumb('uploads/service_images/' . $related->hero_image, 800) : asset('public/front/assets/img/hero/service-details-bg.jpg'); @endphp
                                            <img src="{{ $relImg }}"
                                                alt="{{ $related->name }}" @if($_sz = \App\Support\Img::size($relImg)) width="{{ $_sz[0] }}" height="{{ $_sz[1] }}" @endif loading="lazy" decoding="async">
                                        </div>
                                        <div class="interest-content">
                                            <span class="text-uppercase small fw-bold text-muted mb-2 d-block">
                                                Related Service
                                            </span>
                                            <h3>{{ $related->name }}</h3>
                                            <div class="interest-meta">
                                                <span>View Service</span>
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Case Studies & Related Content Split Section (only related items; hidden when none) -->
        @php
            // Related articles: chosen in the admin (up to 3), else the automatic pick (layout fix, Saroth 5 Nov 2026).
            $relatedList = ($relatedBlogs ?? collect())->filter()->values();
            $blog = $relatedList->first() ?? ($relatedBlog ?? null);
            if ($relatedList->isEmpty() && $blog) { $relatedList = collect([$blog]); }
        @endphp
        @if ($projects->count() || $blog)
        <section class="split-section-ey">
            <div class="container">
                <div class="row">
                    @if ($projects->count())
                    <!-- Left Column: Case Studies -->
                    <div class="{{ $blog ? 'col-lg-7' : 'col-lg-12' }}" data-aos="fade-right">
                        <h2 class="ey-section-title">Case Studies</h2>
                        <div class="row g-4">
                            @foreach ($projects->take(2) as $project)
                                <div class="col-md-6">
                                    <div class="ey-case-card">
                                        <div class="ey-case-img-wrapper">
                                            @if(isset($project->projects_images[0]))
                                                @php $caseImg = \App\Support\Img::thumb($project->projects_images[0]->image, 800); @endphp
                                                <img src="{{ $caseImg }}"
                                                    class="ey-case-img" alt="{{ $project->name }}" @if($_sz = \App\Support\Img::size($caseImg)) width="{{ $_sz[0] }}" height="{{ $_sz[1] }}" @endif loading="lazy" decoding="async">
                                            @endif
                                        </div>
                                        <div class="ey-case-body">
                                            <span class="ey-case-cat">{{ $project->project_category->name }}</span>
                                            <h3 class="ey-case-title">{{ $project->name }}</h3>
                                            <div class="ey-case-meta" style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 15px; display: flex; gap: 10px;">
                                                <span><i class="far fa-calendar-alt"></i> {{ $project->created_at->format('M d, Y') }}</span>
                                                <span><i class="fas fa-history"></i> {{ $project->updated_at->format('M d, Y') }}</span>
                                            </div>
                                            <a href="{{ route('front.project_details', $project->slug) }}" class="text-dark fw-bold text-decoration-none">
                                VIEW CASE STUDY <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if ($blog && !$projects->count())
                    <!-- No case studies: the related articles take the full width, side by side (never one card in a
                         two-thirds column). -->
                    <div class="col-12" data-aos="fade-up">
                        <div class="related-content-block">
                            <h2 class="ey-section-title">{{ $relatedList->count() > 1 ? 'Related Articles' : 'Related Article' }}</h2>
                            <div class="row g-4 ey-related-grid">
                                @foreach ($relatedList->take(3) as $blog)
                                <div class="{{ $relatedList->count() >= 3 ? 'col-lg-4 col-md-6' : ($relatedList->count() === 2 ? 'col-md-6' : 'col-12') }}">
                                <a href="{{ route('front.singleBlog', $blog->slug) }}" class="ey-related-card">
                                    @php $blogImg = isset($blog->image) && $blog->image ? \App\Support\Img::thumb('uploads/blog_images/' . $blog->image, 800) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp'); @endphp
                                    <img src="{{ $blogImg }}"
                                        class="rc-bg-img" alt="{{ $blog->title }}" @if($_sz = \App\Support\Img::size($blogImg)) width="{{ $_sz[0] }}" height="{{ $_sz[1] }}" @endif loading="lazy" decoding="async">

                                    <div class="ey-related-content">
                                        <h3 class="rc-title">{{ $blog->title }}</h3>
                                        <div class="rc-desc">
                                            {!! Str::limit(strip_tags($blog->content), 120) !!}
                                        </div>
                                        <div class="rc-meta">
                                            <span class="rc-author">
                                                <i class="far fa-user"></i> By {{ $blog->tags->first()->name ?? 'Alpha Team' }}
                                            </span>
                                            <div class="rc-divider"></div>
                                            <span class="rc-date" title="Published Date">
                                                <i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('M d, Y') }}
                                            </span>
                                            <div class="rc-divider"></div>
                                            <span class="rc-date" title="Last Updated">
                                                <i class="fas fa-history"></i> {{ $blog->updated_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @elseif ($blog)
                    <!-- Right Column: Related article -->
                    <div class="col-lg-5" data-aos="fade-left">
                        <div class="related-content-block">
                            <h2 class="ey-section-title">Related Article</h2>
                                <a href="{{ route('front.singleBlog', $blog->slug) }}" class="ey-related-card">
                                    @php $blogImg = isset($blog->image) && $blog->image ? \App\Support\Img::thumb('uploads/blog_images/' . $blog->image, 800) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp'); @endphp
                                    <img src="{{ $blogImg }}"
                                        class="rc-bg-img" alt="{{ $blog->title }}" @if($_sz = \App\Support\Img::size($blogImg)) width="{{ $_sz[0] }}" height="{{ $_sz[1] }}" @endif loading="lazy" decoding="async">

                                    <div class="ey-related-content">
                                        <h3 class="rc-title">{{ $blog->title }}</h3>
                                        <div class="rc-desc">
                                            {!! Str::limit(strip_tags($blog->content), 120) !!}
                                        </div>
                                        <div class="rc-meta">
                                            <span class="rc-author">
                                                <i class="far fa-user"></i> By {{ $blog->tags->first()->name ?? 'Alpha Team' }}
                                            </span>
                                            <div class="rc-divider"></div>
                                            <span class="rc-date" title="Published Date">
                                                <i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('M d, Y') }}
                                            </span>
                                            <div class="rc-divider"></div>
                                            <span class="rc-date" title="Last Updated">
                                                <i class="fas fa-history"></i> {{ $blog->updated_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif



        {{-- ── Alpha Blueprint AI launcher ───────────────────── --}}
        @include('front.partials.planner-cta', ['variant' => 'light'])

        {{-- ── Testimonials (conditional) ────────────────────── --}}
        @if($service->show_testimonials)
            @include('front.partials.testimonial-pills')
        @endif



        @include('front.view.announcement')

        <!-- Inquiry Modal — shared default "Book a Consultation" (popup-blocker-safe Bootstrap modal, mobile-optimized) -->
        @include('front.partials.inquiry-modal')
        <script>
            (function () {
                var im = document.getElementById('inquiryModal');
                if (!im) return;
                im.addEventListener('show.bs.modal', function () {
                    if (typeof window.ahgPrefillInquiry === 'function') {
                        window.ahgPrefillInquiry({ serviceId: {{ $service->id }}, serviceName: @json($service->name) });
                    }
                });
            })();
        </script>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Share functionality (Global)
                const shareBtn = document.getElementById('share-icon');
                if (shareBtn) {
                    shareBtn.addEventListener('click', async (e) => {
                        e.preventDefault();
                        if (navigator.share) {
                            try {
                                await navigator.share({
                                    title: '{{ $service->name }}',
                                    text: 'Professional Healthcare Consultancy',
                                    url: window.location.href
                                });
                            } catch (err) { }
                        } else {
                            navigator.clipboard.writeText(window.location.href);
                            alert('Link copied to clipboard!');
                        }
                    });
                }


                // New 4th Item Slider (started when it nears the screen, speed brief 5 Nov 2026)
                window.ahgWhenNear('.gallery-slider-4', () => new Swiper('.gallery-slider-4', {
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    allowTouchMove: false,   /* use arrows/autoplay; never capture vertical scroll on mobile */
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        // disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                }));

                // AOS init
                if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 50
                });
            }

            // Show inquiry modal on hash
            if (window.location.hash === '#inquiryModal' || window.location.hash === '#inquiry_success') {
                const inquiryModalNode = document.getElementById('inquiryModal');
                if (inquiryModalNode) {
                    const inquiryModal = new bootstrap.Modal(inquiryModalNode);
                    inquiryModal.show();
                }
            }
                    });

            // FAQ Toggle Function (outside document.ready so it's globally available to onclick attrs)
            function toggleFaq(button) {
                const item = button.parentElement;
                const isActive = item.classList.contains('active');

                // Close all other items
                document.querySelectorAll('.faq-item').forEach(el => {
                    el.classList.remove('active');
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            }
        </script>
    @endpush

    {{-- Page context for conversion tracker --}}
    @push('scripts')
    <script>
        window._ahgPage = {
            service_name: @json($service->name ?? ''),
            service_slug: @json($service->slug ?? ''),
            page_type:    'service',
            lead_type:    @json(\App\Support\LeadType::for($service ?? null)),
        };
    </script>
    @endpush
@endsection