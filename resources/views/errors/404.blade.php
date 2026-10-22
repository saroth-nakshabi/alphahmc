@extends('front/layout-2')

@push('page_title', 'Page Not Found | Alpha Health Group')

@section('custom_css')
    @include('errors.partials.styles')
@endSection

@section('content')

    <section class="error-section">
        <div class="container">
            <div class="error-inner">

                <div class="error-icon-wrap">
                    <i class="fa-solid fa-compass"></i>
                </div>

                <div class="error-code">404</div>

                <h1 class="error-title">Page Not Found</h1>
                <div class="error-divider"></div>
                <p class="error-desc">
                    The page you're looking for doesn't exist or may have been moved.
                    Let us help you find what you need.
                </p>

                <div class="error-actions">
                    <a href="{{ route('home') }}" class="btn-primary-brand">
                        <i class="fa-solid fa-house"></i>
                        Go to Home
                    </a>
                    <a href="{{ route('front.all-services') }}" class="btn-outline-brand">
                        <i class="fa-solid fa-table-cells-large"></i>
                        View All Services
                    </a>
                </div>

                <div class="error-suggestion mt-4">
                    <p>
                        <strong>Need help?</strong> Browse our services or return home. We're here to support your healthcare facility's compliance and growth.
                    </p>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('custom_js')
@endSection
