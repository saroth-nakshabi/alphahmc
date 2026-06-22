@extends('front/layout-2')

@php
    $brandMetaTitle = empty($brand->meta_title) ? $brand->name : $brand->meta_title;
    $brandMetaDesc  = $brand->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($brand->description ?? ''), 160);
@endphp
@push('page_title', $brandMetaTitle . ' | Alpha Health Group')
@push('meta')
    <meta name="description" content="{{ $brandMetaDesc }}">
    @if(!empty($brand->meta_keywords))<meta name="keywords" content="{{ $brand->meta_keywords }}">@endif
@endpush

@section('content')
<style>
    :root {
        --teal:     #066D77;
        --teal-lt:  rgba(6,109,119,0.08);
        --navy:     #0f172a;
        --slate:    #475569;
        --muted:    #64748b;
        --border:   #e2e8f0;
        --soft:     #f8fafc;
    }

    .brand-page { background:#fff; overflow-x:hidden; }

    /* ── HERO ───────────────────────────────────────────────── */
    .brand-hero {
        position: relative;
        min-height: 82vh;
        display: flex;
        align-items: center;
        margin-top: -120px;
        padding-top: 160px;
        padding-bottom: 100px;
        @if(isset($brandHero) && $brandHero->image)
            background-image: url('{{ asset('public/uploads/brands/' . $brandHero->image) }}');
        @else
            background-image: url('{{ asset('public/front-new/assets/images/service_details/service-details-2.webp') }}');
        @endif
        background-size: cover;
        background-position: center;
        background-attachment: scroll;
        color: #fff;
    }
    .brand-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(105deg, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.5) 60%, rgba(0,0,0,0.25) 100%);
        z-index: 1;
    }
    .brand-hero .hero-inner {
        position: relative; z-index: 2; width: 100%;
    }
    .brand-hero-tag {
        display: inline-block;
        font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 2.5px;
        color: rgba(255,255,255,0.7);
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 6px 16px; border-radius: 100px;
        margin-bottom: 20px;
    }
    .brand-hero-name {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(2.4rem, 5vw, 4rem);
        font-weight: 700; color: #fff; line-height: 1.1;
        margin-bottom: 22px; letter-spacing: -0.02em;
    }
    .brand-hero-desc {
        font-size: 1.1rem; color: rgba(255,255,255,0.75);
        line-height: 1.75; max-width: 600px; font-weight: 300;
    }
    /* Logo card floating in hero */
    .brand-hero-logo-card {
        background: rgba(255,255,255,0.97);
        border-radius: 24px;
        padding: 44px 36px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 24px 60px rgba(0,0,0,0.25);
        max-width: 300px;
        margin-left: auto;
    }
    .brand-hero-logo-card img {
        max-width: 200px; max-height: 120px; object-fit: contain;
    }
    /* Breadcrumb */
    .hero-breadcrumb {
        position: absolute; bottom: 36px; left: 0; right: 0;
        font-size: 0.78rem; text-transform: uppercase;
        letter-spacing: 1.5px; z-index: 10;
    }
    .hero-breadcrumb a { color: rgba(255,255,255,0.65)!important; text-decoration:none; font-weight:600; transition:color 0.2s; }
    .hero-breadcrumb a:hover { color:#fff!important; }
    .hero-breadcrumb .bc-sep { color:rgba(255,255,255,0.35); margin:0 8px; }
    .hero-breadcrumb .bc-current { color:rgba(255,255,255,0.95); font-weight:700; }

    @media (max-width:991px) {
        .brand-hero { min-height:auto; padding:160px 0 80px; }
        .brand-hero-logo-card { max-width:220px; margin:36px auto 0; }
    }
    @media (max-width:767px) {
        .brand-hero { margin-top:-85px; padding:140px 0 70px; }
        .brand-hero-name { font-size:2rem; }
        .brand-hero-logo-card { padding:28px 24px; }
        .hero-breadcrumb { bottom:20px; font-size:0.7rem; }
    }

    /* ── SHARED SECTION STYLES ──────────────────────────────── */
    .bd-section { padding: 96px 0; }
    .bd-section-soft { padding: 96px 0; background: var(--soft); }
    .bd-tag {
        display: inline-block; font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 2px;
        color: var(--teal); background: var(--teal-lt);
        padding: 5px 14px; border-radius: 100px; margin-bottom: 16px;
    }
    .bd-heading {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(1.8rem, 3vw, 2.6rem);
        font-weight: 700; color: var(--navy); line-height: 1.2; margin-bottom: 0;
    }
    .bd-body {
        font-size: 1rem; color: var(--slate); line-height: 1.85;
    }
    .bd-body p  { margin-bottom: 14px; }
    .bd-body ul { padding-left: 20px; }
    .bd-body li { margin-bottom: 8px; }
    .bd-body h1, .bd-body h2, .bd-body h3 {
        font-family: 'Libre Baskerville', serif;
        color: var(--navy); margin-bottom: 12px;
    }

    /* ── ABOUT SECTION ──────────────────────────────────────── */
    .about-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 72px;
        align-items: start;
    }
    .about-logo-panel {
        position: sticky; top: 100px;
    }
    .about-logo-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 40px 30px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    }
    .about-logo-box img {
        max-width: 100%; max-height: 110px; object-fit: contain;
    }
    .about-meta-card {
        background: var(--soft);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px 24px;
        display: flex; flex-direction: column; gap: 14px;
    }
    .meta-row {
        display: flex; align-items: flex-start; gap: 12px;
        font-size: 0.88rem; color: var(--slate); line-height: 1.45;
    }
    .meta-row i { color: var(--teal); width: 16px; flex-shrink: 0; margin-top: 2px; }
    .meta-row strong { color: var(--navy); display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
    .about-text-panel { padding-top: 4px; }

    @media (max-width:991px) {
        .about-layout { grid-template-columns: 1fr; gap: 40px; }
        .about-logo-panel { position: static; }
        .about-logo-box { max-width: 260px; margin: 0 auto 20px; }
    }

    /* ── WHAT WE DO ─────────────────────────────────────────── */
    .whatwedo-inner {
        max-width: 820px;
        margin: 0 auto;
    }
    .whatwedo-divider {
        width: 56px; height: 3px;
        background: linear-gradient(90deg, var(--teal), #66d9e8);
        border-radius: 2px; margin: 20px 0 36px;
    }

    /* ── LOCATION SECTION ───────────────────────────────────── */
    .location-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 40px;
        align-items: start;
    }
    .location-info-panel {
        background: var(--navy);
        border-radius: 20px;
        padding: 40px 32px;
        color: #fff;
        position: sticky; top: 100px;
    }
    .location-info-panel h3 {
        font-family: 'Libre Baskerville', serif;
        font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 28px;
    }
    .location-detail-row {
        display: flex; gap: 14px; margin-bottom: 22px; align-items: flex-start;
    }
    .location-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: rgba(255,255,255,0.08);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: #66d9e8; font-size: 0.95rem;
    }
    .location-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.5); margin-bottom: 4px; font-weight: 700; }
    .location-value { font-size: 0.95rem; color: #fff; line-height: 1.45; }
    .location-map-panel { border-radius: 20px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 4px 24px rgba(0,0,0,0.05); }
    .location-map-panel iframe { display: block; }
    .map-placeholder {
        height: 460px; background: var(--soft); display: flex;
        flex-direction: column; align-items: center; justify-content: center;
        color: var(--muted); gap: 12px;
    }
    .map-placeholder i { font-size: 2.5rem; color: var(--border); }
    @media (max-width:991px) {
        .location-layout { grid-template-columns: 1fr; }
        .location-info-panel { position: static; }
    }

    /* ── CTA SECTION ────────────────────────────────────────── */
    .brand-cta {
        padding: 100px 0;
        background: var(--navy);
        position: relative; overflow: hidden; text-align: center;
    }
    .brand-cta::before {
        content: '';
        position: absolute; top: -80px; right: -80px;
        width: 360px; height: 360px;
        background: radial-gradient(circle, rgba(6,109,119,0.3), transparent 70%);
        filter: blur(60px);
    }
    .brand-cta-inner { position: relative; max-width: 680px; margin: 0 auto; }
    .brand-cta-tag {
        display: inline-block; font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 2px; color: #66d9e8;
        background: rgba(102,217,232,0.1); padding: 5px 16px;
        border-radius: 100px; border: 1px solid rgba(102,217,232,0.2); margin-bottom: 22px;
    }
    .brand-cta h2 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(1.8rem, 3.5vw, 2.6rem);
        color: #fff; font-weight: 700; line-height: 1.25; margin-bottom: 18px;
    }
    .brand-cta p { color: rgba(255,255,255,0.6); font-size: 1rem; line-height: 1.75; margin-bottom: 40px; }
    .brand-cta-actions { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; }
    .cta-btn-solid {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 15px 34px; background: var(--teal); color: #fff;
        font-weight: 700; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 1.5px;
        border-radius: 100px; text-decoration: none; transition: all 0.3s ease;
    }
    .cta-btn-solid:hover { background: #088a95; color:#fff; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(6,109,119,0.4); }
    .cta-btn-ghost {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 15px 34px; background: transparent; color: #fff;
        font-weight: 700; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 1.5px;
        border-radius: 100px; border: 2px solid rgba(255,255,255,0.25);
        text-decoration: none; transition: all 0.3s ease;
    }
    .cta-btn-ghost:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.5); color:#fff; transform: translateY(-2px); }
    @media (max-width:767px) {
        .brand-cta { padding: 70px 0; }
        .brand-cta-actions { flex-direction: column; align-items: center; }
        .cta-btn-solid, .cta-btn-ghost { width: 100%; max-width: 280px; justify-content: center; }
    }
</style>

<div class="brand-page">

    {{-- ── HERO ───────────────────────────────────────────────────── --}}
    <section class="brand-hero">
        <div class="container hero-inner">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="brand-hero-tag">Business Portfolio</span>
                    <h1 class="brand-hero-name">{{ $brand->name }}</h1>
                    @if($brand->description)
                    <p class="brand-hero-desc">
                        {{ Str::limit(strip_tags($brand->description), 180) }}
                    </p>
                    @endif
                </div>
                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end">
                    <div class="brand-hero-logo-card">
                        <img src="{{ $brand->logo ? asset('public/uploads/brands/' . $brand->logo) : asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            alt="{{ $brand->name }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="hero-breadcrumb container" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="bc-sep">›</span>
            <a href="{{ route('front.new-about') }}">About Us</a>
            <span class="bc-sep">›</span>
            <span class="bc-current">{{ $brand->name }}</span>
        </nav>
    </section>

    {{-- ── ABOUT THE BRAND ─────────────────────────────────────────── --}}
    <section class="bd-section">
        <div class="container">
            <div class="about-layout">

                {{-- Sticky left panel: logo + meta --}}
                <div class="about-logo-panel" data-aos="fade-right">
                    <div class="about-logo-box">
                        <img src="{{ $brand->logo ? asset('public/uploads/brands/' . $brand->logo) : asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            alt="{{ $brand->name }}" loading="lazy">
                    </div>

                    <div class="about-meta-card">
                        @if($brand->address)
                        <div class="meta-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Location</strong>
                                {{ $brand->address }}
                            </div>
                        </div>
                        @endif
                        <div class="meta-row">
                            <i class="fas fa-building"></i>
                            <div>
                                <strong>Group</strong>
                                Alpha Health Group
                            </div>
                        </div>
                        <div class="meta-row">
                            <i class="fas fa-globe"></i>
                            <div>
                                <strong>Sector</strong>
                                Healthcare & Consultancy
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right content panel --}}
                <div class="about-text-panel" data-aos="fade-left">
                    <span class="bd-tag">About</span>
                    <h2 class="bd-heading mb-4">About {{ $brand->name }}</h2>
                    <div class="bd-body">
                        {!! $brand->description !!}
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── WHAT WE DO ──────────────────────────────────────────────── --}}
    @if($brand->what_we_do)
    <section class="bd-section-soft">
        <div class="container">
            <div class="whatwedo-inner" data-aos="fade-up">
                <span class="bd-tag">Our Expertise</span>
                <h2 class="bd-heading">What We Do</h2>
                <div class="whatwedo-divider"></div>
                <div class="bd-body">
                    {!! $brand->what_we_do !!}
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ── LOCATION ────────────────────────────────────────────────── --}}
    <section class="bd-section">
        <div class="container">
            <div class="mb-5" data-aos="fade-up">
                <span class="bd-tag">Find Us</span>
                <h2 class="bd-heading">Our Location</h2>
            </div>

            <div class="location-layout" data-aos="fade-up">
                {{-- Info panel --}}
                <div class="location-info-panel">
                    <h3>Contact &amp; Address</h3>

                    @if($brand->address)
                    <div class="location-detail-row">
                        <div class="location-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="location-label">Address</div>
                            <div class="location-value">{{ $brand->address }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="location-detail-row">
                        <div class="location-icon"><i class="fas fa-building"></i></div>
                        <div>
                            <div class="location-label">Company</div>
                            <div class="location-value">{{ $brand->name }}</div>
                        </div>
                    </div>

                    <div class="location-detail-row">
                        <div class="location-icon"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <div class="location-label">Part of</div>
                            <div class="location-value">Alpha Health Group</div>
                        </div>
                    </div>

                    <a href="{{ route('contact') }}" class="cta-btn-solid mt-3 d-inline-flex" style="font-size:0.8rem; padding:12px 24px;">
                        Get in Touch <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Map --}}
                <div class="location-map-panel">
                    @if($brand->address)
                        <iframe
                            width="100%"
                            height="460"
                            frameborder="0"
                            style="border:0; display:block;"
                            src="https://www.google.com/maps?q={{ urlencode($brand->address) }}&output=embed"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    @else
                        <div class="map-placeholder">
                            <i class="fas fa-map-marked-alt"></i>
                            <p class="mb-0 fw-600">Location details not available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA ────────────────────────────────────────────────────── --}}
    <section class="brand-cta">
        <div class="container">
            <div class="brand-cta-inner" data-aos="fade-up">
                <span class="brand-cta-tag">Work With Us</span>
                <h2>Partner with {{ $brand->name }}</h2>
                <p>Ready to experience excellence? Contact our team to learn how {{ $brand->name }} can support your healthcare goals.</p>
                <div class="brand-cta-actions">
                    <a href="{{ route('contact') }}" class="cta-btn-solid">
                        Consult an Expert <i class="fas fa-paper-plane"></i>
                    </a>
                    <a href="{{ route('front.new-about') }}" class="cta-btn-ghost">
                        <i class="fas fa-arrow-left"></i> Back to About Us
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 900, once: true, offset: 80 });
        }
    });
</script>
@endsection
