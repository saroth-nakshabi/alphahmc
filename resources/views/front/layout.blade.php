<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="keywords" content="@yield('meta_keywords', 'Alpha Education description')">
    <meta name="description" content="@yield('meta_description', 'Alpha Education description')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('public/favicon.png') }}">





    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Healthcare Consultancy in Dubai | Alpha Health Group</title>
    <meta name="description"
        content="Choose the best approved healthcare consultant for establishing new clinics, medical engineering, managing existing clinics & hospitals in the UAE">


    <title>@yield('meta_title', 'Alpha Education')</title>
    <!-- Outfit Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/bootstrap.min.css') }}">
    <!-- IcoFont Min CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/icofont.min.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/animate.css') }}">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/owl.carousel.css') }}">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/magnific-popup.css') }}">
    <!-- Owl Theme Default CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/owl.theme.default.min.css') }}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/responsive.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('public/front/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('public/front/assets/css/floating-wpp.css') }}">

    <!-- Other meta tags -->
    @yield('meta_tags')

    {{-- <style>
        label.error {
            color: red;
        }

        .header-login-regi:hover .login-sub-menu {
            visibility: visible;
            opacity: 1;
            transform: scaleY(1);
            z-index: 9;
        }

        .login-sub-menu {
            position: absolute;
            left: -20px;
            min-width: 190px;
            visibility: hidden;
            opacity: 0;
            transform: scaleY(0);
            z-index: 1;
            transition: all 300ms;
        }

        .login-sub-menu a {
            width: auto;
            height: auto;
            background: white;
            color: #c08831;
            padding: 12px 20px;
            line-height: 30px;
            border-radius: 0;
            justify-content: left;
        }

        .login-sub-menu a:hover {
            background: #c08831;
            color: white;
        }

        .category-link.active-category {
            background-color: #c08831 !important;
            color: white !important;
        }

        .category-link.active-category .ms-3 {
            color: white !important;
        }

        .list-group-item:hover {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }
    </style> --}}



    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .breadcum {
            background-color: #066D77;
            color: #fff;
            padding: 2rem 0;
            text-align: center;
        }

        .breadcum .title {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .breadcum .para a {
            color: #fff;
        }

        .breadcum .para {
            font-size: 1rem;
            color: #fff;
            text-transform: uppercase;
            padding-top: 0.5rem;
            font-weight: 600;
        }

        .login-section {
            background-color: #f7f7f7;
            padding: 4rem 0;
        }

        .signin {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .signin .login-title {
            text-transform: uppercase;
            color: #066D77;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .signin .inputBox {
            margin-bottom: 1rem;
        }

        .signin .inputBox input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            transition: all 0.3s ease;
        }

        .signin .inputBox input:focus {
            border-color: #066D77;
            box-shadow: 0 0 5px rgba(6, 109, 119, 0.4);
        }

        .signin .links {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .signin .links a {
            color: #066D77;
            font-size: 1rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .signin .links a:hover {
            color: #044E55;
        }

        .signin .all-btn {
            text-align: center;
        }

        .signin .all-btn button {
            width: 40%;
            background-color: #066D77;
            color: #fff;
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            border-radius: 12px !important;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }


        .signin .all-btn button:hover {
            background-color: #044E55;
            box-shadow: 0 4px 8px rgba(6, 109, 119, 0.3);
        }
    </style>

    @yield('custom_css')
</head>

<body>
    <header>
        <!-- Call Navi -->
        @include('front.view.header')
    </header>


    @yield('content')


    <footer>
        <!-- Footer Area -->
        @include('front.view.footer')

        <!-- End Footer Area -->
    </footer>


    {{-- <div id="myButton"></div> --}}



    <!-- jQuery Min JS -->
    <script src="{{ asset('public/front/assets/js/jquery.min.js') }}"></script>


    <div class="go-top"><i class="icofont-stylish-up"></i></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript" src="{{ asset('front/view/chat-gen.js') }}"></script>
    {{-- <script src="assets/js/jquery.min.js"></script> --}}
    <script type="text/javascript" src="{{ asset('public/front/assets/js/floating-wpp.js') }}"></script>

    <script>
        $("form#contactForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $("#AjaxLoader").show();
            $.ajax({
                url: '{{ url('mail.php') }}',
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#AjaxLoader").hide();
                    $('.success').slideDown('slow', function() {
                        $('.success').delay(7000).slideUp();
                    });
                }
            });

            return false;
        });
    </script>

    {{--
    <script>
        $(function() {
            $('#myButton').floatingWhatsApp({
                phone: '+971564200934',
                popupMessage: "You can chat with our licensing consultant to discuss New license Application, Dataflow Processing, Exam Booking, Renew your license, Transfer your license, Activate your license",
                message: "Hello, I would like to inquire about your services.",
                showPopup: true,
                headerTitle: 'Welcome to Alpha Chat Assistant!',
                headerColor: '#009688',
                backgroundColor: '#009688',
                buttonImage: '<img src="{{ asset('public/front/assets/img/whatsapp.svg') }}" alt="WhatsApp" />'
            });
        });
    </script> --}}


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('public/front/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/front/assets/js/bootstrap.min.js') }}"></script>
    <!-- Owl Carousel JS -->
    <script src="{{ asset('public/front/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/front/assets/js/owl.carousel2.thumbs.min.js') }}"></script>
    <!-- Magnific Popup JS -->
    <script src="{{ asset('public/front/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Mixitup JS -->
    <script src="{{ asset('public/front/assets/js/jquery.mixitup.min.js') }}"></script>
    <!-- Waypoints JS -->
    <script src="{{ asset('public/front/assets/js/waypoints.min.js') }}"></script>
    <!-- CounterUp JS -->
    <script src="{{ asset('public/front/assets/js/jquery.counterup.min.js') }}"></script>
    <!-- ajaxChimp JS -->
    <script src="{{ asset('public/front/assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('public/front/assets/js/main.js') }}"></script>

    <!-- Form Validator Min JS -->
    <script src="{{ asset('public/front/assets/js/form-validator.min.js') }}"></script>
    <!-- Contact Form Min JS -->
    <script src="{{ asset('public/front/assets/js/contact-form-script.js') }}"></script>
    <!-- Turacos Map JS FILE -->
    <script src="{{ asset('public/front/assets/js/turacos-map.js') }}"></script>




    {{-- set the cookie in the backend if it doesn't exist --}}
    {{-- @if (!request()->cookie('userTimezone'))
        <script>
            // Detect the user's timezone
            let userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

            // Send the timezone to the backend via AJAX
            fetch('{{ route('set_timezone') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                },
                body: JSON.stringify({
                    timezone: userTimezone
                })
            });
            location.reload();
        </script>
    @endif --}}


    @yield('custom_js')
    @include('front.partials.ai-assistant')
</body>

</html>