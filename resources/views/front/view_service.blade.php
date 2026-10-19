@extends('front/layout')

@section('meta_title', empty($service->meta_title) ? $service->name : $service->meta_title)
@section('meta_description', empty($service->meta_description) ? null : $service->meta_description)
@section('meta_keywords', empty($service->meta_keywords) ? null : $service->meta_keywords)

@section('meta_tags')
    <!-- Additional meta tags (if necessary) -->
    <meta property="og:title" content="{{ empty($service->meta_title) ? $service->name : $service->meta_title }}">
    <meta property="og:description" content="{{ empty($service->meta_description) ? null : $service->meta_description }}">
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

        .active-schedule {
            background: linear-gradient(135deg, #e0f7f7, #ccffcc);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endSection

@section('content')

    <div class="static-section">
        <div class="container py-5"
            style="background-image: url('{{ asset('public/front/assets/img/bg-pattern-2.png') }}'); background-size: cover; background-position: center;">
            <div class="row align-items-center">

                <div class="col-lg-6 col-md-12 ml-auto">
                    <div class="content ml-4">
                        <h1 class="mb-4 fs-1" style="font-size: 3rem;">{{ $service->name }}</h1>
                        <p class="mb-3">
                            {!! $service->overview !!}
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 text-center">
                    <div class="image">
                        <img src="{{ asset('public/uploads/service_images/' . $service->images->first()->image) }}"
                            alt="services-img" class="img-fluid" style="max-width: 100%; width: 80%;" />
                    </div>
                </div>
            </div>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
                <li class="breadcrumb-item"><a class="text-dark font-weight-bold" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="text-dark font-weight-bold"
                        href="{{ route('front.services') }}">Services</a>
                </li>
                <li class="breadcrumb-item active text-dark font-weight-bold">{{ $service->name }}</li>
            </ol>
        </nav>
    </div>

    <div id="myButton"></div>
    <!-- Right Side Modal -->
    @include('front.view.side-bar')
    <!-- End Right Side Modal -->

    <section>
        <div class="container py-5">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    {!! $service->info_one !!}
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    {!! $service->info_two !!}
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="section-content">
                        {!! $service->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--
    <section class="features-area">
        <div class="faq-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-7">
                        <div class="section-title">
                            <h3>Frequently Asked Questions</h3>
                            <h4 class="textg" style="color:black;  margin-bottom: 2%;";>We have listed the very frequent
                                questions here, you may
                                have some
                                more,
                                please don’t hesitate to leave us a message with your own question, our team is ready to
                                answer your query </h4>
                        </div>
                        <div class="faq">
                            <ul class="accordion">
                                @if (isset($test_questions) && count($test_questions) > 0)
                                    @foreach ($test_questions as $question)
                                        <li class="accordion-item">
                                            <a class="accordion-title" href="javascript:void(0)">
                                                {{ $question->question }}
                                                <span class="xicoopen"><i class="icofont-circled-down"></i></span>
                                                <span class="xicoclose hidden"><i class="icofont-circled-up"></i></span>
                                            </a>
                                            <div class="accordion-content hidden">
                                                @foreach ($test_answers as $answer)
                                                    @if ($answer->test_question_id == $question->id)
                                                        <div>{{ $answer->answer }}</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="accordion-item">
                                        <a class="accordion-title" href="javascript:void(0)">
                                            No questions found
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-5 text-center section-title"
                        style="background-color: #4e4e4e; height: 550px;">
                        <h2>Why to Join Annual Contract</h2>
                        <div class="pf-5">
                            <div class="col-lg-12">
                                <div class="single-inner-services">
                                    <h3 class="text-white">Reliable Partnership</h3>
                                    <p class="text-white">Exclusive & highly trusted annual healthcare management services
                                        for healthcare
                                        establishments.</p>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="single-inner-services">
                                    <h3 class="text-white">Cost Effective</h3>
                                    <p class="text-white">Save money with long term contract with Alpha with customized
                                        service bundle from
                                        ALPHA.</p>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="single-inner-services">
                                    <h3 class="text-white">Hassle Free</h3>
                                    <p class="text-white">Whenever an inquiry arises or change is needed, Alpha team takes
                                        it and it is sorted.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section> --}}

    <section class="features-area">
        <div class="faq-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-7">
                        <div class="section-title">
                            <h3>Frequently Asked Questions</h3>
                            <h4 class="textg" style="color:black;  margin-bottom: 2%;";>We have listed the very frequent
                                questions here, you may
                                have some
                                more,
                                please don’t hesitate to leave us a message with your own question, our team is ready to
                                answer your query </h4>
                        </div>
                        <div class="faq">
                            <ul class="accordion">
                                @foreach ($test_questions as $question)
                                    <li class="accordion-item">
                                        <a class="accordion-title" href="javascript:void(0)">
                                            {{ $question->question }}
                                            <span class="xicoopen"><i class="icofont-circled-down"></i></span>
                                            <span class="xicoclose hidden"><i class="icofont-circled-up"></i></span>
                                        </a>
                                        <div class="accordion-content hidden">
                                            @foreach ($test_answers as $answer)
                                                @if ($answer->test_question_id == $question->id)
                                                    <div>{{ $answer->answer }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-5 text-center section-title"
                        style="background-color: #4e4e4e; height: 550px;">
                        <h2>Why to Join Annual Contract</h2>
                        <div class="pf-5">
                            <div class="col-lg-12">
                                <div class="single-inner-services">
                                    <h3 class="text-white">Reliable Partnership</h3>
                                    <p class="text-white">Exclusive & highly trusted annual healthcare management services
                                        for healthcare
                                        establishments.</p>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="single-inner-services">
                                    <h3 class="text-white">Cost Effective</h3>
                                    <p class="text-white">Save money with long term contract with Alpha with customized
                                        service bundle from
                                        ALPHA.</p>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="single-inner-services">
                                    <h3 class="text-white">Hassle Free</h3>
                                    <p class="text-white">Whenever an inquiry arises or change is needed, Alpha team takes
                                        it and it is sorted.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="analysis-area ptb-100 bg-fbf9f8">
        <div class="container">
            <div class="col-md-8 offset-md-2">
                <div class="section-title" style="text-align: center;">
                    <h3 class="text-nowrap">Other Licensing Services You May Be Looking For</h3>
                    <p>We are trying to answer many questions as possible related to healthcare facility licensing here.
                        If you have any further questions, please drop us a message, and we will provide a solution as soon
                        as possible.</p>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    @foreach ($featuredServices as $service)
                        <div class="col-lg-4 col-md-4">
                            <div class="single-blog-item bg-1">
                                <h4><a href="#">{{ $service->name }}</a></h4>
                                <p>{!! $service->overview !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container my-5 ">
            <div class="row align-items-center bg-white shadow-sm">
                <div class="col-md-4 col-lg-8">
                    <h1 class="fw-bold fs-1 mb-4">Stay updated</h1>
                    <p class="mb-3winky-sans ">
                        To learn more about our services and get the latest healthcare regulations & <br />investment
                        relations from UAE & across the region, click on the link below.
                    </p>
                    {{-- <button type="button" class="btn btn-primary btnx">Send a query</button> --}}
                    <a href="https://wa.me/971564200934?&text=Hello, I wanted to register for new professional licence as Physicians"
                        class="btn btn-primary btnx">Send a query</a>
                </div>

                <div class="col-md-4">
                    <div class="profile-card p-3 border rounded">
                        <h2 class="fw-bold">Talk to the expert</h2>
                        {{-- <div class="d-flex justify-content-start align-items-center my-3">
                            <img src="{{ asset('public/front/assets/img/expert.png') }}" alt="Expert Image"
                                class="mr-3 rounded" style="width: 70px; height: 70px; object-fit: cover;">
                            <div>
                                <p class="fw-semibold mb-0">Willem Steenkamp</p>
                                <p class="text-muted mb-0">Partner</p>
                                <p class="mb-0">
                                    <a href="mailto:w.steenkamp@tamimi.com" class="text-decoration-none">
                                        <i class="bi bi-envelope-fill"></i> w.steenkamp@tamimi.com
                                    </a>
                                </p>
                            </div>
                        </div> --}}

                        @foreach ($agents as $agent)
                            <div class="d-flex justify-content-start align-items-center my-3">
                                {{-- <img src="{{ asset('public/uploads/agent_images/' . $agent->images->first()->image) }}"
                                    alt="Expert Image" class="mr-3 rounded"
                                    style="width: 70px; height: 70px; object-fit: cover;"> --}}
                                <div>
                                    <p class="fw-semibold mb-0">
                                        {{ $agent->user->first_name . ' ' . $agent->user->last_name }}</p>
                                    <p class="text-muted mb-0">{{ $agent->title }}</p>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $agent->user->email }}" class="text-decoration-none">
                                            <i class="bi bi-envelope-fill"></i> {{ $agent->user->email }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        <!-- Button without rounded corners -->
                        <button type="button" class="btn btn-primary btnx">Meet your local team</button>
                    </div>
                </div>


                {{-- <div class="container">
                    <div class="row">
                        @foreach ($staffs as $staff)
                            <div class="col-md-4">
                                <div class="profile-card p-3 border rounded">
                                    <h2 class="fw-bold">Talk to the expert</h2>
                                    <div class="d-flex justify-content-start align-items-center my-3">
                                        <!-- Ensure you check if the image exists and handle the case where the image might be missing -->
                                        <img src="{{ asset('storage/' . $staff->image) }}" alt="Expert Image"
                                            class="mr-3 rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $staff->name }}</p>
                                            <p class="text-muted mb-0">{{ $staff->title }}</p>
                                            <p class="mb-0">
                                                <a href="mailto:{{ $staff->email }}" class="text-decoration-none">
                                                    <i class="bi bi-envelope-fill"></i> {{ $staff->email }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary btnx">Meet your local team</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> --}}

            </div>
        </div>
    </section>

    @include('front.view.chat-hpl')


@endsection

@section('custom_js')

    <!-- validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <!-- int tele input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"
        integrity="sha512-+gShyB8GWoOiXNwOlBaYXdLTiZt10Iy6xjACGadpqMs20aJOoh+PJt3bwUVA6Cefe7yF7vblX6QwyXZiVwTWGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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


    <script type="text/javascript" src="{{ asset('public/front/assets/js/chat-hpl.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/front/assets/js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/front/assets/js/floating-wpp.js') }}"></script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [{
                "@type": "What is MOH product
                registration services?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Product registration is required to import any medical
                    or pharmaceutical-related products and devices in the UAE, the product
                    registration needs to be done at MOHAP regardless of the emirates the drug store
                    is located. "
                }
            },
            {
                "@type": "Question",
                "name": "What is the Process of the
                registation?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Depends on the product type the registration process
                    and required supporting documents may vary, talk to our consultant to get more
                    information for the product type you are planning to register."
                }
            },
            {
                "@type": "Question",
                "name": "Can I do a product
                registration without a pharmaceutical warehouse?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No, it is mandatory only MOHAP licensed pharmaceutical
                    warehouses can only register the product under their license and handle the all
                    post-market obligations."
                }
            },
            {
                "@type": "Question",
                "name": "What are the MOH licensing
                requirements for healthcare facilities?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "MOH licensing requirements vary depending on the type of
                    healthcare facility. Key
                    documents include a valid trade license, a tenancy contract for the facility,
                    detailed floor plans approved by local authorities, and medical staff licenses.
                    Additional requirements may apply for specialized facilities. Contact our team for
                    guidance tailored to your facility type."
                }
            },

        ]
    }
</script>


    <script>
        document.querySelectorAll('.accordion-title').forEach(function(accordionTitle) {
            accordionTitle.addEventListener('click', function() {
                var accordionItem = this.closest('.accordion-item');
                var content = accordionItem.querySelector('.accordion-content');
                var openIcon = accordionItem.querySelector('.xicoopen');
                var closeIcon = accordionItem.querySelector('.xicoclose');

                // Toggle visibility of the content
                content.classList.toggle('hidden');
                openIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        });
    </script>

    {{-- <script>
        document.querySelectorAll('.accordion-title').forEach(function(accordionTitle) {
            accordionTitle.addEventListener('click', function() {
                var accordionItem = this.parentElement;
                var content = accordionItem.querySelector('.accordion-content');
                var openIcon = accordionItem.querySelector('.xicoopen');
                var closeIcon = accordionItem.querySelector('.xicoclose');

                // Toggle the visibility of the content
                content.classList.toggle('hidden');

                // Toggle the icons
                openIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        });
    </script> --}}


    {{-- <script>
        document.querySelectorAll('.accordion-title').forEach(function(accordionTitle) {
            accordionTitle.addEventListener('click', function() {
                var accordionItem = this.parentElement;
                var content = accordionItem.querySelector('.accordion-content');
                var openIcon = accordionItem.querySelector('.xicoopen');
                var closeIcon = accordionItem.querySelector('.xicoclose');

                // Toggle the visibility of the content
                content.classList.toggle('hidden');

                // Toggle the icons
                openIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        });
    </script> --}}

    {{-- <script>
        $(document).ready(function() {
            // Delete Question
            $('.delete-question').click(function() {
                const questionId = $(this).data('id');
                if (confirm('Are you sure you want to delete this question?')) {
                    $.ajax({
                        url: `/test-questions/${questionId}`,
                        type: 'DELETE',
                        success: function(response) {
                            $(`#question${questionId}`).closest('.accordion-item').remove();
                        }
                    });
                }
            });

            // Edit Question (you'll need to implement the modal)
            $('.edit-question').click(function() {
                const questionId = $(this).data('id');
                $.ajax({
                    url: `/test-questions/get`,
                    type: 'POST',
                    data: {
                        id: questionId
                    },
                    success: function(response) {
                        // Populate your edit modal with response.data
                        $('#editQuestionModal').modal('show');
                    }
                });
            });
        });
    </script> --}}
@endSection
