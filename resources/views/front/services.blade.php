@extends('front/layout')

@section('meta_title', 'About Page')
@section('meta_description', 'About Page Description')
@section('meta_keywords', 'About,Alpha,Education')

@section('meta_tags')
    <!--Additional meta tags (if necessary) -->
<meta property="og:title" content="About Page - My Website">
<meta property="og:description" content="This is the about page of My Website.">
<meta property="og:url" content="{{ url()->current() }}">

<title>Healthcare Management Consultant Services | Alpha TSM</title>
@endsection

@section('custom_css')
@endSection

@section('content')
    <!-- Page Title -->
    <div class="page-title s50 animatedBackground">
        <div class="container ">
            <div class="row">
                <div class="col-lg-6 col-md-12">

                    {{-- <h3>Brings Services<br>Together</h3> --}}
                    <h3 class="text-black font-weight-bold text-center p-5 rounded shadow">
                        Brings Services<br>Together
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="dot-divider"></li>
                            <li><a href="{{ route('front.services') }}">Services</a></li>
                        </ul>
                    </h3>
                </div>
                <div class="col-lg-6 col-md-12 about-head">
                    <span>Alpha endeavors to enhance all the aspects of your healthcare facility by providing a gamut of
                        solutions, ranging from, strategy development, operational improvements, total quality management,
                        education &medical staff training, professional resourcing &licensing etc.</span>
                </div>
            </div>
        </div>

        <div class="bg-pattern"><img src="{{ asset('public/front/assets/img/bg-pattern-2.png') }}" alt="pattern"></div>
    </div>
    <!-- End Page Title -->
    <section class="boxes-area" id="01">
        <div class="owl-thumbs" data-slider-id="01">
            <div class="owl-thumb-item">
                <div class="box">
                    <a href="#01" class=" mt-7p ">
                        <div class="d-table">
                            <div class="d-table-cell">
                                <h3>For Healthcare Facilities</h3>
                                <span>01</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="owl-thumb-item">
                <div class="box bg">
                    <a href="#02" class=" mt-7p ">
                        <div class="d-table">
                            <div class="d-table-cell">
                                <h3>For Medical Professionals</h3>
                                <span>02</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="owl-thumb-item">
                <div class="box">
                    <a href="#03" class="mt-7p">
                        <div class="d-table">
                            <div class="d-table-cell">
                                <h3>For Non-medical Professionals</h3>
                                <span>03</span>
                            </div>
                        </div>
                    </a>
                </div></a>

            </div>
        </div>
        </div>
    </section>
    <!-- Right Side Modal -->
    @include('front.view.side-bar')
    <!-- End Right Side Modal -->

    <!-- Features Area -->
    <section class="services-area bg pt-50" id="0y">
        <div class="container">
            <div class="row">
                {{-- @foreach ($services as $service) --}}
                <div class="col-lg-4 col-md-4">
                    <div class="section-title">
                        <h3>Services For Healthcare Facilities</h3>
                    </div>
                    <div class="col-lg-12 col-md-12 ">
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
                        </div>
                    </div>

                    <p class=" pt-50">Our motto is to helps healthcare facilities identify and capitalize on new and
                        emerging opportunities, provide guidance to ensure successful implementation of changes
                        occurring in
                        the industry and work with clients to develop processes that ensure fulfillment of standards.
                    </p>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        <a href="{{ route('view_category', 'healthcare-facility-licensing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Facility<br>Licensing</h4>
                            </div>
                        </a>
                    </div>

                    <div class="contact-box  mt-30">
                        <a href="{{ route('view_category', 'healthcare-professional-resourcing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Professional Resourcing<br>Solution</h4>
                            </div>
                        </a>
                    </div>

                    <div class="contact-box  mt-30">
                        <a href="{{ route('view_category', 'facility-auditing-accreditation') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Facility Auditing &amp; <br>Accreditation</h4>
                            </div>
                        </a>
                    </div>

                    <div class="contact-box  mt-30">
                        <a href="{{ route('view_category', 'healthcare-management-outsourcing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Management<br>Outsourcing</h4>
                            </div>
                        </a>
                    </div>
                    <div class="contact-box  mt-30">
                        <a href="{{ route('view_category', 'healthcare-insurance-empanelment') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Insurance <br>Empanelment</h4>
                            </div>
                        </a>
                    </div>
                    <div class="contact-box  mt-30">
                        <a href="{{ route('view_category', 'healthcare-professional-licensing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>healthcare Professional<br>Licensing</h4>
                            </div>
                        </a>
                    </div>

                    <div class="contact-box  mt-30">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Facility Merger & <br>Acquisition</h4>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="contact-box ">
                        <a href="{{ route('view_category', 'healthcare-feasibility-study') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Feasibility <br>Study</h4>
                            </div>
                        </a>
                    </div>
                    <div class="contact-box  mt-30">
                        <a href="{{ route('view_category', 'healthcare-infrastructure-transformation') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Facility Infrastructure <br>Transformation</h4>
                            </div>
                        </a>
                    </div>

                    <div class="contact-box  mt-30">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Branding & <br>Web Development</h4>
                            </div>
                        </a>
                    </div>

                    <div class="contact-box  mt-30">
                        <a href="{{ route('view_category', 'healthcare-digital-marketing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Digital Marketing for<br>Healthcare</h4>
                            </div>
                        </a>
                    </div>

                    <div class="contact-box  mt-30">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Customer Relationship<br>Solution</h4>
                            </div>
                        </a>
                    </div>
                    <section id="02"></section>
                    <div class="contact-box  mt-30">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Software Development for <br>Healthcare Facilities</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="services-area bg-fbf9f8 pt-50" id="03">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="section-title ">
                        <h3>Services For <br>Medical Professionals</h3>
                    </div>

                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Career<br>Placement</h4>
                            </div>
                        </a>
                    </div>
                    <div class="contact-box mt-30">
                        <a href="{{ route('view_category', 'healthcare-professional-licensing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Professional<br>Licensing</h4>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>CME & Medical Training <br>Courses</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="services-area pt-50" id="0x">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-12">
                    <div class="section-title">
                        <h3>Services For <br>Non-medical Professionals</h3>
                    </div>

                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Management Courses & <br>Training</h4>
                            </div>
                        </a>
                    </div>
                    <div class="contact-box mt-30">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>CPD & Professional <br>Courses</h4>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        <a href="#">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Professional Membership</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="myButton"></div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('public/front/assets/js/chat-hpl.js') }}"></script>
<script>
    $(function () {
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