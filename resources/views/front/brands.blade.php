@extends('front/layout-2')

@push('page_title', 'Alpha Health Group Branches | Our Business Portfolio')

@push('meta')
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
@endpush

@section('content')
<style>
    :root {
        --teal:   #066D77;
        --teal2:  #66d9e8;
        --navy:   #0f172a;
        --slate:  #475569;
        --muted:  #64748b;
        --border: #e2e8f0;
        --soft:   #f8fafc;
    }

    .brands-page { background: #fff; overflow-x: hidden; font-family: 'Inter', sans-serif; }

    /* ── HERO ───────────────────────────────────────────── */
    .brands-hero {
        position: relative;
        min-height: 88vh;
        display: flex;
        align-items: flex-end;
        padding-bottom: 96px;
        margin-top: -120px;
        padding-top: 120px;
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
    .brands-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(0,0,0,0.15) 0%,
            rgba(0,0,0,0.55) 50%,
            rgba(0,0,0,0.88) 100%
        );
        z-index: 1;
    }
    .brands-hero .hero-inner {
        position: relative; z-index: 2; width: 100%;
    }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 10px;
        font-size: 0.75rem; font-weight: 800; letter-spacing: 2.5px;
        text-transform: uppercase; color: var(--teal2);
        margin-bottom: 20px;
    }
    .hero-eyebrow span {
        display: inline-block; width: 32px; height: 1px; background: var(--teal2);
    }
    .brands-hero h1 {
        font-family: 'Inter', sans-serif;
        font-size: clamp(3.2rem, 8vw, 5.2rem);
        font-weight: 600; color: #fff; line-height: 1.05;
        letter-spacing: -0.03em; margin-bottom: 24px; max-width: 820px;
    }
    .brands-hero p {
        font-size: 1.1rem; color: rgba(255,255,255,0.7);
        max-width: 560px; line-height: 1.75; margin-bottom: 0;
    }
    /* count pill */
    .hero-count-pill {
        display: inline-flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 100px; padding: 10px 22px;
        font-size: 0.85rem; font-weight: 700; color: #fff;
        backdrop-filter: blur(6px); margin-top: 32px;
    }
    .hero-count-pill i { color: var(--teal2); }
    /* breadcrumb */
    .hero-breadcrumb {
        position: absolute; bottom: 36px; left: 0; right: 0;
        font-size: 0.78rem; text-transform: uppercase;
        letter-spacing: 1.5px; z-index: 10;
    }
    .hero-breadcrumb a { color: rgba(255,255,255,0.6)!important; text-decoration:none; font-weight:600; transition:color 0.2s; }
    .hero-breadcrumb a:hover { color:#fff!important; }
    .hero-breadcrumb .bc-sep { color: rgba(255,255,255,0.3); margin: 0 8px; }
    .hero-breadcrumb .bc-current { color: rgba(255,255,255,0.9); font-weight:700; }

    @media (max-width: 767px) {
        .brands-hero { margin-top:-85px; min-height:75vh; padding-bottom:70px; }
        .brands-hero h1 { font-size: 2.2rem; }
        .hero-breadcrumb { bottom: 18px; font-size:0.7rem; }
    }

    /* ── INTRO STRIP ────────────────────────────────────── */
    .brands-intro {
        padding: 72px 0 0;
        background: #fff;
    }
    .brands-intro-inner {
        display: flex; align-items: flex-end;
        justify-content: space-between; gap: 40px;
        padding-bottom: 48px;
        border-bottom: 1px solid var(--border);
    }
    .brands-intro-left h2 {
        font-family: 'Inter', sans-serif;
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        color: var(--navy); font-weight: 600; line-height: 1.2;
        letter-spacing: -0.02em; margin-bottom: 12px;
    }
    .brands-intro-left p {
        color: var(--muted); font-size: 1rem; line-height: 1.7; max-width: 560px; margin: 0;
    }
    .brands-count-badge {
        display: flex; flex-direction: column; align-items: flex-end; flex-shrink: 0; gap: 4px;
    }
    .brands-count-badge .big-num {
        font-family: 'Inter', sans-serif;
        font-size: 3.6rem; font-weight: 700; color: var(--teal); line-height: 1;
        letter-spacing: -0.03em;
    }
    .brands-count-badge .big-lbl {
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.5px; color: var(--muted);
    }
    @media (max-width: 767px) {
        .brands-intro-inner { flex-direction: column; align-items: flex-start; }
        .brands-count-badge { align-items: flex-start; }
    }

    /* ── BRAND GRID ─────────────────────────────────────── */
    .brands-grid-section { padding: 60px 0 120px; background: #fff; }
    .brands-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 28px;
    }

    /* Card */
    .brand-card {
        border: 1px solid var(--border);
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
        text-decoration: none;
        display: flex; flex-direction: column;
        transition: transform 0.35s cubic-bezier(0.4,0,0.2,1),
                    box-shadow 0.35s ease,
                    border-color 0.35s ease;
        position: relative;
    }
    .brand-card::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        background: linear-gradient(180deg, var(--teal), var(--teal2));
        transform: scaleY(0); transition: transform 0.35s ease;
        transform-origin: top;
    }
    .brand-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 56px rgba(6,109,119,0.1);
        border-color: rgba(6,109,119,0.3);
    }
    .brand-card:hover::before { transform: scaleY(1); }

    /* Logo panel */
    .bc-logo-panel {
        background: var(--soft);
        border-bottom: 1px solid var(--border);
        height: 180px;
        display: flex; align-items: center; justify-content: center;
        padding: 32px 40px;
        transition: background 0.3s ease;
    }
    .brand-card:hover .bc-logo-panel { background: #f0fafa; }
    .bc-logo-panel img {
        max-width: 100%; max-height: 100px; object-fit: contain;
        transition: transform 0.4s ease;
    }
    .brand-card:hover .bc-logo-panel img { transform: scale(1.06); }

    /* Body */
    .bc-body {
        padding: 32px 32px 28px;
        flex: 1; display: flex; flex-direction: column; gap: 12px;
    }
    .bc-location {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.2px; color: var(--teal);
        background: var(--soft); border: 1px solid var(--border);
        padding: 4px 12px; border-radius: 100px;
        width: fit-content;
    }
    .bc-location i { font-size: 0.65rem; }
    .bc-name {
        font-family: 'Inter', sans-serif;
        font-size: 1.45rem; font-weight: 600;
        color: var(--navy); line-height: 1.2; margin: 0;
        letter-spacing: -0.02em;
        transition: color 0.25s ease;
    }
    .brand-card:hover .bc-name { color: var(--teal); }
    .bc-desc {
        font-size: 0.93rem; color: var(--slate);
        line-height: 1.75; margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Footer */
    .bc-footer {
        padding: 0 32px 28px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .bc-explore {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 0.82rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.5px; color: var(--navy);
        transition: gap 0.25s ease, color 0.25s ease;
    }
    .brand-card:hover .bc-explore { gap: 12px; color: var(--teal); }
    .bc-explore-icon {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--soft); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; color: var(--navy);
        transition: background 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }
    .brand-card:hover .bc-explore-icon { background: var(--teal); border-color: var(--teal); color: #fff; }

    @media (max-width: 767px) {
        .brands-grid { grid-template-columns: 1fr; gap: 20px; }
        .bc-logo-panel { height: 140px; }
        .bc-body { padding: 24px 24px 16px; }
        .bc-footer { padding: 0 24px 24px; }
        .bc-name { font-size: 1.3rem; }
    }

    /* ── EMPTY STATE ────────────────────────────────────── */
    .brands-empty {
        text-align: center; padding: 100px 20px;
        color: var(--muted);
    }
    .brands-empty i { font-size: 3rem; margin-bottom: 20px; opacity: 0.3; display: block; }

    /* ── CTA ────────────────────────────────────────────── */
    .brands-cta {
        padding: 100px 0; background: var(--navy);
        position: relative; overflow: hidden;
    }
    .brands-cta::before {
        content: '';
        position: absolute; top: -100px; right: -100px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(6,109,119,0.3), transparent 70%);
        filter: blur(60px);
    }
    .brands-cta-inner {
        position: relative; max-width: 700px; margin: 0 auto; text-align: center;
    }
    .cta-eyebrow {
        display: inline-block; font-size: 0.72rem; font-weight: 800;
        letter-spacing: 2px; text-transform: uppercase; color: var(--teal2);
        background: rgba(102,217,232,0.1); border: 1px solid rgba(102,217,232,0.2);
        padding: 5px 16px; border-radius: 100px; margin-bottom: 22px;
    }
    .brands-cta h2 {
        font-family: 'Inter', sans-serif;
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        color: #fff; font-weight: 600; line-height: 1.2;
        letter-spacing: -0.02em; margin-bottom: 18px;
    }
    .brands-cta p {
        color: rgba(255,255,255,0.58); font-size: 1rem;
        line-height: 1.8; margin-bottom: 44px;
    }
    .cta-row { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; }
    .btn-teal {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 15px 34px; background: var(--teal); color: #fff;
        font-weight: 700; font-size: 0.88rem; text-transform: uppercase;
        letter-spacing: 1.5px; border-radius: 100px; text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-teal:hover { background:#088a95; color:#fff; transform:translateY(-2px); box-shadow:0 10px 28px rgba(6,109,119,0.4); }
    .btn-ghost-white {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 15px 34px; background: transparent; color: #fff;
        font-weight: 700; font-size: 0.88rem; text-transform: uppercase;
        letter-spacing: 1.5px; border-radius: 100px;
        border: 2px solid rgba(255,255,255,0.25); text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-ghost-white:hover { background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.5); color:#fff; transform:translateY(-2px); }
    @media (max-width:767px) {
        .brands-cta { padding: 70px 0; }
        .cta-row { flex-direction: column; align-items: center; }
        .btn-teal, .btn-ghost-white { width:100%; max-width:280px; justify-content:center; }
    }
</style>

<div class="brands-page">

    {{-- ── HERO (standardized) ──────────────────────────────────────── --}}
    @include('front.partials.page-hero', [
        'heroEyebrow' => 'AGH Business Portfolio',
        'heroTitle'   => 'Alpha Health Group',
        'heroSubtitle'=> 'Brands',
        'heroDesc'    => 'A curated ecosystem of specialised healthcare companies, each delivering excellence and innovation across distinct areas of the sector.',
        'heroBadge'   => ['icon' => 'fas fa-layer-group', 'text' => $brands->count() . ' Subsidiary ' . Str::plural('Company', $brands->count())],
        'breadcrumb'  => ['Home' => route('home'), 'About Us' => route('front.new-about'), 'Our Branches' => null],
    ])

    {{-- ── INTRO ────────────────────────────────────────────────────── --}}
    <section class="brands-intro">
        <div class="container">
            <div class="brands-intro-inner">
                <div class="brands-intro-left" data-aos="fade-right">
                    <h2>Our Business Ecosystem</h2>
                    <p>Each brand under Alpha Health Group operates with its own specialized expertise, serving diverse industries while staying connected through a shared commitment to excellence, integrity, and customer-focused innovation. Together, our brands work to create meaningful impact across the region by delivering trusted solutions, building long-term relationships, and continuously adapting to the evolving needs of the communities and businesses we serve.</p>
                </div>
                <div class="brands-count-badge" data-aos="fade-left">
                    <span class="big-num">{{ $brands->count() }}</span>
                    <span class="big-lbl">Companies</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ── BRANDS GRID ──────────────────────────────────────────────── --}}
    <section class="brands-grid-section">
        <div class="container">
            @if($brands->count())
            <div class="brands-grid">
                @foreach($brands as $brand)
                <a href="{{ route('front.singleBrand', $brand->slug) }}" class="brand-card" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 2) * 100 }}">

                    {{-- Logo --}}
                    <div class="bc-logo-panel">
                        <img src="{{ $brand->logo ? asset('public/uploads/brands/' . $brand->logo) : asset('public/front-new/assets/images/alpha-logo.svg') }}"
                            alt="{{ $brand->name }}" loading="lazy">
                    </div>

                    {{-- Body --}}
                    <div class="bc-body">
                        @if($brand->address)
                        <span class="bc-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ Str::limit($brand->address, 45) }}
                        </span>
                        @endif
                        <h2 class="bc-name">{{ $brand->name }}</h2>
                        @if($brand->description)
                        <p class="bc-desc">{{ strip_tags($brand->description) }}</p>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="bc-footer">
                        <span class="bc-explore">
                            Explore
                            <span class="bc-explore-icon">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </span>
                    </div>

                </a>
                @endforeach
            </div>
            @else
            <div class="brands-empty">
                <i class="fas fa-building"></i>
                <h3 class="mb-2" style="color:var(--navy)">Portfolio Being Updated</h3>
                <p>Please check back soon to discover our expanding ecosystem of healthcare companies.</p>
            </div>
            @endif
        </div>
    </section>

    {{-- ── CTA ─────────────────────────────────────────────────────── --}}
    <section class="brands-cta">
        <div class="container">
            <div class="brands-cta-inner" data-aos="fade-up">
                <span class="cta-eyebrow">Work With Us</span>
                <h2>Partner with Alpha Health Group</h2>
                <p>Whether you're exploring a collaboration with one of our branches or looking for group-level strategic support, our team is ready to connect.</p>
                <div class="cta-row">
                    <a href="{{ route('contact') }}" class="btn-teal">
                        Get in Touch <i class="fas fa-paper-plane"></i>
                    </a>
                    <a href="{{ route('front.new-about') }}" class="btn-ghost-white">
                        <i class="fas fa-arrow-left"></i> About Us
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
