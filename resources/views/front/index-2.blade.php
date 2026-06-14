@extends('front/layout-2')

@push('page_title', 'Healthcare Consultancy in Dubai | Alpha Health Group')

@section('meta_description')Alpha Health Group is a trusted healthcare consultancy in the UAE. We deliver DOH compliance, accreditation support, quality assurance, and operational excellence for hospitals and clinics.@endsection

@push('meta')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "Alpha Health Group Site Navigation",
  "itemListElement": [
    {
      "@type": "SiteNavigationElement",
      "position": 1,
      "name": "Home",
      "description": "Alpha Health Group — UAE healthcare consultancy home",
      "url": "{{ route('home') }}"
    },
    {
      "@type": "SiteNavigationElement",
      "position": 2,
      "name": "About Alpha Health Group",
      "description": "Learn about Alpha Health Group's mission, vision, and expertise in healthcare consultancy across the UAE.",
      "url": "{{ route('front.new-about') }}"
    },
    {
      "@type": "SiteNavigationElement",
      "position": 3,
      "name": "All Services",
      "description": "Explore all healthcare consultancy services offered by Alpha Health Group including DOH compliance and accreditation.",
      "url": "{{ route('front.all-services') }}"
    },
    {
      "@type": "SiteNavigationElement",
      "position": 4,
      "name": "Healthcare Quality Assurance",
      "description": "Tawqeet — Alpha Health Group's healthcare quality assurance and KPI monitoring platform.",
      "url": "{{ route('healthcare_quality_assurance') }}"
    },
    {
      "@type": "SiteNavigationElement",
      "position": 5,
      "name": "How Alpha Works",
      "description": "Our proven 4-step methodology for healthcare facility compliance, planning, execution, and results.",
      "url": "{{ route('how_alpha_work') }}"
    },
    {
      "@type": "SiteNavigationElement",
      "position": 6,
      "name": "Blog & Insights",
      "description": "Healthcare industry news, compliance updates, and expert insights from Alpha Health Group.",
      "url": "{{ route('front.blog') }}"
    },
    {
      "@type": "SiteNavigationElement",
      "position": 7,
      "name": "Contact Us",
      "description": "Get in touch with Alpha Health Group for healthcare consultancy services in the UAE.",
      "url": "{{ route('contact') }}"
    }
  ]
}
</script>
@endpush

@section('content')

     <!-- ===== DUAL-MODE HERO SECTION ===== -->
    <section class="hero-video-modern" id="heroSection">

        <!-- BACKGROUND: Video Layer (Default Active) -->
        <!-- poster shows instantly; source is injected only on desktop to avoid mobile network hit -->
        <video muted loop playsinline class="hero-bg-video hero-bg-active" id="heroVideo"
               poster="{{ asset('public/front-new/assets/images/video-thumbnail.jpg') }}">
        </video>
        <script>
            if (window.innerWidth >= 768) {
                (function () {
                    var v = document.getElementById('heroVideo');
                    var s = document.createElement('source');
                    s.src  = '{{ asset("public/front-new/assets/images/AHG Hero Video.mp4") }}';
                    s.type = 'video/mp4';
                    v.appendChild(s);
                    v.load();
                    v.play().catch(function () {});
                })();
            }
        </script>

        <!-- BACKGROUND: Image Slider Layer -->
        <div class="hero-bg-images" id="heroImageLayer">
            @if(isset($homeSliders) && count($homeSliders) > 0)
                @foreach($homeSliders as $index => $slider)
                    <div class="hero-bg-image-item {{ $index == 0 ? 'hero-img-active' : '' }}"
                        style="background-image: url('{{ asset('public/uploads/slider_images/' . $slider->image) }}');"
                        data-image-index="{{ $index }}">
                    </div>
                    @if($index === 0)
                        {{-- Preload the first hero image so it's fetched at highest priority --}}
                        @push('meta')
                        <link rel="preload" as="image"
                              href="{{ asset('public/uploads/slider_images/' . $slider->image) }}"
                              fetchpriority="high">
                        @endpush
                    @endif
                @endforeach
            @endif
        </div>

        <div class="hero-video-overlay"></div>

        <!-- Content -->
        <div class="container h-100 position-relative d-flex flex-column justify-content-center"
            style="z-index:5; padding-top: 100px;">

           <div class="hero-texts-wrapper">
                @if(isset($homeSliders) && count($homeSliders) > 0)
                    @foreach($homeSliders as $index => $slider)
                        <div class="hero-slide-item {{ $index == 0 ? 'slide-active' : '' }}" data-index="{{ $index }}">
                            <h1 class="hero-main-title">
                                {{ $slider->main_title }}
                            </h1>
                            @if($slider->pre_title)
                                <h3 class="hero-pre-title">{{ $slider->pre_title }}</h3>
                            @endif
                            

                            <div class="hero-cta-wrap">
                                @if(!empty($slider->button_link) && $slider->button_link !== '#')
                                    <a href="{{ $slider->button_link }}" class="btn-hero-premium">
                                        <span>{{ $slider->button_text ?? 'Learn More' }}</span>
                                        <div class="btn-hero-icon"><i class="fa-solid fa-arrow-right"></i></div>
                                    </a>
                                @endif
                                <button type="button" class="btn-hero-secondary"
                                        data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                    Book a Consultation <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>


            <div class="hero-progress-track">
                <div class="hero-progress-fill" id="heroProgressBar"></div>
            </div>

            <!-- MODE TOGGLE BUTTONS -->
            <div class="hero-mode-bar">
                <button class="hero-mode-btn active-mode" id="btnVideoMode">
                    <i class="fa-solid fa-circle-play"></i>

                </button>
                <div class="mode-bar-divider"></div>
                <button class="hero-mode-btn" id="btnImageMode">
                    <i class="fa-solid fa-images"></i>
                </button>
            </div>

            <!-- DYNAMIC HERO QUICK LINKS (Dashboard Slider Data) -->
            <div class="hero-quick-links-wrap shadow-premium-lg">
                {{-- <div class="swiper quickLinksSwiper">
                    <div class="swiper-wrapper">
                        @php
                            $ql_icons = [
                                'fa-solid fa-laptop-medical',
                                'fa-solid fa-newspaper',
                                'fa-solid fa-images',
                                'fa-solid fa-headset',
                                'fa-solid fa-user-plus',
                                'fa-solid fa-microscope',
                                'fa-solid fa-stethoscope',
                                'fa-solid fa-heart-pulse'
                            ];
                        @endphp

                        @if(isset($homeSliders) && count($homeSliders) > 0)
                            @foreach($homeSliders as $index => $slider)
                                <div class="swiper-slide">
                                    <a href="{{ $slider->button_link ?? '#' }}" class="quick-link-card">
                                        <div class="ql-icon">
                                            <i class="{{ $ql_icons[$index % count($ql_icons)] }}"></i>
                                        </div>
                                        <h4 class="ql-title">{{ $slider->main_title }}</h4>
                                        <p class="ql-desc">
                                            {{ Str::limit(strip_tags($slider->pre_title ?? 'Explore our premium healthcare solutions and facility services.'), 70) }}
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        @else --}}
                            <!-- Placeholder cards if no sliders -->
                             {{-- @for($i=1; $i<=5; $i++)
                                <div class="swiper-slide">
                                    <a href="#" class="quick-link-card">
                                        <div class="ql-icon"><i class="fa-solid fa-clinic-medical"></i></div>
                                        <h4 class="ql-title">Service {{ $i }}</h4>
                                        <p class="ql-desc">Professional healthcare solutions for your needs.</p>
                                    </a>
                                </div>
                             @endfor
                        @endif
                    </div> --}}

                    <!-- SLIDER NAVIGATION BUTTONS -->
                    {{-- <div class="ql-nav-wrap">
                        <div class="ql-prev"><i class="fa-solid fa-chevron-left"></i></div>
                        <div class="ql-next"><i class="fa-solid fa-chevron-right"></i></div>
                    </div> --}}
                </div>
            </div>

        </div>
    </section>

    <link rel="stylesheet" href="{{ asset('public/front/assets/css/home.css') }}?v=1">


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var slides = document.querySelectorAll('.hero-slide-item');
            var bgImages = document.querySelectorAll('.hero-bg-image-item');
            var progressBar = document.getElementById('heroProgressBar');
            var btnVideo = document.getElementById('btnVideoMode');
            var btnImage = document.getElementById('btnImageMode');
            var videoBg = document.getElementById('heroVideo');
            var imageBg = document.getElementById('heroImageLayer');
            var currentSlide = 0;
            var currentMode = 'video';
            var progressTimer = null;
            var DURATION = 7000;

            function syncBgImage() {
                bgImages.forEach(function (img, i) { img.classList.toggle('hero-img-active', i === currentSlide); });
            }
            function startProgress() {
                clearInterval(progressTimer);
                var pct = 0;
                if (progressBar) progressBar.style.width = '0%';
                progressTimer = setInterval(function () {
                    pct += 100 / (DURATION / 100);
                    if (progressBar) progressBar.style.width = pct + '%';
                    if (pct >= 100) advanceSlide();
                }, 100);
            }
            function advanceSlide() {
                slides[currentSlide].classList.remove('slide-active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('slide-active');
                if (currentMode === 'image') syncBgImage();
                startProgress();
            }
            if (btnVideo) btnVideo.addEventListener('click', function () {
                if (currentMode === 'video') return;
                currentMode = 'video';
                btnVideo.classList.add('active-mode');
                btnImage.classList.remove('active-mode');
                videoBg.classList.add('hero-bg-active');
                imageBg.classList.remove('hero-bg-active');
            });
            if (btnImage) btnImage.addEventListener('click', function () {
                if (currentMode === 'image') return;
                currentMode = 'image';
                btnImage.classList.add('active-mode');
                btnVideo.classList.remove('active-mode');
                imageBg.classList.add('hero-bg-active');
                videoBg.classList.remove('hero-bg-active');
                syncBgImage();
            });
            if (slides.length > 0) startProgress();
        });
    </script>


    <section class="services-selection-modern position-relative">
        {{-- <div class="background-mesh"></div>
        <div class="background-grid"></div> --}}

        <!-- Decorative Background Elements -->
        {{-- <div class="decor-circle decor-1"></div>
        <div class="decor-circle decor-2"></div>
        <div class="decor-dots"></div> --}}

        <div class="container-fluid p-0">
            <div class="d-flex flex-column flex-lg-row align-items-stretch" style="min-height: 70vh;">

                <!-- CONTROLS COLUMN -->
                <div class="col-lg-6 col-md-12 d-flex flex-column justify-content-center p-4 p-md-5 controls-column">
                    <div class="content-wrapper-professional">
                        <div class="section-tag-wrapper mb-4">
                            <span class="professional-badge">OUR EXPERTISE</span>
                            <div class="tag-line"></div>
                        </div>

                        <h2 class="services-title-premium"> Find the services for <br><span class="text-gradient-green">
                                your requirement</span></h2>
                        <p class="services-subtitle-premium mb-5">Browse our services directory to find the right match for
                            your facility from licensing and accreditation to quality assurance and operational
                            consulting, backed by experts who understand healthcare in the UAE &amp; GCC.</p>

                        <div class="dropdown-selection-grid">
                            <!-- Step 1 -->
                            <div class="selection-step mb-4">
                                <div class="step-indicator">
                                    <span class="step-num">01</span>
                                    <span class="step-text">Select Category</span>
                                </div>
                                <div class="modern-dropdown-pro">
                                    <select id="categoryDropdown" class="pro-select">
                                        <option value="" selected disabled>Choose industry category...</option>
                                        @foreach ($main_categories as $main_category)
                                            @foreach ($main_category->mergedCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    data-image="{{ asset('public/' . $category->image) }}"
                                                    data-url="{{ route('front.service-category', $category->slug) }}"
                                                    data-desc="{{ Str::limit(strip_tags($category->description), 160) }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down select-icon"></i>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="selection-step">
                                <div class="step-indicator">
                                    <span class="step-num">02</span>
                                    <span class="step-text">Select Service</span>
                                </div>
                                <div class="modern-dropdown-pro">
                                    <select name="service_name[]" id="serviceDropdown" class="pro-select">
                                        <option value="" selected disabled>Select required service...</option>
                                        @foreach ($main_categories as $main_category)
                                            @foreach ($main_category->mergedCategories as $category)
                                                @foreach ($category->services as $service)
                                                    <option value="{{ $service->id }}" data-parent-id="{{ $category->id }}"
                                                        data-name="{{ $service->name }}" data-slug="{{ $service->slug }}"
                                                        data-overview="{{ strip_tags($service->overview) }}"
                                                        data-hero="{{ $service->hero_image ? asset('public/uploads/service_images/' . $service->hero_image) : '' }}"
                                                        data-image="{{ $service->images->count() ? asset('public/uploads/service_images/' . $service->images->first()->image) : '' }}">
                                                        {{ $service->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down select-icon"></i>
                                </div>
                            </div>
                        </div>

                        <p class="dropdown-helper-note">
                            <i class="fa-solid fa-circle-info"></i>
                            Already know what you need? Skip the category and pick your service directly, we'll take you straight to it.
                        </p>
                    </div>
                </div>

                <!-- IMAGE / DETAILS COLUMN -->
                <div class="col-lg-6 col-md-12 image-column-pro ">
                    <div class="services-premium-grid">
                        <!-- Premium Vertical Navigator -->
                        <div class="premium-navigator-vertical">
                            <span class="nav-label">SOLUTIONS INDEX</span>
                            <div class="nav-line"></div>
                            <span id="serviceCounter" class="nav-number">01</span>
                        </div>

                        <!-- Grid Div 1: Service Image -->
                        <div id="serviceRight" class="grid-div-image shadow-premium"
                            style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('public/front-new/assets/images/services hmc1.jpg') }}');">

                            <!-- Digital Shield Overlay -->
                            <div class="digital-shield"></div>

                            <!-- Precision Frame Brackets -->
                            <div class="frame-bracket bracket-tl"></div>
                            <div class="frame-bracket bracket-tr"></div>
                            <div class="frame-bracket bracket-bl"></div>
                            <div class="frame-bracket bracket-br"></div>
                            <div class="premium-border-frame"></div>
                        </div>

                        <!-- Grid Div 2: Service Content -->
                        <div class="grid-div-content">
                            <div class="glass-details-card-pro">
                                <div class="card-glow"></div>
                                <div id="service_details">
                                    <div class="service-meta mb-3">
                                        <span class="meta-dot"></span>
                                        <span class="category-name-display text-uppercase">Our Services</span>
                                    </div>
                                    <h2 id="serviceName" class="service-display-title">Healthcare Consultation &amp; Outsourcing</h2>
                                    <div class="divider-pro mb-4"></div>
                                    <p id="serviceOverview" class="service-description-pro">From facility licensing and JCIA
                                        accreditation to quality, staffing and end-to-end operational outsourcing, we partner
                                        with healthcare providers across the UAE &amp; GCC to turn complex requirements into
                                        measurable results. Explore the full range and find the right fit for your facility.
                                    </p>
                                    <div class="preview-cta-row">
                                        <a id="singleService" href="{{ route('front.all-services') }}" class="btn-professional-teal">
                                            <span class="btn-text">VIEW ALL SERVICES</span>
                                            <span class="btn-icon">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </span>
                                        </a>
                                        <button type="button" class="btn-preview-consult"
                                                data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                            Book a Consultation
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--  -->


    <!-- Expert Solutions Section -->
    <section class="recommended-section py-5 mt-5">    <!--##OLD CODE## <section class="recommended-section container py-5 mt-5">-->
        <div class="mb-5">
            <h2 class="display-5 winky-sans font-weight-700">Our Expert Solutions <span class="text-teal-gradient">Based on
                    Your Interests</span></h2>
            <p class="recommended-sub">From licensing and accreditation to operational excellence, explore the specialist
                healthcare consulting solutions trusted to turn complex challenges into measurable, lasting results.</p>
            <div class="header-line"></div>
        </div>

        <div id="articleGrids">
        <div class="parent">
            @foreach ($categories_carts as $index => $category)
                @php
                    $gridClass = isset($gridClasses[$index]) ? $gridClasses[$index] : 'div' . ($index + 1);
                @endphp

                <a href="{{ route('front.service-category', $category->slug) }}"
                    class="article-card {{ $gridClass }}" aria-label="Explore {{ $category->name }}"
                    style="background-image: url('{{ $category->card_image ? asset('public/' . ltrim($category->card_image, '/')) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}')" data-aos="fade-up"
                    data-aos-delay="{{ $index * 50 }}">
                    <div class="article-content">
                        <h4>{{ $category->name }}</h4>
                        <p>{{ Str::limit(strip_tags($category->description), 100) }}</p>
                        <span class="btn-premium-read-more">
                            Explore <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
        </div>{{-- /articleGrids --}}

        @if (($featured_categories_total ?? 0) > 4)
            <div class="text-center mt-5" id="loadMoreWrap">
                <button class="view-more-button-outline" id="loadMoreArticles" data-aos="fade-up" data-aos-delay="100">
                    <span>Load More</span>
                    <i class="fa-solid fa-plus ms-2"></i>
                </button>
            </div>
        @endif
    </section>

    




    


    <!-- Clients Section -->
    {{-- <section class="mt-4 mb-4 text-center">
        <div class="py-5">
            <h2 class="lh-sm winky-sans" style="font-size: 40px">
                Meet Our Clients
            </h2>
            <h6>AND THEIR JOURNEYS</h6>
        </div>

        <div class="container">
            <div class="logo-slider-wrapper ">
                <div class="logo-slider d-flex flex-nowrap ">
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            width="180px" height="100%" alt="Logo 1" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/logo.png') }}" width="180px" height="100%"
                            alt="Logo 2" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            width="180px" height="100%" alt="Logo 3" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/logo.png') }}" width="180px" height="100%"
                            alt="Logo 4" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            width="180px" height="100%" alt="Logo 5" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/logo.png') }}" width="180px" height="100%"
                            alt="Logo 6" />
                    </div>
                    <!-- Duplicate for loop -->
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            width="180px" height="100%" alt="Logo 1" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/logo.png') }}" width="180px" height="100%"
                            alt="Logo 2" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            width="180px" height="100%" alt="Logo 3" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/logo.png') }}" width="180px" height="100%"
                            alt="Logo 4" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            width="180px" height="100%" alt="Logo 5" />
                    </div>
                    <div class="logo-slide">
                        <img class="menu-text" src="{{ asset('public/logo.png') }}" width="180px" height="100%"
                            alt="Logo 6" />
                    </div>
                </div>
            </div>

            <button class="view-more-button mt-5">
                View <strong>More</strong>
                <span class="custom-icon">
                    <i class="fa-solid fa-angle-right"></i>
                </span>
            </button>
        </div>
    </section> --}}

    {{-- Clients carousel moved into the "Our Clients" section above the announcements --}}
    <!-- Careers/About Section -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Playfair+Display:wght@400;500;600&display=swap"
        rel="stylesheet">
    



    {{-- strategy-section removed --}}


    <!-- ═══════════ INSIGHTS BEYOND COMPLIANCE (featured blogs carousel) ═══════════ -->
    @if(isset($insightBlogs) && $insightBlogs->count())
    <section class="ibc-section" aria-labelledby="ibc-heading">
        <div class="ibc-head">
            <span class="ibc-eyebrow">Knowledge Base</span>
            <h2 class="ibc-title" id="ibc-heading">Insights Beyond <span class="ibc-title-accent">Compliance</span></h2>
            <p class="ibc-sub">Practical perspectives on healthcare licensing, accreditation and operational
                excellence, turning regulatory complexity into measurable outcomes for facilities across the UAE.</p>
        </div>

        <div class="ibc-viewport" id="ibcViewport" tabindex="0" aria-label="Featured insights, scrollable">
            <div class="ibc-track" id="ibcTrack">
                @foreach($insightBlogs->concat($insightBlogs) as $loopIndex => $blog)
                    <a href="{{ route('front.singleBlog', $blog->slug) }}"
                       class="ibc-card" draggable="false"
                       aria-hidden="{{ $loopIndex >= $insightBlogs->count() ? 'true' : 'false' }}"
                       @if($loopIndex >= $insightBlogs->count()) tabindex="-1" @endif>
                        <div class="ibc-card-media"
                             style="background-image:url('{{ $blog->image ? asset('public/uploads/blog_images/' . $blog->image) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}')"></div>
                        <div class="ibc-card-scrim"></div>
                        <div class="ibc-card-panel">
                            <span class="ibc-card-label">Insight</span>
                            <h3 class="ibc-card-title">{{ $blog->title }}</h3>
                            <p class="ibc-card-desc">{{ Str::limit(strip_tags($blog->description), 110) }}</p>
                            <span class="ibc-card-more">Read More <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    

    <script>
        (function () {
            var viewport = document.getElementById('ibcViewport');
            var track    = document.getElementById('ibcTrack');
            if (!viewport || !track) return;

            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var copyWidth = 0;                 // width of one full set of cards (half the track)
            function measure() { copyWidth = track.scrollWidth / 2; }
            measure();
            window.addEventListener('resize', measure);
            // images/fonts can change layout; re-measure shortly after load
            window.addEventListener('load', measure);
            setTimeout(measure, 600);

            // Seamless wrap: keep scrollLeft within [0, copyWidth)
            function wrap() {
                if (copyWidth <= 0) return;
                if (viewport.scrollLeft >= copyWidth) viewport.scrollLeft -= copyWidth;
                else if (viewport.scrollLeft < 0)     viewport.scrollLeft += copyWidth;
            }

            // ── Auto-advance (rAF), pauses on interaction ──
            var SPEED = 0.45;                  // px per frame (~27px/s at 60fps)
            var paused = false, idleTimer = null, rafId = null;
            function pause() {
                paused = true;
                clearTimeout(idleTimer);
                idleTimer = setTimeout(function () { paused = false; }, 1600);
            }
            function tick() {
                if (!paused && !reduceMotion) { viewport.scrollLeft += SPEED; wrap(); }
                rafId = requestAnimationFrame(tick);
            }
            if (!reduceMotion) rafId = requestAnimationFrame(tick);

            viewport.addEventListener('scroll', wrap, { passive: true });
            viewport.addEventListener('mouseenter', function () { paused = true; clearTimeout(idleTimer); });
            viewport.addEventListener('mouseleave', function () { paused = false; });
            viewport.addEventListener('wheel',      pause, { passive: true });
            viewport.addEventListener('touchstart', function () { paused = true; clearTimeout(idleTimer); }, { passive: true });
            viewport.addEventListener('touchend',   pause,  { passive: true });
            viewport.addEventListener('focusin',    function () { paused = true; clearTimeout(idleTimer); });

            // ── Pointer drag-to-scroll (desktop tactile feel); a real click still navigates ──
            // Key fix: do NOT capture the pointer or preventDefault on pointerdown — that
            // redirects events to the viewport and swallows the card's click. Only engage
            // drag mode once the pointer actually moves past a small threshold.
            var DRAG_THRESHOLD = 6;
            var down = false, dragging = false, startX = 0, startScroll = 0, moved = 0, activeId = null;

            function endDrag() {
                if (!down) return;
                down = false; dragging = false; activeId = null;
                viewport.classList.remove('is-dragging');
                pause();
            }

            viewport.addEventListener('pointerdown', function (e) {
                if (e.pointerType === 'touch') return;     // native touch handles itself
                if (e.button !== 0) return;                // left button only
                down = true; dragging = false; moved = 0;
                startX = e.clientX; startScroll = viewport.scrollLeft;
                activeId = e.pointerId;
                paused = true; clearTimeout(idleTimer);
                // no preventDefault / no setPointerCapture here — would break the click
            });
            viewport.addEventListener('pointermove', function (e) {
                if (!down || e.pointerId !== activeId) return;
                var dx = e.clientX - startX; moved = Math.abs(dx);
                if (!dragging) {
                    if (moved <= DRAG_THRESHOLD) return;   // still a click, not a drag
                    dragging = true;
                    viewport.classList.add('is-dragging');
                    // now that it's a confirmed drag, capture so move/up reach the viewport
                    try { viewport.setPointerCapture(e.pointerId); } catch (_) {}
                }
                viewport.scrollLeft = startScroll - dx; wrap();
                e.preventDefault();   // stop text/native selection while dragging
            });
            viewport.addEventListener('pointerup', endDrag);
            viewport.addEventListener('pointercancel', endDrag);
            viewport.addEventListener('lostpointercapture', function () {
                down = false; dragging = false; activeId = null; viewport.classList.remove('is-dragging');
            });
            // belt-and-braces: any mouseup anywhere ends a stuck drag
            window.addEventListener('mouseup', function () {
                if (down) { down = false; dragging = false; activeId = null; viewport.classList.remove('is-dragging'); pause(); }
            });
            // kill native HTML5 drag of links/images entirely
            track.addEventListener('dragstart', function (e) { e.preventDefault(); });
            // Suppress navigation ONLY if it was actually a drag
            track.addEventListener('click', function (e) {
                if (moved > DRAG_THRESHOLD) { e.preventDefault(); }
            }, true);
        })();
    </script>
    @endif

    <!-- CASE STUDIES & RELATED CONTENT -->
    <section class="case-related-modern py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Case Studies Column -->
                <div class="col-lg-7">
                    <h2 class="section-heading-minimal mb-2">Case Studies</h2>
                    <p class="case-related-desc">Real projects and the problems we solved. Explore the details and
                        measurable results behind our healthcare facility engagements.</p>
                    <div class="row g-4">
                        @if(isset($projects) && $projects->count() > 0)
                            @foreach($projects as $project)
                                <div class="col-md-6">
                                    <a href="{{ route('front.project_details', $project->slug) }}" class="case-study-card" aria-label="View case study: {{ $project->name }}">
                                        <div class="case-card-media">
                                            @php
                                                $projectCover = $project->projects_images->first();
                                                $projectImagePath = ($projectCover && $projectCover->image) ? asset('public/' . $projectCover->image) : 'https://placehold.co/600x400';
                                            @endphp
                                            <img src="{{ $projectImagePath }}" alt="{{ $project->name }}">
                                        </div>
                                        <div class="case-card-info">
                                            <span class="case-label">{{ $project->project_category->name ?? 'Project' }}</span>
                                            <h3 class="case-card-title">{{ $project->name }}</h3>
                                            <span class="case-card-link">
                                                View Case Study <i class="fas fa-arrow-right"></i>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Alpha Updates Column -->
                <div class="col-lg-5">
                    <h2 class="section-heading-minimal mb-2">Alpha Updates</h2>
                    <p class="case-related-desc">The latest news and updates about Alpha Health Group.</p>

                    @if(isset($alphaUpdates) && $alphaUpdates->count())
                        <div class="alpha-updates-list">
                            @foreach($alphaUpdates as $update)
                                <a href="{{ route('front.singleBlog', $update->slug) }}" class="alpha-update-card">
                                    <div class="alpha-update-media">
                                        <img src="{{ $update->image ? asset('public/uploads/blog_images/' . $update->image) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}"
                                             alt="{{ $update->title }}" loading="lazy">
                                    </div>
                                    <div class="alpha-update-body">
                                        <span class="alpha-update-tag">Alpha Update</span>
                                        <h3 class="alpha-update-title">{{ $update->title }}</h3>
                                        <span class="alpha-update-more">Read More <i class="fa-solid fa-arrow-right"></i></span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="alpha-updates-empty">
                            <i class="fa-regular fa-newspaper"></i>
                            <p>No updates yet. Check back soon for the latest from Alpha Health Group.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ BOOK A CONSULTATION (post-proof conversion band) ═══════════ --}}
    <section class="book-consult-cta">
        <div class="container">
            <h2 class="bcc-title">Let's talk about what your facility needs</h2>
            <p class="bcc-sub">Whether it's licensing, accreditation, quality assurance, staffing, or day-to-day
                operations, our consultants will help you find the right place to start. Tell us a little about your
                facility and we'll reply with clear, practical next steps.</p>
            <button type="button" class="bcc-btn" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                Book a Consultation <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </section>

    

    @push('inquiry_modal')
        @include('front.partials.inquiry-modal')
    @endpush

    

    <!-- ═══════════ OUR CLIENTS (featured logos, minimal marquee) ═══════════ -->
    @if(isset($clients) && $clients->count())
    <section class="oc-section">
        <div class="oc-head">
            <h2 class="oc-title">Our Clients</h2>
            <p class="oc-sub">Trusted by leading healthcare providers and professionals across the UAE and GCC,
                partnering with ambitious organisations to realise their full clinical and operational potential.</p>
        </div>

        <div class="oc-marquee" aria-label="Our clients">
            <div class="oc-track">
                @foreach($clients->concat($clients) as $i => $client)
                    <div class="oc-logo" title="{{ $client->name }}" aria-hidden="{{ $i >= $clients->count() ? 'true' : 'false' }}">
                        <img src="{{ asset('public/uploads/clients/' . $client->logo) }}" alt="{{ $client->name }}" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    
    @endif

    <!-- ANNOUNCEMENTS SECTION (SPLIT MODERN - FULL WIDTH) -->

    @if(isset($announcements) && $announcements->where('feature', 1)->count() > 0)
<section class="announcement-premium-section pt-2 pb-0">
    <div class="container mb-5">
        {{-- <div class="section-header-wrap">
            <span class="ey-badge">Updates</span>
            <h2 class="ey-section-title mt-2">Latest Announcements</h2>
        </div> --}}
    </div>

    <div class="container-fluid px-0">
        @php $featured = $announcements->where('feature', 1)->values(); @endphp

        @if($featured->count() > 1)
        <div class="ann-slider-wrapper" id="annSlider">
            @foreach($featured as $index => $announcement)
                <div class="announcement-split-card ann-slide {{ $index === 0 ? 'active' : '' }}">
                    <div class="ann-split-content" style="background-image:linear-gradient(to right, rgba(0, 0, 0, 80%), rgba(133, 133, 133, 0)), url('{{ asset('public/uploads/announcements/' . $announcement->image) }}'); background-size: cover; background-position: center;">
                        <div class="ann-text-box">
                            <h3 class="ann-split-title">{{ $announcement->title }}</h3>
                            @if($announcement->description)
                                <p class="ann-split-desc">
                                    {{ strip_tags($announcement->description) }}
                                </p>
                            @endif
                            <div class="ann-split-action">
                                <a href="{{ $announcement->button_link ?? '#' }}" class="btn-ann-ghost">
                                    {{ $announcement->button_text ?? 'Know More' }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach

            {{-- Dots --}}
            <div class="ann-slider-dots">
                @foreach($featured as $index => $announcement)
                    <button class="ann-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></button>
                @endforeach
            </div>

            {{-- Arrows --}}
            <button class="ann-arrow ann-arrow-prev" id="annPrev">&#8592;</button>
            <button class="ann-arrow ann-arrow-next" id="annNext">&#8594;</button>
        </div>

        @else
        {{-- Single --}}
        @foreach($featured as $announcement)
            <div class="announcement-split-card">
                <div class="ann-split-content">
                    <div class="ann-text-box">
                        <h3 class="ann-split-title">{{ $announcement->title }}</h3>
                        @if($announcement->description)
                            <p class="ann-split-desc">
                                {{ strip_tags($announcement->description) }}
                            </p>
                        @endif
                        <div class="ann-split-action">
                            <a href="{{ $announcement->button_link ?? '#' }}" class="btn-ann-ghost">
                                {{ $announcement->button_text ?? 'Know More' }}
                            </a>
                        </div>
                    </div>
                </div>
                {{-- <div class="ann-split-media"
                    style="background-image: url('{{ asset('public/uploads/announcements/' . $announcement->image) }}');">
                </div> --}}
            </div>
        @endforeach
        @endif
    </div>
</section>

<style>
    /* ── Your original styles (unchanged) ── */
    .announcement-premium-section {
        background: #ffffff;
    }

    .announcement-split-card {
        display: flex;
        /* background: #000; */
        min-height: 550px;
        width: 100%;
        overflow: hidden;
        margin-bottom: 0;
    }

    .ann-split-content {
        flex: 1;
        padding: 100px calc((100vw - 1320px) / 2 + 15px);
        display: flex;
        align-items: center;
        /* background: #000; */
        background-image:linear-gradient(to right, rgba(0, 0, 0, 80%), rgba(133, 133, 133, 0)), url('{{ asset('public/uploads/announcements/' . $announcement->image) }}'); 
        background-size: cover; 
        background-position: center;
        position: relative;
        z-index: 2;
        box-sizing: border-box;
    }

    .ann-text-box {
        max-width: 650px;
    }

    .ann-split-title {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(2rem, 5vw, 4.5rem);
        color: #fff;
        line-height: 1.1;
        margin-bottom: 30px;
        font-weight: 400;
        letter-spacing: -1px;
    }

    .ann-split-desc {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.6;
        margin-bottom: 45px;
        font-family: 'Outfit', sans-serif;
        font-weight: 400;
    }

    .btn-ann-ghost {
        display: inline-block;
        padding: 14px 45px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .btn-ann-ghost:hover {
        background: #fff;
        color: #000;
        border-color: #fff;
        transform: translateY(-3px);
    }

    .ann-split-media {
        flex: 1.1;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .ann-split-media::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 40%;
        /* background: linear-gradient(to right, #000 0%, transparent 100%); */
    }

    @media (max-width: 1400px) {
        .ann-split-content {
            padding-left: 80px;
        }
    }

    @media (max-width: 991px) {
        .announcement-premium-section {
            padding: 60px 0;
        }

        .announcement-split-card {
            flex-direction: column;
            height: 1200px;
        }

        .container-fluid {
            padding: 0 20px;
        }

        .ann-split-media {
            height: 40%;
            order: 1;
        }

        .ann-split-media::after {
            width: 100%;
            height: 40%;
            /* background: linear-gradient(to bottom, #000 0%, transparent 100%); */
        }

        .ann-split-content {
            order: 2;
            padding: 80px 30px;
        }
    }

    .ey-badge {
        display: inline-block;
        background: rgba(0, 0, 0, 0.05);
        color: #000;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .ey-section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
    }

    /* ── Slider-only additions ── */
    .ann-slider-wrapper {
        position: relative;
        overflow: hidden;
        min-height: 550px;
    }

    .ann-slide {
        display: flex;
    opacity: 0;
    transition: opacity 0.8s ease;
    pointer-events: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    }

    .ann-slide.active {
        opacity: 1;
    pointer-events: auto;
    position: relative;  /* only active drives the wrapper height */
    height: auto;
    }

    @keyframes annFadeIn {
        from { opacity: 0; transform: translateX(30px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Dots */
    .ann-slider-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }

    .ann-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.5);
        background: transparent;
        cursor: pointer;
        padding: 0;
        transition: background 0.3s;
    }

    .ann-dot.active {
        background: #fff;
        border-color: #fff;
    }

    /* Arrows — match btn-ann-ghost feel */
    .ann-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        width: 48px;
        height: 48px;
        border-radius: 0; /* sharp corners like btn-ann-ghost */
        cursor: pointer;
        font-size: 18px;
        z-index: 10;
        transition: all 0.3s ease(0.165, 0.84, 0.44, 1);
    }

    .ann-arrow:hover {
        background: #fff;
        color: #000;
        border-color: #fff;
    }

    .ann-arrow-prev { left: 20px; }
    .ann-arrow-next { right: 20px; }

    @media (max-width: 991px) {
        .ann-arrow-prev { left: 10px; }
        .ann-arrow-next { right: 10px; }
        .ann-slider-dots { bottom: 10px; }
        .ann-slider-wrapper {
        /* min-height: 1200px; */
    }
    }
</style>

<script>
(function () {
    const wrapper = document.getElementById('annSlider');
    autoplay = true;

    if (!wrapper) return;

    const slides = wrapper.querySelectorAll('.ann-slide');
    const dots   = wrapper.querySelectorAll('.ann-dot');
    const total  = slides.length;
    let current  = 0;
    let timer    = null;
    const DELAY  = 10000;

    function goTo(index) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (index + total) % total;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    function startAuto() {
        timer = setInterval(() => goTo(current + 1), DELAY);
    }

    function stopAuto() {
        clearInterval(timer);
    }

    wrapper.addEventListener('mouseenter', stopAuto);
    wrapper.addEventListener('mouseleave', startAuto);

    document.getElementById('annPrev').addEventListener('click', () => {
        stopAuto(); goTo(current - 1); startAuto();
    });

    document.getElementById('annNext').addEventListener('click', () => {
        stopAuto(); goTo(current + 1); startAuto();
    });

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            stopAuto(); goTo(i); startAuto();
        });
    });

    startAuto();
})();
</script>
@endif


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Advanced Hero Slider Transitions
            const heroSlides = document.querySelectorAll('.hero-slider-item');
            const progressBar = document.getElementById('heroProgressBar');
            const slideDuration = 7000; // 7 seconds per slide

            if (heroSlides.length > 0) {
                let currentSlide = 0;
                let progressInterval;

                function startSlideCycle() {
                    // Clear existing progress logic
                    clearInterval(progressInterval);
                    let progress = 0;
                    if (progressBar) progressBar.style.width = '0%';

                    // Progress Bar Animation
                    progressInterval = setInterval(() => {
                        progress += (100 / (slideDuration / 100));
                        if (progressBar) progressBar.style.width = `${progress}% `;

                        if (progress >= 100) {
                            nextSlide();
                        }
                    }, 100);
                }

                function nextSlide() {
                    heroSlides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % heroSlides.length;
                    heroSlides[currentSlide].classList.add('active');
                    startSlideCycle();
                }

                // Initialization
                startSlideCycle();
            }

            const track = document.querySelector('.strategy-track');
            if (track) track.innerHTML += track.innerHTML;

            const gridSlider = document.getElementById('grid-slider');
            if (gridSlider) {
                const sliderTrack = gridSlider.querySelector('.slider-track');
                const slides = gridSlider.querySelectorAll('.slide');
                const prevBtn = gridSlider.querySelector('.prev-btn');
                const nextBtn = gridSlider.querySelector('.next-btn');
                let currentIndex = 0;
                const totalSlides = slides.length;
                function updateSlider() { sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`; }
                nextBtn.addEventListener('click', () => { currentIndex = (currentIndex + 1) % totalSlides; updateSlider(); });
                prevBtn.addEventListener('click', () => { currentIndex = (currentIndex - 1 + totalSlides) % totalSlides; updateSlider(); });
            }
        });

        const articleSection = {
            offset: {{ $categories_carts->count() }},   // featured categories already rendered in the DOM
            total:  {{ $featured_categories_total ?? 0 }},
            loading: false,
            mobileStep: 4,
            shown: 0,                                    // how many cards are revealed on mobile
            init() {
                this.loadMoreBtn = document.getElementById('loadMoreArticles');
                this.loadMoreWrap = document.getElementById('loadMoreWrap');
                this.gridsWrap = document.getElementById('articleGrids');
                if (!this.gridsWrap) return;
                this.mq = window.matchMedia('(max-width: 768px)');
                this.apply();
                this.mq.addEventListener('change', () => this.apply());
                if (this.loadMoreBtn) this.loadMoreBtn.addEventListener('click', () => this.onLoadMore());
            },
            cards() { return Array.from(this.gridsWrap.querySelectorAll('.article-card')); },
            isMobile() { return this.mq.matches; },
            apply() {
                const cards = this.cards();
                if (this.isMobile()) {
                    if (this.shown === 0) this.shown = this.mobileStep;      // first paint: only 4
                    cards.forEach((c, i) => { c.style.display = i < this.shown ? '' : 'none'; });
                } else {
                    cards.forEach(c => { c.style.display = ''; });           // desktop: show everything rendered
                }
                this.updateButton();
            },
            updateButton() {
                if (!this.loadMoreWrap) return;
                const rendered = this.cards().length;
                const more = this.isMobile()
                    ? (this.shown < rendered || this.offset < this.total)    // reveal more or fetch more
                    : (this.offset < this.total);                           // desktop: only if server has more
                this.loadMoreWrap.style.display = more ? '' : 'none';
                if (this.loadMoreBtn && !this.loading) {
                    this.loadMoreBtn.innerHTML = '<span>Load More</span> <i class="fa-solid fa-plus ms-2"></i>';
                }
            },
            onLoadMore() {
                if (this.loading) return;
                if (this.isMobile()) {
                    // need to render more cards before we can reveal the next 4?
                    if (this.shown + this.mobileStep > this.cards().length && this.offset < this.total) {
                        this.fetchMore(() => { this.shown += this.mobileStep; this.apply(); });
                    } else {
                        this.shown += this.mobileStep;
                        this.apply();
                    }
                } else if (this.offset < this.total) {
                    this.fetchMore(() => this.apply());
                }
            },
            fetchMore(done) {
                this.loading = true;
                this.loadMoreBtn.innerHTML = '<span>Loading...</span> <i class="fa-solid fa-spinner fa-spin ms-2"></i>';
                fetch('{{ route('front.load-more-categories') }}?offset=' + this.offset, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.html && data.count > 0) {
                            this.gridsWrap.insertAdjacentHTML('beforeend', data.html);
                            this.offset += data.count;
                        }
                    })
                    .catch(() => {})
                    .finally(() => { this.loading = false; done(); });
            }
        };
        articleSection.init();
    </script>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryDropdown = document.getElementById('categoryDropdown');
            const serviceDropdown = document.getElementById('serviceDropdown');

            const serviceRight = document.getElementById('serviceRight');
            const serviceName = document.getElementById('serviceName');
            const serviceOverview = document.getElementById('serviceOverview');
            const singleService = document.getElementById('singleService');
            const serviceCounter = document.getElementById('serviceCounter');
            const ctaText = singleService ? singleService.querySelector('.btn-text') : null;

            if (categoryDropdown && serviceDropdown) {
                // 1. Initial Reset (optional but recommended)
                // serviceDropdown.value = "";

                const catDisplay = document.querySelector('.category-name-display');

                // 2. Category (Department) Selection -> filter services + prompt in preview
                categoryDropdown.addEventListener('change', function () {
                    const opt = this.options[this.selectedIndex];
                    const selectedCategoryId = this.value;
                    const departmentName = (opt ? opt.text : '').trim() || 'Department';
                    const catUrl  = opt ? (opt.getAttribute('data-url') || '') : '';
                    const catDesc = opt ? (opt.getAttribute('data-desc') || '') : '';
                    const options = Array.from(serviceDropdown.options);

                    options.forEach(option => {
                        if (!option.value) { option.style.display = 'block'; return; }
                        option.style.display =
                            option.getAttribute('data-parent-id') === selectedCategoryId ? 'block' : 'none';
                    });

                    // Reset service selection when the department changes
                    serviceDropdown.value = "";

                    // Show the category overview + an "Explore Category" CTA to its page
                    if (catDisplay)      catDisplay.textContent = 'Department';
                    if (serviceName)     serviceName.textContent = departmentName;
                    if (serviceOverview) serviceOverview.textContent =
                        catDesc || ('Explore our ' + departmentName + ' services and find the right fit for your facility.');
                    if (serviceCounter)  serviceCounter.textContent = '—';
                    if (singleService) {
                        singleService.style.display = '';
                        if (catUrl) singleService.href = catUrl;
                        if (ctaText) ctaText.textContent = 'EXPLORE CATEGORY';
                    }
                });

                // 3. Service Selection -> Update preview card
                serviceDropdown.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (!selectedOption || !selectedOption.value) return;

                    const name     = selectedOption.getAttribute('data-name');
                    const slug     = selectedOption.getAttribute('data-slug');
                    const overview = selectedOption.getAttribute('data-overview');
                    const hero     = selectedOption.getAttribute('data-hero');
                    const image    = selectedOption.getAttribute('data-image');
                    const bg       = hero || image;   // prefer the service hero image
                    const categoryName = categoryDropdown.options[categoryDropdown.selectedIndex]?.text || "Healthcare Services";

                    if (serviceRight && bg) {
                        serviceRight.style.backgroundImage =
                            `linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('${bg}')`;
                    }

                    if (serviceName)     serviceName.textContent = name;
                    if (serviceOverview) serviceOverview.textContent = overview;
                    if (catDisplay)      catDisplay.textContent = categoryName;
                    if (serviceCounter) {
                        const pos = Array.from(this.options).filter(o => o.value).indexOf(selectedOption) + 1;
                        serviceCounter.textContent = String(pos).padStart(2, '0'); // sequential position in the list
                    }
                    if (singleService) {
                        singleService.style.display = '';
                        if (slug) singleService.href = `{{ url('/services') }}/${slug}`;
                        if (ctaText) ctaText.textContent = 'EXPLORE SERVICE';
                    }

                    // On mobile the preview sits well below the dropdown — bring it into
                    // view and flash it so the content change is obvious.
                    if (window.matchMedia('(max-width: 991px)').matches && serviceRight) {
                        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        serviceRight.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
                        const card = document.querySelector('.glass-details-card-pro');
                        if (card && !reduce) {
                            card.classList.remove('preview-flash');
                            void card.offsetWidth;   // force reflow to restart the animation
                            card.classList.add('preview-flash');
                            setTimeout(() => card.classList.remove('preview-flash'), 1300);
                        }
                    }
                });

                // --- NEW: QUICK LINKS SWIPER ---
                new Swiper(".quickLinksSwiper", {
                    slidesPerView: 1,
                    spaceBetween: 15, // Gap between cards like the image
                    loop: true,
                    navigation: {
                        nextEl: ".ql-next",
                        prevEl: ".ql-prev",
                    },
                    autoplay: {
                        delay: 6000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        300: { slidesPerView: 2 },
                        600: { slidesPerView: 3 },
                        900: { slidesPerView: 5 } // Matches your "5 column" reference image
                    }
                });
            }
        });
    </script>
@endpush