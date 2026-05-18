@extends('front/layout')

@section('meta_title', 'About Page')
@section('meta_description', 'About Page Description')
@section('meta_keywords', 'About,Alpha,Education')

@section('meta_tags')
    <!-- Additional meta tags (if necessary) -->
    <meta property="og:title" content="About Page - My Website">
    <meta property="og:description" content="This is the about page of My Website.">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('custom_css')
    <!-- int tele input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"
        integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .iti {
            display: block;
        }

        label.error {
            color: red;
        }
    </style>
@endSection

@section('content')
    <section class="breadcum">
        <div class="container">
            <div class="breadcum-content">
                <h2 class="title">Sign Up</h2>
                <h4 class="para"><a href="index.html">Home</a> / Sign Up</h4>
            </div>
        </div>
    </section>
    <section class="login-section">
        <div class="signin card">
            <div class="content">
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="login-title">Sign Up</h2>
                <form class="form" action="{{ route('register.store') }}" method="POST" id="register_form">
                    @csrf
                    <div class="inputBox">
                        <input type="text" placeholder="First Name" name="first_name" value="{{ old('first_name') }}"
                            required>
                    </div>
                    <div class="inputBox">
                        <input type="text" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}"
                            required>
                    </div>
                    <div class="inputBox">
                        <input type="email" placeholder="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="inputBox">
                        <input type="tel" placeholder="phone" class="ps-5" name="phone_number" id="phone"
                            value="{{ old('phone') }}" required>
                    </div>
                    <div class="inputBox">
                        <input type="password" placeholder="Password" name="password" value="{{ old('password') }}"
                            required>
                    </div>
                    <div class="links"> <a href="#">Forgot Password</a> <a href="{{ route('login') }}">Login</a>
                    </div>
                    <div class="all-btn v2">
                        <button type="submit" class="btn-p btn-blue v1 rounded">Sign Up Now</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <!-- validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <!-- int tele input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"
        integrity="sha512-+gShyB8GWoOiXNwOlBaYXdLTiZt10Iy6xjACGadpqMs20aJOoh+PJt3bwUVA6Cefe7yF7vblX6QwyXZiVwTWGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            const phoneInputField = document.querySelector("#phone");
            const iti = window.intlTelInput(phoneInputField, {
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                initialCountry: "AE",
                preferredCountries: ['AE'],
                hiddenInput: "phone",
            });

            jQuery.validator.addMethod("phoneNumValidation", function(value, element) {
                return this.optional(element) || iti.isValidNumber();
            }, 'Please enter a valid number');

            if ($("#register_form").length > 0) {
                $("#register_form").validate({
                    rules: {
                        first_name: {
                            required: true,
                        },
                        last_name: {
                            required: true,
                        },
                        email: {
                            required: true,
                        },
                        password: {
                            required: true,
                        },
                        phone_number: {
                            required: true,
                            phoneNumValidation: true
                        },
                    },
                    messages: {
                        first_name: {
                            required: 'Frist name is required',
                        },
                        last_name: {
                            required: 'Last name is required',
                        },
                        email: {
                            required: 'Email is required',
                        },
                        password: {
                            required: 'Password is required',
                        },
                        phone_number: {
                            required: 'Phone number is required',
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
@endSection
