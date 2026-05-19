@extends('front/layout-2')

@push('page_title', 'Healthcare Consultancy in Dubai | Alpha Health Group')

@section('meta_description')Alpha Health Group — trusted healthcare consultancy in the UAE. We deliver DOH compliance, accreditation support, quality assurance, and operational excellence for hospitals and clinics.@endsection

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
                                <a href="{{ $slider->button_link ?? '#' }}" class="btn-hero-premium">
                                    <span>{{ $slider->button_text ?? 'Explore More' }}</span>
                                    <div class="btn-hero-icon"><i class="fa-solid fa-arrow-right"></i></div>
                                </a>
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

            </div>

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

    <style>


        .hero-video-modern {
            position: relative;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            background: #000;
        }



        .hero-bg-video,
        .hero-bg-images {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 1.2s ease, visibility 1.2s ease;
            z-index: 1;
        }

        .hero-bg-video {
            object-fit: cover;
        }

        .hero-bg-video.hero-bg-active,
        .hero-bg-images.hero-bg-active {
            opacity: 1;
            visibility: visible;
        }

        .hero-bg-image-item {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transform: scale(1.08);
            transition: opacity 1.5s ease-in-out, transform 8s ease;
        }

        .hero-bg-image-item.hero-img-active {
            opacity: 1;
            transform: scale(1);
        }

        .hero-video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, .05) 0%, rgba(0, 0, 0, .45) 55%, rgba(0, 0, 0, .9) 100%);
            z-index: 2;
            max-width:100%;
        }

        .hero-progress-track {
            width: 300px;
            height: 2px;
            margin-left:20px;
            background: rgba(255, 255, 255, .12);
            border-radius: 2px;
            margin-top: 10px;
            overflow: hidden;
        }

        .hero-progress-fill {
            height: 100%;
            width: 0%;
            background: #ff0101ff;
            border-radius: 2px;
            transition: width .1s linear;
        }

        .hero-texts-wrapper {
            position: relative;
            min-height: 220px;
            width:75%;
            left:20px;
        }

        .hero-slide-item {
            position: absolute;
            bottom: 0;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .6s ease, visibility .6s ease;
        }

        .hero-slide-item.slide-active {
            position: relative;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .hero-slide-item .hero-main-title {
            opacity: 0;
            transform: translateY(45px) scale(.93);
            transition: opacity 1s cubic-bezier(.34, 1.56, .64, 1), transform 1s cubic-bezier(.34, 1.56, .64, 1);
            width: 100%;
        }

        .hero-slide-item .light-text {
            opacity: 0;
            transform: translateY(25px) scale(.96);
            transition: opacity .9s cubic-bezier(.34, 1.56, .64, 1) .12s, transform .9s cubic-bezier(.34, 1.56, .64, 1) .12s;
        }

        .hero-slide-item .hero-cta-wrap {
            opacity: 0;
            transform: translateY(15px) scale(.9);
            transition: opacity .8s cubic-bezier(.34, 1.56, .64, 1) .25s, transform .8s cubic-bezier(.34, 1.56, .64, 1) .25s;
        }

        .hero-slide-item.slide-active .hero-main-title,
        .hero-slide-item.slide-active .hero-pre-title,
        .hero-slide-item.slide-active .hero-cta-wrap {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .hero-slide-item:not(.slide-active) .hero-main-title,
        .hero-slide-item:not(.slide-active) .hero-pre-title,
        .hero-slide-item:not(.slide-active) .hero-cta-wrap {
            opacity: 0;
            transform: translateY(-15px) scale(.98);
            transition: all .5s ease;
        }

        .hero-main-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.5rem, 6vw, 5.2rem);
            line-height: 1.1;
            font-weight: 700;
            color: #fff;
            margin-bottom: 40px;
            letter-spacing: -1.5px;
            max-width: 100%;
            text-shadow: 0 3px 35px rgba(0, 0, 0, .5), 0 1px 8px rgba(0, 0, 0, .4);
        }

        .hero-pre-title {
            display: block;
            font-family: 'Libre Baskerville', serif;
            font-weight: 400;
            font-size: 1.7rem;
            margin:10px 0 10px 0;
            color: rgba(255, 255, 255, 0.9);
            opacity: 0;
            transform: translateY(25px) scale(.96);
            transition: opacity .9s cubic-bezier(.34, 1.56, .64, 1) .12s, transform .9s cubic-bezier(.34, 1.56, .64, 1) .12s;
        }

        .slide-active .hero-pre-title {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .btn-hero-premium {
            display: inline-flex;
            align-items: center;
            gap: 18px;
            padding: 8px 8px 8px 32px;
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: 100px;
            color: #fff;
            text-decoration: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            background: rgba(255, 255, 255, .07);
            backdrop-filter: blur(12px);
            transition: all .4s ease;
            margin: 30px 0 30px 0;
        }

        .btn-hero-premium:hover {
            background: #fff;
            color: #000;
            border-color: #fff;
            transform: translateY(-4px);
        }

        .btn-hero-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .4s ease;
        }

        .btn-hero-premium:hover .btn-hero-icon {
            border-color: #000;
            color: #000;
            transform: rotate(-45deg);
        }

        .hero-mode-bar {
            position: absolute;
            bottom: 40px;
            right: 40px;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .08);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 60px;
            padding: 6px;
            z-index: 10;
        }

        .hero-mode-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, .55);
            padding: 10px 22px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 9px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: .88rem;
            cursor: pointer;
            transition: all .35s ease;
            white-space: nowrap;
        }

        .hero-mode-btn i {
            font-size: .95rem;
        }

        .hero-mode-btn.active-mode {
            background: #fff;
            color: #000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .25);
        }

        .hero-mode-btn:not(.active-mode):hover {
            color: #fff;
            background: rgba(255, 255, 255, .12);
        }

        .mode-bar-divider {
            width: 1px;
            height: 24px;
            background: rgba(255, 255, 255, .15);
            flex-shrink: 0;
        }
    </style>


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
                        <p class="services-subtitle-premium mb-5"> Explore our services directory to find the perfect match
                            your goals,tailored to your needs.
                            from experts in consulting and healthcare solutions. whether you're seeking a trusted healthcare
                            consultant or professional services.</p>

                        <div class="dropdown-selection-grid">
                            <!-- Step 1 -->
                            <div class="selection-step mb-4">
                                <div class="step-indicator">
                                    <span class="step-num">01</span>
                                    <span class="step-text">CATEGORY</span>
                                </div>
                                <div class="modern-dropdown-pro">
                                    <i class="fa-solid fa-layer-group select-prefix-icon"></i>
                                    <select id="categoryDropdown" class="pro-select has-prefix">
                                        <option value="" selected disabled>Choose industry category...</option>
                                        @foreach ($main_categories as $main_category)
                                            @foreach ($main_category->mergedCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    data-image="{{ asset('public/' . $category->image) }}">
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
                                    <span class="step-text">SPECIFIC SERVICE</span>
                                </div>
                                <div class="modern-dropdown-pro">
                                    <i class="fa-solid fa-hand-holding-heart select-prefix-icon"></i>
                                    <select name="service_name[]" id="serviceDropdown" class="pro-select has-prefix">
                                        <option value="" selected disabled>Select required service...</option>
                                        @foreach ($main_categories as $main_category)
                                            @foreach ($main_category->mergedCategories as $category)
                                                @foreach ($category->services as $service)
                                                    <option value="{{ $service->id }}" data-parent-id="{{ $category->id }}"
                                                        data-name="{{ $service->name }}" data-slug="{{ $service->slug }}"
                                                        data-overview="{{ strip_tags($service->overview) }}"
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
                                        <span class="category-name-display text-uppercase">Healthcare Services</span>
                                    </div>
                                    <h2 id="serviceName" class="service-display-title">Healthcare Facility Licensing</h2>
                                    <div class="divider-pro mb-4"></div>
                                    <p id="serviceOverview" class="service-description-pro">This service allows health
                                        facilities to obtain the necessary license to practice and provide health and
                                        treatment services in the UAE. Our experts guide you through every regulatory step
                                        with precision.
                                    </p>
                                    <a id="singleService" href="#" class="btn-professional-teal">
                                        <span class="btn-text">EXPLORE FULL SERVICE</span>
                                        <span class="btn-icon">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--  -->


    <!-- Recommended for You Section -->
    <section class="recommended-section py-5 mt-5">    <!--##OLD CODE## <section class="recommended-section container py-5 mt-5">-->
        <div class="mb-5">
            <h6 class="text-uppercase tracking-wider text-teal">Recommended for You</h6>
            <h2 class="display-5 winky-sans font-weight-700">Our Latest Thinking <span class="text-teal-gradient">Based on
                    Your Interests</span></h2>
            <div class="header-line"></div>
        </div>

        <div class="parent">
            @foreach ($categories_carts as $index => $category)
                @php
                    $gridClass = isset($gridClasses[$index]) ? $gridClasses[$index] : 'div' . ($index + 1);
                @endphp

                <div class="article-card {{ $gridClass }}"
                    style="background-image: url('{{ asset('public/' . $category->image) }}')" data-aos="fade-up"
                    data-aos-delay="{{ $index * 50 }}">
                    <div class="article-content">
                        <span class="article-category-tag">INSIGHT</span>
                        <h4>{{ $category->name }}</h4>
                        <p>{{ Str::limit(strip_tags($category->description), 100) }}</p>
                        <a href="{{ route('front.service-category', $category->slug) }}" class="btn-premium-read-more" aria-label="Read more about {{ $category->name }}">
                            Read More <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <button class="view-more-button-outline" id="loadMoreArticles" data-aos="fade-up" data-aos-delay="100">
                <span>Load More Thinking</span>
                <i class="fa-solid fa-plus ms-2"></i>
            </button>
        </div>
    </section>

    <style>
        /* =========================
                                                                           SPLIT HERO MODERN STYLES
                                                                           ========================= */
        /* --- Enhanced Video Hero Styles --- */
        .hero-video-modern {
            height: 100vh;
            width: 100%;
            position: relative;
            overflow: hidden;
            background: #066D77;
            display: flex;
            align-items: flex-end;
            /* Align to bottom as per image */
            padding-bottom: 120px;
        }

        .hero-bg-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
            z-index: 1;
        }

        .hero-video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0.9) 100%);
            z-index: 2;
        }

        .hero-video-content-box {
            position: relative;
            z-index: 3;
            width: 100%;
            max-width: 1300px;
            margin: 0 auto;
            padding-left: 0;
        }

        .hero-slider-item {
            display: none;
            opacity: 0;
            position: relative;
        }

        .hero-slider-item.active {
            display: block;
            opacity: 1;
        }

        /* Staggered Element Animations */
        .hero-slider-item .hero-main-title {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.215, 0.61, 0.355, 1);
        }

        .hero-slider-item .light-text {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.215, 0.61, 0.355, 1) 0.2s;
        }

        .hero-slider-item .hero-action-area {
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.8s cubic-bezier(0.215, 0.61, 0.355, 1) 0.4s;
        }

        /* Active State for Elements */
        .hero-slider-item.active .hero-main-title,
        .hero-slider-item.active .light-text,
        .hero-slider-item.active .hero-action-area {
            opacity: 1;
            transform: translateY(0);
        }

        /* Slide Progress Tracker (Optional Premium Detail) */
        .hero-progress-container {
            position: absolute;
            bottom: 80px;
            left: 0;
            width: 100%;
            max-width: 300px;
            height: 2px;
            background: #066D77;
            z-index: 10;
        }

        .hero-progress-bar {
            height: 100%;
            width: 0%;
            background: #ffffff;
            transition: width 0.1s linear;
        }

        .hero-main-title {
            /* font-family: 'Outfit', sans-serif;
            font-size: clamp(2.8rem, 7vw, 6rem);
            line-height: 1.05;
            font-weight: 700;
            margin-bottom: 50px;
            letter-spacing: -2px;
            color: #ffffff; */
        }

        .hero-main-title .light-text {
            font-weight: 300;
            display: block;
            opacity: 0.9;
        }

        .btn-play-video-premium {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            padding: 6px 6px 6px 35px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 100px;
            color: #ffffff;
            text-decoration: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            transition: all 0.4s ease;
        }

        .btn-play-video-premium:hover {
            background: #ffffff;
            color: #000000;
            border-color: #ffffff;
            transform: translateY(-5px);
        }

        .play-icon-wrap {
            width: 50px;
            height: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
        }

        .btn-play-video-premium:hover .play-icon-wrap {
            border-color: #000000;
            color: #000000;
            transform: rotate(-45deg);


        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1.2s ease-in-out,
        transform 8s ease;
        transform: scale(1.08);
        /* Gentle zoom */
        z-index: 1;
        /* Removed filters for perfect clarity */
        }

        /* Subtle Vignette only - No heavy colors */
        .slider-image-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 70%, rgba(0, 0, 0, 0.2));
            pointer-events: none;
        }

        .slider-image-item.active {
            opacity: 1;
            transform: scale(1);
            z-index: 2;
        }

        /* --- Center Badge --- */
        .center-badge {
            position: absolute;
            top: 15%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            width: 120px;
            height: 120px;
            background-color: #ffffff;
            /* Keep white */
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .badge-inner {
            text-align: center;
            color: #0c2f2f;
        }

        .badge-text {
            display: block;
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.8rem;
            line-height: 1;
        }

        .badge-sub {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 0.6rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 700;
            margin-top: 5px;
            color: #1ea7a1;
            /* Teal Accent */
        }

        /* Responsive */
        @media (max-width: 991px) {
            .hero-split-modern {
                height: auto;
            }

            .split-left-panel {
                padding: 80px 20px;
                min-height: 50vh;
            }

            .main-title {
                font-size: 3rem;
            }

            .left-panel-decor {
                left: 20px;
            }

            .split-right-panel {
                height: 50vh;
            }
            .image-column-pro{
                max-height: 70%;
            }
            .hero-slide-item.slide-active{
                left: 0;
            }
            .hero-progress-track{
                margin-left:0;
            }
            .hero-mode-bar{
                bottom:-50px;
                right:20px;
            }
        }

        .panel-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.3) 100%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .card-panel.active .panel-overlay {
            opacity: 1;
        }

        .card-panel:not(.active) {
            filter: grayscale(100%);
            /* Optional: grayscale inactive panels */
            opacity: 0.7;
        }

        .card-panel:not(.active):hover {
            filter: grayscale(0%);
            opacity: 1;
        }

        /* Remove Dot Styles as they are no longer needed */

        /* Responsive Design */
        @media (max-width: 991px) {
            .hero-auto-slider {
                padding: 80px 0 60px;
            }

            #hero-title {
                font-size: 2.8rem;
            }

            .slide-image {
                height: 350px;
            }

            .hero-left-content,
            .hero-image-slider {
                margin-bottom: 40px;
            }
        }

        @media (max-width: 576px) {
            #hero-title {
                font-size: 2.2rem;
            }

            .slide-image {
                height: 300px;
            }

            .content-category {
                font-size: 0.7rem;
                padding: 6px 12px;
            }

            .btn-explore {
                padding: 12px 25px;
                font-size: 0.85rem;
            }
        }
    </style>




    <style>
        .services-selection-modern {
            background: #ffffffff;
            color: #0c2f2f;
            position: relative;
            overflow: hidden;
            padding: 80px 0;
        }

        .background-mesh {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(at 0% 0%, rgba(30, 167, 161, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(0, 120, 123, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 167, 161, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(0, 120, 123, 0.03) 0px, transparent 50%);
            z-index: 0;
        }

        .background-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(30, 167, 161, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 167, 161, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            mask-image: radial-gradient(circle at center, black, transparent 80%);
        }

        /* Decorative Elements */
        .decor-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.4;
        }

        .decor-1 {
            width: 400px;
            height: 400px;
            background: rgba(211, 211, 211, 0.2);
            top: -100px;
            left: -100px;
        }

        .decor-2 {
            width: 300px;
            height: 300px;
            background: rgba(0, 120, 123, 0.08);
            bottom: -50px;
            right: 40%;
        }

        .decor-dots {
            position: absolute;
            top: 40px;
            right: 40px;
            width: 100px;
            height: 100px;
            background-image: radial-gradient(#066D77 1px, transparent 1px);
            background-size: 15px 15px;
            opacity: 0.2;
            z-index: 0;
        }

        .content-wrapper-professional {
            position: relative;
            z-index: 2;
            max-width: 600px;
            margin: 0 auto;
        }

        .professional-badge {
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 5px;
            color: #1ea7a1;
            display: inline-block;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .tag-line {
            width: 40px;
            height: 2px;
            background: #1ea7a1;
            margin-top: 5px;
        }

        .services-title-premium {
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            color: #0c2f2f;
            margin-bottom: 25px;
        }

        .text-gradient-green {
            background: linear-gradient(135deg, #1ea7a1 0%, #00787b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .services-subtitle-premium {
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            line-height: 1.7;
            color: #5f6f73;
            max-width: 520px;
        }

        /* Selection Steps */
        .selection-step {
            position: relative;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
        }

        .step-num {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 0.9rem;
            color: #1ea7a1;
            background: rgba(30, 167, 161, 0.1);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .step-text {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #5f6f73;
            text-transform: uppercase;
        }

        .modern-dropdown-pro {
            position: relative;
            width: 100%;
        }

        .pro-select {
            width: 100%;
            background: #ffffff;
            border: 2px solid #edf2f7;
            border-radius: 20px;
            padding: 20px 25px;
            color: #0c2f2f;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 500;
            appearance: none;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.01);
        }

        .pro-select.has-prefix {
            padding-left: 55px;
        }

        .select-prefix-icon {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: #1ea7a1;
            font-size: 1.1rem;
            z-index: 3;
            pointer-events: none;
            opacity: 0.8;
        }

        .pro-select:invalid,
        .pro-select option[value=""] {
            color: #94a3b8;
        }

        .pro-select:hover {
            border-color: #1ea7a1;
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(30, 167, 161, 0.08);
        }

        .pro-select:focus {
            outline: none;
            border-color: #1ea7a1;
            box-shadow:
                0 0 0 4px rgba(30, 167, 161, 0.1),
                0 15px 35px rgba(30, 167, 161, 0.1);
            transform: translateY(-4px);
            background: #ffffff;
        }

        .select-icon {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #1ea7a1;
            font-size: 0.85rem;
            pointer-events: none;
            transition: transform 0.3s ease;
        }

        .modern-dropdown-pro:focus-within .select-icon {
            transform: translateY(-50%) rotate(180deg);
        }

        /* Portfolio Right Column Grid Layout */
        .image-column-pro {
            position: relative;
            background: #dadadaad;
            min-height: 50vh;
            display: flex;
            align-items: stretch;
            padding: 10px;
            border-radius: 40px;
        }

        .services-premium-grid {
            display: grid;
            /* grid-template-columns: repeat(5, 1fr); */
            /* grid-template-rows: repeat(5, 1fr); */
            gap: 20px;
            width: 100%;
            height: 100%;
        }

        .grid-div-image {
            grid-column: 1 / 5;
            grid-row: 1 / 6;
            position: relative;
            border-radius: 35px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        /* NEW: Digital Shield Effect */
        .digital-shield {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(30, 167, 161, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 167, 161, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
            mask-image: linear-gradient(transparent, black, transparent);
            opacity: 0.3;
            z-index: 1;
            pointer-events: none;
        }

        .digital-shield::after {
            content: '';
            position: absolute;
            top: -100%;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(30, 167, 161, 0.2), transparent);
            animation: scanLine 8s linear infinite;
        }

        @keyframes scanLine {
            0% {
                top: -100%;
            }

            100% {
                top: 100%;
            }
        }

        /* NEW: Premium Navigator */
        .premium-navigator-vertical {
            position: absolute;
            left: -40px;
            top: 50%;
            transform: translateY(-50%) rotate(-90deg);
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 20;
        }

        .nav-label {
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 4px;
            color: #94a3b8;
            white-space: nowrap;
        }

        .nav-line {
            width: 60px;
            height: 1px;
            background: #1ea7a1;
        }

        .nav-number {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            color: #1ea7a1;
            transform: rotate(90deg);
            display: block;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* NEW: Floating Status Badge */
        .status-badge-floating {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(12, 47, 47, 0.85);
            backdrop-filter: blur(10px);
            padding: 10px 20px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(30, 167, 161, 0.3);
            z-index: 15;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.5s ease;
        }

        .grid-div-image:hover .status-badge-floating {
            transform: translateY(-5px);
            background: #015353;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #1ea7a1;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(30, 167, 161, 0.4);
            animation: statusPulse 2s infinite;
        }

        @keyframes statusPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(30, 167, 161, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(30, 167, 161, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(30, 167, 161, 0);
            }
        }

        .status-text {
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* NEW: Image Reveal Info */
        .image-info-reveal {
            position: absolute;
            bottom: 30px;
            left: 30px;
            z-index: 15;
            transition: all 0.5s ease;
        }

        .reveal-tag {
            display: block;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 3px;
            color: #1ea7a1;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .reveal-desc {
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 500;
            margin: 0;
        }

        .grid-div-content {
            grid-column: 3 / 6;
            grid-row: 2 / 5;
            z-index: 25;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            /* Let clicks pass to the card */
        }

        .grid-div-content>* {
            pointer-events: auto;
        }

        .shadow-premium {
            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.15),
                inset 0 0 120px rgba(0, 0, 0, 0.3);
        }

        /* Precision Brackets */
        .frame-bracket {
            position: absolute;
            width: 30px;
            height: 30px;
            border-color: rgba(255, 255, 255, 0.6);
            border-style: solid;
            z-index: 5;
            pointer-events: none;
            transition: all 0.5s ease;
        }

        .bracket-tl {
            top: 30px;
            left: 30px;
            border-width: 2px 0 0 2px;
        }

        .bracket-tr {
            top: 30px;
            right: 30px;
            border-width: 2px 2px 0 0;
        }

        .bracket-bl {
            bottom: 30px;
            left: 30px;
            border-width: 0 0 2px 2px;
        }

        .bracket-br {
            bottom: 30px;
            right: 30px;
            border-width: 0 2px 2px 0;
        }

        .grid-div-image:hover .frame-bracket {
            width: 45px;
            height: 45px;
            border-color: #1ea7a1;
        }

        .premium-border-frame {
            position: absolute;
            inset: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            z-index: 4;
            pointer-events: none;
            animation: borderPulse 4s ease-in-out infinite;
        }

        @keyframes borderPulse {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.01);
            }
        }

        .premium-glass-card-wrapper {
            padding: 40px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .glass-details-card-pro {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 40px;
            padding: 65px;
            width: 100%;
            max-width: 620px;
            position: relative;
            z-index: 2;
            box-shadow:
                0 40px 100px rgba(0, 0, 0, 0.1),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            color: #0c2f2f;
            display: flex;
            flex-direction: column;
            align-items: start;
            text-align: start;
            overflow: hidden;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-details-card-pro:hover {
            transform: translateY(-5px);
        }

        .glass-details-card-pro::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: translateX(-100%);
            transition: transform 0.8s ease;
            pointer-events: none;
        }

        .glass-details-card-pro:hover::after {
            transform: translateX(100%);
        }

        .card-glow {
            position: absolute;
            top: -20%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .service-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .meta-dot {
            width: 8px;
            height: 8px;
            background: #1ea7a1;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(30, 167, 161, 0.4);
        }

        .category-name-display {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #1ea7a1;
        }

        .service-display-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #015353;
        }

        .divider-pro {
            width: 60px;
            height: 4px;
            background: #1ea7a1;
            border-radius: 2px;

        }

        .service-description-pro {
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #334155;
            margin-bottom: 40px;
            font-weight: 450;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            letter-spacing: -0.01em;
        }

        .btn-professional-teal {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            text-decoration: none !important;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            width: fit-content;
        }

        .btn-text {
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #0c2f2f;
            text-transform: uppercase;
        }

        .btn-icon {
            width: 50px;
            height: 50px;
            background: #1ea7a1;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: 0 10px 20px rgba(30, 167, 161, 0.2);
        }

        .btn-professional-teal:hover .btn-icon {
            background: #1ea7a1;
            color: #ffffff;
            transform: scale(1.1) rotate(-45deg);
        }

        .btn-professional-teal:hover .btn-text {
            color: #1ea7a1;
            letter-spacing: 2.5px;
        }

        @keyframes cardFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @media (max-width: 991px) {
            .services-title-premium {
                font-size: 2.5rem;
            }

            .glass-details-card-pro {
                padding: 40px;
            }

            .service-display-title {
                font-size: 2rem;
            }
           .grid-div-image {
                grid-column: 1 / 5 !important;
                grid-row: 1 / 6 !important;
            }

            .grid-div-content {
                grid-column: 1 / 5 !important;
                grid-row: 1 / 6 !important;
                padding: 20px;
            }
        }



        /* Grid Layout for Recommended Section */




        .parent {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(6, 140px);
            gap: 5px;
            min-height: 700px;
        }

        .div1 {
            grid-column: span 2 / span 2;
            grid-row: span 2 / span 2;
        }

        .div2 {
            grid-row: span 2 / span 2;
            grid-column-start: 3;
        }

        .div3 {
            grid-row: span 4 / span 4;
            grid-column-start: 4;
        }

        .div4 {
            grid-row: span 4 / span 4;
            grid-row-start: 3;
        }

        .div5 {
            grid-row: span 2 / span 2;
            grid-row-start: 3;
        }

        .div6 {
            grid-row: span 2 / span 2;
            grid-row-start: 3;
        }

        .div7 {
            grid-row: span 2 / span 2;
            grid-column-start: 4;
            grid-row-start: 5;
        }

        .div8 {
            grid-column: span 2 / span 2;
            grid-row: span 2 / span 2;
            grid-column-start: 2;
            grid-row-start: 5;
        }

        @media (max-width: 992px) {
            .parent {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .div1,
            .div2,
            .div3,
            .div4,
            .div5,
            .div6,
            .div7,
            .div8 {
                grid-area: auto !important;
                min-height: 250px !important;
            }
        }

        @media (max-width: 576px) {
            .parent {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                min-height: auto;
            }

            .recommended-section {
                width: 100% !important;
                margin-left: 0 !important;
                padding: 50px 20px !important;
            }
        }

        .recommended-section {
            padding-top: 100px !important;
            padding-bottom: 100px !important;
            border-top: 1px solid rgba(0, 120, 123, 0.05);
            width: 60%;   /*If ant have change delete width and margin-left */
            margin-left:335px;
        }

        /* Premium Section Heading Styles */
        .text-teal {
            color: #1ea7a1 !important;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .text-teal-gradient {
            background: linear-gradient(135deg, #066D77 0%, #00787b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-line {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #1ea7a1, transparent);
            margin-top: 15px;
            border-radius: 2px;
        }

        /* Parent container hover for Focus Effect (Removed aggressive blur/scale for professionalism) */
        .parent:hover .article-card:not(:hover) {
            opacity: 0.95;
        }

        /* Premium Article Card Styles */
        .article-card {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            /* Slightly more rounded for modern feel */
            background-color: #f8fafc;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            /* Softer initial shadow */
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .article-card:hover {
            transform: translateY(-8px);
            /* Subtle lift */
            box-shadow: 0 20px 40px rgba(12, 47, 47, 0.15);
            /* Professional deep shadow */
            z-index: 10;
        }

        /* Background Image Zoom Effect */
        .article-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: inherit;
            background-size: cover;
            background-position: center;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }

        .article-card:hover::before {
            transform: scale(1.1);
        }

        .article-content {
            position: relative;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg,
                    rgba(12, 47, 47, 0) 0%,
                    rgba(12, 47, 47, 0.4) 40%,
                    rgba(12, 47, 47, 0.85) 100%);
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            transition: all 0.5s ease;
            z-index: 2;
        }

        .article-category-tag {
            position: absolute;
            top: 25px;
            right: 25px;
            background: rgba(255, 255, 255, 0.95);
            color: #015353;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            backdrop-filter: blur(8px);
            opacity: 0;
            transform: translateY(-5px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 5;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .article-card:hover .article-category-tag {
            opacity: 1;
            transform: translateY(0);
        }

        .article-card:hover .article-content {
            background: linear-gradient(180deg,
                    rgba(12, 47, 47, 0) 0%,
                    rgba(12, 47, 47, 0.6) 30%,
                    rgba(12, 47, 47, 0.95) 100%);
        }

        .article-content h4 {
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0px;
            line-height: 1.3;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .article-card:hover .article-content h4 {
            color: #1ea7a1;
        }

        .article-content p {
            color: rgba(255, 255, 255, 0.85);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 0px;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transform: translateY(10px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .article-card:hover .article-content p {
            opacity: 1;
            max-height: 100px;
            margin-top: 15px;
            margin-bottom: 20px;
            transform: translateY(0);
        }

        .btn-premium-read-more {
            display: inline-flex;
            align-items: center;
            padding: 10px 24px;
            background: #ffffff;
            color: #015353 !important;
            border-radius: 50px;
            text-decoration: none !important;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 1px;
            width: fit-content;
            margin-bottom: 0px;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transform: translateY(15px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .article-card:hover .btn-premium-read-more {
            max-height: 50px;
            opacity: 1;
            transform: translateY(0);
            margin-top: 10px;
        }

        .btn-premium-read-more i {
            transition: transform 0.3s ease;
            margin-left: 8px;
        }

        .btn-premium-read-more:hover i {
            transform: translateX(5px);
        }

        .btn-premium-read-more:hover {
            background: #1ea7a1;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 167, 161, 0.2);
        }

        .card-glare {
            position: absolute;
            inset: 0;
            pointer-events: none;
            mix-blend-mode: overlay;
            opacity: 0;
            z-index: 3;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2) 0%, transparent 80%);
        }

        .view-more-button-outline {
            background: white;
            border: 2px solid #066D77;
            color: #066D77;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .view-more-button-outline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, #1ea7a1, #00787b);
            transition: all 0.5s ease;
            z-index: -1;
        }

        .view-more-button-outline:hover {
            color: #fff;
            box-shadow: 0 15px 35px rgba(30, 167, 161, 0.4);
            border-color: transparent;
        }

        .view-more-button-outline:hover::before {
            width: 100%;
        }

        .article-card.is-hidden {
            display: none !important;
        }

        /* Professional Blog Card Enhancements */
        .big-card,
        .small-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .big-card:hover,
        .small-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(12, 47, 47, 0.12);
        }

        .big-card::before,
        .bottom-right.small-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: inherit;
            background-size: cover;
            background-position: center;
            transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }

        .big-card:hover::before,
        .bottom-right.small-card:hover::before {
            transform: scale(1.08);
        }

        .big-card>div,
        .small-card>div {
            position: relative;
            z-index: 1;
        }

        .btn-custom {
            background: #066D77 !important;
            border-radius: 50px !important;
            padding: 12px 25px !important;
            font-size: 0.9rem !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(30, 167, 161, 0.2);
            border: none;
            width: fit-content !important;
            margin-right: auto !important;
        }

        .btn-custom:hover {
            background: #015353 !important;
            color: #fff !important;
            transform: translateX(5px);
            box-shadow: 0 6px 15px rgba(1, 83, 83, 0.3);
        }




        /* =========================
                                                                                                                                                                                                                                                                                                               SECTION BACKGROUND
                                                                                                                                                                                                                                                                                                               ========================= */
        .about-modern {
            background: linear-gradient(180deg, #f9fbfc 0%, #ffffff 100%);
            padding: 110px 0;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               CARD
                                                                                                                                                                                                                                                                                                               ========================= */
        .about-card {
            width: 100%;
            max-width: 100%;
            margin: 0;
            background: #ffffff;
            padding: 100px 8%;
            position: relative;
            border-radius: 0;
            box-shadow: none;
            overflow: hidden;
            border-top: 1px solid rgba(0, 120, 123, 0.05);
            border-bottom: 1px solid rgba(0, 120, 123, 0.05);
        }

        /* GRADIENT ACCENT STRIP */
        .about-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(135deg, #1ea7a1, #00787b);
            z-index: 1;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               COLUMNS
                                                                                                                                                                                                                                                                                                               ========================= */
        .image-col,
        .text-col {
            position: relative;
            z-index: 2;
        }

        .image-col {
            display: flex;
            justify-content: center;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               IMAGE STACK
                                                                                                                                                                                                                                                                                                               ========================= */
        .image-stack {
            position: relative;
            width: 100%;
            max-width: 550px;
        }

        /* DECORATIVE GRADIENT SHAPES */
        .shape {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 20px;
            z-index: 0;
        }

        .shape.orange {
            background: linear-gradient(135deg, #1ea7a1, #00787b);
            top: -18px;
            left: 18px;
            opacity: 0.85;
        }

        .shape.blue {
            background: linear-gradient(135deg, #e6f7f7, #ffffff);
            bottom: -22px;
            left: -22px;
        }

        /* IMAGE BOX */
        .image-box {
            position: relative;
            z-index: 2;
            padding: 16px;
            background: #ffffff;
            border-radius: 22px;
            box-shadow:
                0 25px 60px rgba(0, 120, 123, 0.35),
                inset 0 0 0 2px rgba(30, 167, 161, 0.2);
        }

        /* IMAGE */
        .right-image {
            width: 100%;
            display: block;
            border-radius: 16px;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               CONTENT
                                                                                                                                                                                                                                                                                                               ========================= */
        .about-tag {
            display: inline-block;
            background: linear-gradient(135deg, #1ea7a1, #00787b);
            color: #ffffff;
            padding: 7px 18px;
            font-size: 13px;
            letter-spacing: 1px;
            border-radius: 20px;
            margin-bottom: 22px;
        }

        .text-col h2 {
            font-size: 42px;
            font-weight: 800;
            color: #0c2f2f;
            margin-bottom: 22px;
        }

        .description {
            font-size: 21px;
            line-height: 1.75;
            color: #5f6f73;
            max-width: 520px;
            margin-bottom: 38px;
            font-family: 'Roboto', sans-serif;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               BUTTONS
                                                                                                                                                                                                                                                                                                               ========================= */
        .btn-group-custom {
            display: flex;
            gap: 20px;
        }

        /* Outline Button */
        .btn-white {
            padding: 14px 36px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.6px;
            border: 2px solid #1ea7a1;
            color: #1ea7a1;
            background: transparent;
            transition: all 0.35s ease;
        }

        .btn-white:hover {
            background: linear-gradient(135deg, #1ea7a1, #00787b);
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(30, 167, 161, 0.35);
        }

        /* Primary Button */
        .btn-custom-2 {
            padding: 24px 36px;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.6px;
            color: #ffffff;
            background: linear-gradient(135deg, #1ea7a1, #00787b);
            border: none;
            box-shadow: 0 18px 40px rgba(0, 120, 123, 0.45);
            transition: all 0.35s ease;
        }

        .btn-custom-2:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 25px 55px rgba(0, 120, 123, 0.6);
            background: #000;
            color: #fff;
        }

        /* Interactive Shape Parallax hint */
        .shape {
            transition: transform 0.3s ease-out;
        }

        .about-card:hover .shape.orange {
            transform: translate(15px, -15px);
        }

        .about-card:hover .shape.blue {
            transform: translate(-15px, 15px);
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               ANIMATION
                                                                                                                                                                                                                                                                                                               ========================= */
        .image-stack,
        .text-col {
            opacity: 0;
            transform: translateY(40px);
            animation: fadeUp 1s ease forwards;
        }

        .text-col {
            animation-delay: 0.2s;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               RESPONSIVE
                                                                                                                                                                                                                                                                                                               ========================= */
        @media (max-width: 1200px) {
            .about-card {
                padding: 80px 5%;
            }
        }

        @media (max-width: 991px) {
            .about-card {
                padding: 60px 20px;
            }

            .image-stack {
                margin-bottom: 55px;
                max-width: 100%;
            }

            .btn-group-custom {
                flex-direction: column;
                align-items: flex-start;
            }

            .text-col h2 {
                font-size: 34px;
            }
        }

        /* =========================
                                           LOGO SLIDER (MARQUEE)
                                           ========================= */
        .logo-slider-wrapper {
            overflow: hidden;
            padding: 40px 0;
            position: relative;
            width: 134%;
            margin-left:-221px; /* Negative margin to offset container padding and center slider */
            margin-right:-221px;
            /* margin: 50px 0; */
        }

        .logo-slider {
            display: flex;
            width: calc(250px * 12);
            /* Based on double logos for loop */
            animation: marquee 30s linear infinite;
        }

        .logo-slide {
            width: 250px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            padding: 0 40px;
        }

        .logo-slide img {
            width: 140px;

            opacity: 1.5;
            transition: all 0.4s ease;
        }

        .logo-slide img:hover {
            filter: grayscale(0);
            opacity: 1;
            transform: scale(1.1);
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(calc(-250px * 6));
            }

            /* Move by half the count */
        }
    </style>


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

    @include('front.clients')
    <!-- Careers/About Section -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Playfair+Display:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ey-yellow: #ffe600;
            --ey-black: #1a1a1a;
            --ey-dark-grey: #2e2e2e;
            --ey-teal: #066D77;
            --ey-light-teal: #e6f7f8;
            --ey-white: #ffffff;
            --ey-footer-bg: linear-gradient(180deg, #004d50 0%, #002d2f 100%);
            --ey-font: 'Inter', sans-serif;
        }

        .container {
            /* max-width: 1200px; */
            margin: 0 auto;
            padding: 0 20px;
        }

        .strategy-section {
            padding: 130px 0;
            background: #fdfdfd;
        }

        .strategy-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: stretch;
            height: 800px;
            /* margin-left:-221px;
            width: 132%; */
            /*Extra width to accommodate slider overflow */
            /* Constrain grid height for vertical slider */
        }

        .image-grid-parent {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 12px;
            height: 100%;
            margin-top: 40px;
            align-self: flex-start;
        }

        .grid-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: #eee;
        }

        .grid-card img {
            width: 100%;
            height: 90%;
            object-fit: cover;
            display: block;
            transition: transform 0.2s ease;
        }

        .grid-card:hover img {
            transform: scale(1.05);
        }

        .grid-exterior {
            grid-column: span 2 / span 2;
            grid-row: span 2 / span 2;
        }

        .grid-portrait {
            grid-column: span 3 / span 3;
            grid-row: span 2 / span 2;
        }

        .grid-lobby {
            grid-column: span 3 / span 3;
            grid-row: span 2 / span 2;
            grid-row-start: 3;
        }

        .grid-slider-card {
            grid-column: span 2 / span 2;
            grid-row: span 2 / span 2;
            grid-row-start: 3;
        }

        /* Slider Styles for div4 */
        .slider-container {
            position: relative;
        }

        .slider-track {
            display: flex;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .slide {
            min-width: 100%;
            height: 100%;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slider-controls {
            position: absolute;
            bottom: 15px;
            right: 15px;
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .slider-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: var(--ey-teal);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .slider-btn:hover {
            background: var(--ey-teal);
            color: white;
            transform: translateY(-2px);
        }

        .strategy-cards {
            position: relative;
            height: 100%;
            overflow: hidden;
            /* Fade effect at top and bottom */
            mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent);
        }

        .strategy-track {
            display: flex;
            flex-direction: column;
            animation: slideInfinite 75s linear infinite;
        }

        .strategy-track:hover {
            animation-play-state: paused;
        }

        @keyframes slideInfinite {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-50%);
            }
        }

        .strat-card {
            background: white;
            border: 1px solid #f0f0f0;
            padding: 45px;
            border-radius: 15px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.04);
            border-left: 10px solid var(--ey-teal);
            margin-bottom: 25px;
            /* Spacing handled by margin for marquee logic */
            flex-shrink: 0;
        }

        @media(max-width: 991px) {
            .strategy-section {
                padding: 80px 0;
            }
            .strategy-grid {
                grid-template-columns: 1fr;
                gap: 10px;
                height: auto;
                width: 100%;
                margin-left: 0;
            }
             .image-grid-parent {
                /* grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(4, 200px); */
                /* gap: 12px; */
                height: auto;
                margin-top: 0;
             }
             .logo-slider-wrapper{
                width: 100%;
                margin-left: 0;
             }
             .logo-slider {
                width: calc(250px * 12);
                animation: marquee 30s linear infinite;
             }
              .strategy-cards {
                height: auto;
             }
              .strategy-track {
                animation: none;
             }
        }

        .strat-card h3 {
            color: var(--ey-teal);
            font-size: 1.5rem;
            margin-bottom: 12px;
            font-weight: 800;
        }

        .strat-card p {
            color: #555;
            font-size: 1.05rem;
        }
    </style>



    <section class="strategy-section">
        <div class="container">
            <div class="strategy-grid">
                <div class="image-grid-parent">
                    <div class="grid-card grid-exterior">
                        <img src="https://www.mua.edu/uploads/sites/10/2023/02/istock-482499394.webp?w=1536"
                            alt="Hospital Exterior">
                    </div>
                    <div class="grid-card grid-portrait">
                        <img src="https://investin.org/cdn/shop/articles/jafar-ahmed-E285pJbC4uE-unsplash.jpg?v=1634293259"
                            alt="Doctor Portrait">
                    </div>
                    <div class="grid-card grid-lobby">
                        <img
                            src="https://investin.org/cdn/shop/articles/jafar-ahmed-E285pJbC4uE-unsplash.jpg?v=1634293259">
                    </div>
                    <div class="grid-card grid-slider-card slider-container" id="grid-slider">
                        <div class="slider-track">
                            <div class="slide">
                                <img src="https://media-cldnry.s-nbcnews.com/image/upload/t_fit-560w,f_auto,q_auto:best/rockcms/2024-05/240513-women-surgeons-se-1215p-de7fda.jpg"
                                    alt="Site Plan">
                            </div>
                            <div class="slide">
                                <img src="https://www.onlanka.com/wp-content/uploads/2023/04/health-service-doctor.jpg"
                                    alt="Medical Technology">
                            </div>
                            <div class="slide">
                                <img src="https://people.com/thmb/pL4SWz870QR9h-uqQBBoejqPbbw=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc():focal(576x467:578x469)/female-doctor-stock-2-042324-a7bc2e3ef76b4512b6792338539a9b03.jpg"
                                    alt="Architectural Sketch">
                            </div>
                        </div>
                        <div class="slider-controls">
                            <button class="slider-btn prev-btn">‹</button>
                            <button class="slider-btn next-btn">›</button>
                        </div>
                    </div>
                </div>
                <div class="strategy-cards">
                    <div class="strategy-track">
                        <div class="strat-card">
                            <h3>Strategic Foundation</h3>
                            <p>Defining the core principles that drive healthcare excellence and facility efficiency. We
                                establish a robust roadmap for spatial optimization and operational resilience.</p>
                        </div>
                        <div class="strat-card">
                            <h3>Strategic Approach</h3>
                            <p>Tailored methodologies to optimize clinical workflows and patient experience. Our approach
                                integrates evidence-based design with emerging medical trends.</p>
                        </div>
                        <div class="strat-card">
                            <h3>Future Ready Design</h3>
                            <p>Transforming vision into structured reality. We develop scalable facility master plans that
                                adapt to changing community needs and technological advancements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- CASE STUDIES & RELATED CONTENT -->
    <section class="case-related-modern py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Case Studies Column -->
                <div class="col-lg-7">
                    <h2 class="section-heading-minimal mb-4">Case Studies</h2>
                    <div class="row g-4">
                        @if(isset($projects) && $projects->count() > 0)
                            @foreach($projects as $project)
                                <div class="col-md-6">
                                    <div class="case-study-card">
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
                                             <a href="{{ route('front.project_details', $project->slug) }}" class="text-dark fw-bold text-decoration-none">
                                VIEW CASE STUDY <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Recent Blog Column -->
                <div class="col-lg-5">
                    <h2 class="section-heading-minimal mb-4">Recent Blog</h2>
                    @php
                        $featuredBlog = $blogs->first();
                    @endphp
                    @if($featuredBlog)
                        <div class="related-content-card">
                            <div class="related-media-wrap">
                                <img src="{{ asset('public/uploads/blog_images/' . $featuredBlog->image) }}" alt="Blog Image">
                                <div class="related-content-overlay">
                                    <h3 class="related-overlay-title">Client</h3>
                                    <p class="related-overlay-text">
                                        {{ Str::limit(strip_tags($featuredBlog->description), 150) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <style>
        :root {
            --ey-teal: #066D77;
        }

        .section-heading-minimal {
            font-size: 2.8rem;
            font-weight: 800;
            color: #1a1a1a;
            font-family: 'Libre Baskerville', sans-serif;
        }

        /* Case Study Card Styles */
        .case-study-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
        }

        .case-study-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.08);
        }

        .case-card-media {
            height: 200px;
            overflow: hidden;
        }

        .case-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .case-study-card:hover .case-card-media img {
            transform: scale(1.08);
        }

        .case-card-info {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .case-label {
            display: block;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--ey-teal);
            letter-spacing: 1.2px;
            margin-bottom: 12px;
        }

        .case-card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .case-card-link {
            margin-top: auto;
            color: var(--ey-teal);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .case-card-link:hover {
            color: #000;
        }

        /* Related Content Card Styles */
        .related-content-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            /*height: 100%;*/
            max-height: 60%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        .related-media-wrap {
            /* position: relative; */
            width: 100%;
            height: 100%;
        }

        .related-media-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-content-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 40px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: #fff;
        }

        .related-overlay-title {
            font-family: 'Libre Baskerville', serif;
            font-size: 2.8rem;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .related-overlay-text {
            font-size: 1.1rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }

        @media (max-width: 991px) {
            .section-heading-minimal {
                font-size: 2.2rem;
            }

            .related-content-card {
                min-height: 350px;
                margin-top: 30px;
            }
        }
    </style>

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
        background: #ffffff07;
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
            init() {
                this.cards = document.querySelectorAll('.article-card');
                this.loadMoreBtn = document.getElementById('loadMoreArticles');
                this.initialCount = 8;
                if (this.cards.length === 0) return;
                this.setupLoadMore();
            },
            setupLoadMore() {
                if (!this.loadMoreBtn) return;
                const allCards = Array.from(this.cards);
                if (allCards.length <= this.initialCount) {
                    // this.loadMoreBtn.style.display = 'none';
                    return;
                }
                allCards.forEach((card, idx) => {
                    if (idx >= this.initialCount) card.classList.add('is-hidden');
                });
                this.loadMoreBtn.addEventListener('click', () => {
                    const hiddenCards = allCards.filter(c => c.classList.contains('is-hidden'));
                    const batch = hiddenCards.slice(0, 4);
                    this.loadMoreBtn.innerHTML = '<span>Synchronizing Thought...</span> <i class="fa-solid fa-spinner fa-spin ms-2"></i>';
                    setTimeout(() => {
                        batch.forEach((card, i) => {
                            card.classList.remove('is-hidden');
                            card.style.opacity = '1';
                        });
                        const remaining = allCards.filter(c => c.classList.contains('is-hidden')).length;
                        if (remaining === 0) {
                            this.loadMoreBtn.style.display = 'none';
                        } else {
                            this.loadMoreBtn.innerHTML = '<span>Load More Thinking</span> <i class="fa-solid fa-plus ms-2"></i>';
                        }
                    }, 800);
                });
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

            if (categoryDropdown && serviceDropdown) {
                // 1. Initial Reset (optional but recommended)
                // serviceDropdown.value = "";

                // 2. Category Selection -> Filter Services
                categoryDropdown.addEventListener('change', function () {
                    const selectedCategoryId = this.value;
                    const options = Array.from(serviceDropdown.options);

                    let firstVisible = true;
                    options.forEach(option => {
                        if (!option.value) {
                            option.style.display = 'block';
                            return;
                        }
                        const parentId = option.getAttribute('data-parent-id');
                        if (parentId === selectedCategoryId) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    // Reset service selection when category changes
                    serviceDropdown.value = "";
                });

                // 3. Service Selection -> Update UI Card
                serviceDropdown.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (!selectedOption || !selectedOption.value) return;

                    const name = selectedOption.getAttribute('data-name');
                    const slug = selectedOption.getAttribute('data-slug');
                    const overview = selectedOption.getAttribute('data-overview');
                    const image = selectedOption.getAttribute('data-image');
                    const categoryName = categoryDropdown.options[categoryDropdown.selectedIndex]?.text || "Healthcare Services";

                    // Update UI elements with animation/transition
                    if (serviceRight && image) {
                        serviceRight.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('${image}')`;
                    }

                    if (serviceName) serviceName.textContent = name;
                    if (serviceOverview) serviceOverview.textContent = overview;

                    if (singleService && slug) {
                        singleService.href = `{{ url('/services') }}/${slug}`;
                    }

                    // Update Category name if needed
                    const catDisplay = document.querySelector('.category-name-display');
                    if(catDisplay) catDisplay.textContent = categoryName;

                    // Update Service Counter optionally (just for polish)
                    if(serviceCounter) {
                        // Just an example, maybe index + 1 or just keep it
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