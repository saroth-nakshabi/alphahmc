<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign In | Alpha Health Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        *, body { font-family: 'Outfit', sans-serif; }
        html, body { height: 100%; margin: 0; }

        .main-container {
            display: flex;
            min-height: 100vh;
        }

        /* Left panel */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #066D77 0%, #009095 60%, #00b4bd 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 240px; height: 240px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .left-panel .panel-logo {
            position: absolute;
            top: 1.5rem; left: 1.5rem;
            z-index: 2;
        }
        .left-panel .panel-tagline {
            color: rgba(255,255,255,0.9);
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            position: relative; z-index: 2;
        }
        .left-panel .panel-sub {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 32px;
            position: relative; z-index: 2;
        }
        .left-panel .illustration {
            max-width: 380px;
            width: 100%;
            position: relative; z-index: 2;
        }

        /* Right panel */
        .right-panel {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 80px;
            min-width: 420px;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }
        .login-box .brand-mark {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }
        .login-box .brand-mark .dot {
            width: 10px; height: 10px;
            background: linear-gradient(135deg, #066D77, #009095);
            border-radius: 50%;
        }
        .login-box h4 { font-size: 1.6rem; font-weight: 800; color: #0d2126; margin-bottom: 6px; }
        .login-box .sub { color: #6b8a8d; font-size: 0.95rem; margin-bottom: 32px; }

        .login-box .form-label { font-size: 0.84rem; font-weight: 600; color: #3d5a5e; margin-bottom: 6px; }
        .login-box .form-control {
            border: 1.5px solid #d6e8ea;
            border-radius: 10px;
            padding: 13px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            color: #0d2126;
            transition: border-color .2s, box-shadow .2s;
        }
        .login-box .form-control:focus {
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
            color: #6b8a8d; cursor: pointer;
            font-size: 1rem; padding: 0;
        }
        .pw-toggle:focus { outline: none; }

        .forgot-link { font-size: 0.84rem; color: #066D77; font-weight: 600; text-decoration: none; }
        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
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
        }
        .btn-login:hover { opacity: 0.9; transform: translateY(-1px); }

        .divider { border-color: #e5eff0; }
        .footer-links { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
        .footer-links a { color: #066D77; font-size: 0.86rem; font-weight: 600; text-decoration: none; }
        .footer-links a:hover { text-decoration: underline; }

        @@media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { padding: 40px 24px; min-width: auto; width: 100%; }
        }

        label.error { color: #dc3545; font-size: 0.81rem; margin-top: 4px; display: block; }
        .alert-danger { border-radius: 10px; font-size: 0.9rem; }
    </style>
</head>

<body>
    <div class="main-container">

        {{-- Left Panel --}}
        <div class="left-panel">
            <div class="panel-logo">
                <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Alpha Health Group" height="54" />
            </div>
            <p class="panel-tagline mt-5">Healthcare Excellence</p>
            <p class="panel-sub">Empowering facilities with compliance, quality, and growth.</p>
            <img src="{{ asset('public/front-new/assets/images/your-modernize-img.png') }}"
                alt="Alpha Health Portal" class="illustration img-fluid" />
        </div>

        {{-- Right Panel --}}
        <div class="right-panel">
            <div class="login-box">
                <div class="brand-mark">
                    <div class="dot"></div>
                    <span style="font-size:0.8rem;font-weight:700;color:#066D77;letter-spacing:1px;text-transform:uppercase;">Alpha Health Group</span>
                </div>

                <h4>Welcome back</h4>
                <p class="sub">Sign in to your account to continue</p>

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                @if (session('status'))
                    <div class="alert alert-success mb-3">{{ session('status') }}</div>
                @endif

                <form id="login_form" method="post" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control"
                            placeholder="you@example.com" value="{{ old('email') }}" required />
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="password">Password</label>
                        <div class="pw-field">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter your password" required />
                            <button type="button" class="pw-toggle" onclick="togglePw()">
                                <i class="bi bi-eye" id="pw-icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-end mb-4">
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </button>
                </form>

                <hr class="divider mt-4 mb-3">
                <div class="footer-links">
                    <a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Back to Website</a>
                    <a href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i>Create Account</a>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script>
        function togglePw() {
            const f = document.getElementById('password');
            const icon = document.getElementById('pw-icon');
            if (f.type === 'password') {
                f.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                f.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        $(document).ready(function () {
            if ($("#login_form").length) {
                $("#login_form").validate({
                    rules: {
                        email: { required: true, email: true },
                        password: { required: true }
                    },
                    messages: {
                        email: { required: 'Email is required', email: 'Enter a valid email' },
                        password: { required: 'Password is required' }
                    }
                });
            }
        });
    </script>
</body>
</html>
