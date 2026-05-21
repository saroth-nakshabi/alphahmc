@extends('front/layout-2')

@push('page_title')
    {!! $service->name !!}
@endpush

@push('meta')
    <meta name="description" content="{{ $service->meta_description }}">
    <meta name="keywords" content="{{ $service->meta_keywords }}">
    {{-- Non-blocking Inter font (moved out of body to prevent render-block) --}}
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
    {{-- Preload hero image so browser fetches it at highest priority --}}
    @php
        $heroPreload = $service->hero_image
            ? asset('public/uploads/service_images/' . $service->hero_image)
            : asset('public/front/assets/img/hero/service-details-bg.jpg');
    @endphp
    <link rel="preload" as="image" href="{{ $heroPreload }}" fetchpriority="high">
@endpush

@push('og_tags')
    {{-- <link rel="canonical" href="{{ url('/' . $service->slug) }}"> --}}

    <meta name="author" content="Alpha Health Group">
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="{{ $service->meta_title }}" />
    <meta property="og:description" content="{{ $service->meta_description }}" />
    <meta property="og:image" content="{{ asset('public/uploads/service_images/' . $service->hero_image) }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $service->meta_title }}" />
    <meta name="twitter:description" content="{{ $service->meta_description }}" />
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

        /* ── Bottom breadcrumb (shared style) ─────────────────── */
        .hero-breadcrumb {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            z-index: 10;
        }
        .hero-breadcrumb a {
            color: rgba(255,255,255,0.75) !important;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.25s ease;
        }
        .hero-breadcrumb a:hover { color: #ffffff !important; }
        .hero-breadcrumb .bc-sep {
            color: rgba(255,255,255,0.4);
            margin: 0 8px;
        }
        .hero-breadcrumb .bc-current {
            color: rgba(255,255,255,0.95);
            font-weight: 700;
        }
        @media (max-width: 767px) {
            .hero-breadcrumb { bottom: 24px; font-size: 0.75rem; }
        }

        .hero-title {
            font-size: clamp(3.2rem, 8vw, 5.2rem) !important;
            line-height: 1.05;
            margin-bottom: 25px;
            color: #ffffff !important;
            font-weight: 600 !important;
            letter-spacing: -0.03em;
        }

        .hero-desc-wrapper {
            font-size: 1.25rem;
            color: #ffffff !important;
            max-width: 800px;
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
            gap: 15px;
            align-items: center;
        }

        .contact-link-icon {
            color: #1a1a1a;
            font-size: 1.1rem;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f3f4f6;
        }

        .contact-link-icon:hover {
            background: #2e2e2e;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .contact-link-icon.whatsapp-color:hover {
            background: #25D366;
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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #009095;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .faq-item.active .faq-icon {
            background: #009095;
            color: #ffffff;
            transform: rotate(180deg);
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

        .ey-related-card .rc-desc {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.2rem;
            line-height: 1.5;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .rc-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            font-size: 0.85rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .rc-meta i {
            color: #009095;
        }

        .rc-divider {
            width: 1px;
            height: 12px;
            background: rgba(255, 255, 255, 0.3);
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
            padding-right: 60px;
            /* visible lets the next-card peek on desktop; clipped on mobile via media query */
            overflow: visible !important;
        }

        /* Continuous sliding effect */
        .mag-swiper-container .swiper-wrapper {
            transition-timing-function: ease-in-out !important;
            align-items: stretch; /* all slides same height */
        }

        .mag-swiper-container .swiper-slide {
            height: auto !important; /* stretch to tallest card */
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

        /* Only lift on real pointer/mouse devices — tap on mobile triggers hover
           which moves the card up into the overflow:hidden parent and clips it */
        @media (hover: hover) and (pointer: fine) {
            .mag-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            }
        }

        /* Card Eyebrow */
        .mag-card-eyebrow {
            display: block;
            background: transparent;
            color: #00796b;
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: lowercase;
            letter-spacing: 0;
            padding: 0 0 14px 0;
            border-radius: 0;
            margin-bottom: 20px;
            width: 100%;
            border-bottom: 2px solid #00796b;
        }
        .mag-card-eyebrow::first-letter {
            text-transform: uppercase;
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

            .mag-image-container {
                max-height: 400px;
            }

            .mag-image-side {
                width: 100%;
                height: 450px;
            }

            .mag-content-side {
                /* Let height be driven by the tallest card so nothing gets clipped */
                height: auto;
                min-height: unset;
                padding: 40px 20px 50px 20px;
                /* overflow:hidden is kept so the teal rounded corners work, but
                   because we removed the translateY on touch the card never moves
                   up into the clipping boundary */
                overflow: hidden;
            }

            .mag-swiper-container {
                overflow: hidden !important;
                /* pan-y lets the browser handle vertical page scroll;
                   Swiper handles horizontal swipe — prevents touch-event block */
                touch-action: pan-y;
                /* Remove desktop peek-through padding — no room on mobile */
                padding-right: 0;
            }

            .mag-card {
                /* Uniform height across all slides so the teal container
                   doesn't collapse/expand as you swipe */
                min-height: 380px;
                /* Remove the desktop left-margin that shifts cards off-screen */
                margin-left: 0;
                width: 100%;
                border-radius: 20px;
                padding: 32px 24px;
                /* Prevent any residual translate on tap */
                transform: none !important;
                transition: box-shadow 0.3s ease;
            }

            .mag-card-eyebrow {
                font-size: 1rem;
                padding: 0 0 12px 0;
                margin-bottom: 16px;
            }

            .mag-card .mag-desc {
                font-size: 0.95rem;
                line-height: 1.75;
                overflow: visible;
            }
        }

        @media (max-width: 575px) {
            .mag-card {
                min-height: 320px;
                padding: 28px 20px;
            }
        }
    </style>

    <div class="service-page-wrapper">
        <!-- Hero Section -->
        <section class="service-hero">
            <div class="hero-background"
                style="background-image: linear-gradient(to right, rgba(0, 0, 0, 85%), rgba(202, 202, 202, 0.363)),url('{{ $service->hero_image ? asset('public/uploads/service_images/' . $service->hero_image) : (isset($service->images[0]) ? asset('public/uploads/service_images/' . $service->images[0]->image) : asset('public/front/assets/img/hero/service-details-bg.jpg')) }}');">
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $service->name }}</h1>
                        <div class="hero-desc-wrapper">
                            {!! $service->content !!}
                        </div>
                        <div class="hero-meta mt-4" style="display: flex; gap: 25px; font-size: 0.9rem; color: rgba(255,255,255,0.8); border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; max-width: 500px;">
                            <span title="Date this service was listed">Published: {{ ($service->published_date ?? $service->created_at)->format('M d, Y') }}</span>
                            <span title="Date this service was last modified"> Updated: {{ ($service->updated_date ?? $service->updated_at)->format('M d, Y') }}</span>
                        </div>
                        <div class="hero-actions">
                            <button type="button"
    class="glass-btn"
    id="scrollToHelp">
    <span>Contact Us</span>
    <i class="fa-solid fa-arrow-right"></i>
</button>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="hero-breadcrumb" aria-label="Breadcrumb">
                <div class="container">
                    <a href="{{ route('home') }}">Home</a>
                    <span class="bc-sep">›</span>
                    <a href="{{ url('/services') }}">Services</a>
                    <span class="bc-sep">›</span>
                    <span class="bc-current">{{ $service->name }}</span>
                </div>
            </nav>
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

    top:25px;

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
       <section class="quote-section" data-aos="fade-up">
    <div class="container">
        <div class="quote-inner">
            {{-- <span class="quote-eyebrow">Our Approach</span> --}}
            <div class="quote-text">
                {!! $service->overview !!}
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
                            {!! $service->info_one !!}
                        </div>
                        {{-- Left Read more removed as requested --}}
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
        </script>

        <!-- Dynamic Magazine / Insights Section -->
        <section class="magazine-section" id="magazine-insights">
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

                    <!-- Left Side: Large Image -->
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
                : asset('public/uploads/magazines/' . $mag->image)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <span class="mag-card-eyebrow">{{ $mag->title }}</span>
                <div class="mag-desc">{!! strip_tags($mag->description) !!}</div>
            </div>
        </div>
    @endforeach

    {{-- Duplicate slides so loop never runs dry (repeat twice) --}}
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
                        <div class="swiper-pagination mag-pagination"></div>
                    </div>
                </div>
            </div>
        </section>
<style>
.mag-swiper-container {
    overflow: hidden;    /* clips any peeking clone slides */
}

.mag-swiper-container .swiper-slide {
    width: 100% !important;   /* force full width per slide */
    flex-shrink: 0;
}

/* Pagination dots */
.mag-pagination {
    margin-top: 20px;
    text-align: center;
    position: static !important;
    bottom: auto !important;
}
.mag-pagination .swiper-pagination-bullet {
    width: 8px;
    height: 8px;
    background: rgba(255, 255, 255, 0.4);
    opacity: 1;
    transition: all 0.3s ease;
}
.mag-pagination .swiper-pagination-bullet-active {
    background: #ffffff;
    width: 24px;
    border-radius: 4px;
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
        btn.innerHTML = 'Read more <i class="fa-solid fa-arrow-down"></i>';
    } else {
        wrapper.classList.add('expanded');
        btn.innerHTML = 'Read less <i class="fa-solid fa-arrow-up"></i>';
    }
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
                                <i class="fas fa-envelope me-2"></i> Send Enquiry
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
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="contact-link-icon" title="Email"
                                        data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="https://wa.me/{{ $whatsappPhone }}" target="_blank"
                                        class="contact-link-icon whatsapp-color" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


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
                                <h4 class="faq-question">{{ $faq->faq_question }}</h4>
                                <span class="faq-icon"><i class="fa-solid fa-chevron-down"></i></span>
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



        {{-- ── Testimonials (conditional) ────────────────────── --}}
        @if($service->show_testimonials)
            @include('front.partials.testimonial-pills')
        @endif

        <!-- Bottom Interests -->
        <section class="bottom-interests">
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
        };
    </script>
    @endpush
@endsection