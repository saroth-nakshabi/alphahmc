@extends('front/layout-2')

@push('page_title', 'Healthcare Management Updates & Insights | Alpha Health Group')

@section('meta_description', 'Explore healthcare management updates, leadership guides, DOH compliance insights, and operational excellence strategies from Alpha Health Group experts in the UAE and GCC.')

@push('meta')
    <meta name="keywords" content="healthcare management updates, healthcare leaders guide, DOH compliance UAE, healthcare quality assurance, GCC healthcare insights, hospital management, healthcare consulting UAE, patient safety, healthcare operational excellence, Alpha Health Group blog">
    <meta name="author" content="Alpha Health Group">
    <meta name="robots" content="index, follow">
@endpush

@push('og_tags')
    <meta property="og:title" content="Healthcare Management Updates & Insights | Alpha Health Group" />
    <meta property="og:description" content="Expert articles and guides on healthcare management, DOH compliance, leadership strategies, and operational excellence for healthcare professionals across the UAE and GCC." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/healthcare-management-update-insights') }}" />
    <meta name="twitter:title" content="Healthcare Management Updates & Insights | Alpha Health Group" />
    <meta name="twitter:description" content="Expert articles and guides on healthcare management, DOH compliance, leadership strategies, and operational excellence across the UAE and GCC." />
@endpush

@section('content')
    <style>
        :root {
            --primary-navy: #003358;
            --accent-orange: #ff8d1b;
            --text-gray: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        /* HERO SECTION (MATCHING PROJECTS PAGE) */
        .blog-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.037), rgb(0, 0, 0)), url('{{ asset('public/front-new/assets/images/group-concentrated-surgical-doctor-team-260nw-2573615859.webp') }}');
            background-size: cover;
            background-position: center;
            position: relative;
            margin-top: -180px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 220px;
            color: white;
            text-align: left;
        }

        .hero-title {
            font-size: 5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 25px;
            letter-spacing: -2px;
            line-height: 1.1;
            max-width: 900px;
            font-family: 'Playfair Display', serif;
        }

        .hero-description {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 650px;
            margin-bottom: 40px;
            line-height: 1.6;
            font-weight: 400;
        }

        .hero-btn {
            background-color: white;
            color: black;
            padding: 15px 40px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .hero-btn:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            color: black;
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

        @media (max-width: 768px) {
            .blog-hero {
                min-height: 600px;
                margin-top: -85px;
                padding-top: 100px;
            }

            .hero-title {
                font-size: 3rem;
            }

            .hero-description {
                font-size: 1.1rem;
            }
        }

        /* BLOG SUMMARY (MATCHING PROJECT SUMMARY) */
        .blog-page-wrapper {
            background-color: #f8fafc;
            padding-bottom: 50px;
        }

        .blog-summary-section {
            padding: 120px 0 60px;
            background: transparent;
            margin-top: -80px;
        }

        .summary-banner {
            background: #aaffe5;
            display: flex;
            align-items: center;
            padding: 100px 5%;
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.05);
            position: relative;
            gap: 60px;
            min-height: 550px;

            z-index: 10;
        }

        .banner-left {
            flex: 0 0 45%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .banner-left img {
            width: 100%;
            max-width: 380px;
        }

        .banner-right {
            flex: 1;
            padding-right: 5%;
        }

        .summary-title {
            font-family: 'Outfit', sans-serif;
            font-size: 5.5rem;
            color: #1a3b5d;
            margin-bottom: 30px;
            font-weight: 800;
            letter-spacing: -2px;
            line-height: 1.1;
        }

        .summary-content p {
            font-size: 1.45rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 0;
            max-width: 850px;
            font-weight: 400;
        }

        /* FEATURED ARTICLE (MATCHING FEATURED PROJECT) */
        .featured-article-section {
            padding: 40px 0 100px;
            background: transparent;
        }

        .featured-box {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: stretch;
            min-height: 650px;
        }

        .featured-image-side {
            flex: 0 0 55%;
            position: relative;
            overflow: hidden;
            background: #f8fafc;
        }

        .featured-image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .featured-box:hover .featured-image-side img {
            transform: scale(1.05);
        }

        .featured-content-side {
            flex: 1;
            padding: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .featured-badge {
            background: #ff8d1b;
            color: #fff;
            padding: 6px 18px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 25px;
            width: fit-content;
        }

        .featured-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.2rem;
            color: #0f172a;
            font-weight: 800;
            margin-bottom: 25px;
            line-height: 1.1;
        }

        .featured-desc {
            font-size: 1.15rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .featured-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 45px;
            padding-top: 35px;
            border-top: 1px solid #f1f5f9;
        }

        .meta-item h4 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .meta-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .meta-list li {
            font-size: 1.05rem;
            color: #1e293b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .meta-list li i {
            color: #ff8d1b;
            margin-right: 12px;
            font-size: 0.9rem;
        }

        .tag-highlights {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .tag-highlight-item {
            background: #f1f5f9;
            color: #334155;
            padding: 6px 15px;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-featured-cta {
            display: inline-flex;
            align-items: center;
            gap: 15px;
            padding: 18px 40px;
            background: #0f172a;
            color: #ffffff;
            text-decoration: none !important;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: fit-content;
        }

        .btn-featured-cta:hover {
            background: #1e293b;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
            color: #fff;
        }

        /* BLOG GRID CARDS */
        .blog-listing-section {
            padding: 80px 0 100px;
            background: white;
        }

        .listing-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .listing-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 12px;
        }

        .listing-header p {
            font-size: 1.05rem;
            color: var(--text-gray);
            max-width: 520px;
            margin: 0 auto;
        }

        .blog-filter-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 60px;
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 4px;
        }

        .blog-filter-nav::-webkit-scrollbar {
            display: none;
        }

        .blog-filter-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 9px 22px;
            border-radius: 100px;
            font-weight: 700;
            color: var(--primary-navy);
            transition: var(--transition);
            cursor: pointer;
            font-size: 0.82rem;
            letter-spacing: 0.4px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .blog-filter-btn.active,
        .blog-filter-btn:hover {
            background: var(--primary-navy);
            color: white;
            border-color: var(--primary-navy);
            box-shadow: 0 8px 20px rgba(0, 51, 88, 0.15);
            transform: translateY(-1px);
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .blog-card-premium {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid #f1f5f9;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .blog-card-premium:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(0, 51, 88, 0.08);
            border-color: #e2e8f0;
        }

        .blog-card-img {
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .blog-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.2s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .blog-card-premium:hover .blog-card-img img {
            transform: scale(1.1);
        }

        .blog-tag-badge {
            position: absolute;
            top: 25px;
            left: 25px;
            background: var(--accent-orange);
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
        }

        .blog-card-body {
            padding: 28px 28px 28px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .blog-card-body h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 20px;
            line-height: 1.3;
            transition: var(--transition);
        }

        .blog-card-premium:hover .blog-card-body h3 {
            color: var(--accent-orange);
        }

        .blog-excerpt {
            color: var(--text-gray);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 25px;
            opacity: 0.85;
        }

        .blog-meta-minimal {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .meta-author-row {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .meta-date-row {
            font-size: 0.75rem;
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .meta-dot-small {
            width: 3px;
            height: 3px;
            background: #cbd5e1;
            border-radius: 50%;
        }

        .btn-read-more {
            margin-top: auto;
            color: var(--primary-navy);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: var(--transition);
            padding-top: 20px;
            border-top: 1px solid #f8fafc;
        }

        .btn-read-more i {
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .btn-read-more:hover {
            color: var(--accent-orange);
            gap: 18px;
        }

        /* CTA SECTION */
        .conversation-cta-section {
            position: relative;
            padding: 100px 0;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
            overflow: hidden;
            margin: 0;
        }

        .cta-msg {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            line-height: 1.4;
            font-weight: 600;
            max-width: 900px;
            margin: 0 auto 40px;
            color: #0f172a;
        }

        .cta-msg span {
            color: #066D77;
            font-weight: 800;
        }

        .btn-conversation {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 44px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 100px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.9rem;
            border: 2px solid #0f172a;
            cursor: pointer;
        }

        .btn-conversation:hover {
            background: transparent;
            color: #0f172a;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        }

        /* RECENT PROJECTS (FOOTER SECTION) */
        .blog-projects-section {
            padding: 120px 0;
            background: #f8fafc;
        }

        .projects-split-wrapper {
            display: flex;
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.06);
            background: white;
        }

        .projects-info-panel {
            flex: 0 0 38%;
            background: #0f172a;
            color: white;
            padding: 100px 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .projects-info-panel h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 30px;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .projects-info-panel p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 50px;
        }

        .btn-projects-view {
            background: white;
            color: #0f172a;
            padding: 18px 45px;
            text-decoration: none;
            font-weight: 800;
            width: fit-content;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
        }

        .btn-projects-view:hover {
            background: var(--accent-orange);
            color: white;
            transform: scale(1.05);
        }

        .projects-list-panel {
            flex: 1;
            padding: 80px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            justify-content: center;
        }

        .project-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px 0;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none !important;
            color: inherit;
            transition: var(--transition);
        }

        .project-row-item:last-child {
            border-bottom: none;
        }

        .project-row-item h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-navy);
            margin: 0 0 10px 0;
            transition: var(--transition);
        }

        .project-row-item:hover h4 {
            color: var(--accent-orange);
        }

        .project-row-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.9rem;
            color: #94a3b8;
            font-weight: 600;
        }

        .meta-dot {
            width: 5px;
            height: 5px;
            background: var(--accent-orange);
            border-radius: 50%;
        }

        .project-row-thumb {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            overflow: hidden;
            flex-shrink: 0;
            margin-left: 40px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .project-row-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .project-row-item:hover .project-row-thumb img {
            transform: scale(1.1);
        }

        /* INQUIRY MODAL STYLES */
        .inquiry-modal .modal-content {
            border-radius: 24px !important;
            overflow: hidden;
            border: none;
        }

        .inquiry-modal .step-num {
            font-size: 0.75rem;
            font-weight: 800;
            color: #ff8d1b;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .inquiry-modal .step-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
        }

        .inquiry-modal .form-control,
        .inquiry-modal .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 12px !important;
            padding: 1.2rem 1rem !important;
            background-color: #f8fafc !important;
            height: auto !important;
        }

        .inquiry-modal .form-control:focus,
        .inquiry-modal .form-select:focus {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 4px rgba(255, 141, 27, 0.1);
            background-color: white !important;
        }

        .inquiry-modal .btn-close {
            background-color: #f1f5f9;
            padding: 1rem;
            border-radius: 50%;
            z-index: 10;
        }

        @media (max-width: 1199px) {
            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .projects-info-panel {
                padding: 60px 40px;
                flex: 0 0 45%;
            }

            .projects-list-panel {
                padding: 40px;
            }
        }

        @media (max-width: 991px) {
            .blog-hero h1 {
                font-size: 3.5rem;
            }

            .summary-grid {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .summary-title-card {
                padding: 40px;
            }

            .featured-box {
                flex-direction: column;
                min-height: unset;
            }

            .featured-image-side {
                flex: none;
                width: 100%;
                height: 340px;
            }

            .featured-content-side {
                padding: 50px;
            }

            .featured-title {
                font-size: 2.2rem;
            }

            .projects-split-wrapper {
                flex-direction: column;
            }

            .projects-info-panel {
                flex: none;
            }
        }

        @media (max-width: 767px) {
            .blog-grid {
                grid-template-columns: 1fr;
            }

            .blog-hero {
                padding: 140px 0 80px;
            }

            .cta-msg {
                font-size: 1.8rem;
            }

            .project-row-item h4 {
                font-size: 1.4rem;
            }

            .project-row-thumb {
                width: 80px;
                height: 80px;
                margin-left: 20px;
            }

            .featured-image-side {
                height: 260px;
            }

            .featured-content-side {
                padding: 32px 24px;
            }

            .featured-title {
                font-size: 1.8rem;
                margin-bottom: 16px;
            }

            .featured-desc {
                font-size: 1rem;
                margin-bottom: 28px;
            }

            .featured-meta-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                margin-bottom: 30px;
                padding-top: 24px;
            }

            .btn-featured-cta {
                padding: 14px 28px;
                font-size: 0.9rem;
                gap: 10px;
            }
        }
        @media (max-width: 479px) {
            .featured-image-side { height: 220px; }
            .featured-title      { font-size: 1.55rem; }
            .featured-badge      { font-size: 0.7rem; padding: 5px 14px; margin-bottom: 18px; }
        }

        /* ── Blog listing mobile ───────────────────────────────── */
        @media (max-width: 767px) {
            .blog-listing-section { padding: 52px 0 60px; }
            .listing-header { margin-bottom: 32px; }
            .listing-header h2 { font-size: 1.9rem; }
            .listing-header p  { font-size: 0.95rem; }

            .blog-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .blog-card-img   { height: 210px; }
            .blog-card-body  { padding: 22px 20px; }
            .blog-card-body h3 { font-size: 1.2rem; }
            .blog-excerpt    { font-size: 0.9rem; }

            .cta-msg { font-size: 1.5rem; }
            .btn-conversation { padding: 14px 28px; font-size: 0.8rem; }

            /* projects split */
            .projects-split-wrapper { flex-direction: column; border-radius: 24px; }
            .projects-info-panel    { padding: 36px 28px; }
            .projects-info-panel h2 { font-size: 2rem; }
            .projects-list-panel    { padding: 24px 20px; }
            .project-row-item h4    { font-size: 1.1rem; }
            .project-row-thumb      { width: 72px; height: 72px; margin-left: 14px; border-radius: 12px; }
        }

        /* ── Hero mobile ───────────────────────────────────────── */
        @media (max-width: 767px) {
            .blog-hero { padding-top: 140px; margin-top: -85px; min-height: 70vh; }
            .hero-title { font-size: 2.4rem; letter-spacing: -1px; }
            .hero-description { font-size: 1rem; }
            .hero-breadcrumb { bottom: 24px; font-size: 0.75rem; }
        }
    </style>

    {{-- HERO SECTION (MATCHING PROJECTS PAGE) --}}
    <section class="blog-hero">
        <div class="container">
            <div class="banner-text">
                <h1 class="hero-title">Healthcare Management Updates & Insights</h1>
                <p class="hero-description">
                    Your go-to resource for healthcare leaders — covering management updates, DOH compliance guidance, operational excellence strategies, and industry trends shaping healthcare across the UAE and GCC.
                </p>
                <a href="#" class="hero-btn" data-bs-toggle="modal" data-bs-target="#inquiryModal">Contact Us</a>
            </div>
            <nav class="hero-breadcrumb container" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="bc-sep">›</span>
                <a href="#">Insights</a>
                <span class="bc-sep">›</span>
                <span class="bc-current">Healthcare Management Updates</span>
            </nav>
        </div>
    </section>

    <div class="blog-page-wrapper">
        {{-- BLOG SUMMARY (MATCHING PROJECT SUMMARY) --}}
        {{--<section class="blog-summary-section">
            <div class="container-fluid p-0">
                <div class="summary-banner">
                    <div class="banner-left">
                        <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Alpha Logo">
                    </div>
                    <div class="banner-right">
                        <h2 class="summary-title">Blog Summary</h2>
                        <div class="summary-content">
                            <p>
                                At Alpha Innovations, we believe in the power of shared knowledge. Our blog serves as
                                a definitive resource for global business leaders, technology pioneers, and
                                healthcare professionals seeking to navigate the complexities of
                                a rapidly evolving digital world with precision and expertise.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>--}}

        {{-- FEATURED ARTICLE (MATCHING FEATURED PROJECT) --}}
        @php
            $featuredBlog = $blogs->where('featured', 1)->first() ?? $blogs->first();
        @endphp
        @if($featuredBlog)
            <section class="featured-article-section">
                <div class="container">
                    <div class="featured-box">
                        <div class="featured-image-side">
                            <img src="{{ $featuredBlog->image ? asset('public/uploads/blog_images/' . $featuredBlog->image) : asset('public/front-new/assets/images/section-3-1st-image.jpg') }}"
                                alt="{{ $featuredBlog->title }}">
                        </div>
                        <div class="featured-content-side">
                            <span class="featured-badge">Featured Insight</span>
                            <h2 class="featured-title">{{ $featuredBlog->title }}</h2>
                            <p class="featured-desc">
                                {{ Str::limit(strip_tags($featuredBlog->description), 220) }}
                            </p>

                            <div class="featured-meta-grid">
                                <div class="meta-item">
                                    <h4>Insight Focus</h4>
                                    <ul class="meta-list">
                                        <li><i class="fas fa-check-circle"></i> Industry Trends</li>
                                        <li><i class="fas fa-check-circle"></i> Strategic Growth</li>
                                        <li><i class="fas fa-check-circle"></i> Expert Analysis</li>
                                    </ul>
                                </div>
                                <div class="meta-item">
                                    <h4>Author</h4>
                                    <div class="meta-list">
                                        <li style="font-size: 1.2rem; color: var(--primary-navy);"><i class="far fa-user"></i> {{ $featuredBlog->tags->first()->name ?? 'Alpha Team' }}</li>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <h4>Subject Tags</h4>
                                    <div class="tag-highlights">
                                        @foreach($featuredBlog->tags->take(4) as $tag)
                                            <span class="tag-highlight-item">{{ $tag->name }}</span>
                                        @endforeach
                                        @if($featuredBlog->tags->isEmpty())
                                            <span class="tag-highlight-item">Innovation</span>
                                            <span class="tag-highlight-item">Global Economy</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('front.singleBlog', $featuredBlog->slug) }}" class="btn-featured-cta">
                                Read Full Insight <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- BLOG GRID LISTING --}}
        <section class="blog-listing-section">
            <div class="container">
                <div class="listing-header">
                    <h2>Healthcare Management Updates</h2>
                    <p>Browse expert articles, leadership guides, and healthcare management insights filtered by your areas of interest.</p>
                </div>

                {{-- Filter Navigation --}}
                <div class="blog-filter-nav">
                    <button class="blog-filter-btn active" data-filter="all">All Post Collections</button>
                    @foreach($tags as $tag)
                        <button class="blog-filter-btn" data-filter="{{ strtolower($tag->name) }}">{{ $tag->name }}</button>
                    @endforeach
                </div>

                {{-- Grid --}}
                <div class="blog-grid" id="blogContainer">
                    @forelse($blogs as $blog)
                        <div class="blog-card-item" data-tags="{{ strtolower($blog->tags->pluck('name')->implode(' ')) }}">
                            <div class="blog-card-premium">
                                <div class="blog-card-img">
                                    @if($blog->tags->first())
                                        <span class="blog-tag-badge">{{ $blog->tags->first()->name }}</span>
                                    @endif
                                    <img src="{{ $blog->image ? asset('public/uploads/blog_images/' . $blog->image) : asset('public/front-new/assets/images/blog_images/blog-card-image-01.webp') }}"
                                        alt="{{ $blog->title }}">
                                </div>
                                <div class="blog-card-body">
                                    <h3>{{ $blog->title }}</h3>
                                    <div class="blog-meta-minimal">
                                        <div class="meta-author-row">
                                            <i class="far fa-user" style="color: var(--accent-orange); font-size: 0.8rem;"></i>
                                            By {{ $blog->tags->first()->name ?? 'Alpha Team' }}
                                        </div>
                                        <div class="meta-date-row">
                                            <span>Published: {{ $blog->created_at->format('M d, Y') }}</span>
                                            <div class="meta-dot-small"></div>
                                            <span>Updated: {{ $blog->updated_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <p class="blog-excerpt">
                                        {{ Str::limit(strip_tags($blog->description), 130) }}
                                    </p>
                                    <a href="{{ route('front.singleBlog', $blog->slug) }}" class="btn-read-more">
                                        Read Insight <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Stay tuned! We are currently curating more insights for you.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- CONVERSATION CTA --}}
        <section class="conversation-cta-section">
            <div class="container">
                <p class="cta-msg">
                    Inquisitive about how <span>Alpha Innovations</span> can elevate your <span>healthcare or technology
                        systems</span>? Let’s collaborate on your next breakthrough.
                </p>
                <button type="button" class="btn-conversation" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                    Connect With An Expert
                </button>
            </div>
        </section>

        {{-- RECENT PROJECTS SECTION --}}
        <section class="blog-projects-section">
            <div class="container">
                <div class="projects-split-wrapper">
                    {{-- Left Info Panel --}}
                    <div class="projects-info-panel">
                        <h2>Global Impact</h2>
                        <p>
                            Discover how we turn strategy into reality. Explore our recent case studies and project
                            milestones that demonstrate our commitment to excellence and innovation.
                        </p>
                        <a href="{{ route('front.project') }}" class="btn-projects-view">Explore Case Studies</a>
                    </div>

                    {{-- Right Projects List --}}
                    <div class="projects-list-panel">
                        @forelse($projects as $project)
                            <a href="{{ route('front.project_details', $project->id) }}" class="project-row-item">
                                <div class="project-row-content">
                                    <h4>{{ $project->name }}</h4>
                                    <div class="project-row-meta">
                                        <span
                                            style="color: var(--accent-orange);">{{ $project->project_category->name ?? 'Enterprise Solution' }}</span>
                                        <span class="meta-dot"></span>
                                        <span>Recent Achievement</span>
                                    </div>
                                </div>
                                <div class="project-row-thumb">
                                    @if($project->projects_images->first())
                                        <img src="{{ asset($project->projects_images->first()->image) }}" alt="Impact">
                                    @else
                                        <img src="{{ asset('public/front-new/assets/images/section-3-1st-image.jpg') }}"
                                            alt="Impact">
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-muted">Project archives currently being updated.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- JavaScript Filtering --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterBtns = document.querySelectorAll('.blog-filter-btn');
                const blogCards = document.querySelectorAll('#blogContainer > .blog-card-item');

                filterBtns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        // Update active class
                        filterBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');

                        const filter = this.getAttribute('data-filter').toLowerCase();

                        blogCards.forEach(card => {
                            const tags = card.getAttribute('data-tags').toLowerCase();
                            if (filter === 'all' || tags.includes(filter)) {
                                card.style.display = 'block';
                                setTimeout(() => {
                                    card.style.opacity = '1';
                                    card.style.transform = 'translateY(0)';
                                }, 50);
                            } else {
                                card.style.opacity = '0';
                                card.style.transform = 'translateY(20px)';
                                setTimeout(() => {
                                    card.style.display = 'none';
                                }, 300);
                            }
                        });
                    });
                });
            });
        </script>

    </div>

    <!-- Inquiry Modal (Consistent with Projects Page) -->
    <div class="modal fade inquiry-modal" id="inquiryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="row g-0">
                    <div class="col-lg-4 d-none d-lg-block"
                        style="background: linear-gradient(135deg, #0f172a 0%, #000 100%); padding: 60px 45px; color: #fff;">
                        <div class="mb-5">
                            <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Alpha"
                                style="width: 110px; filter: brightness(0) invert(1);">
                        </div>
                        <h3 class="fw-bold mb-4" style="font-size: 2rem; line-height: 1.2;">Innovation meets Expertise.</h3>
                        <p class="opacity-75 mb-5" style="font-size: 1.1rem; line-height: 1.6; font-weight: 300;">Ready to
                            accelerate your journey? Our experts are here to collaborate and innovate.</p>

                        <div class="inquiry-steps">
                            <div class="inquiry-step mb-4">
                                <div class="step-num" style="color: var(--accent-orange);">STEP 01</div>
                                <div class="step-text" style="font-size: 1.1rem; color: white;">Contact Details</div>
                            </div>
                            <div class="inquiry-step mb-4 opacity-50">
                                <div class="step-num" style="color: var(--accent-orange);">STEP 02</div>
                                <div class="step-text" style="font-size: 1.1rem; color: white;">Strategic Goals</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 p-5 bg-white position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal"
                            aria-label="Close"></button>

                        <div class="mb-5">
                            <span class="text-uppercase tracking-wider fw-bold text-muted small d-block mb-2"
                                style="letter-spacing: 2px;">Alpha Connect</span>
                            <h2 class="fw-bold text-dark" style="letter-spacing: -1px; font-size: 2.2rem;">Start a
                                Conversation</h2>
                        </div>

                        <form action="{{ route('front.inquiry.submit') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" class="form-control" id="inqName"
                                            placeholder="Full Name" required>
                                        <label for="inqName">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" class="form-control" id="inqEmail"
                                            placeholder="Email" required>
                                        <label for="inqEmail">Email Address</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select name="service_id" class="form-select" id="inqService">
                                            <option selected disabled>Select an area of interest</option>
                                            @if(isset($all_services))
                                                @foreach($all_services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="inqService">Expertise Needed</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="message" class="form-control" placeholder="Tell us more..."
                                            id="inqMessage" style="height: 140px"></textarea>
                                        <label for="inqMessage">Your specific requirements</label>
                                    </div>
                                </div>
                                <div class="col-12 pt-4">
                                    <button type="submit" class="btn btn-dark w-100 py-4 fw-bold shadow-sm"
                                        style="border-radius: 12px; font-size: 1.1rem; letter-spacing: 1px;">
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
@endsection