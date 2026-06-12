@extends('front/layout-2')
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/service-group-all.css') }}?v=1">
@endsection
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
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
    /* Dynamic (Blade) styles - kept inline intentionally */
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