@extends('front/layout')

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
                <h2 class="title">Reset Password</h2>
                <h4 class="para"><a href="index.html">Home</a> / Reset Password</h4>
            </div>
        </div>
    </section>
    <section class="login-section">
        <div class="signin card">
            <div class="content">
                <h2 class="login-title">Reset Password</h2>
                @if (session('status'))
                    <div class="alert alert-success mt-2" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <ul class="alert alert-danger mt-2" role="alert">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form class="form" id="login_form" method="post" action="{{ route('password.store') }}">
                    @csrf
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <div class="inputBox">
                        <input type="text" placeholder="Email" name="email" value="{{ old('email', $request->email) }}"
                            required>
                    </div>
                    <div class="inputBox">
                        <input type="password" placeholder="Password" name="password" required>
                    </div>
                    <div class="inputBox">
                        <input type="password" placeholder="Password" name="password_confirmation" required>
                    </div>
                    <div class="links"> <a href="{{ route('login') }}">Login</a> <a
                            href="{{ route('register') }}">Signup</a>
                    </div>
                    <div class="all-btn v2">
                        <button type="submit" class="btn-p btn-blue v1 rounded">Reset Password</button>
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
@endSection
