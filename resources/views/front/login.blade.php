{{-- @extends('front/layout')

@section('meta_title', 'About Page')
@section('meta_description', 'About Page Description')
@section('meta_keywords', 'About,Alpha,Education')

@section('meta_tags')
    <meta property="og:title" content="About Page - My Website">
    <meta property="og:description" content="This is the about page of My Website.">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('custom_css')
    <style>
        label.error {
            color: red;
        }
    </style>
@endSection

@section('content')
    <section class="breadcum">
        <div class="container">
            <div class="breadcum-content">
                <h2 class="title">Log In</h2>
                <h4 class="para"><a href="index.html">Home</a> / Log In</h4>
            </div>
        </div>
    </section>
    <section class="login-section">
        <div class="signin card">
            <div class="content">
                <h2 class="login-title">Log In</h2>
                <form class="form" id="login_form" method="post" action="{{ route('login.store') }}">
                    @csrf
                    <div class="inputBox">
                        <input type="text" placeholder="Email" name="email" required>
                    </div>
                    <div class="inputBox">
                        <input type="password" placeholder="Password" name="password" required>
                    </div>
                    <div class="links"> <a href="{{ route('password.request') }}">Forgot Password</a>
                        <a href="{{ route('register') }}">Signup</a>
                    </div>
                    <div class="all-btn v2">
                        <button type="submit" class="btn-p btn-blue v1 rounded">Log in Now</button>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection

@section('custom_js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script>
        $(document).ready(function() {

            if ($("#login_form").length > 0) {
                $("#login_form").validate({
                    rules: {
                        email: {
                            required: true,
                        },
                        password: {
                            required: true,
                        },
                    },
                    messages: {
                        email: {
                            required: 'Email is required',
                        },
                        password: {
                            required: 'Password is required',
                        },
                    },
                    errorPlacement: function(error, element) {
                        if (element.is("#phone")) {
                            error.insertAfter(element.parent());
                        } else if (element.is("select")) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                })
            }
        });
    </script>
@endSection --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Healthcare Consultancy in Dubai | Alpha Health Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: "Segoe UI", sans-serif;
        }

        .main-container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            background: linear-gradient(to bottom right, #eaf1f9, #f3f5fb);
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .right-panel {
            /* flex: 1; */
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px;
        }

        .login-box {
            width: 100%;
            max-width: 350px;
        }

        .login-box h4 {
            font-weight: 700;
            color: #1e1d4d;
            font-size: 1.5rem !important;
        }

        .form-label {
            font-weight: 600;
            color: #2a3547;
        }

        .login-box h4 span {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #5d87ff;
            border-color: #5a78ff;
        }

        .btn-primary:hover {
            background-color: #4d6ae5;
            border-color: #4d6ae5;
        }

        img.logo,
        img.illustration {
            max-width: 100%;
            height: auto;
        }

        .logo-wrapper {
            position: absolute;
            top: 1rem;
            left: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Left Section -->
        <div class="left-panel position-relative">
            <div class="logo-wrapper">
                <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Logo-Dark" class="logo"
                    height="70" width="230" />
            </div>
            <div>
                <img src="{{ asset('public/front-new/assets/images/your-modernize-img.png') }}" alt="modernize-img"
                    class="illustration img-fluid mt-5" width="500" />
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-panel">
            <div class="login-box">
                <h4 class="mb-2">
                    Welcome To <span style="color: #1e1d4d">ALPHA HEALTH</span>
                    <span style="color: #999999; font-weight: 700">GROUP</span>
                </h4>
                <p class="text-muted mb-4">Your Admin Dashboard</p>
                <form class="form" id="login_form" method="post" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter email" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter password"
                            required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fs-5">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
<script>
    $(document).ready(function() {

        if ($("#login_form").length > 0) {
            $("#login_form").validate({
                rules: {
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                },
                messages: {
                    email: {
                        required: 'Email is required',
                    },
                    password: {
                        required: 'Password is required',
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.is("#phone")) {
                        error.insertAfter(element.parent());
                    } else if (element.is("select")) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            })
        }
    });
</script>

</html>
