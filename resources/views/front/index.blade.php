@extends('front/layout')

@section('meta_title', 'Home Page')
@section('meta_description', 'About Page Description')
@section('meta_keywords', 'About,Alpha,Education')

@section('meta_tags')
    <!-- Additional meta tags (if necessary) -->
    <meta property="og:title" content="About Page - My Website">
    <meta property="og:description" content="This is the about page of My Website.">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('content')
    <!-- Main Banner Three -->
    <div class="hompage-slides-wrapper">
        <div class="homepage-slides owl-carousel" style="max-height: 600px;">

            <div class="main-banner-three" data-slider-id="1">
                <div class="d-table" style="margin-top: -3%;">
                    <div class="d-table-cell">
                        <div class="container slider-w">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="hero-content">
                                        {{-- <img class="dx-logo" src="assets/img/na-troi-logo-red.svg" alt="about"> --}}
                                        <img class="dx-logo"
                                            src="{{ asset('public/front/assets/img/na-troi-logo-red.svg') }}"
                                            alt="about">
                                        <h1>Healthcare Facility Management<br> Partners</h1>

                                        <p>We collaborate with healthcare facilities and government entities to facilitate
                                            the best healthcare management & outsourcing service, which are effective and
                                            reliable.</p>
                                        <a href="healthcare-management-services" class="btn btn-primary">Know More</a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="banner-image">
                                        {{-- <img src="assets/img/main-banner.png" alt="banner-img"> --}}
                                        <img src="{{ asset('public/front/assets/img/main-banner.png') }}" alt="banner-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="circle-pattern-1"></div>
            </div>

            <div class="main-banner-three hero-bg-1">
                <div class="d-table" style="margin-top: -3%;">
                    <div class="d-table-cell">
                        <div class="container slider-w">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="hero-content">
                                        {{-- <img class="dx-logo" src="assets/img/na-troi-logo-gold.svg" alt="about"> --}}
                                        <img class="dx-logo"
                                            src="{{ asset('public/front/assets/img/na-troi-logo-gold.svg') }}"
                                            alt="about">
                                        <h1>Professional Development Programs</span></h1>

                                        <p>Keeping you abreast with the current knowledge, we facilitate medical training
                                            services & related programs, provided by international faculties and ensuring
                                            world’s best practices.</p>
                                        <a href="healthcare-professional-development" class="btn btn-primary">Learn More</a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="banner-image">
                                        {{-- <img src="assets/img/main-edu.png" alt="banner-img"> --}}
                                        <img src="{{ asset('public/front/assets/img/main-edu.png') }}" alt="banner-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="circle-pattern-1"></div>
            </div>

            <div class="main-banner-three">
                <div class="d-table" style="margin-top: -3%;">
                    <div class="d-table-cell">
                        <div class="container slider-w">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="hero-content">
                                        {{-- <img class="dx-logo" src="assets/img/na-troi-logo-gray.svg" alt="about"> --}}
                                        <img class="dx-logo"
                                            src="{{ asset('public/front/assets/img/na-troi-logo-gray.svg') }}"
                                            alt="about">
                                        <h1>Healthcare Digital Marketing</h1>

                                        <p>Alpha is the only digital marketing agency who provides tailored digital
                                            marketing services for healthcare provides such as hospitals, medical centers.
                                        </p>
                                        <a href="healthcare-digital-marketing" class="btn btn-primary">Get Started</a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="banner-image">
                                        {{-- <img src="assets/img/hbd.png" alt="banner-img"> --}}
                                        <img src="{{ asset('public/front/assets/img/hbd.png') }}" alt="banner-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="circle-pattern-1"></div>
            </div>
        </div>

        <section class="boxes-area">
            <div class="owl-thumbs" data-slider-id="1">
                <div class="owl-thumb-item">
                    <div class="box">
                        <div class="d-table">
                            <div class="d-table-cell">
                                <h3>Healthcare Management</h3>
                                <span>01</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-thumb-item">
                    <div class="box bg">
                        <div class="d-table">
                            <div class="d-table-cell">
                                <h3>Alpha Education</h3>
                                <span>02</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-thumb-item">
                    <div class="box">
                        <div class="d-table">
                            <div class="d-table-cell">
                                <h3>Digital Marketing</h3>
                                <span>03</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Carousel Thumbs -->

    </div>
    <!-- End Main Banner Three -->

    <!-- Right Side Modal -->
    @include('front.view.side-bar')
    <!-- End Right Side Modal -->

    <!-- Working Process Area -->
    <section class="working-process-area ptb-100 bg-fbf9f8">
        <div class="container">
            <div class="section-title">
                <span>How We Work</span>
                <h3>Our Working Process</h3>
                <p>Our business system will conform to the requirements of the regulatory bodies and align with the
                    International Standards.</p>

                <a href="{{ route('how_alpha_work') }}" class="read-more-btn">Read More</a>
            </div>

            <div class="row">
                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-read-book"></i>
                        </div>
                        <h3>Planning</h3>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-light-bulb"></i>
                        </div>
                        <h3>Research</h3>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-mathematical"></i>
                        </div>
                        <h3>Execute</h3>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-chart-growth"></i>
                        </div>
                        <h3>Results</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Working Process Area -->

    <!-- Services Area -->
    <section class="services-area bg ptb-100">
        <div class="container">
            <div class="section-title">
                <span>We Work With You Not For You</span>
                <h3>We Provide transcending Healthcare Management Services!</h3>
                <p>Our array of service continues to expand and provide the finest services in all aspects of healthcare
                    management.</p>

                <a href="{{ route('front.services') }}" class="read-more-btn">view more services</a>
            </div>

            <div class="row">

                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        {{-- <a href="{{ route('front.services') }}"> --}}
                        <a href="{{ route('view_category', 'healthcare-facility-licensing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Facility<br>Licensing</h4>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        <a href="{{ route('view_category', 'healthcare-professional-resourcing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Professional Resourcing<br>Solution</h4>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="contact-box">
                        <a href="{{ route('view_category', 'facility-auditing-accreditation') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Facility Auditing & <br>Accreditation</h4>
                            </div>
                    </div></a>
                </div>

                <div class="col-lg-4 col-md-12 mt-30">
                    <div class="contact-box">
                        <a href="{{ route('view_category', 'healthcare-management-outsourcing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Management<br>Outsourcing</h4>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 mt-30">
                    <div class="contact-box">
                        <a href="{{ route('view_category', 'healthcare-professional-licensing') }}">
                            <div class="icon">
                                <i class="icofont-arrow-right"></i>
                            </div>

                            <div class="content">
                                <h4>Healthcare Professionals<br>Licensing</h4>
                            </div>
                        </a>
                    </div></a>
                </div>
                <div class="col-lg-4 col-md-12 mt-30">
                    <div class="contact-box">
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

            </div>
        </div>
    </section>
    <!-- End Services Area -->

    <!-- Analysis Area -->
    <section class="analysis-area ptb-100 bg-fbf9f8">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="analysis-text">

                        <h3 style=" margin-bottom: 0; #d13a3f;"><b>Alpha Health Group</b> </h3>
                        <br>
                        <p>Healthcare management consultancy providing safe & compassionate full services healthcare
                            management consultancy customized to UAE</p>
                        <ul>
                            <li><b>Highly Qualified Team</b><br>Be a consulted and collaborative partner with facility,
                                health systems, healthcare professional and government entities to deliver world-class care
                                to our clients.</li>
                            <li><b>Transparent & Trustworthy</b><br>Alpha strives for operational excellence through an
                                ethical approach and implementing evidence-based practices, ensuring best outcomes for all
                                our clients.</li>
                            <li><b>We work with you</b><br>We mitigate healthcare operational risk through effective
                                management in line with regulatory requirements, while reducing the operational costs. </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="analysis-form">
                        <h3>Let Us Know</h3>
                        <p class="mt-20 mb-20">Thank you for visiting our website, if you have any query or any message to
                            any of our department feel free to drop your message below</p>
                        <form id="contactForm" name="contactForm" class="">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <input id="fullName" name="fullName" type="text" placeholder="Your Name*"
                                        class="form-control">
                                </div>

                                <div class="col-lg-6 form-group">
                                    <input id="Email" name="Email" type="email" placeholder="Email*"
                                        class="form-control">
                                </div>

                                <div class="col-lg-6  form-group">
                                    <input id="Phone" name="Phone" type="text" placeholder="Phone Number*"
                                        class="form-control">
                                </div>
                                <div class="col-lg-6 ">
                                    <select class=" browser-default custom-select form-group form-control" name="service">
                                        <option selected>Select Consigned Department</option>
                                        <option value="Digital Marketing">Healthcare Management</option>
                                        <option value="Healthcare Professional Licensing">Healthcare Professional Licensing
                                        </option>
                                        <option value="Healthcare Project Management">Healthcare Project Management
                                        </option>
                                        <option value="Healthcare Digital Marketing">Healthcare Digital Marketing</option>
                                        <option value="Education & Courses">Education & Courses</option>
                                        <option value="General Sales & Marketing">General Sales & Marketing</option>
                                        <option value="Customer Service Care">Customer Service Care</option>
                                        <option value="Technical Support">Technical Support</option>
                                        <option value="Customer Feedback & Suggestions">Customer Feedback & Suggestions
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <textarea id="Message" name="Message" class="form-control required" rows="3"
                                    placeholder="Enter Your Message"></textarea>
                            </div>
                            <p class="success"
                                style="display:none;color:#066d77;text-align:center;width:100%;position:absolute;">We have
                                received your message, wait for the reply thank you.</p><br>
                            <button type="submit" class="btn btn-primary">Send Now</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Subscribe Area -->
    <section class="subscribe-area ptb-100 bg-066D77">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="newsletter">
                        <h4>Join Our Newsletter</h4>
                        <form class="newsletter-form" data-toggle="validator">
                            <input type="email" class="form-control" placeholder="Enter your email address"
                                name="EMAIL" required autocomplete="off">
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                            <div id="validator-newsletter" class="form-result"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Subscribe Area -->
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
