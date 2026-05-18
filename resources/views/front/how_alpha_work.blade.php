@extends('front/layout')

@section('meta_tags')
    <title>Healthcare Management Consultancy in Abu Dhabi | Alpha TSM</title>
@endsection

@section('custom_css')
@endSection

@section('content')
    <!-- Page Title -->
    <div class="page-title " data-bg-img="{{ asset('public/front/assets/img/bg6.jpg') }}"
        style="background-image: url({{ asset('public/front/assets/img/bg6.jpg') }});">
        <div class="container ">
            <div class="row">
                <div class="col-lg-6 col-md-12">

                    <!-- <h3>How Alpha <br>Work</h3> -->
                    <h3 class="text-black font-weight-bold text-center p-5 rounded shadow">
                        How Alpha Work
                    </h3>
                </div>
            </div>
        </div>

        <div class="bg-pattern"><img src="{{ asset('public/front/assets/img/bg-pattern-2.png') }}" alt="pattern"></div>
    </div>
    <nav aria-label="breadcrumb">
        <!-- <ol class="breadcrumb">
                                                                                                                                                                                                                                                                                                                                        <li class="breadcrumb-item"><a class="black-text" href="/">Home</a></li>
                                                                                                                                                                                                                                                                                                                                        <li class="breadcrumb-item active">How Alpha Works</li>
                                                                                                                                                                                                                                                                                                                                    </ol> -->
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a class="text-dark font-weight-bold" href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">How Alpha Works</a></li>
        </ol>
    </nav>
    <!-- End Page Title -->

    <!-- Right Side Modal -->
    @include('front.view.side-bar')
    <!-- End Right Side Modal -->

    <section class="working-process-area ptb-100 bg-fbf9f8" style="margin-top: -15px;">
        <div class="container">
            <div style="text-align: center !important;">
                <h2 style="margin-bottom: 30px;">Alpha Working Process</h2>
                <p style="margin-bottom: 50px;">We meticulously analyse your existing business system which helps us in
                    understanding the areas for improvement and enhance your working processes to attain the desired
                    results, ensuring compliance with the regulatory guidelines.
                    Our expertise in the varied domains of healthcare, helps us in designing customized performance
                    improvement plans which are in alignment with the international best practices.
                    We assign dedicated subject matter experts to work with you during the implementation, continuously
                    monitoring and making the desired changes according to the project requirement.
                    We conduct adequate training exercises and design periodic monitoring systems to ensure adherence, to
                    the set processes and move towards the assigned goal.
                    Improvise, Accomplish and Sustain is what Alpha facilitates for your organisation.
                </p>
            </div>

            <div class="row">
                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-read-book"></i>
                        </div>
                        <h3>Research</h3>
                        <p>We do multiple point-based evaluations on all the required services to find the potential gaps in
                            the current practice compared to the best practices and plan the task.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-light-bulb"></i>
                        </div>
                        <h3>Plan</h3>
                        <p>An account manager will be assigned to each facility who is completely responsible for the work
                            process, and accordingly each planned task is assigned to the relevant team.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-mathematical"></i>
                        </div>
                        <h3>Execute</h3>
                        <p>Teams will be deployed to execute specific tasks, short term goals will be monitored until
                            completion of the process, for annual outsourcing this will be a continuous process.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-3">
                    <div class="single-work-process">
                        <div class="icon">
                            <i class="icofont-chart-growth"></i>
                        </div>
                        <h3>Results</h3>
                        <p>At the end of the project cycle or assigned targets, the achieved results will be reviewed &
                            analyzed with the initial status, to measure the success rate to move further.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="project-details-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="project-details">
                        <div class="row">
                            <div class="col-lg-8 col-md-7">
                                <h4>Quality derives from the commitment to service excellence. Which we believe will be
                                    achieved by ensuring:</h4><br>
                                <ul class="features">
                                    <li>Being cognizant of the fact that customer requirements come first and we strive to
                                        always meet their needs. This is reflected in our flexibility as an organization
                                    </li>
                                    <li>We build mutually beneficial relationships with customers and suppliers.</li>
                                    <li>We focus on being committed by maintaining practical processes to ensure consistent
                                        results and quality deliverables, abiding to the project timelines.</li>
                                    <li>Through our internal review of our Business Management System we aim to continually
                                        improve performance, services and processes to ensure optimal efficiency.</li>
                                    <li>Through active mentoring we create a culture where staff have the skills and are
                                        empowered to take responsibility for the results of their actions which contributes
                                        to the success of the organization.</li>
                                </ul>
                            </div>
                            <div class="col-lg-4 col-md-7"
                                style="
    text-align: center;
    color: #066d77;
    font-style: italic;
">
                                <h5 style="margin-top:7%;line-height: 1.5;color: #066d77;">We are a Department of Health
                                    (DOH) Abu Dhabi approved healthcare management & medical professional training
                                    organization where we full fill all the required government regulations for healthcare
                                    facilities and healthcare professionals </h5><br>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Area -->


    <!-- End Features Area -->
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
