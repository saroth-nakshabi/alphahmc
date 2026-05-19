@extends('front/layout-2')

@push('page_title', 'Page Not Found | Alpha Health Group')

@section('custom_css')
    <style>
        body { font-family: 'Outfit', sans-serif; }

        .error-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #f4f9fa 0%, #e6f4f5 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .error-section::before {
            content: '404';
            position: absolute;
            font-size: clamp(180px, 30vw, 320px);
            font-weight: 900;
            color: rgba(6, 109, 119, 0.05);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            line-height: 1;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .error-inner {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .error-icon-wrap {
            width: 110px;
            height: 110px;
            background: linear-gradient(135deg, #066D77, #009095);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            font-size: 2.6rem;
            color: #fff;
            box-shadow: 0 12px 40px rgba(6, 109, 119, 0.25);
            animation: pulse-ring 2.5s ease infinite;
        }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(6,109,119,0.35); }
            70%  { box-shadow: 0 0 0 18px rgba(6,109,119,0); }
            100% { box-shadow: 0 0 0 0 rgba(6,109,119,0); }
        }

        .error-code {
            font-size: clamp(3.5rem, 10vw, 6rem);
            font-weight: 900;
            background: linear-gradient(135deg, #066D77, #009095);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 16px;
        }

        .error-title {
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 800;
            color: #0d2126;
            margin-bottom: 14px;
        }

        .error-desc {
            color: #5a7070;
            font-size: 1.05rem;
            line-height: 1.75;
            max-width: 480px;
            margin: 0 auto 40px;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            justify-content: center;
        }

        .btn-primary-brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #066D77, #009095);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px 28px;
            font-weight: 700;
            font-size: 0.97rem;
            font-family: 'Outfit', sans-serif;
            text-decoration: none;
            transition: opacity .2s, transform .15s;
            box-shadow: 0 6px 20px rgba(6,109,119,0.2);
        }
        .btn-primary-brand:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            color: #fff;
            text-decoration: none;
        }

        .btn-outline-brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: #066D77;
            border: 2px solid #066D77;
            border-radius: 10px;
            padding: 13px 28px;
            font-weight: 700;
            font-size: 0.97rem;
            font-family: 'Outfit', sans-serif;
            text-decoration: none;
            transition: background .2s, color .2s, transform .15s;
        }
        .btn-outline-brand:hover {
            background: #066D77;
            color: #fff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .error-divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #066D77, #009095);
            border-radius: 4px;
            margin: 24px auto;
        }

        .error-suggestion {
            margin-top: 48px;
            padding: 28px 32px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e0eef0;
            box-shadow: 0 4px 20px rgba(6,109,119,0.06);
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }
        .error-suggestion p {
            color: #5a7070;
            font-size: 0.88rem;
            margin: 0;
        }
        .error-suggestion strong {
            color: #0d2126;
        }
    </style>
@endSection

@section('content')

    <section class="error-section">
        <div class="container">
            <div class="error-inner">

                <div class="error-icon-wrap">
                    <i class="bi bi-compass"></i>
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
                        <i class="bi bi-house-fill"></i>
                        Go to Home
                    </a>
                    <a href="{{ route('front.all-services') }}" class="btn-outline-brand">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        View All Services
                    </a>
                </div>

                <div class="error-suggestion mt-4">
                    <p>
                        <strong>Need help?</strong> Browse our services or return home — we're here to support your healthcare facility's compliance and growth.
                    </p>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('custom_js')
@endSection
