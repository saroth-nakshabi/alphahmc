@extends('front/layout-2')

@push('page_title', 'Healthcare Quality Assurance | Tawqeet | Alpha Health Group')

@section('meta_description')Tawqeet by Alpha Health Group — a comprehensive healthcare quality assurance platform that monitors KPIs, tracks compliance, and drives excellence for medical and homecare facilities in the UAE.@endsection

@section('custom_css')
    <style>
        body { font-family: 'Outfit', sans-serif; }

        /* Hero */
        .tawqeet-hero {
            position: relative;
            min-height: 280px;
            background: url("{{ asset('public/front/assets/img/bg-tawkeet.jpg') }}") center/cover no-repeat;
            display: flex;
            align-items: center;
        }
        .tawqeet-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(6,109,119,0.82) 0%, rgba(0,144,149,0.7) 100%);
        }
        .tawqeet-hero .hero-inner { position: relative; z-index: 1; width: 100%; padding: 60px 0; }
        .tawqeet-hero img.tawqeet-logo { max-width: 240px; filter: brightness(0) invert(1); }
        .tawqeet-hero .badge-pill {
            background: rgba(255,255,255,0.18);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 0.78rem;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 18px;
        }
        .tawqeet-hero h1 { color: #fff; font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; margin-bottom: 10px; }
        .tawqeet-hero p.sub { color: rgba(255,255,255,0.85); font-size: 1rem; }
        .taw-breadcrumb { background: rgba(255,255,255,0.12); border-radius: 8px; padding: 9px 16px; display: inline-flex; gap: 6px; align-items: center; margin-top: 18px; }
        .taw-breadcrumb a, .taw-breadcrumb span { color: rgba(255,255,255,0.85); font-size: 0.83rem; text-decoration: none; }
        .taw-breadcrumb .sep { color: rgba(255,255,255,0.45); }
        .taw-breadcrumb span.active { color: #fff; font-weight: 600; }

        /* Section shared */
        .sec-label {
            display: inline-block;
            background: #e6f4f5;
            color: #066D77;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 12px;
        }
        .sec-title { font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 800; color: #0d2126; }
        .sec-sub { color: #5a7070; font-size: 0.97rem; line-height: 1.75; }

        /* Intro card */
        .intro-quote {
            background: #fff;
            border-left: 4px solid #066D77;
            border-radius: 0 12px 12px 0;
            padding: 32px 36px;
            box-shadow: 0 4px 20px rgba(6,109,119,0.08);
            font-style: italic;
            color: #1a4a4e;
            font-size: 1.05rem;
            line-height: 1.8;
        }

        /* Feature icons */
        .feat-icon-wrap {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #066D77, #009095);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            color: #fff;
            font-size: 1.8rem;
            box-shadow: 0 6px 20px rgba(6,109,119,0.25);
        }
        .feat-label { font-weight: 600; color: #0d2126; font-size: 0.95rem; text-align: center; }

        /* How Tawqeet Works */
        .tawqeet-how { padding: 80px 0; background: #f8fafb; }

        /* Packages */
        .pkg-card {
            background: #fff;
            border-radius: 16px;
            padding: 32px 28px;
            height: 100%;
            border: 1px solid #e4eff0;
            transition: transform .25s, box-shadow .25s;
            position: relative;
            overflow: hidden;
        }
        .pkg-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #066D77, #009095);
        }
        .pkg-card:hover { transform: translateY(-5px); box-shadow: 0 14px 36px rgba(6,109,119,0.13); }
        .pkg-card h4 { font-size: 1.1rem; font-weight: 700; color: #066D77; margin-bottom: 14px; }
        .pkg-card p { color: #5a7070; font-size: 0.93rem; line-height: 1.7; }

        /* Facility cards */
        .fac-card {
            border-radius: 16px;
            padding: 36px 32px;
            height: 100%;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .fac-card.mc { background: linear-gradient(135deg, #066D77 0%, #008a8e 100%); }
        .fac-card.hc { background: linear-gradient(135deg, #0d4f5c 0%, #066D77 100%); }
        .fac-card::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 150px; height: 150px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .fac-card h3 { font-size: 1.4rem; font-weight: 800; margin-bottom: 16px; }
        .fac-card p { color: rgba(255,255,255,0.88); font-size: 0.94rem; line-height: 1.7; margin-bottom: 20px; }
        .fac-list { list-style: none; padding: 0; margin: 0 0 24px; }
        .fac-list li {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            font-size: 0.92rem;
            color: rgba(255,255,255,0.92);
        }
        .fac-list li:last-child { border-bottom: none; }
        .fac-list li i { color: rgba(255,255,255,0.7); font-size: 0.8rem; }
        .fac-card .btn-light-ghost {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.35);
            color: #fff;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            transition: background .2s;
        }
        .fac-card .btn-light-ghost:hover { background: rgba(255,255,255,0.28); }

        /* Contact form */
        .contact-section { padding: 80px 0; background: #f8fafb; }
        .contact-card { background: #fff; border-radius: 20px; padding: 48px 40px; box-shadow: 0 8px 32px rgba(6,109,119,0.08); }
        .contact-card .form-control {
            border: 1.5px solid #d8e8ea;
            border-radius: 8px;
            padding: 12px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            transition: border-color .2s;
        }
        .contact-card .form-control:focus { border-color: #066D77; box-shadow: 0 0 0 3px rgba(6,109,119,0.1); }
        .contact-card .btn-submit {
            background: linear-gradient(135deg, #066D77, #009095);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px 32px;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Outfit', sans-serif;
            transition: opacity .2s;
        }
        .contact-card .btn-submit:hover { opacity: 0.9; }
        .success-msg { display: none; color: #066D77; text-align: center; font-weight: 600; padding: 12px; background: #e6f4f5; border-radius: 8px; }
    </style>
@endSection

@section('content')

    {{-- Hero --}}
    <section class="tawqeet-hero">
        <div class="container hero-inner">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge-pill">Healthcare Quality Assurance</span>
                    <h1>Facility Quality Assurance</h1>
                    <p class="sub">Helping healthcare facilities achieve compliance, operational excellence, and patient satisfaction.</p>
                    <div class="taw-breadcrumb">
                        <a href="{{ route('home') }}"><i class="bi bi-house-fill me-1"></i>Home</a>
                        <span class="sep">/</span>
                        <a href="{{ route('front.all-services') }}">Services</a>
                        <span class="sep">/</span>
                        <span class="active">Facility Quality Assurance</span>
                    </div>
                </div>
                <div class="col-lg-5 text-center mt-4 mt-lg-0">
                    <img class="tawqeet-logo img-fluid" src="{{ asset('public/front/assets/img/tawqueet-logo.svg') }}" alt="Tawqeet — Facility Quality Assurance">
                </div>
            </div>
        </div>
    </section>

    {{-- Main Description --}}
    <section style="padding: 72px 0; background: #fff;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="text-center mb-5">
                        <span class="sec-label">What We Do</span>
                        <h2 class="sec-title mt-2">Tawqeet — Results-Driven Quality Assurance</h2>
                    </div>
                    <div class="intro-quote mb-5">
                        We analyze your existing healthcare business system to understand areas for improvement and enhance the working processes to achieve optimal patient satisfaction and financial outcomes. We help you define combined Key Performance Indicators (KPIs) across all facility processes, which aids in the business improvement plan developed by our healthcare expert team. Our extensive expertise in healthcare makes it easier to design tailor-made performance improvement plans that align with international best practices and ensure regulatory compliance.
                    </div>
                    <p class="sec-sub mb-4">
                        We assign a dedicated project manager to every client — your single point of contact throughout the entire implementation. This manager continuously monitors the engagement and makes necessary adjustments. We also conduct training exercises for your in-house staff and design periodic monitoring systems to ensure continuity of compliance. <strong>Improvise, Accomplish, and Sustain</strong> is what Tawqeet stands for.
                    </p>

                    <div class="row g-4 text-center mt-2">
                        <div class="col-4">
                            <div class="feat-icon-wrap">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div class="feat-label">Dedicated Project Manager</div>
                        </div>
                        <div class="col-4">
                            <div class="feat-icon-wrap">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <div class="feat-label">Continuous Monitoring</div>
                        </div>
                        <div class="col-4">
                            <div class="feat-icon-wrap">
                                <i class="bi bi-clipboard-data-fill"></i>
                            </div>
                            <div class="feat-label">Industry Best Practices</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="tawqeet-how">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-lg-4">
                    <span class="sec-label">How It Works</span>
                    <h2 class="sec-title mt-2 mb-4">How Tawqeet Works</h2>
                    <p class="sec-sub">
                        Tawqeet analyses and reviews the adaptability and compliance of your facility against defined standards and guidelines across Clinical, Operational, Infrastructural, and Administrative domains.
                    </p>
                    <p class="sec-sub mt-3">
                        Tawqeet ensures the facility fulfils operational efficiency, patient satisfaction, and quality of service — providing meaningful insights to facility owners and investors to evaluate outcomes and sustainability. Tawqeet not only enhances the entire work system of a facility, but ensures full regulatory compliance too.
                    </p>
                </div>
                <div class="col-lg-8">
                    <span class="sec-label mb-3 d-block">Key Performance Indicators</span>
                            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 641.74 272.54">
                                <defs>
                                    <style>
                                        .cls-1 {
                                            fill: #409bb2;
                                            opacity: 0.8;
                                        }

                                        .cls-2 {
                                            font-size: 12.55px;
                                        }

                                        .cls-12,
                                        .cls-13,
                                        .cls-2,
                                        .cls-30,
                                        .cls-35 {
                                            fill: #fff;
                                        }

                                        .cls-13,
                                        .cls-2,
                                        .cls-26,
                                        .cls-30,
                                        .cls-35,
                                        .cls-38,
                                        .cls-41,
                                        .cls-46 {
                                            font-family: Roboto-Regular, Roboto;
                                        }

                                        .cls-3 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-4 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-5 {
                                            letter-spacing: -0.13em;
                                        }

                                        .cls-6 {
                                            letter-spacing: -0.08em;
                                        }

                                        .cls-7 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-8 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-9 {
                                            fill: #d81c54;
                                        }

                                        .cls-10,
                                        .cls-11,
                                        .cls-9 {
                                            opacity: 0.7;
                                        }

                                        .cls-10 {
                                            fill: #08609f;
                                        }

                                        .cls-11 {
                                            fill: #209b5f;
                                        }

                                        .cls-13 {
                                            font-size: 11.78px;
                                        }

                                        .cls-14 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-15 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-16 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-17 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-18 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-19 {
                                            letter-spacing: -0.07em;
                                        }

                                        .cls-20 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-21 {
                                            letter-spacing: -0.02em;
                                        }

                                        .cls-22 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-23 {
                                            letter-spacing: -0.08em;
                                        }

                                        .cls-24 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-25 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-26 {
                                            font-size: 10.45px;
                                        }

                                        .cls-26,
                                        .cls-38,
                                        .cls-41,
                                        .cls-46 {
                                            fill: #414042;
                                        }

                                        .cls-27 {
                                            fill: #3471b8;
                                        }

                                        .cls-28 {
                                            fill: #ef3d6c;
                                        }

                                        .cls-29 {
                                            fill: #24b574;
                                        }

                                        .cls-30 {
                                            font-size: 9.57px;
                                        }

                                        .cls-31 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-32 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-33 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-34 {
                                            fill: #7f4a9d;
                                        }

                                        .cls-35 {
                                            font-size: 8.82px;
                                        }

                                        .cls-36 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-37 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-38,
                                        .cls-41,
                                        .cls-46 {
                                            font-size: 15.67px;
                                        }

                                        .cls-38,
                                        .cls-45 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-39 {
                                            letter-spacing: 0.02em;
                                        }

                                        .cls-40,
                                        .cls-41 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-42 {
                                            letter-spacing: -0.12em;
                                        }

                                        .cls-43 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-44 {
                                            letter-spacing: -0.05em;
                                        }

                                        .cls-46 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-47 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-48 {
                                            letter-spacing: -0.01em;
                                        }
                                    </style>
                                </defs>
                                <title>towqeet-process</title>
                                <circle class="cls-1" cx="579.86" cy="118.51" r="54.84" /><text class="cls-2"
                                    transform="translate(557.35 106.11)">B<tspan class="cls-3" x="7.81" y="0">E</tspan>
                                    <tspan class="cls-4" x="15.07" y="0">T</tspan>
                                    <tspan x="22.66" y="0">TER</tspan>
                                    <tspan x="-15.99" y="15.06">HEA</tspan>
                                    <tspan class="cls-5" x="8.28" y="15.06">L</tspan>
                                    <tspan x="13.34" y="15.06">THCARE</tspan>
                                    <tspan class="cls-6" x="-2.85" y="30.12">F</tspan>
                                    <tspan class="cls-7" x="3.04" y="30.12">A</tspan>
                                    <tspan x="11.16" y="30.12">CIL</tspan>
                                    <tspan class="cls-8" x="29.5" y="30.12">I</tspan>
                                    <tspan class="cls-4" x="32.74" y="30.12">T</tspan>
                                    <tspan x="40.32" y="30.12">Y</tspan>
                                </text>
                                <circle class="cls-9" cx="322.39" cy="156.72" r="56.09" />
                                <circle class="cls-10" cx="320.55" cy="80.29" r="56.09" />
                                <circle class="cls-11" cx="386.4" cy="111.33" r="56.09" />
                                <path class="cls-12"
                                    d="M368.13,104.06a5.29,5.29,0,0,1-.41,2.15,3.15,3.15,0,0,1-1.18,1.4,3.47,3.47,0,0,1-3.52,0,3.25,3.25,0,0,1-1.19-1.39,5.06,5.06,0,0,1-.43-2.09v-.6a5.06,5.06,0,0,1,.42-2.13,3.1,3.1,0,0,1,2.94-1.91,3.32,3.32,0,0,1,1.78.48,3.23,3.23,0,0,1,1.18,1.41,5.32,5.32,0,0,1,.41,2.15Zm-1.1-.54a3.83,3.83,0,0,0-.59-2.29,2.15,2.15,0,0,0-3.33,0,3.67,3.67,0,0,0-.61,2.21v.62a3.8,3.8,0,0,0,.6,2.27,2.13,2.13,0,0,0,3.33,0,3.7,3.7,0,0,0,.6-2.23Z" />
                                <path class="cls-12"
                                    d="M370.89,104.7V108h-1.1V99.6h3.09a3.14,3.14,0,0,1,2.15.7,2.39,2.39,0,0,1,.78,1.86,2.35,2.35,0,0,1-.76,1.88,3.25,3.25,0,0,1-2.18.66Zm0-.91h2a2,2,0,0,0,1.35-.41,1.52,1.52,0,0,0,.48-1.21,1.57,1.57,0,0,0-.48-1.2,1.83,1.83,0,0,0-1.29-.46h-2.05Z" />
                                <path class="cls-12" d="M382,104.11h-3.63v3h4.22V108h-5.32V99.6h5.26v.91h-4.16v2.69H382Z" />
                                <path class="cls-12"
                                    d="M387,104.59h-2V108h-1.11V99.6h2.77a3.3,3.3,0,0,1,2.18.65,2.3,2.3,0,0,1,.76,1.87,2.24,2.24,0,0,1-.42,1.36,2.56,2.56,0,0,1-1.18.87l2,3.56V108H388.8Zm-2-.9h1.69a1.93,1.93,0,0,0,1.31-.43,1.44,1.44,0,0,0,.49-1.14,1.53,1.53,0,0,0-.46-1.19,1.94,1.94,0,0,0-1.34-.42H385Z" />
                                <path class="cls-12"
                                    d="M395.79,105.79h-3.51l-.79,2.19h-1.14l3.2-8.38h1l3.2,8.38h-1.13Zm-3.17-.91h2.84L394,101Z" />
                                <path class="cls-12" d="M403.89,100.51H401.2V108h-1.1v-7.47h-2.68V99.6h6.47Z" />
                                <path class="cls-12" d="M406.32,108h-1.1V99.6h1.1Z" />
                                <path class="cls-12"
                                    d="M414.78,104.06a5.29,5.29,0,0,1-.41,2.15,3.15,3.15,0,0,1-1.18,1.4,3.47,3.47,0,0,1-3.52,0,3.25,3.25,0,0,1-1.19-1.39,5.06,5.06,0,0,1-.44-2.09v-.6a5.22,5.22,0,0,1,.42-2.13,3.29,3.29,0,0,1,1.19-1.42,3.17,3.17,0,0,1,1.76-.49,3.26,3.26,0,0,1,1.77.48,3.18,3.18,0,0,1,1.19,1.41,5.32,5.32,0,0,1,.41,2.15Zm-1.1-.54a3.76,3.76,0,0,0-.6-2.29,2.14,2.14,0,0,0-3.32,0,3.74,3.74,0,0,0-.62,2.21v.62a3.8,3.8,0,0,0,.61,2.27,2.13,2.13,0,0,0,3.33,0,3.7,3.7,0,0,0,.6-2.23Z" />
                                <path class="cls-12"
                                    d="M422.87,108h-1.11l-4.21-6.46V108h-1.11V99.6h1.11l4.22,6.48V99.6h1.1Z" />
                                <path class="cls-12"
                                    d="M429.56,105.79h-3.51l-.79,2.19h-1.14l3.2-8.38h1l3.2,8.38h-1.13Zm-3.17-.91h2.84L427.81,101Z" />
                                <path class="cls-12" d="M433.73,107.07h4V108h-5.08V99.6h1.11Z" />
                                <path class="cls-12"
                                    d="M373.25,118.24h-3.63v3h4.22v.9h-5.32v-8.37h5.26v.9h-4.16v2.7h3.63Z" />
                                <path class="cls-12" d="M379.83,118.41h-3.52v3.7h-1.1v-8.37h5.19v.9h-4.09v2.87h3.52Z" />
                                <path class="cls-12" d="M386.34,118.41h-3.52v3.7h-1.1v-8.37h5.19v.9h-4.09v2.87h3.52Z" />
                                <path class="cls-12" d="M389.41,122.11h-1.1v-8.37h1.1Z" />
                                <path class="cls-12"
                                    d="M397.59,119.45a3.05,3.05,0,0,1-1,2,3.17,3.17,0,0,1-2.19.73,3,3,0,0,1-2.38-1.07,4.27,4.27,0,0,1-.89-2.85v-.8a4.72,4.72,0,0,1,.41-2.05,3.09,3.09,0,0,1,1.18-1.36,3.26,3.26,0,0,1,1.77-.48,3,3,0,0,1,2.14.75,3.09,3.09,0,0,1,.94,2.06h-1.11a2.42,2.42,0,0,0-.62-1.46,1.94,1.94,0,0,0-1.35-.45,2,2,0,0,0-1.65.79,3.59,3.59,0,0,0-.61,2.23v.81a3.7,3.7,0,0,0,.58,2.17,1.81,1.81,0,0,0,1.59.8,2.17,2.17,0,0,0,1.41-.41,2.29,2.29,0,0,0,.65-1.46Z" />
                                <path class="cls-12" d="M400.28,122.11h-1.1v-8.37h1.1Z" />
                                <path class="cls-12" d="M407,118.24h-3.63v3h4.21v.9H402.3v-8.37h5.27v.9h-4.16v2.7H407Z" />
                                <path class="cls-12"
                                    d="M415.43,122.11h-1.11l-4.21-6.45v6.45H409v-8.37h1.11l4.22,6.48v-6.48h1.1Z" />
                                <path class="cls-12"
                                    d="M423.55,119.45a3.05,3.05,0,0,1-1,2,3.17,3.17,0,0,1-2.19.73,3,3,0,0,1-2.38-1.07,4.27,4.27,0,0,1-.89-2.85v-.8a4.72,4.72,0,0,1,.41-2.05,3.09,3.09,0,0,1,1.18-1.36,3.26,3.26,0,0,1,1.77-.48,3,3,0,0,1,2.14.75,3.09,3.09,0,0,1,.94,2.06h-1.11a2.42,2.42,0,0,0-.62-1.46,1.94,1.94,0,0,0-1.35-.45,2,2,0,0,0-1.65.79,3.59,3.59,0,0,0-.61,2.23v.81a3.62,3.62,0,0,0,.58,2.17,1.81,1.81,0,0,0,1.59.8,2.17,2.17,0,0,0,1.41-.41,2.29,2.29,0,0,0,.65-1.46Z" />
                                <path class="cls-12"
                                    d="M427.61,117.94l2.19-4.2h1.25L428.16,119v3.12h-1.1V119l-2.89-5.25h1.27Z" /><text
                                    class="cls-13" transform="translate(283.12 58.56)">Q<tspan class="cls-14" x="8.1"
                                        y="0">U</tspan>
                                    <tspan x="15.61" y="0">AL</tspan>
                                    <tspan class="cls-15" x="29.63" y="0">I</tspan>
                                    <tspan class="cls-16" x="32.67" y="0">T</tspan>
                                    <tspan x="39.79" y="0">Y OF</tspan>
                                    <tspan x="9.25" y="14.13">SE</tspan>
                                    <tspan class="cls-17" x="22.93" y="14.13">R</tspan>
                                    <tspan class="cls-18" x="30.08" y="14.13">VICE</tspan>
                                </text><text class="cls-13" transform="translate(292.55 164.99)">
                                    <tspan class="cls-19">P</tspan>
                                    <tspan class="cls-20" x="6.64" y="0">A</tspan>
                                    <tspan x="13.58" y="0">TIE</tspan>
                                    <tspan class="cls-15" x="30.5" y="0">N</tspan>
                                    <tspan class="cls-21" x="38.73" y="0">T</tspan>
                                    <tspan x="-16.5" y="14.13">S</tspan>
                                    <tspan class="cls-22" x="-9.51" y="14.13">A</tspan>
                                    <tspan class="cls-18" x="-2.57" y="14.13">TIS</tspan>
                                    <tspan class="cls-23" x="14.65" y="14.13">F</tspan>
                                    <tspan class="cls-24" x="20.18" y="14.13">A</tspan>
                                    <tspan class="cls-25" x="27.8" y="14.13">C</tspan>
                                    <tspan class="cls-18" x="35.3" y="14.13">TION</tspan>
                                </text><text class="cls-26"
                                    transform="matrix(0.93, 0.36, -0.36, 0.93, 397.1, 21.23)">H</text><text class="cls-26"
                                    transform="translate(403.94 23.89) rotate(24.81)">E</text><text class="cls-26"
                                    transform="translate(409.24 26.32) rotate(28.33)">A</text><text class="cls-26"
                                    transform="matrix(0.85, 0.53, -0.53, 0.85, 415.14, 29.52)">L</text><text
                                    class="cls-26" transform="translate(418.68 31.66) rotate(34.24)">T</text><text
                                    class="cls-26"
                                    transform="matrix(0.79, 0.62, -0.62, 0.79, 423.75, 35.09)">H</text><text
                                    class="cls-26" transform="matrix(0.76, 0.65, -0.65, 0.76, 429.51, 39.66)">
                                </text><text class="cls-26"
                                    transform="translate(431.46 41.27) rotate(43.32)">A</text><text class="cls-26"
                                    transform="translate(436.29 45.82) rotate(46.98)">U</text><text class="cls-26"
                                    transform="matrix(0.64, 0.77, -0.77, 0.64, 440.83, 50.69)">T</text><text
                                    class="cls-26" transform="matrix(0.58, 0.81, -0.81, 0.58, 444.74, 55.4)">H</text><text
                                    class="cls-26"
                                    transform="matrix(0.53, 0.85, -0.85, 0.53, 449.01, 61.33)">O</text><text
                                    class="cls-26" transform="translate(452.74 67.37) rotate(61.95)">R</text><text
                                    class="cls-26" transform="translate(455.69 72.98) rotate(64.47)">I</text><text
                                    class="cls-26"
                                    transform="matrix(0.39, 0.92, -0.92, 0.39, 456.86, 75.33)">T</text><text
                                    class="cls-26" transform="translate(459.29 81.03) rotate(70.35)">Y</text><text
                                    class="cls-26" transform="translate(461.34 86.9) rotate(72.79)"> </text><text
                                    class="cls-26" transform="translate(462.14 89.27) rotate(75.46)">G</text><text
                                    class="cls-26" transform="translate(463.9 96.06) rotate(79.3)">U</text><text
                                    class="cls-26" transform="translate(465.09 102.63) rotate(81.95)">I</text><text
                                    class="cls-26" transform="matrix(0.09, 1, -1, 0.09, 465.53, 105.36)">D</text><text
                                    class="cls-26" transform="translate(466.15 112.09) rotate(88.15)">E</text><text
                                    class="cls-26" transform="translate(466.34 117.91) rotate(91.3)">L</text><text
                                    class="cls-26" transform="translate(466.19 123.46) rotate(93.61)">I</text><text
                                    class="cls-26" transform="translate(466.07 126.22) rotate(96.4)">N</text><text
                                    class="cls-26"
                                    transform="matrix(-0.17, 0.98, -0.98, -0.17, 465.23, 133.52)">E</text><text
                                    class="cls-26" transform="translate(464.22 139.26) rotate(103.3)">S</text><text
                                    class="cls-26" transform="translate(462.78 145.22) rotate(105.7)"> </text><text
                                    class="cls-26" transform="translate(462.14 147.66) rotate(108.29)">A</text><text
                                    class="cls-26"
                                    transform="matrix(-0.38, 0.93, -0.93, -0.38, 460.06, 154)">N</text><text
                                    class="cls-26"
                                    transform="matrix(-0.44, 0.9, -0.9, -0.44, 457.28, 160.8)">D</text><text
                                    class="cls-26" transform="translate(454.25 166.86) rotate(118.77)"> </text><text
                                    class="cls-26"
                                    transform="matrix(-0.52, 0.85, -0.85, -0.52, 453.08, 169.08)">R</text><text
                                    class="cls-26"
                                    transform="matrix(-0.57, 0.82, -0.82, -0.57, 449.79, 174.49)">E</text><text
                                    class="cls-26" transform="translate(446.5 179.3) rotate(128.24)">G</text><text
                                    class="cls-26" transform="translate(442.17 184.79) rotate(132.03)">U</text><text
                                    class="cls-26"
                                    transform="matrix(-0.71, 0.7, -0.7, -0.71, 437.69, 189.74)">L</text><text
                                    class="cls-26" transform="translate(433.7 193.7) rotate(138.86)">A</text><text
                                    class="cls-26" transform="translate(429.16 197.68) rotate(142.05)">T</text><text
                                    class="cls-26" transform="translate(424.27 201.45) rotate(144.52)">I</text><text
                                    class="cls-26"
                                    transform="matrix(-0.84, 0.54, -0.54, -0.84, 422.05, 203.1)">O</text><text
                                    class="cls-26" transform="translate(416.12 206.92) rotate(151.24)">N</text><text
                                    class="cls-26" transform="translate(409.68 210.44) rotate(154.97)">S</text>
                                <circle class="cls-27" cx="115.69" cy="87.28" r="28.17" />
                                <path class="cls-12"
                                    d="M100.27,90.83a4.4,4.4,0,0,1-1.85.34,3.29,3.29,0,0,1-3.48-3.56,3.5,3.5,0,0,1,3.67-3.7,3.85,3.85,0,0,1,1.67.31l-.21.74a3.46,3.46,0,0,0-1.42-.29,2.62,2.62,0,0,0-2.75,2.9,2.55,2.55,0,0,0,2.7,2.83,3.63,3.63,0,0,0,1.49-.29Z" />
                                <path class="cls-12" d="M101.41,84h.91V90.3h3v.76h-3.92Z" />
                                <path class="cls-12" d="M107.25,84v7h-.91V84Z" />
                                <path class="cls-12"
                                    d="M108.84,91.06V84h1l2.25,3.57a19.32,19.32,0,0,1,1.27,2.28h0c-.08-.94-.11-1.79-.11-2.89V84h.86v7h-.92L111,87.49a23.39,23.39,0,0,1-1.32-2.35h0c0,.89.07,1.74.07,2.91v3Z" />
                                <path class="cls-12" d="M116.62,84v7h-.91V84Z" />
                                <path class="cls-12"
                                    d="M123.12,90.83a4.42,4.42,0,0,1-1.86.34,3.29,3.29,0,0,1-3.47-3.56,3.5,3.5,0,0,1,3.67-3.7,3.85,3.85,0,0,1,1.67.31l-.22.74a3.38,3.38,0,0,0-1.42-.29,2.61,2.61,0,0,0-2.74,2.9,2.55,2.55,0,0,0,2.7,2.83,3.58,3.58,0,0,0,1.48-.29Z" />
                                <path class="cls-12"
                                    d="M125.44,88.85l-.73,2.21h-.94l2.39-7h1.1l2.4,7h-1l-.75-2.21Zm2.31-.71-.69-2c-.16-.46-.26-.88-.37-1.28h0c-.1.42-.22.84-.35,1.27l-.69,2Z" />
                                <path class="cls-12" d="M130.69,84h.91V90.3h3v.76h-3.92Z" />
                                <circle class="cls-28" cx="175.53" cy="116.76" r="34.94" />
                                <path class="cls-12" d="M145.48,118.44h-.65v-5h.65Z" />
                                <path class="cls-12"
                                    d="M150.48,118.44h-.66l-2.49-3.82v3.82h-.66v-5h.66l2.5,3.83v-3.83h.65Z" />
                                <path class="cls-12" d="M154.36,116.25h-2.07v2.19h-.65v-5h3.06V114h-2.41v1.7h2.07Z" />
                                <path class="cls-12"
                                    d="M157.3,116.44h-1.16v2h-.66v-5h1.64a2,2,0,0,1,1.29.38,1.51,1.51,0,0,1,.2,1.91,1.56,1.56,0,0,1-.7.52l1.16,2.1v0h-.7Zm-1.16-.54h1a1.12,1.12,0,0,0,.77-.25.86.86,0,0,0,.29-.67.91.91,0,0,0-.27-.71,1.24,1.24,0,0,0-.79-.25h-1Z" />
                                <path class="cls-12"
                                    d="M162.5,117.14h-2.07l-.47,1.3h-.67l1.89-5h.57l1.89,5H163Zm-1.87-.53h1.68l-.84-2.32Z" />
                                <path class="cls-12"
                                    d="M165.77,116.23a3.07,3.07,0,0,1-1.22-.59,1.13,1.13,0,0,1-.39-.87,1.22,1.22,0,0,1,.47-1,1.89,1.89,0,0,1,1.22-.38,2.06,2.06,0,0,1,.91.2,1.55,1.55,0,0,1,.62.54,1.45,1.45,0,0,1,.21.76h-.65a.91.91,0,0,0-.29-.71,1.17,1.17,0,0,0-.8-.26,1.23,1.23,0,0,0-.76.22.71.71,0,0,0-.27.59.64.64,0,0,0,.26.51,2.37,2.37,0,0,0,.88.38,4.11,4.11,0,0,1,1,.39,1.62,1.62,0,0,1,.52.48,1.29,1.29,0,0,1,.16.66,1.15,1.15,0,0,1-.46,1,2,2,0,0,1-1.26.37,2.31,2.31,0,0,1-.95-.2,1.67,1.67,0,0,1-.68-.53A1.33,1.33,0,0,1,164,117h.66a.82.82,0,0,0,.33.71,1.34,1.34,0,0,0,.88.26,1.26,1.26,0,0,0,.79-.21.67.67,0,0,0,.28-.57.7.7,0,0,0-.26-.57A3,3,0,0,0,165.77,116.23Z" />
                                <path class="cls-12" d="M171.86,114h-1.59v4.42h-.65V114H168v-.53h3.83Z" />
                                <path class="cls-12"
                                    d="M174.41,116.44h-1.16v2h-.66v-5h1.64a2,2,0,0,1,1.29.38,1.51,1.51,0,0,1,.2,1.91,1.56,1.56,0,0,1-.7.52l1.16,2.1v0h-.7Zm-1.16-.54h1a1.12,1.12,0,0,0,.77-.25.82.82,0,0,0,.29-.67.91.91,0,0,0-.27-.71,1.24,1.24,0,0,0-.79-.25h-1Z" />
                                <path class="cls-12"
                                    d="M180.37,113.49v3.36a1.59,1.59,0,0,1-.45,1.15,1.76,1.76,0,0,1-1.18.5h-.17a1.88,1.88,0,0,1-1.3-.44,1.6,1.6,0,0,1-.49-1.21v-3.37h.65v3.35a1.14,1.14,0,1,0,2.28,0v-3.35Z" />
                                <path class="cls-12"
                                    d="M185,116.87a1.83,1.83,0,0,1-.58,1.21,1.94,1.94,0,0,1-1.3.43,1.77,1.77,0,0,1-1.41-.63,2.55,2.55,0,0,1-.52-1.69v-.47a2.84,2.84,0,0,1,.24-1.21,1.83,1.83,0,0,1,1.75-1.09,1.79,1.79,0,0,1,1.26.44,1.86,1.86,0,0,1,.56,1.22h-.66a1.43,1.43,0,0,0-.37-.86,1.1,1.1,0,0,0-.79-.27,1.16,1.16,0,0,0-1,.47,2.08,2.08,0,0,0-.36,1.31v.48a2.17,2.17,0,0,0,.34,1.29,1.1,1.1,0,0,0,.94.47,1.26,1.26,0,0,0,.83-.24,1.33,1.33,0,0,0,.39-.86Z" />
                                <path class="cls-12" d="M189.25,114h-1.59v4.42H187V114h-1.59v-.53h3.83Z" />
                                <path class="cls-12"
                                    d="M193.47,113.49v3.36A1.63,1.63,0,0,1,193,118a1.79,1.79,0,0,1-1.19.5h-.17a1.89,1.89,0,0,1-1.3-.44,1.6,1.6,0,0,1-.49-1.21v-3.37h.65v3.35a1,1,0,0,0,1.14,1.13,1,1,0,0,0,1.14-1.13v-3.35Z" />
                                <path class="cls-12"
                                    d="M196.31,116.44h-1.16v2h-.66v-5h1.64a2,2,0,0,1,1.29.38,1.36,1.36,0,0,1,.45,1.11,1.29,1.29,0,0,1-.25.8,1.56,1.56,0,0,1-.7.52l1.17,2.1v0h-.7Zm-1.16-.54h1a1.14,1.14,0,0,0,.78-.25.85.85,0,0,0,.28-.67.91.91,0,0,0-.27-.71,1.22,1.22,0,0,0-.79-.25h-1Z" />
                                <path class="cls-12"
                                    d="M201.52,117.14h-2.08l-.46,1.3h-.68l1.89-5h.58l1.89,5H202Zm-1.88-.53h1.68l-.84-2.32Z" />
                                <path class="cls-12" d="M204,117.9h2.35v.54h-3v-5H204Z" />
                                <circle class="cls-29" cx="115.69" cy="154.58" r="33.01" /><text class="cls-30"
                                    transform="translate(84.19 154.8)">OPER<tspan class="cls-31" x="23.95" y="0">A</tspan>
                                    <tspan class="cls-32" x="29.6" y="0">TIO</tspan>
                                    <tspan class="cls-33" x="44.49" y="0">N</tspan>
                                    <tspan x="51.4" y="0">AL</tspan>
                                </text>
                                <circle class="cls-34" cx="29.23" cy="115.45" r="24.35" />
                                <circle class="cls-34" cx="56.74" cy="145.43" r="13.68" />
                                <circle class="cls-34" cx="72.58" cy="117.06" r="16.98" /><text class="cls-35"
                                    transform="translate(7.95 117.07)">ADMINISTR<tspan class="cls-36" x="46.26" y="0">A
                                    </tspan>
                                    <tspan class="cls-37" x="51.46" y="0">TIVE</tspan>
                                </text>
                                <path class="cls-12"
                                    d="M134.19,14.73A103.79,103.79,0,1,1,58.39,189.4h-9.6a111,111,0,1,0,.15-141.94h9.61A103.45,103.45,0,0,1,134.19,14.73Z" />
                                <path class="cls-12"
                                    d="M503.17,118.42A122.12,122.12,0,0,1,474.72,197H464.09a114.68,114.68,0,0,0,.17-156.89h10.62A122.18,122.18,0,0,1,503.17,118.42Z" />
                                <text class="cls-38" transform="translate(52.87 264.59)">FOCUSED DO<tspan class="cls-39"
                                        x="94.77" y="0">M</tspan>
                                    <tspan class="cls-40" x="108.74" y="0">AINS</tspan>
                                </text><text class="cls-41" transform="translate(275.35 264.59)">RESU<tspan
                                        class="cls-42" x="38.64" y="0">L</tspan>
                                    <tspan class="cls-43" x="45.12" y="0">T</tspan>
                                    <tspan x="54.51" y="0">S OPTIMIS</tspan>
                                    <tspan class="cls-44" x="130.6" y="0">A</tspan>
                                    <tspan class="cls-45" x="140" y="0">TION</tspan>
                                </text><text class="cls-46" transform="translate(525.93 264.59)">A<tspan class="cls-45"
                                        x="10.29" y="0">CHIEVEME</tspan>
                                    <tspan class="cls-47" x="87.55" y="0">N</tspan>
                                    <tspan class="cls-48" x="98.66" y="0">T</tspan>
                                </text>
                            </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- Tawqeet Process --}}
    <section style=”padding: 72px 0; background: #fff;”>
        <div class=”container”>
            <div class=”text-center mb-5”>
                <span class=”sec-label”>Our Process</span>
                <h2 class=”sec-title mt-2”>The Tawqeet Process</h2>
                <p class=”sec-sub mx-auto mt-3” style=”max-width:700px;”>
                    A comprehensive audit of the healthcare facility is conducted by healthcare experts across all functional domains. Processes are evaluated based on preset checklists derived from combined KPIs and international best practices. GAP analysis is presented to facility management, and a Corrective Action Plan is developed in concurrence with the facility. Implementation involves staff training and process improvisation — all overseen by an assigned client manager.
                </p>
            </div>
            <div class=”text-center”>
                <img src=”{{ asset('public/front/assets/img/towqeet-result.svg') }}” class=”img-fluid” style=”max-width: 100%;” alt=”Tawqeet Process Diagram”>
            </div>
        </div>
    </section>
    {{-- Packages --}}
    <section style="padding: 80px 0; background: #f8fafb;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="sec-label">Choose Your Plan</span>
                <h2 class="sec-title mt-2">Tawqeet Packages</h2>
                <p class="sec-sub mx-auto mt-3" style="max-width:650px;">Select the Tawqeet plan that best meets your facility's requirements. All packages are custom-made to suit your facility size, budget, and needs — and can be converted or amended as required.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="pkg-card">
                        <div class="feat-icon-wrap mb-3" style="width:52px;height:52px;font-size:1.4rem;border-radius:12px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Audit Compliance</h4>
                        <p>A one-off solution for situational audits — periodic regulatory inspections, change of location, addition of specialty, or internal plan changes. Ensures your facility is audit-ready and fully compliant.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pkg-card">
                        <div class="feat-icon-wrap mb-3" style="width:52px;height:52px;font-size:1.4rem;border-radius:12px;">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </div>
                        <h4>Facility Enhancement</h4>
                        <p>Redesign existing work processes, optimize resources, improve efficiency and business productivity while satisfying regulatory requirements. A complete facility overhaul across all operational domains.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pkg-card">
                        <div class="feat-icon-wrap mb-3" style="width:52px;height:52px;font-size:1.4rem;border-radius:12px;">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <h4>Full Compliance Program</h4>
                        <p>The comprehensive package ensuring long-term sustainability of enhancements. Overseen by a dedicated client manager, each domain is optimised for compliance — keeping your facility ready for any regulatory audit at any time.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Facility Types --}}
    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="sec-label">Facility Types</span>
                <h2 class="sec-title mt-2">Tawqeet For Your Facility</h2>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="fac-card mc">
                        <h3><i class="bi bi-hospital-fill me-2"></i>Medical Centers</h3>
                        <p>Get your medical center audited across Clinical, Operational, Infrastructural, and Administrative domains. Identify gaps in existing processes, adopt international best practices, and ensure full regulatory compliance for better care delivery and improved business outcomes.</p>
                        <ul class="fac-list">
                            <li><i class="bi bi-check-circle-fill"></i> DOH Audit Compliance</li>
                            <li><i class="bi bi-check-circle-fill"></i> Jawda Compliance</li>
                            <li><i class="bi bi-check-circle-fill"></i> Resource Efficiency</li>
                            <li><i class="bi bi-check-circle-fill"></i> Operational Enhancement</li>
                        </ul>
                        <a href="https://wa.me/971555595200?text=Request%20Tawqeet%20for%20Medical%20center" class="btn-light-ghost" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp me-2"></i>Get Started
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="fac-card hc">
                        <h3><i class="bi bi-house-heart-fill me-2"></i>Homecare Centers</h3>
                        <p>Get your Home Care operation audited across focus domains including billing verification. Tawqeet analysis covers both your corporate office and patient care area, addressing Health Authority and Tasneef requirements for better care delivery and improved business outcomes.</p>
                        <ul class="fac-list">
                            <li><i class="bi bi-check-circle-fill"></i> Tasneef Compliance</li>
                            <li><i class="bi bi-check-circle-fill"></i> DOH Audit Compliance</li>
                            <li><i class="bi bi-check-circle-fill"></i> Jawda Compliance</li>
                            <li><i class="bi bi-check-circle-fill"></i> Operational Enhancement</li>
                        </ul>
                        <a href="https://wa.me/971555595200?text=Request%20Tawqeet%20for%20homecare%20center" class="btn-light-ghost" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp me-2"></i>Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Form --}}
    <section class="contact-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="contact-card">
                        <div class="text-center mb-4">
                            <span class="sec-label">Enquire Now</span>
                            <h2 class="sec-title mt-2">Send Us an Email</h2>
                            <p class="sec-sub mt-2">Fill in your details and we'll get back to you shortly.</p>
                        </div>
                        <form id="contactForm" name="contactForm">
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <input id="fullName" name="fullName" type="text" placeholder="Your Name *" required class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input id="Email" name="Email" type="email" placeholder="Email Address *" required class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input id="Phone" name="Phone" type="tel" placeholder="Phone Number" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <select name="service" class="form-control form-select">
                                        <option selected>Select package type</option>
                                        <option value="Tawqeet for Medical Center">Tawqeet for Medical Center</option>
                                        <option value="Tawqeet for Home Care Center">Tawqeet for Home Care Center</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea id="Message" name="Message" class="form-control" rows="4" placeholder="Your message..."></textarea>
                            </div>
                            <p class="success-msg" id="formSuccess">We have received your enquiry — a representative will contact you shortly.</p>
                            <div class="text-center">
                                <button type="submit" class="btn-submit">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
@endSection
