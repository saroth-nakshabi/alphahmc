@extends('front/layout-2')

@push('page_title', 'How Alpha Works | Healthcare Management Consultancy | Alpha Health Group')

@section('meta_description')Our proven 4-step methodology — Research, Plan, Execute, and Results — ensures your healthcare facility achieves full DOH compliance and sustained operational excellence.@endsection

@section('custom_css')
    <style>
        body { font-family: 'Outfit', sans-serif; }

        /* Hero */
        .haw-hero {
            background: linear-gradient(135deg, #066D77 0%, #009095 60%, #00b4bd 100%);
            padding: 72px 0 56px;
            position: relative;
            overflow: hidden;
        }
        .haw-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .haw-hero .badge-pill {
            background: rgba(255,255,255,0.18);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .haw-hero h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            color: #fff;
            margin: 16px 0 12px;
            line-height: 1.2;
        }
        .haw-hero p.lead {
            color: rgba(255,255,255,0.88);
            font-size: 1.05rem;
            max-width: 640px;
        }
        .haw-breadcrumb { background: rgba(255,255,255,0.12); border-radius: 8px; padding: 10px 18px; display: inline-flex; gap: 6px; align-items: center; margin-top: 20px; }
        .haw-breadcrumb a, .haw-breadcrumb span { color: rgba(255,255,255,0.85); font-size: 0.85rem; text-decoration: none; }
        .haw-breadcrumb .sep { color: rgba(255,255,255,0.5); }
        .haw-breadcrumb span.active { color: #fff; font-weight: 600; }

        /* Process Steps */
        .process-section { padding: 80px 0; background: #f8fafb; }
        .section-label {
            display: inline-block;
            background: #e6f4f5;
            color: #066D77;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 14px;
        }
        .section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #0d2126; margin-bottom: 16px; }
        .section-sub { color: #5a7070; font-size: 1rem; max-width: 680px; line-height: 1.7; }

        .process-card {
            background: #fff;
            border-radius: 16px;
            padding: 36px 28px 32px;
            height: 100%;
            border: 1px solid #e8f0f1;
            transition: transform .25s ease, box-shadow .25s ease;
            position: relative;
            overflow: hidden;
        }
        .process-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #066D77, #009095);
            transform: scaleX(0);
            transition: transform .3s ease;
            transform-origin: left;
        }
        .process-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(6,109,119,0.12); }
        .process-card:hover::after { transform: scaleX(1); }

        .step-num {
            font-size: 3.5rem;
            font-weight: 800;
            color: #e6f4f5;
            line-height: 1;
            margin-bottom: 16px;
            font-variant-numeric: tabular-nums;
        }
        .step-icon {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, #066D77, #009095);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
            color: #fff;
            font-size: 1.4rem;
        }
        .process-card h4 { font-size: 1.15rem; font-weight: 700; color: #0d2126; margin-bottom: 10px; }
        .process-card p { color: #5a7070; font-size: 0.93rem; line-height: 1.7; margin: 0; }

        /* Connector line between steps (desktop) */
        .process-connector {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 28px;
        }
        .process-connector i { color: #009095; font-size: 1.4rem; }

        /* Quality Section */
        .quality-section { padding: 80px 0; background: #fff; }
        .quality-card {
            background: #f8fafb;
            border-radius: 16px;
            padding: 40px;
            border: 1px solid #e8f0f1;
            height: 100%;
        }
        .quality-card h3 { font-size: 1.35rem; font-weight: 700; color: #0d2126; margin-bottom: 28px; line-height: 1.5; }
        .quality-list { list-style: none; padding: 0; margin: 0; }
        .quality-list li {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 14px 0;
            border-bottom: 1px solid #edf1f2;
            color: #3d5a5e;
            font-size: 0.95rem;
            line-height: 1.65;
        }
        .quality-list li:last-child { border-bottom: none; }
        .quality-list li .check-icon {
            flex-shrink: 0;
            width: 22px; height: 22px;
            background: linear-gradient(135deg, #066D77, #009095);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 0.65rem;
            margin-top: 2px;
        }

        /* DOH Callout */
        .doh-card {
            background: linear-gradient(135deg, #066D77 0%, #009095 100%);
            border-radius: 20px;
            padding: 44px 40px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .doh-card::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .doh-card::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -30px;
            width: 220px; height: 220px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .doh-card .doh-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.18);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            color: #fff;
            margin-bottom: 24px;
            position: relative; z-index: 1;
        }
        .doh-card h4 { font-size: 1.15rem; font-weight: 700; margin-bottom: 16px; position: relative; z-index: 1; }
        .doh-card p { font-size: 1rem; line-height: 1.75; color: rgba(255,255,255,0.9); position: relative; z-index: 1; margin: 0; }
        .doh-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            margin-top: 24px;
            position: relative; z-index: 1;
        }

        /* Hero CTA */
        .haw-hero-cta {
            display: inline-flex; align-items: center; gap: 10px; background: #fff; color: #066D77;
            font-weight: 700; padding: 13px 30px; border-radius: 100px; text-decoration: none; margin-top: 24px;
            transition: all .25s ease; box-shadow: 0 12px 30px rgba(0,0,0,0.18);
        }
        .haw-hero-cta:hover { transform: translateY(-2px); color: #066D77; background: #e6f4f5; }
        .haw-hero-cta i { transition: transform .25s; }
        .haw-hero-cta:hover i { transform: translateX(4px); }

        /* Planner launcher band */
        .haw-launch { padding: 84px 0; background: linear-gradient(135deg, #06363c 0%, #066D77 100%); position: relative; overflow: hidden; }
        .haw-launch::before { content: ''; position: absolute; top: -70px; right: -50px; width: 260px; height: 260px; background: rgba(255,255,255,0.06); border-radius: 50%; }
        .haw-launch::after { content: ''; position: absolute; bottom: -90px; left: -40px; width: 300px; height: 300px; background: rgba(255,255,255,0.04); border-radius: 50%; }
        .haw-launch-inner { position: relative; z-index: 1; text-align: center; max-width: 740px; margin: 0 auto; color: #fff; }
        .haw-launch .badge-pill { background: rgba(255,255,255,0.16); border: 1px solid rgba(255,255,255,0.3); color: #fff; border-radius: 50px; padding: 6px 18px; font-size: 0.78rem; letter-spacing: 1.4px; text-transform: uppercase; font-weight: 700; }
        .haw-launch h2 { font-size: clamp(1.7rem, 4vw, 2.5rem); font-weight: 800; color: #fff; margin: 18px 0 14px; line-height: 1.2; }
        .haw-launch p { color: rgba(255,255,255,0.9); font-size: 1.05rem; line-height: 1.65; margin: 0 0 26px; }
        .haw-launch-steps { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 30px; }
        .haw-launch-step { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); border-radius: 100px; padding: 9px 18px; font-size: 0.85rem; font-weight: 600; }
        .haw-launch-step i { margin-right: 7px; opacity: .85; }
        .haw-launch-btn { display: inline-flex; align-items: center; gap: 12px; background: #fff; color: #06363c; font-weight: 700; font-size: 1.02rem; padding: 17px 40px; border-radius: 100px; text-decoration: none; transition: all .25s ease; box-shadow: 0 18px 44px rgba(0,0,0,0.28); }
        .haw-launch-btn:hover { transform: translateY(-3px); color: #06363c; background: #e6f4f5; }
        .haw-launch-btn i { transition: transform .25s; }
        .haw-launch-btn:hover i { transform: translateX(5px); }
        .haw-launch-meta { margin-top: 18px; font-size: 0.84rem; color: rgba(255,255,255,0.7); }
    </style>
@endSection

@section('content')

    {{-- Hero --}}
    <section class="haw-hero">
        <div class="container">
            <span class="badge-pill">Our Methodology</span>
            <h1>How Alpha Works</h1>
            <p class="lead">We meticulously analyse your existing business system, design customised performance improvement plans aligned with international best practices, and ensure full regulatory compliance.</p>
            <div>
                <a href="{{ route('planner.page') }}" class="haw-hero-cta">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Plan your project in 60 seconds <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="haw-breadcrumb">
                <a href="{{ route('home') }}"><i class="bi bi-house-fill me-1"></i>Home</a>
                <span class="sep">/</span>
                <span class="active">How Alpha Works</span>
            </div>
        </div>
    </section>

    {{-- Working Process --}}
    <section class="process-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-label">Step by Step</span>
                <h2 class="section-title">Alpha Working Process</h2>
                <p class="section-sub mx-auto">Our structured four-step methodology ensures every engagement is grounded in evidence, expertly planned, precisely executed, and rigorously measured for impact.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                    <div class="process-card">
                        <div class="step-num">01</div>
                        <div class="step-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4>Research</h4>
                        <p>We conduct multi-point evaluations to identify gaps between current practice and international best practices, establishing a clear baseline for improvement.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                    <div class="process-card">
                        <div class="step-num">02</div>
                        <div class="step-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h4>Plan</h4>
                        <p>A dedicated account manager is assigned to your facility, taking full ownership of the work process and distributing planned tasks to the relevant subject matter experts.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                    <div class="process-card">
                        <div class="step-num">03</div>
                        <div class="step-icon">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <h4>Execute</h4>
                        <p>Specialised teams are deployed to execute targeted tasks. Short-term goals are monitored until completion; for annual outsourcing this becomes a continuous improvement cycle.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                    <div class="process-card">
                        <div class="step-num">04</div>
                        <div class="step-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4>Results</h4>
                        <p>At the end of each project cycle, achieved results are reviewed against the initial baseline to measure success rate, validate outcomes, and inform the next phase of growth.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Plan Your Project launcher --}}
    <section class="haw-launch" data-aos="fade-up">
        <div class="container">
            <div class="haw-launch-inner">
                <span class="badge-pill">Alpha Blueprint AI</span>
                <h2>Not sure where to start?</h2>
                <p>Let <strong>Alpha Blueprint AI</strong> map your project in about a minute. Tell us your goal and it'll
                    outline exactly how we'd approach it — with the services best suited to you.</p>
                <div class="haw-launch-steps">
                    <span class="haw-launch-step"><i class="fa-solid fa-bullseye"></i> Your goal</span>
                    <span class="haw-launch-step"><i class="fa-solid fa-location-dot"></i> Region &amp; facility</span>
                    <span class="haw-launch-step"><i class="fa-solid fa-list-check"></i> Tailored plan + services</span>
                </div>
                <a href="{{ route('planner.page') }}" class="haw-launch-btn">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Start planning <i class="bi bi-arrow-right"></i>
                </a>
                <div class="haw-launch-meta"><i class="fa-regular fa-clock"></i> ~60 seconds · No commitment</div>
            </div>
        </div>
    </section>

    {{-- Quality Commitment + DOH Callout --}}
    <section class="quality-section">
        <div class="container">
            <div class="row g-4 align-items-stretch">

                <div class="col-lg-7">
                    <div class="quality-card">
                        <span class="section-label">Our Commitment</span>
                        <h3 class="mt-3">Quality derives from commitment to service excellence, achieved by ensuring:</h3>
                        <ul class="quality-list">
                            <li>
                                <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                                <span>Customer requirements always come first. We strive to meet every need, reflected in our organisational flexibility and responsiveness.</span>
                            </li>
                            <li>
                                <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                                <span>We build mutually beneficial long-term relationships with clients and suppliers, grounded in transparency and shared success.</span>
                            </li>
                            <li>
                                <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                                <span>We maintain practical, proven processes to ensure consistent results and quality deliverables, strictly abiding to agreed project timelines.</span>
                            </li>
                            <li>
                                <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                                <span>Through internal review of our Business Management System we continually improve performance, services, and processes to ensure optimal efficiency.</span>
                            </li>
                            <li>
                                <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                                <span>Through active mentoring we cultivate a culture where staff have the skills and autonomy to take ownership of results — contributing to organisational success.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="doh-card h-100">
                        <div class="doh-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <h4>DOH Abu Dhabi Approved Organisation</h4>
                        <p>We are a Department of Health (DOH) Abu Dhabi approved healthcare management and medical professional training organisation. We fulfil all required government regulations for healthcare facilities and healthcare professionals.</p>
                        <div class="doh-badge">
                            <i class="bi bi-shield-fill-check"></i>
                            DOH Approved &nbsp;·&nbsp; ISO Certified
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('custom_js')
@endSection
