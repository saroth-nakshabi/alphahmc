@extends('front/layout-2')

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

        /* HERO SECTION */
        .brands-hero {
            @if(isset($brandHero) && $brandHero->image)
                background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgb(0, 0, 0)), url('{{ asset('public/uploads/brands/' . $brandHero->image) }}');
            @else
                background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgb(0, 0, 0)), url('{{ asset('public/front-new/assets/images/service_details/service-details-2.webp') }}');
            @endif
            background-size: cover;
            background-position: center;
            position: relative;
            margin-top: -180px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 150px;
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

        .service-nav {
            position: absolute;
            bottom: 50px;
            left: 0;
            right: 0;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .service-nav a {
            color: white !important;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        /* BRANDS LISTING */
        .brands-section {
            padding: 120px 0;
            background: #ffffff;
        }

        .brand-card {
            background: #fff;
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            margin-bottom: 60px;
            transition: var(--transition);
            display: flex;
            flex-direction: row;
        }

        .brand-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 70px rgba(0, 51, 88, 0.08);
            border-color: var(--accent-orange);
        }

        .brand-logo-panel {
            flex: 0 0 300px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px;
            border-right: 1px solid #f1f5f9;
        }

        .brand-logo-panel img {
            max-width: 100%;
            height: auto;
            filter: grayscale(1);
            transition: var(--transition);
            opacity: 0.7;
        }

        .brand-card:hover .brand-logo-panel img {
            filter: grayscale(0);
            opacity: 1;
            transform: scale(1.1);
        }

        .brand-content-panel {
            flex: 1;
            padding: 60px;
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 15px;
            letter-spacing: -1px;
        }

        .brand-address {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--accent-orange);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand-desc {
            font-size: 1.15rem;
            color: var(--text-gray);
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .what-we-do-box {
            background: #fdf2f2;
            padding: 40px;
            border-radius: 20px;
            border-left: 5px solid var(--accent-orange);
        }

        .what-we-do-box h4 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .what-we-do-text {
            color: #475569;
            font-size: 1rem;
            line-height: 1.7;
        }

        @media (max-width: 992px) {
            .brand-card {
                flex-direction: column;
            }
            .brand-logo-panel {
                flex: none;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #f1f5f9;
            }
        }
    </style>

    {{-- HERO SECTION --}}
    <section class="brands-hero">
        <div class="container">
            <div class="banner-text">
                <h1 class="hero-title">Our Brands</h1>
                <p class="hero-description">
                    Alpha Health Group is a diverse ecosystem of specialized brands, each dedicated to delivering 
                    excellence and innovation across the healthcare and technology spectrum.
                </p>
            </div>
            <div class="service-nav container">
                <a class="text-decoration-none" href="{{ route('home') }}">Home</a>
                <span class="mx-2 text-white-50">></span>
                <a class="text-decoration-none" href="#">Our Group</a>
                <span class="mx-2 text-white-50">></span>
                <a class="text-decoration-none" href="#">Our Brands</a>
            </div>
        </div>
    </section>

    <section class="brands-section">
        <div class="container">
            @forelse($brands as $brand)
                <div class="brand-card">
                    <div class="brand-logo-panel">
                        <img src="{{ $brand->logo ? asset('public/uploads/brands/' . $brand->logo) : asset('public/front-new/assets/images/alpha-logo.svg') }}" 
                             alt="{{ $brand->name }}">
                    </div>
                    <div class="brand-content-panel">
                        <div class="brand-address">
                            <i class="fas fa-map-marker-alt"></i> {{ $brand->address ?? 'Alpha Corporate Center' }}
                        </div>
                        <h2 class="brand-name">
                            @if($brand->slug)
                                <a href="{{ route('front.singleBrand', $brand->slug) }}" class="text-decoration-none" style="color: inherit;">
                                    {{ $brand->name }}
                                </a>
                            @else
                                {{ $brand->name }}
                            @endif
                        </h2>
                        <p class="brand-desc">
                            {!! Str::limit(strip_tags($brand->description), 200) !!}
                        </p>
                        
                        <div class="what-we-do-box">
                            <h4>What We Do</h4>
                            <div class="what-we-do-text">
                                {!! Str::limit(strip_tags($brand->what_we_do), 150) !!}
                            </div>
                        </div>

                        @if($brand->slug)
                            <div class="mt-4">
                                <a href="{{ route('front.singleBrand', $brand->slug) }}" class="btn btn-primary rounded-pill px-4">
                                    View Brand Details <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <h3 class="text-muted">Our brand portfolio is currently being updated.</h3>
                    <p>Please check back soon to discover our expanding ecosystem.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection