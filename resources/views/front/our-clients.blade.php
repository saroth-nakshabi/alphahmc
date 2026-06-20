@extends('front/layout-2')

@push('page_title', 'Our Clients | Trusted Healthcare Partners | Alpha Health Group')

@section('meta_description', 'Discover the healthcare facilities, hospitals, and medical organisations across the UAE that trust Alpha Health Group for DOH compliance, accreditation, and quality consultancy.')

@push('og_tags')
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="Our Clients | Alpha Health Group" />
    <meta property="og:description" content="Healthcare facilities across the UAE trust Alpha Health Group for compliance, accreditation, and quality consultancy." />
    <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('custom_css')
<style>
    :root {
        --navy:   #003358;
        --navy2:  #00527a;
        --red:    #e50303;
        --teal:   #009095;
        --muted:  #64748b;
        --bg:     #f8fafc;
        --ease:   cubic-bezier(0.16, 1, 0.3, 1);
    }

    body { font-family: 'Outfit', sans-serif; background: #fff; }

    /* ── HERO ──────────────────────────────────────────── */
    .cl-hero {
        background: var(--navy);
        padding: 160px 0 120px;
        position: relative;
        overflow: hidden;
    }
    .cl-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 20% 50%, rgba(0,144,149,0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(229,3,3,0.08) 0%, transparent 45%);
        pointer-events: none;
    }
    .cl-hero-grid {
        max-width: 760px;
    }
    .cl-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.9);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 6px 18px;
        border-radius: 50px;
        margin-bottom: 24px;
    }
    .cl-hero h1 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(2.4rem, 5vw, 4rem);
        color: #fff;
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 24px;
    }
    .cl-hero h1 span { color: #4dd9dc; }
    .cl-hero p.lead {
        color: rgba(255,255,255,0.75);
        font-size: 1.05rem;
        line-height: 1.8;
        max-width: 560px;
    }

    /* ── TICKER ─────────────────────────────────────────── */
    .cl-ticker {
        background: var(--bg);
        border-bottom: 1px solid #e2e8f0;
        padding: 28px 0;
        overflow: hidden;
    }
    .cl-ticker-track {
        display: flex;
        gap: 60px;
        animation: ticker-scroll 30s linear infinite;
        width: max-content;
    }
    .cl-ticker-track:hover { animation-play-state: paused; }
    .cl-ticker-item {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 52px;
        flex-shrink: 0;
        opacity: 0.55;
        filter: grayscale(1);
        transition: opacity 0.3s, filter 0.3s;
    }
    .cl-ticker-item:hover { opacity: 1; filter: grayscale(0); }
    .cl-ticker-item img { max-height: 44px; max-width: 140px; object-fit: contain; }
    .cl-ticker-item .cl-ticker-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--navy);
        white-space: nowrap;
    }
    @keyframes ticker-scroll {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* ── SECTION HEADER ──────────────────────────────────── */
    .cl-section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff0f0;
        color: var(--red);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 16px;
        border-radius: 50px;
        margin-bottom: 14px;
    }
    .cl-section-title {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        color: var(--navy);
        font-weight: 700;
        margin-bottom: 16px;
        line-height: 1.2;
    }
    .cl-section-sub {
        color: var(--muted);
        font-size: 1.05rem;
        line-height: 1.75;
        max-width: 600px;
    }

    /* ── CLIENT CARDS ────────────────────────────────────── */
    .cl-listing { padding: 90px 0 110px; background: #fff; }

    .cl-card {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 20px;
        overflow: hidden;
        transition: box-shadow 0.3s var(--ease), transform 0.3s var(--ease), border-color 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .cl-card:hover {
        box-shadow: 0 20px 50px rgba(0,51,88,0.09);
        transform: translateY(-5px);
        border-color: #c7d6e3;
    }

    /* Logo area */
    .cl-card-logo-area {
        background: var(--bg);
        border-bottom: 1px solid #f1f5f9;
        padding: 36px 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 140px;
        position: relative;
        overflow: hidden;
    }
    .cl-card-logo-area::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 70% 30%, rgba(0,144,149,0.06) 0%, transparent 60%);
    }
    .cl-card-logo-area img {
        max-height: 72px;
        max-width: 200px;
        object-fit: contain;
        position: relative;
        z-index: 1;
        transition: transform 0.4s var(--ease);
    }
    .cl-card:hover .cl-card-logo-area img { transform: scale(1.06); }
    .cl-logo-placeholder {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--navy), var(--navy2));
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.6rem;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    /* Card body */
    .cl-card-body {
        padding: 30px 32px 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .cl-card-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 10px;
        line-height: 1.35;
    }
    .cl-card-short {
        font-size: 0.9rem;
        color: var(--muted);
        line-height: 1.7;
        margin-bottom: 16px;
        flex: 1;
    }

    /* Expand / collapse description */
    .cl-card-desc {
        font-size: 0.88rem;
        color: #374151;
        line-height: 1.75;
        display: none;
        border-top: 1px solid #f1f5f9;
        padding-top: 16px;
        margin-top: 6px;
    }
    .cl-card.expanded .cl-card-desc { display: block; }

    .cl-card-footer {
        padding: 0 32px 24px;
        display: flex;
        justify-content: flex-end;
    }
    .btn-cl-expand {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--navy);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: color 0.2s;
    }
    .btn-cl-expand i {
        width: 28px;
        height: 28px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        transition: background 0.2s, transform 0.3s;
    }
    .btn-cl-expand:hover { color: var(--red); }
    .btn-cl-expand:hover i { background: #fce4e4; color: var(--red); }
    .cl-card.expanded .btn-cl-expand i { transform: rotate(180deg); background: var(--navy); color: #fff; }

    /* ── EMPTY STATE ─────────────────────────────────────── */
    .cl-empty {
        text-align: center;
        padding: 80px 20px;
        color: var(--muted);
    }
    .cl-empty i { font-size: 3.5rem; color: #e2e8f0; display: block; margin-bottom: 16px; }

    /* ── CTA BAND ─────────────────────────────────────────── */
    .cl-cta {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 100%);
        padding: 80px 0;
        text-align: center;
    }
    .cl-cta h2 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        color: #fff;
        margin-bottom: 16px;
    }
    .cl-cta p {
        color: rgba(255,255,255,0.7);
        font-size: 1.05rem;
        max-width: 520px;
        margin: 0 auto 36px;
        line-height: 1.75;
    }
    .btn-cta-white {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        color: var(--navy);
        padding: 16px 36px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.9rem;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: background 0.2s, color 0.2s, transform 0.2s;
        margin: 6px;
    }
    .btn-cta-white:hover { background: var(--red); color: #fff; transform: translateY(-2px); }
    .btn-cta-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: transparent;
        color: #fff;
        border: 2px solid rgba(255,255,255,0.4);
        padding: 14px 34px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.9rem;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: background 0.2s, border-color 0.2s, transform 0.2s;
        margin: 6px;
    }
    .btn-cta-outline:hover { background: rgba(255,255,255,0.1); border-color: #fff; transform: translateY(-2px); color: #fff; }

    /* ── RESPONSIVE ─────────────────────────────────────── */
    @media (max-width: 991px) {
        .cl-hero { padding: 110px 20px 80px; }
        .cl-card-logo-area { padding: 28px; min-height: 110px; }
        .cl-card-body { padding: 24px 24px 16px; }
        .cl-card-footer { padding: 0 24px 20px; }
    }
    @media (max-width: 575px) {
        .cl-hero h1 { font-size: 2.1rem; }
        .cl-hero-stats { gap: 20px; }
    }
</style>
@endsection

@section('content')

{{-- ── HERO ─────────────────────────────────────────────── --}}
@php
    // Unified page settings (Dashboard → Pages & SEO). Falls back to legacy ClientSetting.
    $cs = $pageMeta ?? ($clientSetting ?? \App\Models\ClientSetting::current());
    $clHeroBg = $cs && $cs->hero_image
        ? asset('public/uploads/page_images/' . $cs->hero_image)
        : null;
@endphp
<section class="cl-hero{{ $clHeroBg ? ' has-bg' : '' }}"
    @if($clHeroBg) style="background-image: linear-gradient(rgba(8,23,43,0.82), rgba(8,23,43,0.88)), url('{{ $clHeroBg }}'); background-size: cover; background-position: center;" @endif>
    <div class="container">
        <div class="cl-hero-grid">
            <div>
                @if($cs->hero_eyebrow)
                    <span class="cl-hero-eyebrow"><i class="fas fa-handshake"></i> {{ $cs->hero_eyebrow }}</span>
                @endif
                <h1>{{ $cs->hero_title }}@if($cs->hero_subtitle)<br><span>{{ $cs->hero_subtitle }}</span>@endif</h1>
                @if(trim(strip_tags((string) $cs->hero_description)) !== '')
                    <p class="lead">{!! nl2br(e($cs->hero_description)) !!}</p>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ── LOGO TICKER ───────────────────────────────────────── --}}
@if($clients->count() > 0)
<div class="cl-ticker">
    <div class="cl-ticker-track" id="tickerTrack">
        @foreach($clients as $client)
            <div class="cl-ticker-item">
                @if($client->logo)
                    <img src="{{ asset('public/uploads/clients/' . $client->logo) }}" alt="{{ $client->name }}">
                @else
                    <span class="cl-ticker-name">{{ $client->name }}</span>
                @endif
            </div>
        @endforeach
        {{-- Duplicate for seamless loop --}}
        @foreach($clients as $client)
            <div class="cl-ticker-item" aria-hidden="true">
                @if($client->logo)
                    <img src="{{ asset('public/uploads/clients/' . $client->logo) }}" alt="{{ $client->name }}">
                @else
                    <span class="cl-ticker-name">{{ $client->name }}</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── CLIENT LISTING ────────────────────────────────────── --}}
<section class="cl-listing">
    <div class="container">

        {{-- Section header --}}
        <div class="row mb-5">
            <div class="col-lg-7">
                <span class="cl-section-label"><i class="fas fa-users"></i> Our Clients</span>
                <h2 class="cl-section-title">The Organisations<br>We Serve</h2>
                <p class="cl-section-sub">
                    Every client partnership is built on trust, expertise, and a shared commitment
                    to raising the standard of healthcare delivery across the UAE.
                </p>
            </div>
            <div class="col-lg-5 d-flex align-items-end justify-content-lg-end mt-4 mt-lg-0">
                <div style="text-align:right">
                    <div style="font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">
                        Showing {{ $clients->count() }} clients
                    </div>
                    <div style="font-size:0.88rem;color:var(--muted);">
                        Click <strong style="color:var(--navy)">Read More</strong> on any card to learn about the partnership
                    </div>
                </div>
            </div>
        </div>

        @if($clients->count() > 0)
            <div class="row g-4" id="clients-grid">
                @foreach($clients as $client)
                    <div class="col-lg-4 col-md-6">
                        <div class="cl-card" id="client-card-{{ $client->id }}">

                            {{-- Logo area --}}
                            <div class="cl-card-logo-area">
                                @if($client->logo)
                                    <img src="{{ asset('public/uploads/clients/' . $client->logo) }}"
                                         alt="{{ $client->name }} logo" loading="lazy">
                                @else
                                    <div class="cl-logo-placeholder">
                                        {{ strtoupper(substr($client->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Body --}}
                            <div class="cl-card-body">
                                <h3 class="cl-card-name">{{ $client->name }}</h3>
                                @if($client->short_description)
                                    <p class="cl-card-short">{{ $client->short_description }}</p>
                                @endif
                                @if(trim(strip_tags((string) $client->description)) !== '')
                                    <div class="cl-card-desc">
                                        {{-- Rich-text HTML from the editor — render as-is, do NOT escape --}}
                                        {!! $client->description !!}
                                    </div>
                                @endif
                            </div>

                            {{-- Expand button --}}
                            @if(trim(strip_tags((string) $client->description)) !== '')
                                <div class="cl-card-footer">
                                    <button class="btn-cl-expand" data-target="client-card-{{ $client->id }}">
                                        <span class="expand-label">Read More</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="cl-empty">
                <i class="fas fa-building"></i>
                <h4 style="color:var(--navy);font-weight:800;margin-bottom:8px;">Client profiles coming soon</h4>
                <p>We're preparing our client showcase. Check back shortly.</p>
            </div>
        @endif

    </div>
</section>

{{-- ── CTA BAND ──────────────────────────────────────────── --}}
<section class="cl-cta">
    <div class="container">
        <h2>Ready to Join Our Client Network?</h2>
        <p>
            Partner with Alpha Health Group and benefit from our decade-long expertise in
            UAE healthcare compliance, DOH licensing, and accreditation support.
        </p>
        <div>
            <a href="{{ route('contact') }}" class="btn-cta-white">
                <i class="fas fa-envelope"></i> Get in Touch
            </a>
            <a href="{{ route('front.all-services') }}" class="btn-cta-outline">
                <i class="fas fa-th-list"></i> Explore Services
            </a>
        </div>
    </div>
</section>

@endsection

@section('custom_js')
<script>
    /* Expand / collapse client description */
    document.querySelectorAll('.btn-cl-expand').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var card = document.getElementById(this.dataset.target);
            var label = this.querySelector('.expand-label');
            card.classList.toggle('expanded');
            label.textContent = card.classList.contains('expanded') ? 'Show Less' : 'Read More';
        });
    });
</script>
@endsection
