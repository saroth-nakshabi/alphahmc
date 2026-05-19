@extends('front/layout-2')

@push('page_title', 'Reset Password | Alpha Health Group')

@section('custom_css')
    <style>
        body { font-family: 'Outfit', sans-serif; }

        .auth-hero {
            background: linear-gradient(135deg, #066D77 0%, #009095 100%);
            padding: 60px 0 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .auth-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .auth-hero h1 { color: #fff; font-size: 2.2rem; font-weight: 800; margin-bottom: 8px; position: relative; }
        .auth-breadcrumb { display: inline-flex; gap: 6px; align-items: center; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 7px 18px; margin-top: 14px; position: relative; }
        .auth-breadcrumb a, .auth-breadcrumb span { color: rgba(255,255,255,0.85); font-size: 0.83rem; text-decoration: none; }
        .auth-breadcrumb .sep { color: rgba(255,255,255,0.4); }
        .auth-breadcrumb span.active { color: #fff; font-weight: 600; }

        .auth-section { background: #f4f8f9; padding: 60px 0 80px; min-height: 60vh; display: flex; align-items: center; }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 48px 44px;
            box-shadow: 0 8px 40px rgba(6,109,119,0.1);
            max-width: 480px;
            margin: 0 auto;
        }
        .auth-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #e6f4f5, #c8eaeb);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            font-size: 1.9rem;
            color: #066D77;
        }
        .auth-card h2 { font-size: 1.5rem; font-weight: 800; color: #0d2126; text-align: center; margin-bottom: 10px; }
        .auth-card .auth-desc { color: #5a7070; font-size: 0.95rem; text-align: center; line-height: 1.7; margin-bottom: 28px; }

        .auth-card label { font-size: 0.85rem; font-weight: 600; color: #3d5a5e; margin-bottom: 6px; display: block; }
        .auth-card .form-control {
            border: 1.5px solid #d8e8ea;
            border-radius: 10px;
            padding: 13px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            color: #0d2126;
            transition: border-color .2s, box-shadow .2s;
        }
        .auth-card .form-control:focus {
            border-color: #066D77;
            box-shadow: 0 0 0 3px rgba(6,109,119,0.1);
            outline: none;
        }

        .pw-field { position: relative; }
        .pw-toggle {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            border: none; background: none;
            color: #5a7070; cursor: pointer;
            font-size: 1rem; padding: 0;
        }
        .pw-toggle:focus { outline: none; }

        .auth-card .btn-primary-brand {
            width: 100%;
            background: linear-gradient(135deg, #066D77, #009095);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
            margin-top: 8px;
        }
        .auth-card .btn-primary-brand:hover { opacity: 0.92; transform: translateY(-1px); }

        .auth-links { display: flex; justify-content: center; gap: 24px; margin-top: 20px; }
        .auth-links a { color: #066D77; font-size: 0.88rem; font-weight: 600; text-decoration: none; }
        .auth-links a:hover { text-decoration: underline; }

        label.error { color: #dc3545; font-size: 0.82rem; margin-top: 4px; display: block; }
    </style>
@endSection

@section('content')

    <section class="auth-hero">
        <div class="container">
            <h1>Reset Password</h1>
            <div class="auth-breadcrumb">
                <a href="{{ route('home') }}"><i class="bi bi-house-fill me-1"></i>Home</a>
                <span class="sep">/</span>
                <span class="active">Reset Password</span>
            </div>
        </div>
    </section>

    <section class="auth-section">
        <div class="container">
            <div class="auth-card">
                <div class="auth-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h2>Create New Password</h2>
                <p class="auth-desc">Enter your email and choose a strong new password for your account.</p>

                @if (session('status'))
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-3" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="reset_form" method="post" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="your@email.com"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $request->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password">New Password</label>
                        <div class="pw-field">
                            <input type="password" id="password" name="password" placeholder="New password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation">Confirm New Password</label>
                        <div class="pw-field">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Confirm password" class="form-control" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-brand">
                        <i class="bi bi-check-lg me-2"></i>Reset Password
                    </button>
                </form>

                <div class="auth-links">
                    <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Back to Login</a>
                    <a href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i>Create Account</a>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_js')
    <script>
        function togglePw(fieldId, btn) {
            const field = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
@endSection
