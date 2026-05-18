@extends('front/layout-2')

@push('page_title')
    {!! $service->name !!} - All Services
@endpush

@push('meta')
<meta name="description" content="{{ $service->meta_description }}">
    <meta name="keywords" content="{{ $service->meta_keywords }}">
@endpush

@push('og_tags')
    <meta name="author" content="Alpha Health Management Consultancy">
    <meta property="og:title" content="{{ $service->meta_title }} - All Services" />
    <meta property="og:description" content="{{ $service->meta_description }}" />
    <meta property="og:image" content="{{ $service->hero_image ? asset('public/' . ltrim($service->hero_image, '/')) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website" />

    <meta name="twitter:card" content="{{ strip_tags($service->content)}}">
@endpush

@section('content')
    <!-- Standardized Typography & Modern Iconography -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-body);
        }

        .hero-section {
            position: relative;
            background: linear-gradient(135deg, var(--brand-primary) 0%, #0a8a94 100%);
            color: var(--white);
            padding: 175px 0 80px 0;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ $service->hero_image ? asset('public/' . ltrim($service->hero_image, '/')) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: 'Libre Baskerville', serif;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb a {
            color: var(--white);
            text-decoration: none;
            opacity: 0.8;
        }

        .breadcrumb a:hover {
            opacity: 1;
        }

        .services-section {
            padding: 80px 0;
            background: var(--bg-gray);
        }

        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .services-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .services-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-title);
            margin-bottom: 1rem;
            font-family: 'Libre Baskerville', serif;
        }

        .services-subtitle {
            font-size: 1.125rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .service-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .service-card-bg {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .service-card-body {
            padding: 1.5rem;
        }

        .service-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-title);
            margin-bottom: 0.5rem;
            font-family: 'Libre Baskerville', serif;
        }

        .service-card-desc {
            color: var(--text-light);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .service-card-footer {
            padding: 1rem 1.5rem;
            background: var(--bg-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .service-card-tag {
            background: var(--brand-primary);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 500;
        }

        .service-card-arrow {
            font-size: 1.25rem;
            color: var(--brand-primary);
            transition: var(--transition);
        }

        .service-card:hover .service-card-arrow {
            transform: translateX(5px);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--brand-primary);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .back-button:hover {
            background: var(--brand-dark);
            color: var(--white);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .services-title {
                font-size: 2rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title">{{ $service->name }}</h1>
            <p class="hero-subtitle">All Services</p>
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('service-packages', $service->slug) }}">{{ $service->name }}</a>
                <span>/</span>
                <span>All Services</span>
            </nav>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="services-container">
            <a href="{{ route('service-packages', $service->slug) }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back to {{ $service->name }}
            </a>

            <div class="services-header">
                <h2 class="services-title">All Services in {{ $service->name }}</h2>
                <p class="services-subtitle">
                    {{ $selectedServices->count() }} specialist services available
                </p>
            </div>

            <div class="services-grid">
                @forelse($selectedServices as $selectedService)
                    @php
                        $selectedServiceImage = '';
                        if (!empty($selectedService->hero_image)) {
                            $selectedServiceImage = asset('public/uploads/service_images/' . $selectedService->hero_image);
                        } elseif (!empty($selectedService->images) && $selectedService->images->count()) {
                            $selectedServiceImage = asset('public/uploads/service_images/' . $selectedService->images->first()->image);
                        } else {
                            $selectedServiceImage = $service->image ? asset('public/uploads/service_groups/' . $service->image) : '';
                        }
                    @endphp

                    <a href="{{ route('front.service', $selectedService->slug) }}" class="service-card {{ $loop->first ? 'featured' : '' }}">
                      <div class="service-card-bg" style="background-image: url('{{ $selectedServiceImage }}');"></div>

                      @if($loop->first)
                        <span class="featured-badge">Most Requested</span>
                      @endif

                      <div class="service-card-body">
                        <h3 class="service-card-title">{{ $selectedService->name }}</h3>
                        <p class="service-card-desc">
                            {{ Str::limit(strip_tags($selectedService->content ?? $selectedService->overview ?? ''), 120) }}
                        </p>
                      </div>

                      <div class="service-card-footer">
                        <span class="service-card-tag">Service</span>
                        <div class="service-card-arrow">→</div>
                      </div>
                    </a>
                @empty
                    <p class="text-muted">No services selected for this service group.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection