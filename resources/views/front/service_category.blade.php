@extends('front/layout-2')

@push('page_title')
    {!! $service->name !!}
@endpush

@push('meta')
    <meta name="description" content="{{ $service->meta_description }}">
    <meta name="keywords" content="{{ $service->meta_keywords }}">
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
    @php
        $heroPreload = $service->hero_image
            ? asset('public/' . ltrim($service->hero_image, '/'))
            : asset('public/front/assets/img/hero/service-details-bg.jpg');
    @endphp
    <link rel="preload" as="image" href="{{ $heroPreload }}" fetchpriority="high">
@endpush

@push('og_tags')
    {{-- <link rel="canonical" href="{{ url('/' . $service->slug) }}"> --}}

    <meta name="author" content="Alpha Health Management Consultancy">
    <meta property="og:title" content="{{ $service->meta_title }}" />
    <meta property="og:description" content="{{ $service->meta_description }}" />
    <meta property="og:image" content="{{ $service->hero_image ? asset('public/' . ltrim($service->hero_image, '/')) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website" />

    <meta name="twitter:card" content="{{ strip_tags($service->content)}}">
    {{-- <meta name="twitter:description" content="{{ strip_tags($service->meta_description ?? $service->overview) }}"> --}}
@endpush







@section('content')
    <style>
        :root {
            --brand-primary: #066D77;
            --brand-primary-rgb: 6, 109, 119;
            --brand-dark: #066D77;
            --brand-soft: #f4fcfc;
            --text-title: #1a1a1a;
            --text-body: #4a4a4a;
            --text-light: #6b7280;
            --white: #ffffff;
            --bg-gray: #f9fafb;
            --border-ui: #e5e7eb;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 40px -4px rgba(0, 0, 0, 0.12);
            --radius-md: 16px;
            --radius-lg: 32px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .service-page-wrapper {
            background-color: var(--white);
            color: var(--text-body);
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            overflow-x: hidden;
        }

        /* Hero Section Enhancements */
        .service-hero {
            position: relative;
            padding: 200px 0 120px;
            background-color: #0c121d;
            color: var(--white);
            overflow: hidden;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            z-index: 0;
            animation: cinematicFocus 25s ease-out infinite alternate;
        }

        @keyframes cinematicFocus {
            0%   { transform: scale(1);   filter: brightness(0.8); }
            100% { transform: scale(1.1); filter: brightness(1);   }
        }
        @media (max-width: 768px) {
            .hero-background { animation: none; transform: scale(1); filter: brightness(0.8); }
        }

        .service-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.3) 100%);
            z-index: 1;
        }

        .service-hero .container {
            position: relative;
            z-index: 10;
        }

        .hero-title {
            font-size: 4.5rem !important;
            line-height: 1.05;
            margin-bottom: 25px;
            color: #ffffff !important;
            font-weight: 800 !important;
            /* letter-spacing: -0.04em; */
        }

        .hero-desc-wrapper {
            font-size: 1.25rem;
            color: #ffffff !important;
            max-width: 800px;
            margin-bottom: 45px;
            font-weight: 500;
        }

        /* Quote Section Styling */
       .quote-section {
    padding: 40px 0;
    background: #ffffff;
    position: relative;
    overflow: hidden;
}


.quote-inner {
    max-width: 820px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 32px;
}

.quote-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #6366f1;
    background: #eef2ff;
    padding: 6px 14px;
    border-radius: 999px;
}

.quote-eyebrow::before {
    content: '';
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #6366f1;
    border-radius: 50%;
}

.quote-text,
.quote-text * {
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 500;
    line-height: 1.4;
    color: #0f0f0f;
    text-align: center;
    letter-spacing: -0.02em;
}

.quote-text strong {
    font-weight: 700;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.quote-divider {
    width: 40px;
    height: 2px;
    background: #e5e7eb;
    border-radius: 999px;
}



.transformation-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #1f1f1f, #2e2e2e);
    color: #ffffff;
}

/* Left Content */
.transformation-section .large-info {
    margin-bottom: 30px;
}

.transformation-section .large-info h1,
.transformation-section .large-info h2 {
    font-size: clamp(2.2rem, 4vw, 3rem);
    font-weight: 700;
    line-height: 1.25;
    margin-bottom: 20px;
    letter-spacing: -0.5px;
}

.transformation-section .large-info h3,
.transformation-section .large-info h4 {
    font-size: clamp(1.4rem, 2.5vw, 2rem);
    font-weight: 600;
    margin-bottom: 15px;
    color: #e0e0e0;
}

.transformation-section .large-info p {
    font-size: 1.05rem;
    line-height: 1.9;
    opacity: 0.85;
    margin-bottom: 18px;
}

/* Right Content Box */
.transformation-desc-wrapper {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 25px;
    max-height: 260px;
    overflow: hidden;
    position: relative;
    transition: all 0.4s ease;
}

/* Fade effect */
.transformation-desc-wrapper::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background: linear-gradient(to top, #2e2e2e, transparent);
}

/* Expanded state */
.transformation-desc-wrapper.active {
    max-height: 1000px;
}

.transformation-desc-wrapper.active::after {
    display: none;
}

/* Text styling */
.transformation-desc,
.transformation-desc * {
    font-size: 1rem;
    line-height: 1.8;
    color: #f5f5f5;
}

/* Button */
.transformation-read-more {
    margin-top: 20px;
    background: transparent;
    border: 1px solid #ffffff40;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.transformation-read-more:hover {
    background: #ffffff;
    color: #2e2e2e;
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
            border-bottom-color: #066D77;
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

        /* 4. IMAGE + STRATEGY SECTION STRUCTURE (NEW) */
        :root {
            --primary-teal: #066D77;
            --light-teal-badge: #e6f3f3;
            --text-badge: #4c9696;
            --text-main: #6b6b6b;
            --text-heading: #2d2d2d;
            --card-bg: #ffffff;
            --transition: all 0.4s ease-in-out;
        }


        /* 7. HOW ALPHA CAN HELP SECTION - EY STYLE REFINEMENT */
        .help-section {
            padding: 120px 0;
            background: #ffffff;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .help-flex {
            display: flex;
            align-items: flex-start;
            gap: 100px;
        }

        .help-info {
            flex: 1.2;
        }

        .help-info h2 {
            font-size: 3.2rem;
            font-weight: 300;
            /* Thin weight for EY style */
            margin-bottom: 60px;
            color: #2e2e2e;
            font-family: 'Inter', sans-serif;
        }

        .help-text-block h4 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2e2e2e;
        }

        .help-text-block p {
            font-size: 1.15rem;
            color: #666;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .help-enquiry-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            padding: 13px 28px;
            background: #1a1a1a;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            border: 2px solid #1a1a1a;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            letter-spacing: 0.3px;
            transition: var(--transition);
        }

        .help-enquiry-btn:hover {
            background: #066D77;
            border-color: #009095;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 144, 149, 0.3);
        }

        .leader-sidebar {
            width: 320px;
            flex-shrink: 0;
            padding-top: 100px;
            /* Align with content start */
        }

        .leader-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 10px;
            display: block;
        }

        .leader-hr {
            border: 0;
            border-top: 1px solid #d1d5db;
            margin: 0 0 25px 0;
        }

        .leader-profile {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .leader-circle-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .leader-meta {
            display: flex;
            flex-direction: column;
        }

        .leader-name-bold {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .leader-job-title {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .leader-contact-links {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .contact-link-icon {
            color: #1a1a1a;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }

        .contact-link-icon:hover {
            background: #2e2e2e;
            color: #ffffff;
            border-color: #2e2e2e;
            transform: translateY(-2px);
        }

        .contact-link-icon.whatsapp-color:hover {
            background: #25D366;
            border-color: #25D366;
        }

        /* Inquiry Modal Premium Styling */
        .inquiry-modal .modal-content {
            border-radius: 24px !important;
            overflow: hidden;
        }

        .modal-content{
            padding-bottom: 10px !important;
        }
        .inquiry-modal .step-num {
            font-size: 0.75rem;
            font-weight: 800;
            color: #4ade80;
            /* Brighter green for contrast on dark */
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .inquiry-modal .step-text {
            font-size: 1rem;
            font-weight: 500;
        }

        .inquiry-modal .form-floating>.form-control:focus~label,
        .inquiry-modal .form-floating>.form-control:not(:placeholder-shown)~label,
        .inquiry-modal .form-floating>.form-select~label {
            color: #1a1a1a;
            opacity: 0.8;
        }

        .inquiry-modal .form-control,
        .inquiry-modal .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
        }

        .inquiry-modal .form-control:focus,
        .inquiry-modal .form-select:focus {
            border-color: #1a1a1a;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
        }

        .inquiry-modal .modal-close-btn {
            background: #f3f4f6;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .inquiry-modal .modal-close-btn:hover {
            background: #e5e7eb;
            transform: rotate(90deg);
        }

        @media (max-width: 991px) {
            .help-flex {
                flex-direction: column;
                gap: 50px;
            }

            .leader-sidebar {
                width: 100%;
                padding-top: 0;
            }
            .service-hero{
                padding: 120px 0 80px;

            }

        }

        /* Help List Styling */
        .help-list-section {
            padding: 100px 0;
            background: #ffffff;
        }

        .help-list-section h2 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 60px;
        }

        .help-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 35px 0;
            border-top: 1px solid #e0e0e0;
            text-decoration: none;
            color: #1a1a1a;
            transition: var(--transition);
        }

        .help-list-item:hover {
            color: var(--brand-primary);
            padding-left: 20px;
        }

        .help-list-item:last-child {
            border-bottom: 1px solid #e0e0e0;
        }

        .help-list-item h4 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }

        .help-list-item i {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .help-list-item:hover i {
            transform: translateX(10px);
        }

        /* FAQ Accordion Styling */
        .faq-accordion {
            max-width: 1000px;
            margin: 0 auto;
        }

        .faq-item {
            margin-bottom: 20px;
            border-bottom: 1px solid #eef2f6;
            transition: var(--transition);
        }

        .faq-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 0;
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            text-align: left;
            transition: var(--transition);
        }

        .faq-question {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            transition: var(--transition);
        }

        .faq-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f0f9f9;
            border: 1.5px solid rgba(0, 144, 149, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #009095;
            transition: var(--transition);
            font-size: 1.2rem;
            font-weight: 300;
            line-height: 1;
            flex-shrink: 0;
        }

        .faq-item.active .faq-icon {
            background: #009095;
            color: #ffffff;
            border-color: #009095;
        }

        .faq-item.active .faq-question {
            color: #009095;
        }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
        }

        .faq-body {
            padding: 0 0 30px 0;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
        }

        .faq-item.active .faq-content {
            max-height: 500px;
        }

        @media (max-width: 768px) {
            .faq-question {
                font-size: 1.2rem;
            }
        }

        /* Split Section Layout (Case Studies & Related Content) */
        .split-section-ey {
            padding: 120px 0;
            background: #ffffff;
        }

        .ey-section-title {
            font-size: 3rem;
            font-weight: 800;
            font-family: 'Inter', sans-serif;
            color: #1a1a1a;
            margin-bottom: 50px;
            letter-spacing: -0.03em;
        }

        .ey-case-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            height: 100%;
            transition: var(--transition);
            border: 1px solid #f0f0f0;
        }

        .ey-case-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .ey-case-img-wrapper {
            width: 100%;
            height: 220px;
            overflow: hidden;
        }

        .ey-case-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1s ease;
        }

        .ey-case-card:hover .ey-case-img {
            transform: scale(1.08);
        }

        .ey-case-body {
            padding: 25px 30px 40px;
        }

        .ey-case-cat {
            color: #009095;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 0.8rem;
            letter-spacing: 1.5px;
            margin-bottom: 20px;
            display: block;
        }

        .ey-case-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 25px;
            line-height: 1.25;
        }

        .ey-case-link {
            color: #009095;
            font-weight: 700;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .ey-case-link:hover {
            gap: 15px;
            color: #00767a;
        }

        /* Related Content - Overlaid Image Card */
        .related-content-block {
            padding-left: 20px;
        }

        .ey-related-card {
            position: relative;

            overflow: hidden;
            height: 600px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-decoration: none !important;
            transition: var(--transition);
        }

        .ey-related-card .rc-bg-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            transition: transform 1.2s ease;
        }

        .ey-related-card:hover .rc-bg-img {
            transform: scale(1.1);
        }

        .ey-related-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.4) 60%, transparent 100%);
            z-index: 1;
        }

        .ey-related-content {
            position: relative;
            z-index: 2;
            padding: 60px 40px;
            color: #ffffff;
        }

        .ey-related-card .rc-title {
            color: #ffffff;
            font-size: 2.6rem;
            font-weight: 900;
            margin-bottom: 25px;
            line-height: 1.1;
            transition: var(--transition);
        }

        .ey-related-card .rc-actions {
            margin-top: 20px;
        }

        .ey-related-card .ey-related-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            padding: 14px 22px;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            transition: all 0.3s ease;
        }

        .ey-related-card .ey-related-btn i {
            transition: transform 0.3s ease;
        }

        .ey-related-card:hover .ey-related-btn {
            background: rgba(255, 255, 255, 0.2);
        }

        .ey-related-card:hover .ey-related-btn i {
            transform: translateX(4px);
        }

        @media (max-width: 991px) {
            .related-content-block {
                padding-left: 0;
                margin-top: 80px;
            }

            .ey-related-card {
                height: 500px;
            }
        }

        .leader-name-bold {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .consultant-info p {
            margin: 0;
            font-size: 0.9rem;
            color: #777;
        }

        /* Bottom Interests */
        .bottom-interests {
            padding: 120px 0;
            background: #ffffff;
        }

        .interest-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .interest-card {
            background: #ffffff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
            transition: all 0.4s ease;
            height: 100%;
        }

        .interest-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            border-color: var(--brand-primary);
        }

        .interest-img-wrapper {
            width: 100%;
            height: 240px;
            overflow: hidden;
        }

        .interest-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .interest-card:hover .interest-img-wrapper img {
            transform: scale(1.1);
        }

        .interest-content {
            padding: 30px;
        }

        .interest-content h4 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a1a1a;
            line-height: 1.4;
        }

        .interest-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #009095;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .interest-meta i {
            transition: transform 0.3s ease;
        }

        .interest-card:hover .interest-meta i {
            transform: translateX(5px);
        }

        @media (max-width: 768px) {
            .bottom-interests {
                padding: 80px 0;
            }
        }

        @media (max-width: 991px) {
            .help-list-section h2 {
                font-size: 2.5rem;
            }

            .transformation-section {
                padding: 30px 0;

            }
            .transformation-section h2 {
                font-size: 2.2rem;
            }
        }

        /* =============================================
               MAGAZINE / INSIGHTS SECTION (SCREENSHOT STYLE)
            ============================================= */
        .magazine-section {
            padding: 50px 0;
            background-color: #f8fafc;
        }

        .mag-container {
            display: flex;
            align-items: stretch;
            gap: 40px;
            width: 1400px;
        }

        /* --- Image Side --- */
        .mag-image-side {
            flex: 0 0 42%;
            max-height: 600px;
            position: relative;
            transition: all 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
            opacity: 1;
        }

        /* --- When no image is available for current slide --- */
        .mag-container.no-img-active .mag-image-side {
            flex: 0 0 0%;
            opacity: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .mag-container.no-img-active {
            gap: 0;
        }

        .mag-image-container {
            width: 100%;
            max-height: 700px;
            border-radius: 25px 25px 25px 25px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .mag-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        /* Image nav buttons */
        .mag-img-nav {
            position: absolute;
            bottom: 28px;
            right: 28px;
            display: flex;
            gap: 12px;
            z-index: 10;
        }

        .mag-img-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #fff;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
        }

        .mag-img-btn:hover {
            background: rgba(255, 255, 255, 0.45);
            transform: scale(1.1);
        }

        /* --- Text Side (Teal Block) --- */
        .mag-content-side {
            flex: 1;
            background-color: #004d4d;
            border-radius: 25px 25px 25px 25px;
            padding: 30px 0 80px 30px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            /* justify-content: center; */
            height: 700px;
        }

        /* Swiper Container */
        .mag-swiper-container {
            /* width: 100%; */
            padding-right: 60px;
            overflow: visible !important;
            /* Allow cards to overflow container */
        }

        /* Continuous sliding effect (Marquee) */
        .mag-swiper-container .swiper-wrapper {
            transition-timing-function: ease-in !important;
            max-height: 85%;
        }

        /* The Cards */
        .mag-card {
            background: #ffffff;
            border-radius: 30px;
            padding: 50px 45px;
            width: 92%;
             height: 100%;
            min-height: 650px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-left: 60px;
        }

        .mag-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        /* Card Eyebrow (Pill) */
        .mag-card-eyebrow {
            display: inline-block;
            background: #e0f2f1;
            color: #00796b;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;

            letter-spacing: 0.15em;
            padding: 8px 22px;
            border-radius: 30px;
            margin-bottom: 30px;
            width: fit-content;

        }



        .mag-card .mag-desc {
            font-family: 'Libre Baskerville', serif;
            font-size: 1.1rem;
            line-height: 1.9;
            color: #444444;
            margin-bottom: 0;
        }



        .mag-control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
        }

        .mag-control-btn:hover {
            background: #ffffff;
            color: #004d4d;
        }

        .modal-dialog-centered{
            margin-top:7vh !important;
        }

        /* --- Responsive --- */
        @media (max-width: 1200px) {
            .mag-image-side {
                flex: 0 0 38%;
            }

            .mag-card {
                padding: 40px 30px;
            }
        }

        @media (max-width: 991px) {
            .mag-container {
                flex-direction: column;
                width: 100%;
                gap: 30px;
            }
            .mag-image-container{
                max-height: 400px;
            }

            .mag-image-side {
                width: 100%;
                height: 450px;
            }

            .mag-content-side {
                padding: 60px 25px;
            }

            .mag-swiper-container {
                overflow: hidden !important;
                touch-action: pan-y;
            }

            .mag-card {
                min-height: auto;
                margin-left: 40px;
            }

            .mag-card-eyebrow {
                font-size: 0.7rem;
                padding: 6px 18px;
            }

            .mag-card .mag-desc {
                font-size: 0.95rem;
                overflow: visible;
            }

        }
    </style>

    <div class="service-page-wrapper">
        <!-- Hero Section -->
        <section class="service-hero">
            <div class="hero-background"
                style="background-image: linear-gradient(to right, rgba(0, 0, 0, 85%), rgba(202, 202, 202, 0.363)),url('{{ $service->hero_image ? asset('public/' . ltrim($service->hero_image, '/')) : (isset($service->images[0]) ? asset('public/' . ltrim($service->images[0]->image, '/')) : asset('public/front/assets/img/hero/service-details-bg.jpg')) }}');">
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $service->name }}</h1>
                        <div class="hero-desc-wrapper">
                            {!! $service->content !!}
                        </div>
                        <div class="hero-actions">
                            <button type="button"
    class="glass-btn"
    id="scrollToHelp">
    <span>Contact Us</span>
    <span class="btn-arrow">→</span>
</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .glass-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;

    padding: 14px 30px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.4px;

    color: #fff;
    background: rgba(255, 255, 255, 0.08);

    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 50px;

    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    cursor: pointer;
    position: relative;
    overflow: hidden;

    transition: all 0.35s ease;
}

/* Glow border on hover */
.glass-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);

    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.15);
}

/* Click press */
.glass-btn:active {
    transform: scale(0.96);
}

/* Icon animation */
.glass-btn i {
    transition: transform 0.3s ease;
}

.glass-btn:hover i {
    transform: translateX(6px);
}

/* Soft inner glow */
.glass-btn::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.25), transparent 60%);
    opacity: 0;
    transition: 0.4s;
}

.glass-btn:hover::after {
    opacity: 1;
}
            </style>
        <script>
            document.getElementById('scrollToHelp').addEventListener('click', function () {
    const target = document.getElementById('how-alpha-can-help');

    if (target) {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
});

document.getElementById('scrollToHelp').addEventListener('click', function () {
    const target = document.getElementById('how-alpha-can-help');

    if (target) {
        const offset = 100; // adjust based on navbar height
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = window.pageYOffset + elementPosition - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
        });
    }
});
</script>



        <!-- Quote Section -->
       <!-- <section class="quote-section" data-aos="fade-up">
    <div class="container">
        <div class="quote-inner">
            {{-- <span class="quote-eyebrow">Our Approach</span> --}}
            <div class="quote-text">
                {!! $service->overview !!}
            </div>
            <div class="quote-divider"></div>
        </div>
    </div>
</section> -->

<!--About category section-->

<section class="premium-intro light-theme">
  <div class="container">
    <div class="intro-layout">
      
      <!-- Left Content -->
      <div class="intro-content">
        <!-- <span class="eyebrow">About {{ $service->categories->first()->name ?? 'This Category' }}</span> -->
         {{-- <span class="eyebrow">ABOUT THIS CATEGORY</span> --}}
         <span class="eyebrow">{{ $service->name }} </span> 
        {{-- <h2 class="display-title">
          UAE regulations are <em>precise</em>. <br>
          <span class="primary-text">Preparation is everything.</span>
        </h2> --}}
        <div class="lead-text">
          {!! $service->overview !!}
        </div>
        
        {{-- <div class="stats-mini">
          <div class="stat-item"><span class="stat-num">400+</span> <small>Approved</small></div>
          <div class="stat-divider"></div>
          <div class="stat-item"><span class="stat-num">100%</span> <small>Compliance</small></div>
        </div> --}}

        <div class="cta-group">
          <a href="#services" class="btn-primary">Explore Services</a>
          {{-- <a href="/about" class="btn-outline">Our Track Record</a> --}}
        </div>
      </div>

      <!-- Right Content: Auto-Scroll -->
    <div class="intro-visual">
        <div class="scroll-wrapper">
            @php
                $tabSlider = $service->ServiceTab->count() > 3;
                $coreHeaders = !empty($service->core_service_header)
                    ? (is_array($service->core_service_header) ? $service->core_service_header : [$service->core_service_header])
                    : [];
                $coreDescriptions = !empty($service->core_service_description)
                    ? (is_array($service->core_service_description) ? $service->core_service_description : [$service->core_service_description])
                    : [];
                $coreSlider = count($coreHeaders) > 3;
                $scrollTrackClass = ($tabSlider || $coreSlider) ? 'scroll-track' : 'scroll-track no-scroll';
            @endphp
          <div class="{{ $scrollTrackClass }}">
            @if($service->ServiceTab->count() > 0)
                @foreach($service->ServiceTab as $index => $tab)
                    <!-- Card {{ $index + 1 }} -->
                    <div class="feature-card">
                      <div class="card-header">
                        <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4>{{ $tab->name }}</h4>
                      </div>
                      <p>{{ $tab->description }}</p>
                    </div>
                @endforeach
                
                @if($tabSlider)
                    @foreach($service->ServiceTab as $index => $tab)
                        <div class="feature-card aria-hidden">
                          <div class="card-header">
                            <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h4>{{ $tab->name }}</h4>
                          </div>
                          <p>{{ $tab->description }}</p>
                        </div>
                    @endforeach
                @endif
            @elseif(!empty($service->core_service_header))
                @foreach($coreHeaders as $index => $header)
                    <div class="feature-card">
                      <div class="card-header">
                        <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4>{{ $header }}</h4>
                      </div>
                      <p>{!! $coreDescriptions[$index] ?? '' !!}</p>
                    </div>
                @endforeach
                @if($coreSlider)
                    @foreach($coreHeaders as $index => $header)
                        <div class="feature-card aria-hidden">
                          <div class="card-header">
                            <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h4>{{ $header }}</h4>
                          </div>
                          <p>{!! $coreDescriptions[$index] ?? '' !!}</p>
                        </div>
                    @endforeach
                @endif
            @else
                <!-- Fallback if no ServiceTabs -->
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">01</span>
                    <h4>Authority Expertise</h4>
                  </div>
                  <p>DOH, DHA, and MOH specific checklists tailored to your specialty mix.</p>
                </div>
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">02</span>
                    <h4>End-to-End Management</h4>
                  </div>
                  <p>We manage submissions, inspector coordination, and follow-ups directly.</p>
                </div>
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">03</span>
                    <h4>Predictable Timelines</h4>
                  </div>
                  <p>Realistic milestones with resubmissions handled at no extra cost.</p>
                </div>
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">04</span>
                    <h4>Integrated Logistics</h4>
                  </div>
                  <p>Coordination with engineering and accreditation for a turnkey launch.</p>
                </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
    :root {
  --primary-teal: #066e78;
  --accent-red: #cf2732;
  --bg-light: #e6e6e6;
  --white: #ffffff;
  --text-dark: #1a2b2c;
  --text-muted: #546263;
}

.premium-intro.light-theme {
  background-color: var(--bg-light);
  color: var(--text-dark);
  padding: 100px 0;
  font-family: 'Inter', sans-serif;
}

.intro-layout {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 80px;
  align-items: center;
}

/* Typography & Colors */
.eyebrow {
  color: var(--accent-red);
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 0.8rem;
  font-weight: 800;
  margin-bottom: 15px;
  display: block;
}

.display-title {
  font-size: clamp(2.2rem, 4vw, 3rem);
  line-height: 1.1;
  font-weight: 700;
  margin-bottom: 25px;
}

.display-title em {
  font-family: 'Libre Baskerville', serif;
  font-style: italic;
  color: var(--primary-teal);
  font-weight: 400;
}

.primary-text {
  color: var(--primary-teal);
}

.lead-text {
  font-size: 1.1rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin-bottom: 35px;
  max-width: 520px;
}

/* Buttons */
.btn-primary {
  background: var(--primary-teal);
  color: white;
  padding: 18px 35px;
  text-decoration: none;
  font-weight: 600;
  border-radius: 4px;
  display: inline-block;
  transition: transform 0.3s ease, background 0.3s ease;
}

.btn-primary:hover {
  background: #04525a; /* Darker Teal */
  color:white;
  transform: translateY(-2px);
}

.btn-outline {
  color: var(--primary-teal);
  text-decoration: none;
  font-weight: 600;
  margin-left: 25px;
  border-bottom: 2px solid transparent;
  transition: 0.3s;
}

.btn-outline:hover {
  border-color: var(--accent-red);
}

/* Auto-Scroll Customization */
.scroll-wrapper {
  height: 450px;
  overflow: hidden;
  position: relative;
  mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent);
}

.scroll-track {
  display: flex;
  flex-direction: column;
  gap: 10px;
  animation: scrollVertical 40s linear infinite;
}

.scroll-track.no-scroll {
  animation: none;
}

.scroll-track:hover {
  animation-play-state: paused;
}

@keyframes scrollVertical {
  0% { transform: translateY(0); }
  100% { transform: translateY(-50%); }
}

/* Cards */
.feature-card {
  /* background: var(--white); */
  padding: 20px;
  /* border-left: 4px solid var(--primary-teal); */
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  transition: 0.3s ease;
}

.feature-card:hover {
  border-left-color: var(--accent-red);
  transform: translateX(10px);
}

.card-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 10px;
}

.card-num {
  font-weight: 800;
  color: #066D77;
  /* color: var(--accent-red); */
  font-size: 0.9rem;
  /* background: rgba(207, 39, 50, 0.1); */
  padding: 4px 8px;
  border-radius: 4px;
}

.feature-card h4 {
  margin: 0;
  color: black;
  /* color: var(--primary-teal); */
  font-size: 1.1rem;
}

.feature-card p {
  font-size: 0.95rem;
  color: var(--text-muted);
  margin: 10px 0 0 50px;
}

/* Stats Section */
.stats-mini {
  display: flex;
  gap: 40px;
  margin-bottom: 40px;
}

.stat-num {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--primary-teal);
  display: block;
}

.stat-divider {
  width: 1px;
  background: #ccc;
  height: 40px;
}
</style>


<!-- ════════════════════════════════════════
  BROWSE ALL CATEGORIES SECTION
════════════════════════════════════════ -->
<section class="browse-section" id="services">
    <div class="container">
  
      @php
        $categoryServices      = $service->services ?? collect();
        $categoryServiceGroups = $service->serviceGroups ?? collect();
        $totalItems            = $categoryServices->count() + $categoryServiceGroups->count();
      @endphp

      <div class="browse-header reveal">
        <div class="browse-header-left">
          <div class="category-info mb-2">
            <p class="category-name-text" style="font-size:1.75rem">
              {{ $service->name }}
              <span class="service-count-badge">{{ $categoryServices->count() }} Services</span>
              @if($categoryServiceGroups->count())
                <span class="service-count-badge" style="background:rgba(0,144,149,.15);color:#009095;margin-left:.4rem">
                  {{ $categoryServiceGroups->count() }} Package{{ $categoryServiceGroups->count() > 1 ? 's' : '' }}
                </span>
              @endif
            </p>
          </div>
          <h2>{{ $service->service_header ?? 'Services in ' . $service->name }}</h2>
          <p>Explore all services and service packages available under this category.</p>
        </div>
        <div class="browse-header-right">
          <a href="{{ route('front.all-services') }}">View All Services &nbsp;→</a>
        </div>
      </div>

      <div class="browse-grid reveal">
        @if($totalItems === 0)
          <div class="browse-card current">
            <div class="browse-card-left">
              <div class="browse-card-icon-wrap">i</div>
              <div class="browse-card-text">
                <div class="browse-card-title">No services added for this category yet.</div>
                <div class="browse-card-count">Please connect services from dashboard.</div>
              </div>
            </div>
          </div>
        @else
          {{-- Individual services --}}
          @foreach($categoryServices as $categoryService)
            <a href="{{ route('front.service', $categoryService->slug) }}" class="browse-card current"
              aria-label="{{ $categoryService->name }}">
              <div class="browse-card-left">
                <div class="browse-card-text">
                  <div class="browse-card-title">{{ $categoryService->name }}</div>
                  <div class="browse-card-count">{!! $service->description !!}</div>
                </div>
              </div>
              <div class="browse-card-arrow-btn">→</div>
            </a>
          @endforeach

          {{-- Service packages / groups --}}
          @foreach($categoryServiceGroups as $group)
            <a href="{{ route('service-packages', $group->slug) }}" class="browse-card current"
              aria-label="{{ $group->name }}"
              style="border-left:3px solid #009095">
              <div class="browse-card-left">
                <div class="browse-card-text">
                  <div class="browse-card-title" style="display:flex;align-items:center;gap:.45rem">
                    <i class="bi bi-collection-fill" style="color:#009095;font-size:.85rem;flex-shrink:0"></i>
                    {{ $group->name }}
                  </div>
                  <div class="browse-card-count" style="color:#009095;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.4px">
                    Service Package
                  </div>
                </div>
              </div>
              <div class="browse-card-arrow-btn">→</div>
            </a>
          @endforeach
        @endif
      </div>
  
    </div>
  </section>
  
  <style>
    /* ── Section wrapper ── */
    .browse-section {
      padding: 72px 0;
      background: #f0f4f8;
      border-top: 1px solid rgba(0, 0, 0, 0.06);
      position: relative;
      overflow: hidden;
    }
  
    /* Soft background orbs */
    .browse-section::before,
    .browse-section::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(80px);
    }
    .browse-section::before {
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(13,183,148,0.10) 0%, transparent 70%);
      top: -120px; left: -100px;
    }
    .browse-section::after {
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
      bottom: -80px; right: -80px;
    }
  
    /* ── Header ── */
    .browse-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 24px;
      flex-wrap: wrap;
      margin-bottom: 40px;
    }
  
    .browse-header-left h2 {
      font-family: 'Outfit', sans-serif;
      font-size: clamp(1.7rem, 2.8vw, 2.3rem);
      color: var(--navy);
      margin-bottom: 8px;
      line-height: 1.15;
    }
  
    .browse-header-left p {
      font-size: .92rem;
      color: var(--gray-600);
      max-width: 520px;
      line-height: 1.65;
    }
  
    .browse-header-right {
      display: flex;
      align-items: center;
      padding-top: 6px;
    }
  
    .browse-header-right a {
      font-family: 'Outfit', sans-serif;
      font-size: .82rem;
      font-weight: 700;
      letter-spacing: .04em;
      color: var(--teal);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 18px;
      border-radius: 30px;
      border: 1.5px solid rgba(13,183,148,0.35);
      background: rgba(13,183,148,0.06);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      transition: all 0.3s ease;
      white-space: nowrap;
    }
  
    .browse-header-right a:hover {
      background: rgba(13,183,148,0.14);
      border-color: var(--teal);
      color: var(--teal);
      box-shadow: 0 0 18px rgba(13,183,148,0.15);
    }

    /* ── Category Info ── */
    .category-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .category-name-text {
      font-family: 'Outfit', sans-serif;
      font-size: .95rem;
      font-weight: 600;
      color: var(--teal);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .service-count-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 4px 12px;
      background: rgba(13,183,148,0.12);
      border-radius: 20px;
      font-size: .85rem;
      font-weight: 700;
      color: var(--teal);
      letter-spacing: .02em;
    }
  
    /* ── Grid ── */
    .browse-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }
  
    /* ── Card ── */
    .browse-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 24px 22px;
      background: rgba(255, 255, 255, 0.55);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.75);
      border-radius: 18px;
      box-shadow:
        0 2px 12px rgba(0, 0, 0, 0.06),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
      text-decoration: none;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: all 0.32s ease;
    }
  
    /* Glass shimmer top-left highlight */
    .browse-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      right: 0; height: 50%;
      /* background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%); */
      border-radius: 18px 18px 0 0;
      pointer-events: none;
    }
  
    /* Teal glow on hover */
    .browse-card::after {
      content: '';
      position: absolute;
      inset: 0;
      /* background: radial-gradient(circle at 30% 50%, rgba(13,183,148,0.08) 0%, transparent 70%); */
      opacity: 0;
      transition: opacity 0.35s ease;
      pointer-events: none;
      border-radius: 18px;
    }
  
    .browse-card:hover {
      background: rgba(255, 255, 255, 0.80);
      border-color: rgba(13,183,148,0.35);
      transform: translateY(-3px);
      box-shadow:
        0 12px 36px rgba(0, 0, 0, 0.10),
        0 0 0 1px rgba(13,183,148,0.15),
        inset 0 1px 0 rgba(255,255,255,1);
    }
  
    .browse-card:hover::after { opacity: 1; }
  
    /* Active / current card */
    .browse-card.current {
      /* background: rgba(13,183,148,0.07); */
      /* border-color: rgba(13,183,148,0.30); */
      /* box-shadow:
        0 4px 20px rgba(13,183,148,0.12),
        inset 0 1px 0 rgba(255,255,255,0.95); */
    }
  
    .browse-card.current::after { opacity: 1; }
  
    /* ── Card left ── */
    .browse-card-left {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      position: relative;
      z-index: 1;
      flex: 1;
      min-width: 0;
    }
  
    /* Optional icon wrap (kept in case you re-enable it) */
    .browse-card-icon-wrap {
      width: 40px; height: 40px;
      border-radius: 10px;
      flex-shrink: 0;
      background: rgba(13,183,148,0.12);
      border: 1px solid rgba(13,183,148,0.22);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 700;
      color: var(--teal);
      transition: all 0.3s ease;
    }
  
    .browse-card:hover .browse-card-icon-wrap,
    .browse-card.current .browse-card-icon-wrap {
      background: rgba(13,183,148,0.20);
      border-color: rgba(13,183,148,0.40);
      box-shadow: 0 0 12px rgba(13,183,148,0.18);
    }
  
    .browse-card-text { min-width: 0; }
  
    .browse-card-title {
      font-family: 'Outfit', sans-serif;
      font-size: .9rem;
      font-weight: 700;
      color: #000000 !important;
      line-height: 1.35;
      margin-bottom: 4px;
      transition: color 0.2s;
    }
  
    .browse-card:hover .browse-card-title,
    .browse-card.current .browse-card-title {
      color: #066D77;
    }
  
    .browse-card-count {
      font-size: .72rem;
      color: #252525de !important;
      font-family: 'Outfit', sans-serif;
      font-weight: 500;
      letter-spacing: 0.02em;
    }
  
    /* ── Arrow button ── */
    .browse-card-arrow-btn {
      width: 36px; height: 36px;
      border-radius: 50%;
      flex-shrink: 0;
      border: 1px solid rgba(0, 0, 0, 0.10);
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      display: flex;
      align-items: center;
      justify-content: center;
      /* color: var(--teal); */
      color: #066D77;
      font-size: 1rem;
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
    }
  
    .browse-card:hover .browse-card-arrow-btn {
      background: #066D77;
      border-color: #066D77;
      /* color: #fff; */
      transform: translateX(3px);
      box-shadow: 0 4px 14px rgba(13,183,148,0.30);
    }
  
    .browse-card.current .browse-card-arrow-btn {
      background: #ffffff;
      border-color: #066D77;
      /* color: #fff; */
      box-shadow: 0 4px 14px rgba(13,183,148,0.25);
    }
  
    /* ── Responsive ── */
    @media (max-width: 900px) {
      .browse-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 540px) {
      .browse-grid { grid-template-columns: 1fr; gap: 12px; }
      .browse-card { padding: 18px; }
    }
  </style>

{{-- <section class="services-section">
  <div class="container">
    <h2 class="section-title">All Services in Facility Licensing & Setup</h2>
    <p>28 specialist services — select a filter or browse all</p>
  </div>

  <div class="services-grid">
    @foreach($service_groups as $group)
        @php
            $bgImage = $group->image ? asset('public/uploads/service_groups/' . $group->image) : '';
        @endphp
        <a href="#" class="service-card {{ $group->is_featured ? 'featured' : '' }}">
          <div class="service-card-bg" style="background-image: url('{{ $bgImage }}');"></div>
          
          @if($group->is_featured)
            <span class="featured-badge">Most Requested</span>
          @endif
          
          <div class="service-card-body">
            <h3 class="service-card-title">{{ $group->name }}</h3>
            <p class="service-card-desc">
                @if($group->is_featured)
                    {{ $group->description }}
                @else
                    {{ Str::limit($group->description, 120) }}
                @endif
            </p>
          </div>
          
          <div class="service-card-footer">
            <span class="service-card-tag">{{ $group->is_featured ? 'Alpha HMC' : 'Service' }}</span>
            <div class="service-card-arrow">→</div>
          </div>
        </a>
    @endforeach

    @if($service_groups->isEmpty())
        <p class="text-muted">No service groups available.</p>
    @endif
  </div>
</section> --}}

{{-- <style>
:root {
  --navy: #0b1f3a;
  --navy-mid: #152c4e;
  --teal: #0a7a6e;
  --gold: #d4af37;
  --white: #ffffff;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-600: #4b5563;
  --radius-md: 12px;
  --transition: all 0.3s ease;
}

/* Section Container */
.services-section {
  max-width: 1200px;
  margin: 0 auto;
  padding: 80px 20px;
  background: var(--white);
}

.section-title {
  font-family: 'Outfit', sans-serif;
  font-size: 2.2rem;
  font-weight: 800;
  color: var(--navy);
  margin-bottom: 8px;
}

/* Service cards grid */
.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
  gap: 16px;
  margin-top: 40px;
}

/* Standard service card */
.service-card {
  background: var(--white);
  border: 1px solid var(--gray-200);
  border-radius: var(--radius-md);
  padding: 28px 24px 22px;
  transition: border-color .3s ease, box-shadow .3s ease, transform .3s ease;
  cursor: pointer; text-decoration: none;
  display: flex; flex-direction: column; gap: 10px;
  position: relative; overflow: hidden;
  min-height: 200px;
}

/* Background image layer — hidden by default, revealed on hover */
.service-card-bg {
  position: absolute; inset: 0; z-index: 0;
  background-size: cover; background-position: center;
  opacity: 0;
  transition: opacity .45s ease;
}
/* Dark gradient overlay that sits on top of the image to keep text legible */
.service-card-bg::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(
    160deg,
    rgba(11, 31, 58, 0.82) 0%,
    rgba(10, 122, 110, 0.70) 100%
  );
}

/* Left accent bar */
.service-card::before {
  content: ''; position: absolute; left: 0; top: 0; bottom: 0;
  width: 4px; background: var(--teal); transform: scaleY(0);
  transform-origin: bottom; transition: transform .35s ease;
  z-index: 2;
}

/* Hover state */
.service-card:hover {
  border-color: var(--teal);
  box-shadow: 0 12px 40px rgba(10,122,110,.22);
  transform: translateY(-4px);
}
.service-card:hover .service-card-bg { opacity: 1; }
.service-card:hover::before { transform: scaleY(1); }

/* On hover — flip text colours to white for contrast over image */
.service-card:hover .service-card-title { color: var(--white); }
.service-card:hover .service-card-desc  { color: rgba(255,255,255,.82); }
.service-card:hover .service-card-tag   { color: rgba(255,255,255,.55); }
.service-card:hover .service-card-footer { border-color: rgba(255,255,255,.18); }

/* All content must sit above the image layer */
.service-card-body,
.service-card-footer { position: relative; z-index: 1; }

.service-card-title {
  font-family: 'Outfit', sans-serif; font-size: 1rem; font-weight: 700;
  color: var(--navy); margin-bottom: 8px; line-height: 1.3;
  transition: color .3s ease;
}
.service-card-desc {
  font-size: .85rem; color: var(--gray-600); line-height: 1.6;
  transition: color .3s ease;
}
.service-card-footer {
  display: flex; align-items: center; justify-content: space-between;
  margin-top: auto; padding-top: 15px; border-top: 1px solid var(--gray-100);
  transition: border-color .3s ease;
}
.service-card-tag {
  font-size: .7rem; font-family: 'Outfit', sans-serif; font-weight: 600;
  letter-spacing: .06em; text-transform: uppercase; color: var(--gray-400);
  transition: color .3s ease;
}
.service-card-arrow {
  width: 32px; height: 32px; border-radius: 50%;
  border: 1.5px solid var(--gray-200);
  display: flex; align-items: center; justify-content: center;
  color: var(--gray-400); font-size: .9rem;
  transition: var(--transition); position: relative; z-index: 1;
}
.service-card:hover .service-card-arrow {
  background: var(--white); border-color: var(--white); color: var(--teal);
}

/* FEATURED card (larger, accent background — always dark, has its own image) */
.service-card.featured {
  background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
  border-color: transparent; color: var(--white);
  grid-column: span 2;
}
.service-card.featured .service-card-bg::after {
  background: linear-gradient(160deg, rgba(11,31,58,.88) 0%, rgba(10,122,110,.65) 100%);
}
.service-card.featured::before { background: var(--gold); }
.service-card.featured .service-card-title { color: var(--white); }
.service-card.featured .service-card-desc  { color: rgba(255,255,255,.65); }
.service-card.featured .service-card-footer { border-color: rgba(255,255,255,.1); }
.service-card.featured .service-card-tag   { color: rgba(255,255,255,.4); }
.service-card.featured .service-card-arrow { border-color: rgba(255,255,255,.25); color: rgba(255,255,255,.7); }
.service-card.featured:hover .service-card-arrow { background: var(--gold); border-color: var(--gold); color: var(--white); }

.featured-badge {
  background: var(--gold);
  color: var(--navy);
  font-size: 10px;
  font-weight: 800;
  text-transform: uppercase;
  padding: 4px 12px;
  border-radius: 20px;
  position: absolute; top: 20px; right: 20px; z-index: 2;
}

/* Featured always shows its bg image at low opacity, full on hover */
.service-card.featured .service-card-bg { opacity: .35; }
.service-card.featured:hover .service-card-bg { opacity: 1; }
</style> --}}


<!--Process Section-->

<style>

  :root {
    --process-bg: #fdfdfd;
    --process-primary: #066D77;
    --process-primary-rgb: 6, 109, 119;
    --process-accent: #cf2732;
    --process-text: #0f172a;
    --process-muted: #64748b;
    --glass-card: rgba(255, 255, 255, 0.45);
    --glass-border: rgba(255, 255, 255, 0.8);
    --inner-glow: inset 0 0 20px rgba(255, 255, 255, 0.5);
    --premium-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.05);
    --hover-shadow: 0 50px 100px -20px rgba(6, 109, 119, 0.12);
  }

  .process-section .container {
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 40px;
  }

  .process-section {
    padding: 80px 0;
    background: var(--process-bg);
    position: relative;
    overflow: hidden;
  }

  /* Animated Background Blobs */
  .blob-container {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
  }

  .blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.4;
    animation: blobFloat 20s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
  }

  .blob-1 {
    width: 400px;
    height: 400px;
    background: rgba(6, 109, 119, 0.1);
    top: -100px;
    left: -100px;
  }

  .blob-2 {
    width: 500px;
    height: 500px;
    background: rgba(207, 39, 50, 0.05);
    bottom: -150px;
    right: -100px;
    animation-delay: -5s;
  }

  @keyframes blobFloat {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(100px, 50px) scale(1.1); }
  }

  .process-header {
    text-align: center;
    margin-bottom: 60px;
    position: relative;
    z-index: 2;
  }

  .process-eyebrow {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 4px;
    color: var(--process-accent);
    display: inline-block;
    margin-bottom: 15px;
    padding: 6px 16px;
    background: rgba(207, 39, 50, 0.05);
    border-radius: 50px;
  }

  .process-title {
    font-size: clamp(2.2rem, 5vw, 3.2rem);
    font-weight: 900;
    color: var(--process-text);
    line-height: 1.1;
    margin-bottom: 20px;
    letter-spacing: -0.03em;
  }

  .process-title em {
    font-style: italic;
    font-family: 'Libre Baskerville', serif;
    font-weight: 400;
    color: var(--process-primary);
    position: relative;
  }

  .process-title em::after {
    content: '';
    position: absolute;
    bottom: 10px;
    left: 0;
    width: 100%;
    height: 12px;
    background: rgba(6, 109, 119, 0.08);
    z-index: -1;
  }

  .process-subtitle {
    font-size: 1.25rem;
    color: var(--process-muted);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.7;
    font-weight: 400;
  }

  .process-grid {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-wrap: nowrap;
    gap: 28px;
    width: fit-content;
    max-width: 100%;
    margin: 0 auto;
    z-index: 2;
  }

  .process-grid::before {
    content: '';
    position: absolute;
    left: 22px;
    right: 22px;
    top: 21px;
    height: 2px;
    background: linear-gradient(90deg, #2e8b80 0%, #c5a560 100%);
    opacity: 0.65;
    z-index: 0;
  }

  .process-card {
    position: relative;
    width: 150px;
    flex: 0 0 150px;
    text-align: center;
    padding: 0 6px;
    background: transparent;
    border: 0;
    box-shadow: none;
    z-index: 1;
  }

  .card-shine,
  .step-icon {
    display: none;
  }

  .step-num-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 16px;
  }

  .step-num {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid #2e8b80;
    background: #f8fbfb;
    color: #2e8b80;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    letter-spacing: 0.02em;
  }

  .process-card h4 {
    font-size: 0.96rem;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.3;
    color: #102a43;
  }

  .process-card p {
    font-size: 0.78rem;
    color: #5b6777;
    line-height: 1.45;
    margin: 0;
  }

  @media (max-width: 1200px) {
    .process-grid {
      flex-wrap: wrap;
      gap: 24px 18px;
      width: 100%;
    }

    .process-grid::before {
      display: none;
    }
  }

  @media (max-width: 768px) {
    .process-section { padding: 90px 0; }

    .process-grid {
      justify-content: center;
    }
  }

  @media (max-width: 560px) {
    .process-grid {
      gap: 18px;
      width: 100%;
    }

    .process-card {
      width: 100%;
      flex: 0 0 100%;
    }
  }
</style>

@php
  $processHeaders = $service->process_header;
  $processDescriptions = $service->process_description;

  if (!is_array($processHeaders)) {
    $decodedHeaders = json_decode($service->process_header, true);
    $processHeaders = is_array($decodedHeaders)
      ? $decodedHeaders
      : (!empty($service->process_header) ? [$service->process_header] : []);
  }

  if (!is_array($processDescriptions)) {
    $decodedDescriptions = json_decode($service->process_description, true);
    $processDescriptions = is_array($decodedDescriptions)
      ? $decodedDescriptions
      : (!empty($service->process_description) ? [$service->process_description] : []);
  }

  $processCount = max(count($processHeaders), count($processDescriptions));
@endphp

@if ($processCount > 0)
  <section class="process-section" id="process-journey">
    <!-- Dynamic Blobs for Premium Background -->
    <div class="blob-container">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
    </div>

    <div class="container">
      <div class="process-header" data-aos="fade-up">
        <span class="process-eyebrow">Our Process</span>
        <h2 class="process-title">From first call to <em>license</em> in hand</h2>
        <p class="process-subtitle">A meticulously structured {{ $processCount }}-phase engagement model designed for absolute precision and regulatory speed.</p>
      </div>

      <div class="process-grid">
        @for ($i = 0; $i < $processCount; $i++)
          <div class="process-card" data-aos="fade-up" data-aos-delay="{{ min(($i + 1) * 100, 500) }}">
            <div class="card-shine"></div>
            <div class="step-num-wrapper">
              <span class="step-num">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
              <div class="step-icon"></div>
            </div>
            <h4>{{ $processHeaders[$i] ?? 'Process Step' }}</h4>
            <p>{!! $processDescriptions[$i] ?? '' !!}</p>
          </div>
        @endfor
      </div>
    </div>
  </section>
@endif

        <!-- Transformation Section (Dark) -->
        {{-- <section class="transformation-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4" data-aos="fade-right">
                        <div class="large-info">
                            {!! $service->info_one !!}
                        </div>
                        
                    </div>
                    <div class="col-lg-7 offset-lg-1" data-aos="fade-left">
                        <div class="transformation-desc-wrapper" id="transformation-desc-wrapper">
                            <div class="transformation-desc">
                                {!! $service->info_two !!}
                            </div>
                        </div>
                        <button class="transformation-read-more" id="transformation-toggle"
                            onclick="toggleTransformationDesc()">
                            Read more <i class="fa-solid fa-arrow-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

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
        </script> --}}

        <!-- Dynamic Magazine / Insights Section -->
        {{-- <section class="magazine-section" id="magazine-insights">
            <div class="container">
                @php
                    $displayMagazines = $service->magazines;
                    if ($displayMagazines->count() == 0) {
                        $displayMagazines = collect([
                            (object) [
                                'title' => 'Strategic Foundation',
                                'description' => 'We begin by understanding the healthcare model, service mix, patient volumes, and future growth strategy. This information is translated into high-level planning concepts.',
                                'image' => 'https://images.unsplash.com/photo-1559839734-2b71f1536785?q=80&w=1200'
                            ],
                            (object) [
                                'title' => 'Strategic Approach',
                                'description' => 'Transform vision into structured, compliant, and scalable healthcare concepts setting the direction for successful projects.',
                                'image' => 'https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=1200'
                            ],
                            (object) [
                                'title' => 'Excellence in Care',
                                'description' => 'Our designs prioritize efficient patient and staff movement, separation of clean and dirty flows, emergency access, and compliance.',
                                'image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=1200'
                            ]
                        ]);
                    }

                    $firstMag = $displayMagazines->first();
                    $hasFirstImg = $firstMag && $firstMag->image;
                @endphp

                <div class="mag-container {{ !$hasFirstImg ? 'no-img-active' : '' }}">

                   
                    <div class="mag-image-side" data-aos="fade-right">
                        <div class="mag-image-container">
                            @php
                                $firstMag = $displayMagazines->first();
                                $firstImg = ($firstMag && $firstMag->image)
                                    ? ((strpos($firstMag->image, 'http') === 0)
                                        ? $firstMag->image
                                        : asset('public/uploads/magazines/' . $firstMag->image))
                                    : '';
                            @endphp
                            <img src="{{ $firstImg }}" alt="Magazine Feature" id="mag-main-image">

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

                    
                    <div class="mag-content-side" data-aos="fade-left">
                        <div class="swiper mag-swiper-container">
                           <div class="swiper-wrapper">
    
    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : asset('public/uploads/magazines/' . $mag->image)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <span class="mag-card-eyebrow">{{ $mag->title }}</span>
                <div class="mag-desc">{!! strip_tags($mag->description) !!}</div>
            </div>
        </div>
    @endforeach

    
    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : asset('public/uploads/magazines/' . $mag->image)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <span class="mag-card-eyebrow">{{ $mag->title }}</span>
                <div class="mag-desc">{!! strip_tags($mag->description) !!}</div>
            </div>
        </div>
    @endforeach

    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : asset('public/uploads/magazines/' . $mag->image)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <span class="mag-card-eyebrow">{{ $mag->title }}</span>
                <div class="mag-desc">{!! strip_tags($mag->description) !!}</div>
            </div>
        </div>
    @endforeach
</div>
                        </div>


                    </div>
                </div>
            </div>
        </section> --}}
{{-- <style>
.mag-swiper-container {
    overflow: hidden;    /* clips any peeking clone slides */
}

.mag-swiper-container .swiper-slide {
    width: 100% !important;   /* force full width per slide */
    flex-shrink: 0;
}
    </style>
        <!-- Slider Script (Screenshot Style) -->
  <script>
document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('mag-main-image');

    // Collect only ORIGINAL images (first set before duplicates)
    const allCards = document.querySelectorAll('.mag-card');
    const totalOriginal = allCards.length / 3;
    const magazineImages = [];
    allCards.forEach((card, i) => {
        if (i < totalOriginal) {
            magazineImages.push(card.getAttribute('data-img') || '');
        }
    });

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
        loop: true,
        loopedSlides: totalOriginal,
        speed: 800,
        grabCursor: true,
        allowTouchMove: true,
        centeredSlides: true,
        watchSlidesProgress: true,
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
        btn.innerHTML = 'Read more <i class="fa-solid fa-arrow-down"></i>';
    } else {
        wrapper.classList.add('expanded');
        btn.innerHTML = 'Read less <i class="fa-solid fa-arrow-up"></i>';
    }
}
</script> --}}


<!-- How Alpha Can Help Section - EY Style Refined -->
        <section class="help-section" id="how-alpha-can-help">
            <div class="container">
                <div class="help-flex">
                    <div class="help-info" data-aos="fade-up">
                        {{-- <h2>How Alpha can help</h2> --}}

                        <div class="help-text-block">
                            {{-- <h4>{{ $service->name }} solution</h4> --}}
                            @if($service->info_three && trim($service->info_three) != '')
                                {!! $service->info_three !!}
                            @else
                                <p>Our multidisciplinary team combines architectural excellence with deep clinical insights to
                                    deliver projects that are not just buildings, but platforms for healing.</p>
                            @endif

                            @if($service->info_four && trim($service->info_four) != '')
                                <div class="mt-4">
                                    {!! $service->info_four !!}
                                </div>
                            @endif
                            <button type="button" class="help-enquiry-btn" data-bs-toggle="modal"
                                data-bs-target="#inquiryModal">
                                Send Enquiry →
                            </button>
                        </div>
                    </div>

                    <div class="leader-sidebar" data-aos="fade-left">
                        <span class="leader-label">Service Leader</span>
                        <hr class="leader-hr">
                        <div class="leader-profile">
                            <img src="{{ isset($service->agent) && $service->agent->image ? asset('public/uploads/agent_images/' . $service->agent->image) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
                                alt="Leader" class="leader-circle-img">
                            <div class="leader-meta">
                                <div class="leader-name-bold">
                                    {{ isset($service->agent) ? ($service->agent->user->first_name . ' ' . $service->agent->user->last_name) : 'Dr. Vikram Singh' }}
                                </div>
                                <div class="leader-job-title">
                                    {{ isset($service->agent) ? $service->agent->title : 'Global Healthcare Leader, Alpha' }}
                                </div>

                                <div class="leader-contact-links">
                                    @php
                                        $agentUser = isset($service->agent) ? $service->agent->user : null;
                                        $phone = $agentUser ? $agentUser->phone : '+971 4 272 4064';
                                        $email = $agentUser ? $agentUser->email : 'info@alphahealth.com';
                                        $whatsappPhone = preg_replace('/[^0-9]/', '', $phone);
                                    @endphp

                                    <a href="tel:{{ $phone }}" class="contact-link-icon" title="Call">
                                        Call
                                    </a>
                                    <a href="javascript:void(0)" class="contact-link-icon" title="Email"
                                        data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                        Email
                                    </a>
                                    <a href="https://wa.me/{{ $whatsappPhone }}" target="_blank"
                                        class="contact-link-icon whatsapp-color" title="WhatsApp">
                                        WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        @php
            $displayFaqs = $service->faqs ?? collect();
        @endphp
        @if ($displayFaqs->count() > 0)
            <!--FAQ Section-->
            <section class="help-list-section" id="faq-section">
                <div class="container">
                    <header class="mb-5" data-aos="fade-up">
                        <h2 class="mb-3">Frequently Asked Questions</h2>
                        <p class="text-muted">Common questions about {{ $service->name }} and our approach.</p>
                    </header>

                    <div class="faq-accordion">
                        @foreach ($displayFaqs as $index => $faq)
                            <div class="faq-item {{ $index === 0 ? 'active' : '' }}" data-aos="fade-up"
                                data-aos-delay="{{ min($index * 100, 500) }}">
                                <button class="faq-header" onclick="toggleFaq(this)">
                                    <h4 class="faq-question">{{ $faq->faq_question }}</h4>
                                    <span class="faq-icon">+</span>
                                </button>
                                <div class="faq-content">
                                    <div class="faq-body">
                                        {!! $faq->faq_answer !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

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

            // Initialize active FAQ content height and icon
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.faq-item.active').forEach(item => {
                    const content = item.querySelector('.faq-content');
                    if (content) content.style.maxHeight = content.scrollHeight + "px";
                    const icon = item.querySelector('.faq-icon');
                    if (icon) icon.textContent = '−';
                });
            });
        </script>

        <!-- Case Studies & Related Content Split Section -->
        <section class="split-section-ey">
            <div class="container">
                <div class="row">
                    <!-- Left Column: Case Studies -->
                    <div class="col-lg-7" data-aos="fade-right">
                        <h2 class="ey-section-title">Case Studies</h2>
                        <div class="row g-4">
                            @foreach ($projects->take(2) as $project)
                                <div class="col-md-6">
                                    <div class="ey-case-card">
                                        <div class="ey-case-img-wrapper">
                                            @if(isset($project->projects_images[0]))
                                                <img src="{{ asset('public/' . $project->projects_images[0]->image) }}"
                                                    class="ey-case-img" alt="{{ $project->name }}">
                                            @endif
                                        </div>
                                        <div class="ey-case-body">
                                            <span class="ey-case-cat">{{ $project->project_category->name }}</span>
                                            <h4 class="ey-case-title">{{ $project->name }}</h4>
                                            <a href="{{ route('front.project_details', $project->slug) }}" class="text-dark fw-bold text-decoration-none">
                                VIEW CASE STUDY →
                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Column: Blog Posts -->
                    <div class="col-lg-5" data-aos="fade-left">
                        <div class="related-content-block">
                            <h2 class="ey-section-title">Related Article</h2>
                            @php
                                $blog = $latest_blogs->first();
                            @endphp
                            @if($blog)
                                <a href="{{ route('front.singleBlog', $blog->slug) }}" class="ey-related-card">
                                    <img src="{{ isset($blog->image) && $blog->image ? asset('public/uploads/blog_images/' . $blog->image) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
                                        class="rc-bg-img" alt="{{ $blog->title }}">

                                    <div class="ey-related-content">
                                        <h3 class="rc-title">{{ $blog->title }}</h3>
                                        <div class="rc-actions">
                                            <span class="ey-related-btn">Read more →</span>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="text-center py-5">
                                    <p class="text-muted">No related content available.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Bottom Interests -->
        <section class="bottom-interests" id="related-services">
            <div class="container">
                <h3 class="mb-5 text-center" style="font-size: 2.8rem; font-weight: 700; color: #1a1a1a;">You might be
                    interested in</h3>
                <div class="row g-4">
                    @php
                        $displayServices = isset($featuredServices) ? $featuredServices->take(3) : collect();
                    @endphp
                    @foreach ($displayServices as $related)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <a href="{{ route('front.service', $related->slug) }}" class="interest-card-link">
                                <div class="interest-card">
                                    <div class="interest-img-wrapper">
                                        <img src="{{ $related->hero_image ? asset('public/uploads/service_images/' . $related->hero_image) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}"
                                            alt="{{ $related->name }}">
                                    </div>
                                    <div class="interest-content">
                                        <span class="text-uppercase small fw-bold text-muted mb-2 d-block">
                                            Featured Service
                                        </span>
                                        <h4>{{ $related->name }}</h4>
                                        <div class="interest-meta">
                                            <span>View Service →</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        @include('front.view.announcement')

        <!-- Inquiry Modal -->
        <div class="modal fade inquiry-modal" id="inquiryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="row g-0">
                        <!-- Left Panel: Brand Context -->
                        <div class="col-lg-4 d-none d-lg-block"
                            style="background: linear-gradient(135deg, #1a1a1a 0%, #000 100%); padding: 50px 20px; color: #fff;">
                            <div class="mb-5">
                                <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Alpha"
                                    style="width: 100px; filter: brightness(0) invert(1);">
                            </div>
                            <h3 class="fw-bold mb-4" style="font-size: 1.8rem; line-height: 1.2;">Elevate your healthcare
                                standards.</h3>
                            <p class="opacity-75 mb-5" style="font-size: 0.95rem; line-height: 1.6;">Our experts are ready
                                to
                                partner with you. Share your requirements and we'll craft a bespoke solution.</p>

                            {{-- Agent info inside modal --}}
                            @if(isset($service->agent))
                                <div class="d-flex align-items-center gap-3 mb-4 p-3"
                                    style="background:rgba(255,255,255,0.1); border-radius:12px;">
                                    <img src="{{ $service->agent->image ? asset('public/uploads/agent_images/' . $service->agent->image) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
                                        alt="Agent"
                                        style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.4);">
                                    <div>
                                        <div style="font-weight:700;font-size:1rem;">
                                            {{ $service->agent->user->first_name . ' ' . $service->agent->user->last_name }}
                                        </div>
                                        <div style="opacity:0.7;font-size:0.85rem;">
                                            {{ $service->agent->title ?? 'Service Leader' }}</div>
                                    </div>
                                </div>
                            @endif

                            <div class="inquiry-steps">
                                <div class="inquiry-step mb-4">
                                    <div class="step-num">STEP 01</div>
                                    <div class="step-text">Contact Information</div>
                                </div>
                                <div class="inquiry-step mb-4 opacity-50">
                                    <div class="step-num">STEP 02</div>
                                    <div class="step-text">Service Selection</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Form -->
                        <div class="col-lg-8 p-4 p-md-5 bg-white position-relative">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-4"
                                data-bs-dismiss="modal" aria-label="Close"></button>

                            <div class="mb-5">
                                <span class="text-uppercase tracking-wider fw-bold text-muted small d-block mb-2">Connect
                                    with us</span>
                                <h2 class="fw-bold text-dark" style="letter-spacing: -0.5px;">Service Inquiry</h2>
                                @if(session('success'))
                                    <div class="alert alert-success mt-3" style="border-radius: 12px;">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>

                            <form id="inquiryForm" action="{{ route('front.inquiry.submit') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="name" class="form-control bg-light border-0"
                                                id="inqName" placeholder="Name" required>
                                            <label for="inqName">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" name="email" class="form-control bg-light border-0"
                                                id="inqEmail" placeholder="Email" required>
                                            <label for="inqEmail">Email Address</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="tel" name="phone" class="form-control bg-light border-0"
                                                id="inqPhone" placeholder="Phone" required>
                                            <label for="inqPhone">Phone Number</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select name="service_id" class="form-select bg-light border-0" id="inqService">
                                                <option selected disabled>Choose a specialization</option>
                                                @foreach($all_services as $s)
                                                    <option value="{{ $s->id }}" {{ $s->id == $service->id ? 'selected' : '' }}>
                                                        {{ $s->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="inqService">Requested Service</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea name="message" class="form-control bg-light border-0"
                                                placeholder="Message" id="inqMessage" style="height: 250px"></textarea>
                                            <label for="inqMessage">How can we help you?</label>
                                        </div>
                                    </div>
                                    <div class="col-12 pt-3">
                                        <button type="submit" class="btn btn-dark w-100 py-3 fw-bold shadow-sm"
                                            style="border-radius: 12px; letter-spacing: 0.5px;">
                                            SEND INQUIRY
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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


                // New 4th Item Slider
                new Swiper('.gallery-slider-4', {
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
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
                });
            }

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
                    const icon = el.querySelector('.faq-icon');
                    if (icon) icon.textContent = '+';
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                    const icon = item.querySelector('.faq-icon');
                    if (icon) icon.textContent = '−';
                }
            }
        </script>
    @endpush
@endsection