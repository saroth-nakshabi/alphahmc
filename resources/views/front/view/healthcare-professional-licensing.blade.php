@extends('front/layout')

@section('meta_tags')
    <title>Healthcare Professional License Services for DHA, DOH & MOH</title>
    <!--Floating WhatsApp css-->
    <meta name="description"
        content="Apply DOH/DHA/MOH license for Doctors, Nurses by personally evaluate all required documents with approved healthcare licencing consultancy in Dubai." />
    <meta name="keywords"
        content="Health Professionals Licensing, DOH Professionals Licensing, HAAD Healthcare License, DHA Professional Licensce, Get UAE Healthcare License" />
    <meta property="og:title" content="Healthcare Professional License Services for DHA, DOH & MOH" />

    <meta property="og:type" content="product" />
    <meta property="og:url" content="#" />
    <meta property="og:image" content="#" />
    <meta property="og:image:width" content="800" />
    <meta property="og:image:height" content="530" />
    <meta property="og:site_name" content="Alpha TSM" />
    <meta property="og:description"
        content="Apply a hustle free healthcare professional license from DOH, DHA & MOH. fully assisted or semi assisted licensing consultants with alpha healthcare licensing experts." />
@endsection

@section('custom_css')
@endSection

@section('content')
    <!-- Page Title -->
    <div class="page-title" data-bg-img="{{ asset('public/front/assets/img/bg6.jpg') }}"
        style="background-image: url({{ asset('public/front/assets/img/bg6.jpg') }}); background-size: cover;">
        <div class="container ">
            <div class="row">
                <div class="col-lg-6 col-md-12">

                    <!-- <h3>Healthcare Professional<br>Licensing</h3> -->
                    <h3 class="text-black font-weight-bold text-center p-5 rounded shadow">
                        Healthcare Professional<br>Licensing
                    </h3>
                </div>
            </div>
        </div>

        <div class="bg-pattern"><img src="{{ asset('public/front/assets/img/bg-pattern-2.png') }}" alt="pattern"></div>
    </div>
    <!-- End Page Title -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a class="text-dark font-weight-bold" href="#">Home</a></li>
            <li class="breadcrumb-item"><a class="text-dark font-weight-bold" href="alpha-services">Services</a></li>
            <li class="breadcrumb-item active text-muted font-weight-bold">Healthcare Professional Licensing</li>
        </ol>
    </nav>

    <!-- End Right Side Modal -->
    <section class="service-area ptb-100 bg-fbf9f8">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="col-md-8 offset-md-2 page-b-text text-center">
                        <h2 class="mb-30">What We Do</h2>
                        <p class="lead mb-5 text-gs">We are a Government approved healthcare licensing consultant who
                            facilitate DOH/DHA/MOH professional license processing services with experts to obtain your
                            license with quick process. We ensure that the entire process is taken care of, end to end,
                            from document evaluation, to coordinating with the Regulatory bodies, license application
                            processing, dataflow process & book your exam wherever you are and give you an opportunity
                            to hold eligibility to practice even before you arrive to UAE. Our team of experts are
                            mindful of the process and work dynamics to work along with the authorities for a better
                            outcome.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="single-blog-post shadow-sm rounded bg-white">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="blog-post-content p-4">
                                    <h4 class="text-gs">New License</h4>
                                    <p>Are you new to UAE or planning to relocate for better opportunities and wish to
                                        obtain your healthcare professional license to practice?</p>
                                    <a href="new-professional-license" class="btn btn-primary mt-20">Get Started</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <a href="#" class="blog-image">
                                    <img src="{{ asset('public/front/assets/img/new-license.jpg') }}" alt="New License"
                                        class="img-fluid rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-blog-post shadow-sm rounded bg-white">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="blog-post-content p-4">
                                    <h4 class="text-gs">Transfer License</h4>
                                    <p>You already have a healthcare professional license or eligibility letter and now
                                        plan to work in a different city/authority.</p>
                                    <a href="transfer-professional-license" class="btn btn-primary mt-20">Get
                                        Started</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <a href="#" class="blog-image">
                                    <img src="{{ asset('public/front/assets/img/transfer-license.jpg') }}"
                                        alt="Transfer License" class="img-fluid rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-blog-post shadow-sm rounded bg-white">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="blog-post-content p-4">
                                    <h4 class="text-gs">Register License</h4>
                                    <p>Are you already holding eligibility from one of the local authorities but haven’t
                                        obtained your final license yet and/or looking for a job opportunity?</p>
                                    <a href="activate-professional-license" class="btn btn-primary mt-20">Get
                                        Started</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <a href="#" class="blog-image">
                                    <img src="{{ asset('public/front/assets/img/register-license.jpg') }}"
                                        alt="Register License" class="img-fluid rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-blog-post shadow-sm rounded bg-white">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="blog-post-content p-4">
                                    <h4 class="text-gs">Upgrade/Change of Title</h4>
                                    <p>If you are holding a professional license and now need to change your
                                        professional title or upgrade your license for better career opportunities.</p>
                                    <a href="#" class="btn btn-primary mt-20">Get Started</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <a href="#" class="blog-image">
                                    <img src="{{ asset('public/front/assets/img/upgrade-title.png') }}"
                                        alt="Upgrade or Change of Title" class="img-fluid rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-blog-post shadow-sm rounded bg-white">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="blog-post-content p-4">
                                    <h4 class="text-gs">Dataflow Verification</h4>
                                    <p>It is the initial stage of license processing and it is critical, many of us
                                        don’t understand the right process, but our experts are best to understand.</p>
                                    <a href="#" class="btn btn-primary mt-20">Get Started</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <a href="#" class="blog-image">
                                    <img src="{{ asset('public/front/assets/img/dataflow-verification.png') }}"
                                        alt="Dataflow Verification" class="img-fluid rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-blog-post shadow-sm rounded bg-white">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="blog-post-content p-4">
                                    <h4 class="text-gs">Exam Registration & Preparation</h4>
                                    <p>All set but wondering what to do for the exam and how it is done? Prepare for
                                        your exam with experts, rationale, and past questions are key to..</p>
                                    <a href="#" class="btn btn-primary mt-20">Get Started</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <a href="#" class="blog-image">
                                    <img src="{{ asset('public/front/assets/img/exam-registration.jpg') }}"
                                        alt="Exam Registration & Preparation" class="img-fluid rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Area -->
    <section id="tabs">
        <div class="container ptb-100">
            <h2 class="section-title" style="text-align: center; margin-bottom: 20px;">Healthcare licensing
                authorities in UAE </h2>
            <h6 class="section-title" style="text-align: center;">Based on your choice of region you are planing to
                relocate</h6>
            <div class="row">
                <div class="col-xs-12 ">
                    <nav>
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active show" id="nav-home-tab" data-toggle="tab"
                                href="#nav-home" role="tab" aria-controls="nav-home"
                                aria-selected="false">Department of Health (DOH)</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile"
                                role="tab" aria-controls="nav-profile" aria-selected="false">Dubai Health
                                Authority (DHA)</a>
                            <a class="nav-item nav-link " id="nav-contact-tab" data-toggle="tab" href="#nav-contact"
                                role="tab" aria-controls="nav-contact" aria-selected="true">Ministry of Health
                                (MOH)</a>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            Department of Health (DOH) earlier known as Health Authority of Abu Dhabi (HAAD) is the
                            government regulatory authority in the Emirate of Abu Dhabi for healthcare also the
                            goverment healthcare profosional licencing authority for all healthcare professionals witin
                            the region, for those who are looking to work in hospitals and medical centers in capital
                            city of Abu Dhabi and Al Ain.<br><br>Alpha for Training &amp; Strategic Management is a DOH
                            approved healthcare management consultancy providing DOH healthcare licensing assistance
                            services with expert licensing consultants, we undertake the whole process of license
                            processing starting from document preparation, dataflow verification, DOH approval and exam
                            booking.
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            Dubai Healthcare Authority (DHA) is one of the largest government regulatory authority in
                            UAE which is the governing body of the Emirate of Dubai for healthcare hence hold the entire
                            control of license for all healthcare professionals for those who are looking to obtain the
                            DHA licenses, each government regulatory authority has it’s own regulations and exams, DHA
                            exam is connected with Prometric and it has exam centers in 135 countries.<br><br>Alpha for
                            Training & Strategic Management is a healthcare management consultancy providing healthcare
                            professional licensing assistance services with excellent expert licensing consultants, we
                            undertake the whole process of licensing processing starting from document preparation,
                            dataflow verification, DHA approval and exam booking.
                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            Ministry of Health (MOH) is one of the government regulatory authority in UAE which is the
                            governing body of the Emirates of Sharjah, Ras Al Khaimah, Ajman & Fujairah Those who are
                            looking to work in the above area need to obtain the license from Ministry of Health
                            (MOH)<br>Alpha for Training & Strategic Management is a healthcare management consultancy
                            providing healthcare professional licensing assistance services with excellent expert
                            licensing consultants, we undertake the whole process of licensing processing starting from
                            document preparation, dataflow verification, MOH approval and exam booking.
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Features Area -->

    <section class="analysis-area ptb-100 bg-fbf9f8">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="features-text xservice-content mb-0">
                        <h3>How we process your license</h3>
                        <h5 class="textg">We facilitate a comprehensive licensing service for both overseas and
                            locally based health care professionals wanting to pursue a career in the UAE.</h5>
                        <p>By reduceing the turnaround time and ensure that you are not entangled in the administrative
                            and logistic hassles with the authorities, resulting in unnecessary delay in obtaining your
                            healthcare professional license</p>
                        <h4 class="textg mt-30">Why Alpha</h4>
                        <ul>
                            <li>Experience expert in licensing processing over 15 years.</li>
                            <li> Fast & reliable service with best cost </li>
                            <li>In hand facility to attach, active license & can be transferred to other emirates.</li>
                            <li>Transparent communication policy for better understanding</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="analysis-form">
                        <h4>Start your eligibility evaluation now! </h4>
                        <br>
                        <p style="font-weight: 400; font-size: 1.2rem;
    font-style: italic; color: #0c6e78;"> We
                            have shifted our eligibility evaluation for fast and easy WhatsApp based document submission
                            to reduce the reply timing and make the communication seamless</p><br>
                        <p>Send all your documents with just a button click below and get your documents evaluated by
                            DOH/DHA/MOH healthcare licensing experts to start your process now.</p><br>
                        <p>If you are visiting from a computer send your documents to <a href="tel:971564200934">+971
                                56 420 0934</a> to get a instant results. </p>

                        <div class="chatWa">
                            <a class="btn btn-primary btnx"
                                href="https://wa.me/971564200934?&text=Hello, I am looking for healthcare professional licensing services">EVALUATE
                                NOW</a> <a
                                href="https://wa.me/971564200934?&text=Hello, I am looking for healthcare professional licensing services"><img
                                    src="assets/img/whatsapp.png" alt="+971 56 420 0934"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Features Area -->
    <!-- FAQ Area -->
    <section class="features-area" id="faq">
        <div class="faq-area ptb-100">
            <div class="container">

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="section-title" style="text-align: center;">
                            <h3>Frequently Asked Questions</h3>
                            <p>We are trying to answer many questions as possible related to healthcare professional
                                licensing here, if you have any further question, please drop us a WhatsApp, we will
                                give you solution as soon as possible</p>
                        </div>
                        <div class="faq">
                            <ul class="accordion">
                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">What is healthcare license
                                        exam exemption criteria? <span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">Exemption is dependent on the healthcare Professional
                                        Qualification Requirement (PQR) laid down. Each case is assessed on a case by
                                        case scenario and the final decision for any exemption is decided by the
                                        examination committee. Documents would need to be submitted at the time of
                                        application.</p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Can i apply for professional
                                        license in UAE if I don’t have a home country license?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">NO. You cant apply UAE healthcare professional
                                        license, Experience is only counted if it is LICENSED experience in your home
                                        country.</p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">What is PSV in UAE healthcare
                                        license process?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">PSV (Primary Source Verification) is the process of
                                        checking the educational background, training, experience as well as other
                                        credentials of all healthcare practitioners applying for registration and
                                        licensing in both the private and public healthcare sectors in the UAE. The
                                        company used to perform PSV is Data Flow, a third Party organisation.
                                    </p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">What does it mean by Good
                                        Standing Certificate (GSC)?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">Good Standing Certificate is a certificate issued by
                                        the Medical Council / Regulatory body from where your previous healthcare
                                        license has been issued. </p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">What is the validity for Good
                                        Standing Certificate (GSC)? <span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">Good Standing Certificate is valid for 6 months only.
                                        For some countries like the UK, Good Standing is only valid for 3 months.</p>
                                </li>



                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">How long will this whole
                                        healthcare professional licensing process take?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">This whole process once we have been given all the
                                        documentation, can take 8-10 weeks. It can take longer if more information is
                                        required. Please note that when documents are verified by Data Flow, this is an
                                        independent company and verification can take up to 3 months. This is done
                                        independently from the regulatory body and we have no control over this.</p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Once Data Flow verification
                                        has been done, what happens next?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">You will be advised to book your healthcare license
                                        exam if required. You can still sit your exam while Data Flow is being
                                        conducted. Only once Data Flow has been completed and you have passed the exam,
                                        the healthcare professional license will be issued. </p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Do I need to sit for the
                                        Prometric exam in Dubai/ Abu Dhabi only?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content"><b>DHA Licensing uses Prometric.</b><br>
                                        They have a robust test center network in 135 countries. So you can choose the
                                        country you wish to sit the exam for the professions where Prometric is
                                        required. Check our examination section for details on this.<br><br>
                                        <b>DOH has international test centres with Pearson:</b><br>
                                        Some specialists and consultants need to sit the exam locally in Abu Dhabi or
                                        Dubai.
                                    </p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">What happens if I fail the
                                        exam?<span class="xicoopen"><i class="fa fa-chevron-circle-down"></i></span><span
                                            class="xicoclose"><i class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">If you fail the exam, you have 3 attempts in total
                                        (across DHA, DOH and MOH) to appear again for the exam.<br>
                                        We would need to re- apply for your license and go through the process all over
                                        again. You would need to wait around 6 weeks before you can re-appear for the
                                        exam. You would need to pay the re-registration fee again for DHA, other
                                        Re-Application for DOH. The exam fee will also need to be paid again to the
                                        relevant test center.
                                    </p>
                                </li>
                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">What is healthcare
                                        malpractice insurance?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">Evidence of malpractice insurance is required for all
                                        approved applications before issuing the license. This is usually taken out by
                                        the employer on your behalf. Only after you have malpractice insurance the
                                        license can be issued.</p>
                                </li>


                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Once I get the healthcare
                                        license, how long is it valid for?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">All healthcare professional are required to renew
                                        their professional license annually.</p>
                                </li>

                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Is all UAE healthcare
                                        licenses have been unified. What does this mean?<span class="xicoopen"><i
                                                class="fa fa-chevron-circle-down"></i></span><span class="xicoclose"><i
                                                class="fa fa-chevron-circle-up"></i> </span></a>
                                    <p class="accordion-content">Unification simply means the PQR is now the same. Each
                                        authority is still it’s own, and you still need to apply to each one if you want
                                        to work in that Emirate. The requirements however, to qualify for a license is
                                        now the same across the board. <br>
                                        This DOES NOT mean if you have the DHA eligibility letter, that you can freely
                                        work in Sharjah or Abu Dhabi, and vice versa. To be able to obtain a license
                                        transfer in any of the authorities, you have to first obtain the license.
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- End FAQ Area -->
    @include('front.view.amc-link')
    @include('front.view.chat-hpl')

    <div class="go-top"><i class="fa fa-hand-point-up"></i></div>
    <!-- Chat -->
@endsection

@section('custom_js')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [{
                "@type": "Question",
                "name": "What is healthcare license exam exemption criteria?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Exemption is dependent on the healthcare Professional Qualification Requirement (PQR) laid down. Each case is assessed on a case by case scenario and the final decision for any exemption is decided by the examination committee. Documents would need to be submitted at the time of application."
                }
            },
            {
                "@type": "Question",
                "name": "Can i apply for healthcare professional license in UAE if I don’t have a home country license?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "NO. You cant apply UAE healthcare professional license, Experience is only counted if it is LICENSED experience in your home country."
                }
            },
            {
                "@type": "Question",
                "name": "What is PSV in UAE healthcare license process?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "PSV (Primary Source Verification) is the process of checking the educational background, training, experience as well as other credentials of all healthcare practitioners applying for registration and licensing in both the private and public healthcare sectors in the UAE. The company used to perform PSV is Data Flow, a third Party organisation."
                }
            },
            {
                "@type": "Question",
                "name": "What does it mean by Good Standing Certificate (GSC)?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Good Standing Certificate is a certificate issued by the Medical Council / Regulatory body from where your previous healthcare license has been issued."
                }
            },
            {
                "@type": "Question",
                "name": "How long will this whole  healthcare professional licensing process take?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "This whole process once we have been given all the documentation, can take 8-10 weeks. It can take longer if more information is required. Please note that when documents are verified by Data Flow, this is an independent company and verification can take up to 3 months. This is done independently from the regulatory body and we have no control over this."
                }
            },
            {
                "@type": "Question",
                "name": "Is all UAE healthcare licenses have been unified. What does this mean?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Unification simply means the PQR is now the same. Each authority is still it’s own, and you still need to apply to each one if you want to work in that Emirate. The requirements however, to qualify for a license is now the same across the board. This DOES NOT mean if you have the DHA eligibility letter, that you can freely work in Sharjah or Abu Dhabi, and vice versa. To be able to obtain a license transfer in any of the authorities, you have to first obtain the license."
                }
            },
            {
                "@type": "Question",
                "name": "Once I get the healthcare license, how long is it valid for?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "All healthcare professional are required to renew their professional license annually."
                }
            }
        ]
    }
</script>

    <script src="https://kit.fontawesome.com/6326b0ec79.js" crossorigin="anonymous"></script>
    <script>
        $(window).load(function() {
            setTimeout(function() {
                $('#myModal').modal('show');
            }, 5000);
        });
    </script>
    <script>
        $("#chatBtn").click(function() {
            $('#myModal').modal('show');
        });

        $(function() {
            $('.fa-angle-down').click(function() {
                $(this).closest('.chatbox').toggleClass('chatbox-min');
            });
        });
    </script>
@endSection
