@extends('front/layout-2')

@push('page_title', 'About Alpha Health Group | Healthcare Consultancy UAE')

@section('meta_description')Discover Alpha Health Group — a leading healthcare consultancy in the UAE delivering DOH compliance, accreditation, quality assurance, and operational excellence for hospitals and clinics.@endsection

@section('content')
    <style>
        :root {
            --teal: #066D77;
            --teal-light: rgba(6,109,119,0.08);
            --teal-mid: rgba(6,109,119,0.18);
            --navy: #0f172a;
            --slate: #475569;
            --muted: #64748b;
            --border: #e2e8f0;
            --soft-bg: #f8fafc;
        }

        .about-page-wrapper {
            background: #fff;
            overflow-x: hidden;
        }

        /* ── Hero ──────────────────────────────────────────────── */
        .hero-premium-v2 {
            position: relative;
            display: flex;
            align-items: center;
            background-image: url('{{ asset('public/uploads/about_us_images/' . ($about_us->image ?? 'default.jpg')) }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            color: #fff;
            padding-top: 100px;
            height: 915px;
            max-height: 1150px;
        }
        .hero-overlay {
            position: absolute; top:0; left:0; width:100%; height:100%;
            background: linear-gradient(90deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 100%);
            z-index: 1;
        }
        .hero-content-container { position:relative; z-index:2; width:100%; }
        .hero-premium-v2 .display-large {
            font-family: 'Libre Baskerville', serif;
            font-size: 4rem; color:#fff!important; line-height:1.1; margin-bottom:30px; font-weight:700;
        }
        .display-large {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 700; line-height: 1.1; letter-spacing: -0.02em;
        }
        .hero-desc {
            max-width:700px; font-family:'Outfit',sans-serif;
            font-size:1.25rem; line-height:1.6; font-weight:300;
            letter-spacing:0.5px; color:rgba(255,255,255,0.9)!important;
        }
        .btn-contact-premium {
            display:inline-block; background:#fff; color:#000;
            padding:18px 50px; font-weight:700; text-transform:uppercase;
            text-decoration:none; letter-spacing:2px;
            transition:all 0.4s cubic-bezier(0.165,0.84,0.44,1); font-size:0.85rem; margin-top:20px;
        }
        .btn-contact-premium:hover {
            background:#f0f0f0; transform:translateY(-5px);
            box-shadow:0 20px 40px rgba(0,0,0,0.3); color:#000;
        }
        .hero-logo {
            width:100%; max-height:650px; border-radius:75%;
            background-size:cover; box-shadow:0 30px 60px rgba(0,0,0,0.1);
        }
        @media (max-width: 991px) {
            .hero-premium-v2 { min-height:auto; padding:80px 0 60px; }
            .div-content { margin-top:0!important; }
            .hero-logo { width:280px; }
            .hero-premium-v2 .display-large { font-size:clamp(2rem,5vw,3rem); }
            .col-lg-5.text-end { text-align:center!important; }
        }
        @media (max-width: 767px) {
            .hero-premium-v2 { padding:60px 0 50px; background-attachment:scroll; }
            .div-content { flex-direction:column-reverse; margin-left:0; margin-right:0; }
            .col-lg-5.text-end { text-align:center!important; margin-bottom:24px; }
            .col-lg-7 { text-align:center; }
            .hero-logo { width:180px; margin:0 auto; display:block; }
            .hero-premium-v2 .display-large { font-size:clamp(1.75rem,8vw,2.5rem); text-align:center; line-height:1.2; }
            .hero-desc { font-size:0.95rem; text-align:center; margin-bottom:1.5rem!important; }
            .btn-contact-premium { display:block; text-align:center; width:100%; max-width:280px; margin:0 auto; }
            [data-aos] { opacity:1!important; transform:none!important; }
        }
        @media (max-width: 480px) {
            .hero-premium-v2 { padding:50px 0 40px; }
            .hero-logo { width:140px; }
            .hero-premium-v2 .display-large { font-size:clamp(1.5rem,7vw,2rem); }
            .hero-desc { font-size:0.875rem; line-height:1.6; }
            .btn-contact-premium { padding:12px 24px; font-size:0.9rem; }
        }

        /* ── Stats Strip ────────────────────────────────────────── */
        .about-stats-strip {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 56px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto 1fr auto 1fr;
            align-items: center;
        }
        .stat-block { text-align: center; padding: 16px 24px; }
        .stat-num {
            display: block;
            font-family: 'Libre Baskerville', serif;
            font-size: 2.6rem; font-weight: 700;
            color: var(--teal); line-height: 1; margin-bottom: 6px;
        }
        .stat-lbl {
            font-size: 0.78rem; text-transform: uppercase;
            letter-spacing: 1.5px; color: var(--muted); font-weight: 600;
        }
        .stat-sep { width: 1px; height: 56px; background: var(--border); }
        @media (max-width: 767px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1px; background: var(--border);
            }
            .stat-block { background: #fff; padding: 28px 16px; }
            .stat-sep { display: none; }
            .stat-num { font-size: 2rem; }
        }

        /* ── About Story Sections ────────────────────────────────── */
        .about-story-section { background: #fff; }
        .about-story-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 480px;
        }
        .about-story-row.reverse { direction: rtl; }
        .about-story-row.reverse > * { direction: ltr; }
        .story-img-col { overflow: hidden; position: relative; }
        .story-img-frame { height: 100%; min-height: 440px; }
        .story-img-frame img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.9s cubic-bezier(0.4,0,0.2,1);
            display: block;
        }
        .about-story-row:hover .story-img-frame img { transform: scale(1.04); }
        .story-text-col {
            display: flex; align-items: center;
            padding: 64px 72px;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }
        .about-story-row.reverse .story-text-col { background: var(--soft-bg); }
        .story-tag {
            display: inline-block; font-size: 0.72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; color: var(--teal);
            background: var(--teal-light); padding: 5px 14px;
            border-radius: 100px; margin-bottom: 18px;
        }
        .story-heading {
            font-family: 'Libre Baskerville', serif;
            font-size: 2rem; font-weight: 700; color: var(--navy);
            line-height: 1.25; margin-bottom: 18px;
        }
        .story-body { color: var(--slate); font-size: 0.98rem; line-height: 1.85; }
        .story-body p { margin-bottom: 12px; }
        .story-body ul { padding-left: 18px; }
        .story-body li { margin-bottom: 6px; }
        @media (max-width: 991px) {
            .about-story-row, .about-story-row.reverse {
                grid-template-columns: 1fr; direction: ltr;
            }
            .story-img-frame { min-height: 300px; }
            .story-text-col { padding: 48px 40px; }
        }
        @media (max-width: 767px) {
            .story-text-col { padding: 36px 24px; }
            .story-heading { font-size: 1.65rem; }
            .story-img-frame { min-height: 240px; }
        }

        /* ── Quote Section ───────────────────────────────────────── */
        .about-quote-section {
            background: var(--soft-bg);
            padding: 100px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .quote-block { max-width: 860px; margin: 0 auto; text-align: center; padding: 0 20px; }
        .quote-mark {
            font-family: 'Libre Baskerville', serif;
            font-size: 7rem; line-height: 0.5;
            color: rgba(6,109,119,0.12); margin-bottom: 28px; display: block;
        }
        .quote-text {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(1.35rem, 2.4vw, 1.9rem);
            color: var(--navy); line-height: 1.55; font-weight: 400; font-style: italic;
            margin: 0 0 40px; border: none; padding: 0;
        }
        .quote-text * { font-style: italic; color: var(--navy); }
        .quote-attribution { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .quote-name { font-weight: 700; font-size: 1rem; color: var(--navy); }
        .quote-role { font-size: 0.82rem; color: var(--teal); letter-spacing: 0.5px; }
        @media (max-width: 767px) {
            .about-quote-section { padding: 70px 0; }
            .quote-mark { font-size: 5rem; }
        }

        /* ── Ecosystem Section ───────────────────────────────────── */
        .about-ecosystem-section { padding: 100px 0; background: #fff; }
        .eco-header { max-width: 580px; margin-bottom: 60px; }
        .eco-tag {
            display: inline-block; font-size: 0.72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; color: var(--teal);
            background: var(--teal-light); padding: 5px 14px;
            border-radius: 100px; margin-bottom: 14px;
        }
        .eco-title {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            color: var(--navy); font-weight: 700; line-height: 1.2;
        }
        .eco-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px; background: var(--border);
            border: 1px solid var(--border); border-radius: 20px; overflow: hidden;
        }
        .eco-card {
            background: #fff; padding: 44px 36px;
            transition: background 0.3s ease; position: relative;
        }
        .eco-card::after {
            content: ''; position: absolute; bottom: 0; left: 36px; right: 36px;
            height: 2px;
            background: linear-gradient(90deg, var(--teal), transparent);
            transform: scaleX(0); transition: transform 0.4s ease; transform-origin: left;
        }
        .eco-card:hover { background: var(--soft-bg); }
        .eco-card:hover::after { transform: scaleX(1); }
        .eco-num {
            display: block; font-family: 'Libre Baskerville', serif;
            font-size: 0.75rem; font-weight: 700; color: var(--teal);
            letter-spacing: 2px; margin-bottom: 14px;
        }
        .eco-card-title { font-size: 1.15rem; font-weight: 700; color: var(--navy); margin-bottom: 12px; line-height: 1.3; }
        .eco-card-body { font-size: 0.93rem; color: var(--muted); line-height: 1.75; }
        .eco-card-body p { margin: 0; }
        @media (max-width: 991px) { .eco-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 767px) {
            .about-ecosystem-section { padding: 70px 0; }
            .eco-grid { grid-template-columns: 1fr; }
            .eco-card { padding: 32px 24px; }
        }

        /* ── Business Portfolio ──────────────────────────────────── */
        .portfolio-section {
            padding: 100px 0;
            background: var(--navy);
            position: relative;
            overflow: hidden;
        }
        .portfolio-section::before {
            content: '';
            position: absolute;
            bottom: -120px; left: -120px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(6,109,119,0.25), transparent 70%);
            filter: blur(60px);
        }
        .portfolio-header {
            margin-bottom: 56px;
        }
        .portfolio-tag {
            display: inline-block; font-size: 0.72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; color: #66d9e8;
            background: rgba(102,217,232,0.1); padding: 5px 14px;
            border-radius: 100px; margin-bottom: 14px;
            border: 1px solid rgba(102,217,232,0.2);
        }
        .portfolio-title {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            color: #ffffff; font-weight: 700; line-height: 1.2;
        }
        .portfolio-subtitle {
            color: rgba(255,255,255,0.55);
            font-size: 1rem; line-height: 1.7;
            max-width: 520px; margin-top: 14px;
        }
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .portfolio-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 36px 24px 28px;
            text-align: center;
            text-decoration: none;
            transition: background 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
            display: flex; flex-direction: column; align-items: center; gap: 18px;
            position: relative; overflow: hidden;
        }
        .portfolio-card::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--teal), #66d9e8);
            transform: scaleX(0); transition: transform 0.35s ease; transform-origin: left;
        }
        .portfolio-card:hover {
            background: rgba(255,255,255,0.09);
            border-color: rgba(6,109,119,0.5);
            transform: translateY(-6px);
        }
        .portfolio-card:hover::after { transform: scaleX(1); }
        .portfolio-logo-wrap {
            width: 90px; height: 90px;
            background: #ffffff;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            padding: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .portfolio-logo-wrap img {
            max-width: 100%; max-height: 58px; object-fit: contain;
        }
        .portfolio-card-name {
            font-size: 0.95rem; font-weight: 700; color: #ffffff;
            line-height: 1.3; letter-spacing: 0.2px;
        }
        .portfolio-card-arrow {
            font-size: 0.75rem; color: rgba(102,217,232,0.7);
            text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700;
            display: flex; align-items: center; gap: 6px;
            transition: gap 0.25s ease, color 0.25s ease;
        }
        .portfolio-card:hover .portfolio-card-arrow {
            gap: 10px; color: #66d9e8;
        }
        @media (max-width: 1100px) { .portfolio-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 767px)  { .portfolio-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } .portfolio-section { padding: 70px 0; } }
        @media (max-width: 480px)  { .portfolio-grid { grid-template-columns: 1fr 1fr; } .portfolio-logo-wrap { width: 70px; height: 70px; } }

        /* ── Trusted Partners Carousel ───────────────────────────── */
        .trusted-partners-section {
            padding: 60px 0 48px;
            background: var(--soft-bg);
            border-top: 1px solid var(--border);
            overflow: hidden;
        }
        .partners-header { text-align: center; margin-bottom: 32px; }
        .partners-tag {
            display: inline-block; font-size: 0.72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; color: var(--teal);
            background: var(--teal-light); padding: 5px 14px;
            border-radius: 100px; margin-bottom: 12px;
        }
        .partners-title {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(1.5rem, 3vw, 2.1rem);
            color: var(--navy); font-weight: 700;
        }

        /* Swiper partner strip */
        .partner-swiper-outer {
            overflow: hidden;
            padding: 6px 0 8px;
        }
        .partnerSwiper {
            overflow: visible !important;
            height: auto !important;
        }
        .partnerSwiper .swiper-wrapper {
            align-items: stretch !important;
        }
        .partnerSwiper .swiper-slide {
            height: auto !important;
        }

        .partner-slide { width: 170px !important; }

        .partner-logo-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 16px 14px;
            height: 100%;
            box-sizing: border-box;
            transition: box-shadow 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
        }
        .partner-logo-item:hover {
            box-shadow: 0 8px 28px rgba(6,109,119,0.15);
            border-color: var(--teal);
            transform: scale(1.04);
        }
        .partner-logo-item img {
            max-width: 120px;
            max-height: 120px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            transition: filter 0.3s ease;
        }
        .partner-logo-item:hover img {
            filter: drop-shadow(0 2px 6px rgba(6,109,119,0.25));
        }
        .partner-logo-item .partner-name {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--muted);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            line-height: 1.3;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: color 0.3s ease;
        }
        .partner-logo-item:hover .partner-name {
            color: var(--teal);
        }

        /* ── CTA Section ─────────────────────────────────────────── */
        .about-cta-section {
            padding: 120px 0; background: var(--navy);
            position: relative; overflow: hidden;
        }
        .about-cta-section::before {
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(6,109,119,0.3), transparent 70%);
            filter: blur(80px);
        }
        .cta-inner { max-width: 800px; margin: 0 auto; text-align: center; position: relative; }
        .cta-tag {
            display: inline-block; font-size: 0.72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; color: #66d9e8;
            background: rgba(102,217,232,0.1); padding: 5px 14px;
            border-radius: 100px; margin-bottom: 24px;
            border: 1px solid rgba(102,217,232,0.2);
        }
        .cta-heading {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: #fff; font-weight: 700; line-height: 1.2; margin-bottom: 22px;
        }
        .cta-desc {
            font-size: 1.05rem; color: rgba(255,255,255,0.6);
            line-height: 1.8; max-width: 680px; margin: 0 auto 48px;
        }
        .cta-actions { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; }
        .cta-btn-primary {
            display: inline-flex; align-items: center;
            padding: 16px 36px; background: var(--teal); color: #fff;
            font-weight: 700; font-size: 0.88rem; text-transform: uppercase;
            letter-spacing: 1.5px; border-radius: 100px; text-decoration: none;
            transition: all 0.3s ease;
        }
        .cta-btn-primary:hover {
            background: #088a95; color: #fff; transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(6,109,119,0.4);
        }
        .cta-btn-outline {
            display: inline-flex; align-items: center;
            padding: 16px 36px; background: transparent; color: #fff;
            font-weight: 700; font-size: 0.88rem; text-transform: uppercase;
            letter-spacing: 1.5px; border-radius: 100px;
            border: 2px solid rgba(255,255,255,0.25); text-decoration: none;
            transition: all 0.3s ease;
        }
        .cta-btn-outline:hover {
            background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.5);
            color: #fff; transform: translateY(-3px);
        }
        @media (max-width: 767px) {
            .about-cta-section { padding: 80px 0; }
            .cta-heading { font-size: 1.8rem; }
            .cta-actions { flex-direction: column; align-items: center; }
            .cta-btn-primary, .cta-btn-outline { width: 100%; max-width: 300px; justify-content: center; }
        }
    </style>

    <div class="about-page-wrapper">

        {{-- ══════════════════════ HERO (UNCHANGED) ══════════════════════ --}}
        <section class="hero-premium-v2">
            <div class="hero-overlay"></div>
            <div class="container hero-content-container">
                <div class="row align-items-center g-5 div-content">
                    <div class="col-lg-7" data-aos="fade-right">
                        <h1 class="display-large">
                            About <br>
                            <span style="color:#fff;">{{ $about_us->title ?? 'Alpha Corp' }}</span>
                        </h1>
                        <p class="hero-desc mb-5">
                            {!! $about_us->description ?? 'Default Description Here' !!}
                        </p>
                        <a href="#about-story" class="btn-contact-premium">Explore Our Journey</a>
                    </div>
                    <div class="col-lg-5 text-end" data-aos="fade-left" data-aos-delay="200">
                        <img src="{{ asset('public/uploads/about_us_logos/' . ($about_us->logo ?? 'default.jpg')) }}"
                            alt="Logo" class="hero-logo" />
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════ STATS STRIP ══════════════════════════ --}}
        <section class="about-stats-strip">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-block">
                        <span class="stat-num">25+</span>
                        <span class="stat-lbl">Years of Excellence</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-block">
                        <span class="stat-num">500+</span>
                        <span class="stat-lbl">Healthcare Clients</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-block">
                        <span class="stat-num">50+</span>
                        <span class="stat-lbl">Countries Served</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-block">
                        <span class="stat-num">1,000+</span>
                        <span class="stat-lbl">Projects Delivered</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════ ABOUT STORY SECTIONS ════════════════ --}}
        <section class="about-story-section" id="about-story">
            @foreach($about_content as $item)
            <div class="about-story-row {{ $loop->index % 2 !== 0 ? 'reverse' : '' }}" data-aos="fade-up">
                <div class="story-img-col">
                    <div class="story-img-frame">
                        <img src="{{ $item->image
                            ? asset('public/uploads/about_content/' . $item->image)
                            : asset('public/uploads/about_content/default.jpg') }}"
                            alt="{{ $item->content_title }}" loading="lazy">
                    </div>
                </div>
                <div class="story-text-col">
                    <div class="story-text-inner">
                        <span class="story-tag">About Us</span>
                        <h2 class="story-heading">{{ $item->content_title }}</h2>
                        <div class="story-body">{!! $item->content !!}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </section>

        {{-- ══════════════════════ QUOTE BLOCK ════════════════════════ --}}
        @if($about_quotes->count())
        <section class="about-quote-section">
            <div class="container">
                @foreach($about_quotes as $quote)
                <div class="quote-block">
                    <span class="quote-mark">&ldquo;</span>
                    <blockquote class="quote-text">{!! $quote->About_quote !!}</blockquote>
                    @if($quote->quote_title)
                    <div class="quote-attribution">
                        <span class="quote-name">{{ $quote->quote_title }}</span>
                        @if($quote->quote_sub_title || $quote->company_name)
                        <span class="quote-role">
                            {{ $quote->quote_sub_title ?? '' }}{{ $quote->company_name ? ' · ' . $quote->company_name : '' }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ══════════════════════ ECOSYSTEM SECTION ══════════════════ --}}
        @if($eco_systems->count())
        <section class="about-ecosystem-section">
            <div class="container">
                <div class="eco-header" data-aos="fade-up">
                    <span class="eco-tag">Our Ecosystem</span>
                    <h2 class="eco-title">Specialized Business<br>Consultancy</h2>
                </div>
                <div class="eco-grid">
                    @foreach($eco_systems as $index => $eco)
                    <div class="eco-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 80 }}">
                        <span class="eco-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="eco-card-title">{{ $eco->heading }}</h3>
                        <div class="eco-card-body">{!! $eco->description !!}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- ══════════════════════ BUSINESS PORTFOLIO ════════════════ --}}
        @if(isset($brands) && $brands->count())
        <section class="portfolio-section">
            <div class="container">
                <div class="portfolio-header" data-aos="fade-up">
                    <span class="portfolio-tag">Our Portfolio</span>
                    <h2 class="portfolio-title">Alpha Health Group Brands</h2>
                    <p class="portfolio-subtitle">Explore our family of specialised healthcare companies, each delivering excellence across distinct areas of the sector.</p>
                </div>
                <div class="portfolio-grid">
                    @foreach($brands as $brand)
                    <a href="{{ route('front.singleBrand', $brand->slug) }}" class="portfolio-card" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 80 }}">
                        <div class="portfolio-logo-wrap">
                            @if($brand->logo)
                                <img src="{{ asset('public/uploads/brands/' . $brand->logo) }}" alt="{{ $brand->name }}" loading="lazy">
                            @else
                                <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="{{ $brand->name }}" loading="lazy">
                            @endif
                        </div>
                        <span class="portfolio-card-name">{{ $brand->name }}</span>
                        <span class="portfolio-card-arrow">View <i class="fas fa-arrow-right"></i></span>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- ══════════════════════ TRUSTED PARTNERS ═══════════════════ --}}
        @if($clients->count())
        <section class="trusted-partners-section">
            <div class="container">
                <div class="partners-header" data-aos="fade-up">
                    <span class="partners-tag">Trusted By</span>
                    <h2 class="partners-title">Healthcare Leaders Who Trust Us</h2>
                </div>
            </div>

            <div class="partner-swiper-outer">
                <div class="swiper partnerSwiper">
                    <div class="swiper-wrapper">
                        @foreach($clients as $client)
                        <div class="swiper-slide partner-slide">
                            <div class="partner-logo-item">
                                <img src="{{ asset('public/uploads/clients/' . $client->logo) }}"
                                    alt="{{ $client->name }}" loading="lazy">
                                <span class="partner-name">{{ $client->name }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="text-center mt-3" data-aos="fade-up">
                    <a href="{{ route('front.our-clients') }}" class="btn btn-outline-dark rounded-pill px-5 py-2 fw-bold" style="font-size:0.88rem;letter-spacing:0.5px;">
                        View All Clients <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </section>
        @endif

        {{-- ══════════════════════ TESTIMONIAL PILLS ══════════════════ --}}
        @include('front.partials.testimonial-pills')

        {{-- ══════════════════════ CTA SECTION ════════════════════════ --}}
        <section class="about-cta-section">
            <div class="container">
                <div class="cta-inner" data-aos="fade-up">
                    <span class="cta-tag">Get In Touch</span>
                    <h2 class="cta-heading">Partner with the Global Leader<br>in Healthcare Excellence</h2>
                    <p class="cta-desc">
                        From advanced diagnostics to strategic consultancy, we empower healthcare providers to reach their peak potential. Our experts are ready to architect your success.
                    </p>
                    <div class="cta-actions">
                        <a href="{{ route('contact') }}" class="cta-btn-primary">
                            Consult an Expert <i class="fas fa-paper-plane ms-2"></i>
                        </a>
                        <a href="tel:+97137802818" class="cta-btn-outline">
                            <i class="fas fa-phone-alt me-2"></i> +971 3 780 2818
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>{{-- .about-page-wrapper --}}

@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 900, once: true, offset: 80 });
        }

        /* Partner carousel — continuous scroll, no pause between slides */
        if (typeof Swiper !== 'undefined' && document.querySelector('.partnerSwiper')) {
            new Swiper('.partnerSwiper', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                loop: true,
                speed: 3500,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                allowTouchMove: true,
                grabCursor: true,
            });
        }
    });
</script>
@endsection
