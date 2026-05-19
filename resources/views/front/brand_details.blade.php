@extends('front/layout-2')

@section('content')
    <!-- Professional Typography & Icons -->

    <style>
        :root {
            --primary-accent: #009095;
            --primary-dark: #066D77;
            --primary-light: #f4fcfc;
            --text-main: #009095;
            --text-muted: #000000;
            --bg-soft: #f8fafc;
            --border-color: rgba(0, 144, 149, 0.08);
            --premium-shadow: 0 20px 50px rgba(0, 0, 0, 0.03);
            --transition-smooth: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            --accent-orange: #ff8d1b;
        }

        .brand-page-wrapper {
            background-color: #fff;
            color: var(--text-muted);
            font-family: 'Roboto', sans-serif;
            line-height: 1.7;
            overflow-x: hidden;
        }

        /* Type System */
        .display-large {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .section-tag {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            padding: 8px 16px;
            background: var(--primary-light);
            color: var(--primary-accent);
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 24px;
        }

        /* Hero V2 Styles */
        .hero-premium-v2 {
            position: relative;
            display: flex;
            align-items: center;
            @if(isset($brandHero) && $brandHero->image)
                background-image: url('{{ asset('public/uploads/brands/' . $brandHero->image) }}');
            @else
                background-image: url('{{ asset('public/front-new/assets/images/service_details/service-details-2.webp') }}');
            @endif
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            color: #fff;
            padding-top: 100px;
            height: 700px;
            max-height: 900px;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 100%);
            z-index: 1;
        }

        .hero-content-container {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .hero-premium-v2 .display-large {
            font-size: 4rem;
            font-family: 'Libre Baskerville', serif;
            color: #fff !important;
            line-height: 1.1;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .hero-desc {
            max-width: 700px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            line-height: 1.6;
            font-weight: 300;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.9) !important;
        }

        .hero-breadcrumbs {
            position: absolute;
            bottom: 30px;
            left: 0;
            width: 100%;
            z-index: 2;
        }

        .hero-breadcrumbs .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .hero-breadcrumbs .breadcrumb-item {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
        }

        .hero-breadcrumbs .breadcrumb-item a {
            color: #fff;
            text-decoration: none;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .hero-breadcrumbs .breadcrumb-item a:hover {
            opacity: 1;
            color: var(--primary-accent);
        }

        .hero-breadcrumbs .breadcrumb-item.active {
            color: #fff;
        }

        .hero-breadcrumbs .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: rgba(255,255,255,0.4);
            padding: 0 20px;
            font-size: 0.7rem;
            font-weight: 900;
        }

        /* Section Layouts */
        .section-padding {
            padding: 100px 0;
        }

        .bg-soft {
            background-color: var(--bg-soft);
        }

        /* Story Style Layout */
        .story-strip {
            display: flex;
            align-items: center;
            gap: 80px;
        }

        .brand-visual {
            width: 40%;
            background: #fff;
            padding: 50px;
            border-radius: 30px;
            box-shadow: var(--premium-shadow);
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid var(--border-color);
        }

        .brand-visual img {
            max-width: 100%;
            height: auto;
            max-height: 200px;
        }

        .brand-story-content {
            width: 60%;
        }

        .brand-story-content h3 {
            font-family: 'Libre Baskerville', serif;
            font-size: 2.8rem;
            margin-bottom: 25px;
            color: var(--primary-dark);
        }

        .location-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--accent-orange);
            font-weight: 700;
            margin-bottom: 20px;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Expertise Section */
        .expertise-container {
            background: #fff;
            border-radius: 40px;
            padding: 80px;
            border: 1px solid var(--border-color);
            box-shadow: var(--premium-shadow);
        }

        .expertise-title {
            font-family: 'Libre Baskerville', serif;
            font-size: 2.5rem;
            color: var(--primary-dark);
            margin-bottom: 40px;
            text-align: center;
            position: relative;
        }

        .expertise-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--accent-orange);
            margin: 20px auto 0;
            border-radius: 2px;
        }

        .expertise-content-wrapper {
            font-size: 1.15rem;
            color: #475569;
            line-height: 1.8;
        }

        @media (max-width: 991px) {
            .story-strip {
                flex-direction: column;
                gap: 40px;
            }
            .brand-visual, .brand-story-content {
                width: 100%;
            }
            .hero-premium-v2 {
                height: 500px;
            }
            .expertise-container {
                padding: 40px;
            }
        }
    </style>

    <div class="brand-page-wrapper">
        {{-- HERO SECTION --}}
        <section class="hero-premium-v2">
            <div class="hero-overlay"></div>
            <div class="container hero-content-container">
                <div class="row align-items-center">
                    <div class="col-lg-10">
                        <span class="section-tag">Brand Portfolio</span>
                        <h1 class="display-large">{{ $brand->name }}</h1>
                        <p class="hero-desc">
                            Alpha Health Group is proud to present {{ $brand->name }}, a specialized entity within our ecosystem dedicated to excellence in the healthcare sector.
                        </p>
                    </div>
                </div>
            </div>

            {{-- BREADCRUMBS --}}
            <div class="hero-breadcrumbs">
                <div class="container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.brands') }}">Our Brands</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $brand->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        {{-- ABOUT THE BRAND SECTION --}}
        <section class="section-padding">
            <div class="container">
                <div class="story-strip">
                    <div class="brand-visual" data-aos="fade-right">
                        <img src="{{ $brand->logo ? asset('public/uploads/brands/' . $brand->logo) : asset('public/front-new/assets/images/alpha-logo.svg') }}" 
                             alt="{{ $brand->name }}">
                    </div>
                    <div class="brand-story-content" data-aos="fade-left">
                        <div class="location-info">
                            <i class="fas fa-map-marker-alt"></i> {{ $brand->address ?? 'Alpha Corporate Center' }}
                        </div>
                        <h3>About the Brand</h3>
                        <div class="brand-description-text">
                            {!! $brand->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- EXPERTISE SECTION --}}
        <section class="section-padding bg-soft">
            <div class="container">
                <div class="expertise-container" data-aos="zoom-in">
                    <h2 class="expertise-title">What We Do</h2>
                    <div class="expertise-content-wrapper">
                        {!! $brand->what_we_do !!}
                    </div>
                </div>
            </div>
        </section>

        {{-- LOCATION MAP SECTION --}}
        <section class="section-padding">
            <div class="container">
                <div class="expertise-container" data-aos="fade-up">
                    <h2 class="expertise-title">Our Location</h2>
                    <div class="location-info justify-content-center mb-4">
                        <i class="fas fa-map-marker-alt"></i> {{ $brand->address ?? 'Alpha Corporate Center' }}
                    </div>
                    
                    <div class="map-frame-wrapper shadow-sm rounded-4 overflow-hidden border">
                        @if($brand->address)
                            <iframe 
                                width="100%" 
                                height="450" 
                                frameborder="0" 
                                style="border:0; display: block;" 
                                src="https://www.google.com/maps?q={{ urlencode($brand->address) }}&output=embed" 
                                allowfullscreen>
                            </iframe>
                        @else
                            <div class="p-5 text-center bg-light">
                                <p class="text-muted">Location details are not available for this brand.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- FOOTER CTA STYLE (Similar to About Page) --}}
        <!-- <section class="section-padding text-center">
            <div class="container">
                <h2 class="display-large mb-4">Partner with {{ $brand->name }}</h2>
                <p class="hero-desc mx-auto mb-5" style="color: var(--text-muted) !important;">
                    Ready to experience excellence? Contact us today to learn more about how {{ $brand->name }} can support your healthcare needs.
                </p>
                <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold">
                    Get In Touch <i class="fas fa-paper-plane ms-2"></i>
                </a>
            </div>
        </section> -->
    </div>

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
@endsection