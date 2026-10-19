@extends('front/layout')

@section('meta_title', 'About Page')
@section('meta_description', 'About Page Description')
@section('meta_keywords', 'About,Alpha,Education')

@section('meta_tags')
    <!-- Additional meta tags (if necessary) -->
    <meta property="og:title" content="About Page - My Website">
    <meta property="og:description" content="This is the about page of My Website.">
    <meta property="og:url" content="{{ url()->current() }}">


    <title>About Alpha Health group | Health Consultants</title>
@endsection

@section('custom_css')
@endSection

@section('content')
    <!-- Page Title -->
    <div class="page-title animatedBackground">
        <div class="container ">
            <div class="row">
                <div class="col-lg-6 col-md-12">

                    <!-- <h3>About Alpha</h3> -->
                    <h3 class="text-black font-weight-bold text-center p-5 rounded shadow">
                        About Alpha
                    </h3>


                </div>
                <div class="col-lg-6 col-md-12 about-head">
                    <span>A comprehensive healthcare consultants with over 20 years of intense expertise in consulting,
                        compliance, operating and managing healthcare facilities in the region.</span>
                </div>
            </div>
        </div>

        <div class="bg-pattern">
            <img src="{{ asset('public/front/assets/img/bg-pattern-2.png') }}" alt="pattern">
        </div>
    </div>
    <!-- End Page Title -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a class="text-dark font-weight-bold" href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">About Us</li>
        </ol>
    </nav>
    <!-- Right Side Modal -->
    @include('front.view.side-bar')
    <!-- End Right Side Modal -->

    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="about-text">
                        <img src="{{ asset('public/front/assets/img/alpha-hg-logo-01.svg') }}" alt="about">
                        <p> </p>

                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="about-text">
                        <img src="{{ asset('public/front/assets/img/alpha-hg-logo-02.svg') }}" alt="about">
                        <p> </p>

                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="about-text">
                        <img src="{{ asset('public/front/assets/img/alpha-hg-logo-03.svg') }}" alt="about">
                        <p> </p>

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Features Area -->
    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="about-text">
                        <h3>We Are Alpha</h3><br>
                        <span>Alpha Health Group of comprises healthcare consultants with expertise and over 20 years of
                            experience in the Middle East, delivering high-quality services to the healthcare
                            industry.</span><br><br>
                        <p>We work closely with healthcare facilities, and government healthcare regulatory bodies, enabling
                            you to provide the best healthcare service effectively and reliably. Our motto is to help
                            healthcare facilities and healthcare professionals identify and capitalize on new and emerging
                            opportunities, provide guidance to ensure successful navigation of changes occurring in the
                            healthcare industry, and work with clients to develop processes that ensure fulfillment of
                            international standards.</p>
                        <p>Our team focuses on being committed, by maintaining practical processes to ensure consistent
                            results and quality deliverables, abiding by the project timelines while we maintain the
                            customer first cognizant of the fact that customer requirements come first and we strive to
                            always meet their needs. This is reflected in our flexibility as an organization.</p>
                        <p>We believe in Continual Improvement of our services proves by internal review of our business
                            management system with the aim to continually improve business performance, processes and
                            services, to ensure optimal efficiency.</p>

                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="row about-image">
                        <div class="col-6 col-lg-6 col-md-6">
                            <div class="image">
                                <img src="{{ asset('public/front/assets/img/about-img1.jpg') }}" alt="about">
                            </div>
                        </div>

                        <div class="col-6 col-lg-6 col-md-6">
                            <div class="image">
                                <img src="{{ asset('public/front/assets/img/about-img3.jpg') }}" alt="about">
                            </div>
                        </div>

                        <div class="col-6 col-lg-6 col-md-6">
                            <div class="image mt-30">
                                <img src="{{ asset('public/front/assets/img/about-img2.jpg') }}" alt="about">
                            </div>
                        </div>

                        <div class="col-6 col-lg-6 col-md-6">
                            <div class="image mt-30">
                                <img src="{{ asset('public/front/assets/img/about-img4.jpg') }}" alt="about">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Area -->
    <section class="services-area bg ptb-100">
        <div class="container">
            <div class="section-title">
                <span>adapting our services to offer every client the best solutions</span>
                <h3>Brings services together!</h3>
                <p>Alpha endeavors to enhance all the aspects of your healthcare facility by providing a gamut of solutions,
                    ranging from, strategy development, operational improvements, total quality management, education
                    &medical staff training, professional resourcing &licensing etc.</p>

            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single-services">
                        <div class="icon">
                            <i class="icofont-bullseye"></i>
                        </div>
                        <h3>Our Mission</h3>
                        <p>Be a consulted and collaborative partner with facility, health systems, healthcare professional
                            and government entities to deliver world-class care to our clients.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="single-services">
                        <div class="icon">
                            <i class="icofont-eye-alt"></i>
                        </div>


                        <h3>Our Vision</h3>
                        <p>Help facility owners and healthcare professionals secure their future in the UAE.</p>
                        <br>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6">
                    <div class="single-services">
                        <div class="icon">
                            <i class="icofont-holding-hands"></i>
                        </div>
                        <h3>Alpha Promise</h3>
                        <p>Provide all the required services for any healthcare establishment effectively and efficiently
                            with proven better result</p>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Services Area -->

    <!-- FunFacts Area -->
    <section class="funfacts-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-6 col-lg-3 col-sm-6 col-md-6">
                    <div class="fun-fact">
                        <i class="icofont-users-alt-5"></i>
                        <p>Active Clients:</p>
                        <h3><span class="count">10,000</span>+</h3>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-sm-6 col-md-6">
                    <div class="fun-fact">
                        <i class="icofont-law-document"></i>
                        <p>Licence Obtained:</p>
                        <h3><span class="count">6000</span>+</h3>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-sm-6 col-md-6">
                    <div class="fun-fact">
                        <i class="icofont-hospital"></i>
                        <p>Projects Done:</p>
                        <h3><span class="count">100</span>+</h3>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-sm-6 col-md-6">
                    <div class="fun-fact">
                        <i class="icofont-award"></i>
                        <p>Approved Gov't Body </p>
                        <h3><span class="count">5</span>+</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End FunFacts Area -->
    <div id="myButton"></div>
@endsection

@section('custom_js')

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
    </script>
@endSection
