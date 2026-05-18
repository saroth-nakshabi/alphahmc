@extends('dashboard/layout')

@section('content')
    <!--  Owl carousel -->
    <div class="owl-carousel counter-carousel owl-theme">
        <div class="item">
            <div class="card border-0 zoom-in bg-light-primary shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-user-male.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-primary mb-1"> Students </p>
                        <h5 class="fw-semibold text-primary mb-0">96</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-warning shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-briefcase.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-warning mb-1">Courses</p>
                        <h5 class="fw-semibold text-warning mb-0">3,650</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-info shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-mailbox.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-info mb-1">Projects</p>
                        <h5 class="fw-semibold text-info mb-0">356</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-danger shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-favorites.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-danger mb-1">Events</p>
                        <h5 class="fw-semibold text-danger mb-0">696</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-success shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-speech-bubble.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-success mb-1">Payroll</p>
                        <h5 class="fw-semibold text-success mb-0">$96k</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-info shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-connect.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-info mb-1">Reports</p>
                        <h5 class="fw-semibold text-info mb-0">59</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <!--  current page js files -->
    <script src="{{ asset('public/dashboard/dist/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    {{-- <script src="{{ asset('public/dashboard/dist/js/dashboard.js') }}"></script> --}}
    <script>
        //
        // Carousel
        //
        $(".counter-carousel").owlCarousel({
            loop: true,
            margin: 30,
            mouseDrag: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplaySpeed: 2000,
            nav: false,
            rtl: true,
            responsive: {
                0: {
                    items: 2,
                },
                576: {
                    items: 2,
                },
                768: {
                    items: 3,
                },
                1200: {
                    items: 5,
                },
                1400: {
                    items: 6,
                },
            },
        });
    </script>
@endsection
