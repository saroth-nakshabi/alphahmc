@extends('front/layout-2')

@section('content')
    <!-- Professional Typography & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        }

        .about-page-wrapper {
            background-color: #fff;
            color: var(--text-main);
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

        .visual-text-highlight {
            position: relative;
            display: inline-block;
        }

        .visual-text-highlight::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 0;
            width: 100%;
            height: 12px;
            background: rgba(0, 144, 149, 0.1);
            z-index: -1;
        }

        /* Layout Utilities */
        .section-padding {
            padding: 60px 0;
            position: relative;
        }

        .bg-soft {
            background-color: var(--bg-soft);
        }

        /* Hero V2 Styles (Reference Match) */
        .hero-premium-v2 {
            position: relative;
            display: flex;
            align-items: center;
            background-image: url('{{ asset('public/uploads/about_us_images/' . ($about_us->image ?? 'default.jpg')) }}');
            /* background-image: url("{{ asset('public/front-new/assets/images/section-3-2nd-image.jpg') }}"); */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment:fixed;
            background-position: center;
            color: #fff;
            padding-top: 100px;
            height: 915px;
            max-height: 1150px;
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

        .btn-contact-premium {
            display: inline-block;
            background: #fff;
            color: #000;
            padding: 18px 50px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 2px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            font-size: 0.85rem;
            margin-top: 20px;
        }

        .btn-contact-premium:hover {
            background: #f0f0f0;
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            color: #000;
        }

        .hero-breadcrumbs {
            position: absolute;
            /* bottom: -50px; */
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
            color: var(--alpha-teal);
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



        .hero-visual-frame {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.1);
        }

        /* Stats Bar -> Brand Showcase */
        .stats-infographic {
            background: #ffffff;
            color: #000;
            padding: 100px 0;
            position: relative;
            z-index: 2;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-family: 'Libre Baskerville', serif;
            font-size: 3.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
            color: #fff;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: rgba(255,255,255,0.7);
        }

        /* Logo Marquee Styles - Exact Match for Reference Image */
        .logo-marquee-wrapper {
            overflow: hidden;
            width: 100%;
            position: relative;
            padding: 20px 0;
        }

        .logo-slider-inner {
            display: flex;
            align-items: center;
            width: max-content;
            animation: scroll-logos 50s linear infinite;
            gap: 40px; /* Tighter gap as seen in reference */
        }

        .logo-slider-inner:hover {
            animation-play-state: paused;
        }

        .logo-slide-item {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }

        .logo-slide-item img {
            height: 200px; /* Matches the rectangular box height in image */
            width: auto;
            opacity: 1;
            filter: none;
            transition: all 0.3s ease;
        }

        .logo-slide-item img:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.05);
        }

        /* Impact Section - Midnight Gold Premium Style */
        :root {
    --primary-color: #066D77;
    --accent-color: #ff9d00;
    --text-dark: #1a1a1a;
    --glass-bg: rgba(255, 255, 255, 0.8);
}

#impact {
    background: radial-gradient(circle at top right, #f8f9fa, #ffffff);
    padding: 100px 0;
    overflow: hidden;
}

/* Typography & Decorations */
.tracking-widest { letter-spacing: 0.15em; font-size: 0.85rem; }
.custom-gradient-line {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    border-radius: 2px;
}

/* Left Content Animation */
#impact-content-box {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.content-fade-out {
    opacity: 0;
    transform: translateY(20px);
}

/* Modern Button */
.modern-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 14px 35px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(6, 109, 119, 0.2);
}
.modern-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(6, 109, 119, 0.3);
    background: #055a63;
}

/* The Glass Card */
.modern-card-container {
    position: relative;
    background: var(--glass-bg);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255,255,255,0.4);
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 40px 80px rgba(0,0,0,0.08);
}

.card-glass-glow {
    position: absolute;
    top: -20px;
    right: -20px;
    width: 100px;
    height: 100px;
    background: var(--accent-color);
    filter: blur(80px);
    opacity: 0.2;
    z-index: -1;
}

/* Logo Viewport */
.logo-viewport {
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border-radius: 18px;
    margin: 20px 0;
    padding: 40px;
    box-shadow: inset 0 0 20px rgba(0,0,0,0.02);
}

.logo-viewport img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.swiper-slide-active img {
    transform: scale(1.1);
}

/* Navigation Circles */
.nav-wrapper {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 20px;
}

.nav-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 1px solid #eee;
    background: white;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-circle:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

/* Header inside card */
.member-card-header h4 {
    color: var(--accent-color);
    font-weight: 800;
    text-transform: uppercase;
    font-size: 0.8rem;
    text-align: center;
    margin-bottom: 10px;
}

        .member-visual-container {
            position: relative;
            padding: 0;
            overflow: hidden;
            border-radius: 8px;
            /* background: #000000; */
            /* box-shadow: 0 15px 35px rgba(0,0,0,0.25); */
        }

        .member-visual-container img {
            /* width: 100%; */
            height: 480px;
            object-fit: contain;
            opacity: 0.95;
            transition: all 1.2s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .member-card-premium:hover img {
            transform: scale(1.08);
            opacity: 1;
        }

        .member-info {
            padding-top: 30px;
            text-align: left;
        }

        .member-info h4 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .member-info p {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.65);
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-weight: 300;
        }

        /* Swiper Navigation - Deep Gold Buttons */
        .member-nav-btn {
            position: absolute;
            top: calc(50% + 20px);
            z-index: 10;
            cursor: pointer;
            /* background: #ff9d00; */
            color: #000000;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            /* box-shadow: 0 15px 30px rgba(255, 157, 0, 0.25); */
            transition: all 0.4s ease;
            border: 2px solid #ffffff;
        }

        .member-nav-btn:hover {
            background: #066D77;
            color: #ffffff;
            transform: scale(1.15) rotate(5deg);
        }

        .btn-prev-member { left: -30px; }
        .btn-next-member { right: -30px; }

        @media (max-width: 991px) {
            .member-card-premium {
                margin-top: 60px;
            }
            .btn-prev-member { left: 5px; }
            .btn-next-member { right: 5px; }
        }

        /* Alternating Story Sections */
        .story-strip {
            display: flex;
            align-items: center;
            gap: 80px;
            padding: 80px 0;
        }

        .story-strip.reverse {
            flex-direction: row-reverse;
        }

        .story-visual {
            width: 50%;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
        }

        .story-content {
            width: 50%;
        }

        .story-content h3 {
            font-family: 'Libre Baskerville', serif;
            font-size: 2.8rem;
            margin-bottom: 25px;
            color: var(--text-main);
        }

        /* Premium Buttons */
        .btn-gold-premium {
            background: #e6e6e6;
            color: #000;
            border: none;
            padding: 18px 45px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 15px 30px rgba(108, 165, 165, 0.2);
            text-decoration: none;
            display: inline-block;
        }

        .btn-gold-premium:hover {
            transform: translateY(-5px);
            background: #000000;
            color: #e6e6e6;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .btn-outline-light-premium {
            background: #e6e6e6;
            color: #000000;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 16px 45px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 50px;
            transition: all 0.4s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-light-premium:hover {
            background: #000000;
            color: #e6e6e6;
            border-color: #fff;
            transform: translateY(-5px);
        }

        /* Specialized Business Consultancy - Ecosystem Style */
        .section-tag-light {
            background: #e0f2f1;
            color: #009095;
            display: inline-block;
            padding: 8px 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-radius: 4px;
            margin-bottom: 25px;
        }

        .ecosystem-title {
            font-family: 'Libre Baskerville', serif;
            font-size: 3.5rem;
            color: #00767c;
            line-height: 1.1;
            margin-bottom: 50px;
        }

        .expertise-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .expertise-card {
            background: #ffffff;
            padding: 50px 40px;
            border: 1px solid rgba(6, 110, 120, 0.5);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.01);
            text-align: left;
        }

        .expertise-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0, 144, 149, 0.08);
            border-color: rgba(0, 144, 149, 0.15);
        }

        .expertise-card i {
            display: block;
            margin-bottom: 25px;
            font-size: 1.8rem;
            color: #009095;
        }

        .expertise-card h4 {
            font-family: 'Libre Baskerville', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #00767c;
            margin-bottom: 15px;
        }

        .expertise-card p {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0;
        }

        @media (max-width: 991px) {
            .expertise-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .ecosystem-title {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 767px) {
            .expertise-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Logo Cloud Showcase - Premium Redesign */
        .logo-portfolio {
            padding: 140px 0;
                background: linear-gradient(180deg, #fff 0%, #f1f5f9 100%);
                border-top: 1px solid rgba(0,0,0,0.05);
            }

            .brand-card-premium {
                background: #fff;
                padding: 60px 40px;
                border-radius: 40px;
                border: 1px solid rgba(0, 144, 149, 0.05);
                text-align: center;
                transition: var(--transition-smooth);
                box-shadow: 0 10px 40px rgba(0,0,0,0.02);
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            .brand-card-premium::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                background: var(--primary-accent);
                transform: scaleX(0);
                transition: transform 0.4s ease;
                transform-origin: left;
            }

            .brand-card-premium:hover {
                transform: translateY(-20px);
                box-shadow: 0 40px 80px rgba(0, 144, 149, 0.12);
                border-color: rgba(0, 144, 149, 0.2);
            }

            .brand-card-premium:hover::before {
                transform: scaleX(1);
            }

            .brand-logo-container {
                width: 130px;
                height: 130px;
                background: var(--bg-soft);
                border-radius: 35px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 35px;
                padding: 25px;
                transition: var(--transition-smooth);
            }

            .brand-card-premium:hover .brand-logo-container {
                background: var(--primary-light);
                transform: scale(1.1) rotate(-5deg);
            }

            .brand-title-premium {
                font-size: 1.25rem;
                font-weight: 800;
                letter-spacing: 1px;
                color: var(--text-main);
                margin-bottom: 5px;
            }

            .brand-subtitle-premium {
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 2px;
                color: var(--text-muted);
                font-weight: 600;
            }

            @media (max-width: 991px) {

                .story-strip,
                .story-strip.reverse {
                    flex-direction: column;
                    gap: 40px;
                }

                .story-visual,
                .story-content {
                    width: 100%;
                }

                .expertise-grid {
                    grid-template-columns: 1fr;
                }

                .display-large {
                    font-size: 3rem;
                }
                .transformation-section {
                padding: 100px 0;
                padding: 0%;
            }
            .transformation-section h2 {
                font-size: 2.2rem;
            }
            }

             .transformation-section {
            padding: 160px 0;
            /* Increased height/padding */
            background: #2e2e2e;
            color: #ffffff;
            min-height: 700px;
            display: flex;
            align-items: center;
        }

        .transformation-section .transformation-desc,
        .transformation-section .transformation-desc * {
            font-size: 1.15rem;
            line-height: 1.8;
            opacity: 0.9;
            color: #ffffff;
        }

        .transformation-section .large-info {
            color: #ffffff;
            margin-bottom: 40px;
        }

        .transformation-section .large-info * {
            color: #ffffff;
        }

        .transformation-section .large-info h1,
        .transformation-section .large-info h2 {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .transformation-section .large-info h3,
        .transformation-section .large-info h4 {
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .transformation-section .large-info p {
            font-size: 1.15rem;
            line-height: 1.8;
            margin-bottom: 20px;
            opacity: 0.9;
            font-weight: 400;
        }

        .transformation-read-more {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #ffffff;
            font-weight: 700;
            text-decoration: none;
            margin-top: 30px;
            font-size: 1.1rem;
            transition: var(--transition);
            border-bottom: 2px solid #ffffff;
            border-left: none;
            border-right: none;
            border-top: none;
            padding-bottom: 5px;
            background: none;
            cursor: pointer;
        }

        .transformation-read-more:hover {
            color: #009095;
            gap: 15px;
            border-bottom-color: #009095;
        }

        /* Collapsible desc wrapper */
        .transformation-desc-wrapper {
            overflow: hidden;
            max-height: 130px;
            transition: max-height 0.55s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .transformation-desc-wrapper.expanded {
            max-height: 800px;
        }

        .hero-logo{
            width:100%;
            max-height: 650px;
            border-radius: 75%;
            background-size: cover;
             box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        }

        
        
        </style>

        <div class="about-page-wrapper">

            <section class="hero-premium-v2">

                <div class="hero-overlay"></div>

                <div class="container hero-content-container">
                    <div class="row align-items-center g-5 div-content">

                        <!-- LEFT CONTENT -->
                        <div class="col-lg-7" data-aos="fade-right">
                            <h1 class="display-large">
                                About <br>
                                <span style="color:#fff;">
                                    {{ $about_us->title ?? 'Alpha Corp' }}
                                </span>
                            </h1>

                            <p class="hero-desc mb-5">
                                {!! $about_us -> description ??  'Default Description Here' !!}
                                {{-- {{ $about_us->description }} --}}
                            </p>

                            <a href="#impact" class="btn-contact-premium">
                                Explore Our Journey
                            </a>
                        </div>

                        <!-- RIGHT LOGO -->
                        <div class="col-lg-5 text-end" data-aos="fade-left" data-aos-delay="200">
                            <img src="{{ asset('public/uploads/about_us_logos/' . ($about_us->logo ?? 'default.jpg')) }}"
                                alt="Logo"
                                class="hero-logo" />
                        </div>

                    </div>
                </div>
            </section>
            <style>
                        @media (max-width: 991px) {
                .hero-premium-v2 {
                    min-height: auto;
                    padding: 80px 0 60px;
                }

                .div-content {
                    margin-top: 0 !important;
                }

                .hero-logo {
                    width: 280px;
                }

                .display-large {
                    font-size: clamp(2rem, 5vw, 3rem);
                }

                .col-lg-5.text-end {
                    text-align: center !important;
                }
            }

            /* ===== MOBILE (max-width: 767px) ===== */
            @media (max-width: 767px) {
                .hero-premium-v2 {
                    padding: 60px 0 50px;
                }

                /* Stack logo ABOVE text on mobile */
                .div-content {
                    flex-direction: column-reverse;
                    margin-left:0;
                    margin-right:0;
                }

                .col-lg-5.text-end {
                    text-align: center !important;
                    margin-bottom: 24px;
                }

                .col-lg-7{
                    text-align: center;
                }
                .hero-logo {
                    width: 180px;
                    margin: 0 auto;
                    display: block;
                }

                .display-large {
                    font-size: clamp(1.75rem, 8vw, 2.5rem);
                    text-align: center;
                    line-height: 1.2;
                }

                .hero-desc {
                    font-size: 0.95rem;
                    text-align: center;
                    margin-bottom: 1.5rem !important;
                }

                .btn-contact-premium {
                    display: block;
                    text-align: center;
                    width: 100%;
                    max-width: 280px;
                    margin: 0 auto;
                }

                [data-aos] {
                    /* Prevent AOS from breaking mobile layout */
                    opacity: 1 !important;
                    transform: none !important;
                }
            }

            /* ===== SMALL MOBILE (max-width: 480px) ===== */
            @media (max-width: 480px) {
                .hero-premium-v2 {
                    padding: 50px 0 40px;
                }

                .hero-logo {
                    width: 140px;
                }

                .display-large {
                    font-size: clamp(1.5rem, 7vw, 2rem);
                }

                .hero-desc {
                    font-size: 0.875rem;
                    line-height: 1.6;
                }

                .btn-contact-premium {
                    padding: 12px 24px;
                    font-size: 0.9rem;
                }
            }


            #impact-content-box {
    transition: opacity 0.3s ease;
}

            </style>



            <!---=== Content Area ===-->



            <section class="about-premium py-5">
                <div class="container">

                    @foreach($about_content as $item)
                    <div class="about-card row align-items-center mb-5">

                        <div class="col-lg-6 {{ $loop->index % 2 ? 'order-lg-2' : '' }}">
                            <div class="image-box" data-aos="zoom-in">
                                <img src="{{ $item->image
                                    ? asset('public/uploads/about_content/' . $item->image)
                                    : asset('public/uploads/about_content/default.jpg') }}">
                            </div>
                        </div>

                        <div class="col-lg-6 {{ $loop->index % 2 ? 'order-lg-1' : '' }}">
                            <div class="content-box" data-aos="fade-up">

                                <span class="badge-tag">About Us</span>

                                <h2>{{ $item->content_title }}</h2>

                                <div class="about-text">
                                    {!! $item->content !!}
                                </div>

                            </div>
                        </div>

                    </div>
                    @endforeach

                </div>
            </section>

            <style>

            .stats-section {
                background: #f8fafc;
            }
            .about-card {
                padding: 30px;
                border-radius: 20px;
                background: #fff;
                box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            }

            /* IMAGE */
            .image-box img {
                width: 100%;
                border-radius: 15px;
                transition: 0.4s;
                max-height: 700px;
            }

            .image-box img:hover {
                transform: scale(1.05);
            }
            </style>

            <script>


            function toggleText(id) {
                let el = document.getElementById('text-' + id);
                el.classList.toggle('expanded');
            }

            </script>



           <section id="impact" class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="impact-text-wrapper" id="impact-content-box">
                    <span class="text-uppercase fw-bold text-primary tracking-widest mb-2 d-block">Success Story</span>
                    <h2 class="display-4 fw-bold mb-4" id="impact-title"></h2>
                    <div class="custom-gradient-line mb-4"></div>
                    <div class="impact-desc fs-5 text-secondary mb-4" id="impact-desc"></div>
                    
                    <button class="modern-btn" onclick="openClientModal()">
                        <span>Explore Impact</span>
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <div class="col-lg-5 offset-lg-1">
                <div class="modern-card-container" data-aos="zoom-in">
                    <div class="card-glass-glow"></div>
                    <div class="member-card-header">
                        <h4 id="member-highlight">Trusted Partner</h4>
                    </div>

                    <div class="swiper memberSwiper">
                        <div class="swiper-wrapper">
                            @foreach($clients as $client)
                            <div class="swiper-slide" 
                                 data-name="{{ $client->name }}" 
                                 data-short="{{ $client->short_description }}" 
                                 data-index="{{ $loop->index }}">
                                
                                <div class="client-desc-hidden" style="display:none;">
                                    {!! $client->description !!}
                                </div>

                                <div class="logo-viewport">
                                    <img src="{{ asset('public/uploads/clients/' . $client->logo) }}" alt="{{ $client->name }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="nav-wrapper">
                        <button class="nav-circle btn-prev-member"><i class="fas fa-chevron-left"></i></button>
                        <button class="nav-circle btn-next-member"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popup Modal -->
<div id="clientModal" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.6);
    z-index:9999;
    align-items:center;
    justify-content:center;">
    <div style="
        background:#fff;
        border-radius:12px;
        padding:40px;
        max-width:650px;
        width:90%;
        max-height:80vh;
        overflow-y:auto;
        position:relative;
        box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        
        <!-- Close Button -->
        <button onclick="closeClientModal()" style="
            position:absolute;
            top:15px;
            right:20px;
            background:none;
            border:none;
            font-size:24px;
            cursor:pointer;
            color:#333;">
            &times;
        </button>

        <!-- Modal Content -->
        <h3 id="modal-title" class="fw-bold mb-3"></h3>
        <hr>
        <div id="modal-desc" class="mt-3 lh-lg"></div>
    </div>
</div>

<script>
    // Store current slide data
    let currentClient = { name: '', desc: '' };

    function openClientModal() {
        document.getElementById('modal-title').textContent  = currentClient.name;
        document.getElementById('modal-desc').innerHTML     = currentClient.desc;
        document.getElementById('clientModal').style.display = 'flex';
        memberSwiper.autoplay.stop(); 
    }

    function closeClientModal() {
        document.getElementById('clientModal').style.display = 'none';
        memberSwiper.autoplay.start(); // Resume autoplay when modal closes
    }

    // Close modal when clicking outside
    document.getElementById('clientModal').addEventListener('click', function(e) {
        if (e.target === this) closeClientModal();
    });

    function updateLeftContent(slide) {
    if (!slide) return;
    
    const name = slide.dataset.name;
    const short = slide.dataset.short;
    const descEl = slide.querySelector('.client-desc-hidden');
    const desc = descEl ? descEl.innerHTML : '';

    currentClient = { name, desc };

    const contentBox = document.getElementById('impact-content-box');
    
    // Start Animation
    contentBox.classList.add('content-fade-out');

    setTimeout(() => {
        document.getElementById('impact-title').textContent = name;
        document.getElementById('impact-desc').textContent = short;
        
        // Finish Animation
        contentBox.classList.remove('content-fade-out');
    }, 400); // Matches the CSS transition
}

    const memberSwiper = new Swiper(".memberSwiper", {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".btn-next-member",
            prevEl: ".btn-prev-member",
        },
        on: {
            slideChange: function () {
                const activeSlide = this.slides[this.activeIndex];
                updateLeftContent(activeSlide);
            }
        }
    });

    // Initialize first slide content on page load
    document.addEventListener('DOMContentLoaded', function () {
        const firstSlide = memberSwiper.slides[memberSwiper.activeIndex];
        updateLeftContent(firstSlide);
    });
</script>

<style>
    
    #impact-content-box {
        transition: opacity 0.3s ease;
    }
    #clientModal {
        animation: fadeIn 0.2s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
</style>

            <!-- Quote Block: Professional Minimalist -->

            <section class="section-padding bg-soft">
                <div class="container" data-aos="zoom-in">

                    @foreach($about_quotes as $quote)

                    <div class="text-center mb-5">

                        <i class="fa-solid fa-quote-left text-primary-accent mb-4 fs-1 opacity-25"></i>

                        {{-- QUOTE TEXT --}}
                        <h2 class="display-large text-center mx-auto"
                            style="max-width: 1000px; font-size: 2.5rem; line-height: 1.4;">
                            {!! $quote -> About_quote !!}
                        </h2>

                        {{-- FOOTER --}}
                        <div class="mt-5">

                            {{-- TITLE --}}
                            @if($quote->quote_title)
                                <p class="fw-bold mb-0">
                                    {{ $quote->quote_title }}
                                </p>
                            @endif

                            {{-- SUB TITLE / COMPANY --}}
                            @if($quote->quote_sub_title || $quote->company_name)
                                <p class="text-primary-accent small letter-spacing-1">
                                    {{ $quote->sub_title ?? '' }}
                                    {{ $quote->company_name ? ' - ' . $quote->company_name : '' }}
                                </p>
                            @endif

                        </div>

                    </div>

                    @endforeach

                </div>
            </section>



            <!-- Expertise Ecosystem: Grid -->
             {{-- <section class="section-padding" style="background: #ffffff;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8" data-aos="fade-right">
                            <span class="section-tag-light">Our Ecosystem</span>
                            <h2 class="ecosystem-title">Specialized Business <br>Consultancy</h2>
                        </div>
                    </div>

                    <div class="expertise-grid">
                        <div class="expertise-card" data-aos="fade-up" data-aos-delay="100">
                            <i class="fa-solid fa-flask-vial"></i>
                            <h4 class="fw-bold">Alpha X</h4>
                            <p>A dedicated venture studio building the next generation of healthcare technology and platforms.</p>
                        </div>
                        <div class="expertise-card" data-aos="fade-up" data-aos-delay="200">
                            <i class="fa-solid fa-diagram-project"></i>
                            <h4 class="fw-bold">Alpha Platinion</h4>
                            <p>Architecting future-proof IT landscapes and digital experiences for global health systems.</p>
                        </div>
                        <div class="expertise-card" data-aos="fade-up" data-aos-delay="300">
                            <i class="fa-solid fa-brain"></i>
                            <h4 class="fw-bold">Alpha Brighthouse</h4>
                            <p>Consultancy focused on purpose-driven organizational transformation and ethical profitability.</p>
                        </div>
                        <div class="expertise-card" data-aos="fade-up" data-aos-delay="400">
                            <i class="fa-solid fa-lightbulb"></i>
                            <h4 class="fw-bold">Henderson Institute</h4>
                            <p>Our strategic think-tank exploring the ideas and inspiration that will help leaders shape the future.</p>
                        </div>
                        <div class="expertise-card" data-aos="fade-up" data-aos-delay="500">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <h4 class="fw-bold">Alpha Learning</h4>
                            <p>Empowering teams through world-class development programs and organizational learning.</p>
                        </div>
                        <div class="expertise-card" data-aos="fade-up" data-aos-delay="600">
                            <i class="fa-solid fa-stethoscope"></i>
                            <h4 class="fw-bold">Diagnostics Unit</h4>
                            <p>Providing data-driven decision support and benchmark comparisons for optimized healthcare operations.</p>
                        </div>
                    </div>
                </div>
            </section> --}}

            <section class="section-padding" style="background: #ffffff;">
                <div class="container">

                    <div class="row">
                        <div class="col-lg-8" data-aos="fade-right">
                            <span class="section-tag-light">Our Ecosystem</span>
                            <h2 class="ecosystem-title">Specialized Business <br>Consultancy</h2>
                        </div>
                    </div>

                    <div class="expertise-grid">

                        @foreach($eco_systems as $index => $eco)

                            <div class="expertise-card"
                                data-aos="fade-up"
                                data-aos-delay="{{ ($index + 1) * 100 }}">

                                {{-- ICON (optional fallback) --}}
                                <i class="fa-solid fa-star"></i>

                                <h4 class="fw-bold">
                                    {{ $eco->heading }}
                                </h4>

                                <p>
                                    <!--{{ $eco->description }}-->
                                    {!! $eco->description !!}
                                </p>

                            </div>

                        @endforeach

                    </div>
                </div>
            </section>

            @include('front.clients')

            <!-- Contact CTA -->
            <section class="section-padding" style="background: #066D77; color: #fff;">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-lg-10" data-aos="zoom-in">
                            <span class="section-tag-light mb-4" style="background: rgba(255,157,0,0.1); color: #e6e6e6;">GET IN TOUCH</span>
                            <h2 class="display-large text-white mb-4" style="font-size: 3.5rem;">Partner with the Global Leader <br>in Healthcare Excellence</h2>
                            <p class="fs-5 opacity-75 mb-5 mx-auto" style="max-width: 800px; line-height: 1.8;">
                                From advanced diagnostics to strategic consultancy, we empower healthcare providers to reach
                                their peak potential. Our experts are ready to architect your success.
                            </p>
                            <div class="d-flex flex-wrap justify-content-center gap-4 mt-4">
                                <a href="{{ route('contact') }}" class="btn btn-gold-premium btn-lg">
                                    CONSULT AN EXPERT <i class="fas fa-paper-plane ms-2"></i>
                                </a>
                                <a href="tel:+97137802818" class="btn btn-outline-light-premium btn-lg">
                                    <i class="fas fa-phone-alt me-2"></i> +971 3 780 2818
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Brand Showcase -->
            <section class="logo-portfolio">
                <div class="container">
                    <div class="row justify-content-center text-center mb-5">
                        <div class="col-lg-8" data-aos="fade-up">
                            <span class="section-tag">Global Portfolio</span>
                            <h2 class="display-large" style="font-size: 3rem;">The Alpha Portfolio</h2>
                            <p class="fs-5 text-muted mt-3">Excellence across every healthcare discipline and market we serve.</p>
                        </div>
                    </div>

                    <div class="row g-5 pt-4">
                        @php
                            $brands = [
                                ['name' => 'ALPHA HEALTHCARE', 'tag' => 'Medical Consultancy'],
                                ['name' => 'ALPHA DIAGNOSTICS', 'tag' => 'Advanced Testing'],
                                ['name' => 'ALPHA WELLNESS', 'tag' => 'Holistic Care'],
                                ['name' => 'ALPHA PHARMA', 'tag' => 'Supply Chain']
                            ];
                        @endphp
                        @foreach($brands as $index => $brand)
                            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                <div class="brand-card-premium">
                                    <div class="brand-logo-container">
                                        <img src="{{ asset('public/front-new/assets/images/about/alpha-about-img.png') }}" class="img-fluid rounded" alt="{{ $brand['name'] }}" />
                                    </div>
                                    <h4 class="brand-title-premium">{{ $brand['name'] }}</h4>
                                    <span class="brand-subtitle-premium">{{ $brand['tag'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1200,
                once: true,
                offset: 100
            });
        }

        // Initialize Premium Member Swiper with Content Sync
        // const impactData = [
        //     {
        //         title: "Our Tenured Staff",
        //         highlight: "25 Years at Alpha",
        //         desc: "At Alpha Healthcare Services, we take immense pride in our dedicated team, many of whom <span class='highlight-text'>have been with us for over a decade</span>, and some for an impressive 20 years. Their long-standing commitment and expertise are a testament to the supportive and collaborative work environment we cultivate. These valued employees bring a <span class='highlight-text'>wealth of knowledge and experience</span>, leading to more successful outcomes."
        //     },
        //     {
        //         title: "Global Leadership",
        //         highlight: "Excellence in Care",
        //         desc: "Our leadership team ensures that every strategic decision is guided by <span class='highlight-text'>unwavering professional ethics</span>. By fostering a culture of collective genius, we enable our experts to reach their peak potential, ensuring that <span class='highlight-text'>Alpha remains the benchmark</span> for institutional integrity and healthcare consultancy across the globe."
        //     }
        // ];

        // const memberSwiper = new Swiper(".memberSwiper", {
        //     loop: true,
        //     speed: 800,
        //     effect: 'fade',
        //     fadeEffect: { crossFade: true },
        //     navigation: {
        //         nextEl: ".btn-next-member",
        //         prevEl: ".btn-prev-member",
        //     },
        //     on: {
        //         slideChange: function() {
        //             const index = this.realIndex;
        //             const contentBox = document.getElementById('impact-content-box');
        //             const titleEl = document.getElementById('impact-title');
        //             const descEl = document.getElementById('impact-desc');
        //             const highlightEl = document.getElementById('member-highlight');

        //             if(!contentBox || !titleEl || !descEl || !highlightEl) return;

        //             // Fade out
        //             contentBox.style.opacity = '0';
        //             contentBox.style.transform = 'translateY(10px)';

        //             setTimeout(() => {
        //                 // Update content
        //                 titleEl.innerText = impactData[index].title;
        //                 descEl.innerHTML = impactData[index].desc;
        //                 highlightEl.innerText = impactData[index].highlight;

        //                 // Fade in
        //                 contentBox.style.opacity = '1';
        //                 contentBox.style.transform = 'translateY(0)';
        //             }, 400);
        //         }
        //     }
        // });
    });
</script>
@endsection
