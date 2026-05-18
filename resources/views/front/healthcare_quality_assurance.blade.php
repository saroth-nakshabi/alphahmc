@extends('front/layout')

@section('meta_tags')
    <title>Medical facility quality assurance services| Alpha TSN</title>
@endsection

@section('custom_css')
@endSection

@section('content')
    <div class="page-title " data-bg-img="{{ asset('public/front/assets/img/bg-audit.jpg') }}"
        style="background-image: url({{ asset('public/front/assets/img/bg-tawkeet.jpg') }}); padding-bottom: 50px; padding-top: 50px; background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <img class="department-logo-width" src="{{ asset('public/front/assets/img/tawqueet-logo.svg') }}"
                        alt="Facility Quality Assurance">
                </div>
                <div class="col-lg-6 col-md-12 about-head" style="margin: auto; ">
                </div>
            </div>
        </div>

        <div class="bg-pattern"><img src="{{ asset('public/front/assets/img/bg-pattern-2.png') }}" alt="pattern"></div>
    </div>
    <!-- Page Title -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a class="text-dark font-weight-bold" href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active"><a class="text-dark font-weight-bold"
                    href="{{ route('front.services') }}">Services</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Facility Quality Assurance</li>
        </ol>
    </nav>
    <!-- End Page Title -->

    <!-- Right Side Modal -->
    @include('front.view.side-bar')
    <!-- End Right Side Modal -->

    <!-- Features Area -->

    <section class="features-area  ptb-100">
        <div class="container">
            <div class="row ">
                <div class="col-lg-2 col-md-6"></div>
                <div class="col-lg-8 col-md-10 text-center mx-auto">
                    <div class="xservice-content p-5 border rounded-lg shadow-lg bg-white">
                        <h4 class="mb-4" style="color: #076371;">
                            <i>
                                We analyze your existing healthcare business system to understand areas for improvement and
                                enhance the working processes to achieve an optimal outcome of patient satisfaction and
                                financial circumstances for the organisation. We help you define combined Key Performance
                                Indicators (KPIs) in all processes of the facility, which aids in the business improvement
                                plan developed by our healthcare expert team. Our extensive and varied expertise in the
                                domains of healthcare makes it easier for us to design tailor-made performance improvement
                                plans, which align with international best practices and also ensure regulatory compliance.
                            </i>
                        </h4>
                    </div>

                    <div class="p-5 mt-4 border-top rounded-lg shadow-sm bg-light">
                        <p class="lead mb-3">
                            We assign a dedicated project manager to handle every client, so you have access to a single
                            point of contact at all times during the implementation. This manager will continuously monitor
                            and make necessary changes to bridge any shortcomings.
                        </p>
                        <p class="mb-4">
                            We conduct adequate training exercises for your in-house staff to implement industry best
                            practices and design periodic monitoring systems to ensure continuity of compliance after the
                            processes have been set, moving towards the assigned goal. <strong>Improvise, Accomplish, and
                                Sustain</strong> is what Tawqeet stands for.
                        </p>

                        <div class="d-flex justify-content-center align-items-center">
                            <div class="d-flex flex-column align-items-center mx-4">
                                <div class="p-4 rounded-circle shadow-sm d-flex justify-content-center align-items-center"
                                    style="width: 60px; height: 60px; background-color:#066d77;">
                                    <i class="bi bi-person-check fs-1 text-white" style="font-size: 1.75rem;"></i>
                                </div>
                                <p class="mt-2 text-center">Dedicated Project Manager</p>
                            </div>
                            <div class="d-flex flex-column align-items-center mx-4">
                                <div class="p-4 rounded-circle shadow-sm d-flex justify-content-center align-items-center"
                                    style="width: 60px; height: 60px; background-color:#066d77;">
                                    <i class="bi bi-gear-fill fs-1 text-white" style="font-size: 1.75rem;"></i>
                                </div>
                                <p class="mt-2 text-center">Continuous Monitoring</p>
                            </div>
                            <div class="d-flex flex-column align-items-center mx-4">
                                <div class="p-4 rounded-circle shadow-sm d-flex justify-content-center align-items-center"
                                    style="width: 60px; height: 60px; background-color:#066d77;">
                                    <i class="bi bi-clipboard-data fs-1 text-white" style="font-size: 1.75rem;"></i>
                                </div>
                                <p class="mt-2 text-center">Industry Best Practices</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">

                </div>
            </div>
        </div>
    </section>
    <section class="bg-fbf9f8">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 ptb-100 bg-fbf9f8">
                    <div class="row">

                        <div class="col-lg-4 col-md-4">
                            <div class="xservice-content">
                                <h2>How Tawqeet Works </h2>
                                <h4 class="textg"><i>The objective of Tawqeet is to analyses and review the adaptability
                                        and compliance of an existing facility for a set of defined standard and guidelines,
                                        across Clinical, Operational, Infrastructural, Administrative domains. Tawqeet
                                        ensures the facility is fulfilling operational efficiency, patient satisfaction and
                                        quality of service, this helps in providing meaningful insight to the facility
                                        owners and investors to evaluate the outcomes and sustainability. Tawqeet not only
                                        enhances the entire work system of a facility, but ensures that the facility
                                        satisfies the regulatory compliances too. </i></h4>
                                <br>
                            </div>
                        </div>

                        <div class="col-lg-1 col-md-4"> </div>
                        <div class="col-lg-7 col-md-4">
                            <h3>The Tawqeet Key Point Indicators</h3> <br>
                            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 641.74 272.54">
                                <defs>
                                    <style>
                                        .cls-1 {
                                            fill: #409bb2;
                                            opacity: 0.8;
                                        }

                                        .cls-2 {
                                            font-size: 12.55px;
                                        }

                                        .cls-12,
                                        .cls-13,
                                        .cls-2,
                                        .cls-30,
                                        .cls-35 {
                                            fill: #fff;
                                        }

                                        .cls-13,
                                        .cls-2,
                                        .cls-26,
                                        .cls-30,
                                        .cls-35,
                                        .cls-38,
                                        .cls-41,
                                        .cls-46 {
                                            font-family: Roboto-Regular, Roboto;
                                        }

                                        .cls-3 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-4 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-5 {
                                            letter-spacing: -0.13em;
                                        }

                                        .cls-6 {
                                            letter-spacing: -0.08em;
                                        }

                                        .cls-7 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-8 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-9 {
                                            fill: #d81c54;
                                        }

                                        .cls-10,
                                        .cls-11,
                                        .cls-9 {
                                            opacity: 0.7;
                                        }

                                        .cls-10 {
                                            fill: #08609f;
                                        }

                                        .cls-11 {
                                            fill: #209b5f;
                                        }

                                        .cls-13 {
                                            font-size: 11.78px;
                                        }

                                        .cls-14 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-15 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-16 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-17 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-18 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-19 {
                                            letter-spacing: -0.07em;
                                        }

                                        .cls-20 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-21 {
                                            letter-spacing: -0.02em;
                                        }

                                        .cls-22 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-23 {
                                            letter-spacing: -0.08em;
                                        }

                                        .cls-24 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-25 {
                                            letter-spacing: -0.01em;
                                        }

                                        .cls-26 {
                                            font-size: 10.45px;
                                        }

                                        .cls-26,
                                        .cls-38,
                                        .cls-41,
                                        .cls-46 {
                                            fill: #414042;
                                        }

                                        .cls-27 {
                                            fill: #3471b8;
                                        }

                                        .cls-28 {
                                            fill: #ef3d6c;
                                        }

                                        .cls-29 {
                                            fill: #24b574;
                                        }

                                        .cls-30 {
                                            font-size: 9.57px;
                                        }

                                        .cls-31 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-32 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-33 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-34 {
                                            fill: #7f4a9d;
                                        }

                                        .cls-35 {
                                            font-size: 8.82px;
                                        }

                                        .cls-36 {
                                            letter-spacing: -0.06em;
                                        }

                                        .cls-37 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-38,
                                        .cls-41,
                                        .cls-46 {
                                            font-size: 15.67px;
                                        }

                                        .cls-38,
                                        .cls-45 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-39 {
                                            letter-spacing: 0.02em;
                                        }

                                        .cls-40,
                                        .cls-41 {
                                            letter-spacing: 0.01em;
                                        }

                                        .cls-42 {
                                            letter-spacing: -0.12em;
                                        }

                                        .cls-43 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-44 {
                                            letter-spacing: -0.05em;
                                        }

                                        .cls-46 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-47 {
                                            letter-spacing: 0em;
                                        }

                                        .cls-48 {
                                            letter-spacing: -0.01em;
                                        }
                                    </style>
                                </defs>
                                <title>towqeet-process</title>
                                <circle class="cls-1" cx="579.86" cy="118.51" r="54.84" /><text class="cls-2"
                                    transform="translate(557.35 106.11)">B<tspan class="cls-3" x="7.81" y="0">E</tspan>
                                    <tspan class="cls-4" x="15.07" y="0">T</tspan>
                                    <tspan x="22.66" y="0">TER</tspan>
                                    <tspan x="-15.99" y="15.06">HEA</tspan>
                                    <tspan class="cls-5" x="8.28" y="15.06">L</tspan>
                                    <tspan x="13.34" y="15.06">THCARE</tspan>
                                    <tspan class="cls-6" x="-2.85" y="30.12">F</tspan>
                                    <tspan class="cls-7" x="3.04" y="30.12">A</tspan>
                                    <tspan x="11.16" y="30.12">CIL</tspan>
                                    <tspan class="cls-8" x="29.5" y="30.12">I</tspan>
                                    <tspan class="cls-4" x="32.74" y="30.12">T</tspan>
                                    <tspan x="40.32" y="30.12">Y</tspan>
                                </text>
                                <circle class="cls-9" cx="322.39" cy="156.72" r="56.09" />
                                <circle class="cls-10" cx="320.55" cy="80.29" r="56.09" />
                                <circle class="cls-11" cx="386.4" cy="111.33" r="56.09" />
                                <path class="cls-12"
                                    d="M368.13,104.06a5.29,5.29,0,0,1-.41,2.15,3.15,3.15,0,0,1-1.18,1.4,3.47,3.47,0,0,1-3.52,0,3.25,3.25,0,0,1-1.19-1.39,5.06,5.06,0,0,1-.43-2.09v-.6a5.06,5.06,0,0,1,.42-2.13,3.1,3.1,0,0,1,2.94-1.91,3.32,3.32,0,0,1,1.78.48,3.23,3.23,0,0,1,1.18,1.41,5.32,5.32,0,0,1,.41,2.15Zm-1.1-.54a3.83,3.83,0,0,0-.59-2.29,2.15,2.15,0,0,0-3.33,0,3.67,3.67,0,0,0-.61,2.21v.62a3.8,3.8,0,0,0,.6,2.27,2.13,2.13,0,0,0,3.33,0,3.7,3.7,0,0,0,.6-2.23Z" />
                                <path class="cls-12"
                                    d="M370.89,104.7V108h-1.1V99.6h3.09a3.14,3.14,0,0,1,2.15.7,2.39,2.39,0,0,1,.78,1.86,2.35,2.35,0,0,1-.76,1.88,3.25,3.25,0,0,1-2.18.66Zm0-.91h2a2,2,0,0,0,1.35-.41,1.52,1.52,0,0,0,.48-1.21,1.57,1.57,0,0,0-.48-1.2,1.83,1.83,0,0,0-1.29-.46h-2.05Z" />
                                <path class="cls-12" d="M382,104.11h-3.63v3h4.22V108h-5.32V99.6h5.26v.91h-4.16v2.69H382Z" />
                                <path class="cls-12"
                                    d="M387,104.59h-2V108h-1.11V99.6h2.77a3.3,3.3,0,0,1,2.18.65,2.3,2.3,0,0,1,.76,1.87,2.24,2.24,0,0,1-.42,1.36,2.56,2.56,0,0,1-1.18.87l2,3.56V108H388.8Zm-2-.9h1.69a1.93,1.93,0,0,0,1.31-.43,1.44,1.44,0,0,0,.49-1.14,1.53,1.53,0,0,0-.46-1.19,1.94,1.94,0,0,0-1.34-.42H385Z" />
                                <path class="cls-12"
                                    d="M395.79,105.79h-3.51l-.79,2.19h-1.14l3.2-8.38h1l3.2,8.38h-1.13Zm-3.17-.91h2.84L394,101Z" />
                                <path class="cls-12" d="M403.89,100.51H401.2V108h-1.1v-7.47h-2.68V99.6h6.47Z" />
                                <path class="cls-12" d="M406.32,108h-1.1V99.6h1.1Z" />
                                <path class="cls-12"
                                    d="M414.78,104.06a5.29,5.29,0,0,1-.41,2.15,3.15,3.15,0,0,1-1.18,1.4,3.47,3.47,0,0,1-3.52,0,3.25,3.25,0,0,1-1.19-1.39,5.06,5.06,0,0,1-.44-2.09v-.6a5.22,5.22,0,0,1,.42-2.13,3.29,3.29,0,0,1,1.19-1.42,3.17,3.17,0,0,1,1.76-.49,3.26,3.26,0,0,1,1.77.48,3.18,3.18,0,0,1,1.19,1.41,5.32,5.32,0,0,1,.41,2.15Zm-1.1-.54a3.76,3.76,0,0,0-.6-2.29,2.14,2.14,0,0,0-3.32,0,3.74,3.74,0,0,0-.62,2.21v.62a3.8,3.8,0,0,0,.61,2.27,2.13,2.13,0,0,0,3.33,0,3.7,3.7,0,0,0,.6-2.23Z" />
                                <path class="cls-12"
                                    d="M422.87,108h-1.11l-4.21-6.46V108h-1.11V99.6h1.11l4.22,6.48V99.6h1.1Z" />
                                <path class="cls-12"
                                    d="M429.56,105.79h-3.51l-.79,2.19h-1.14l3.2-8.38h1l3.2,8.38h-1.13Zm-3.17-.91h2.84L427.81,101Z" />
                                <path class="cls-12" d="M433.73,107.07h4V108h-5.08V99.6h1.11Z" />
                                <path class="cls-12"
                                    d="M373.25,118.24h-3.63v3h4.22v.9h-5.32v-8.37h5.26v.9h-4.16v2.7h3.63Z" />
                                <path class="cls-12" d="M379.83,118.41h-3.52v3.7h-1.1v-8.37h5.19v.9h-4.09v2.87h3.52Z" />
                                <path class="cls-12" d="M386.34,118.41h-3.52v3.7h-1.1v-8.37h5.19v.9h-4.09v2.87h3.52Z" />
                                <path class="cls-12" d="M389.41,122.11h-1.1v-8.37h1.1Z" />
                                <path class="cls-12"
                                    d="M397.59,119.45a3.05,3.05,0,0,1-1,2,3.17,3.17,0,0,1-2.19.73,3,3,0,0,1-2.38-1.07,4.27,4.27,0,0,1-.89-2.85v-.8a4.72,4.72,0,0,1,.41-2.05,3.09,3.09,0,0,1,1.18-1.36,3.26,3.26,0,0,1,1.77-.48,3,3,0,0,1,2.14.75,3.09,3.09,0,0,1,.94,2.06h-1.11a2.42,2.42,0,0,0-.62-1.46,1.94,1.94,0,0,0-1.35-.45,2,2,0,0,0-1.65.79,3.59,3.59,0,0,0-.61,2.23v.81a3.7,3.7,0,0,0,.58,2.17,1.81,1.81,0,0,0,1.59.8,2.17,2.17,0,0,0,1.41-.41,2.29,2.29,0,0,0,.65-1.46Z" />
                                <path class="cls-12" d="M400.28,122.11h-1.1v-8.37h1.1Z" />
                                <path class="cls-12" d="M407,118.24h-3.63v3h4.21v.9H402.3v-8.37h5.27v.9h-4.16v2.7H407Z" />
                                <path class="cls-12"
                                    d="M415.43,122.11h-1.11l-4.21-6.45v6.45H409v-8.37h1.11l4.22,6.48v-6.48h1.1Z" />
                                <path class="cls-12"
                                    d="M423.55,119.45a3.05,3.05,0,0,1-1,2,3.17,3.17,0,0,1-2.19.73,3,3,0,0,1-2.38-1.07,4.27,4.27,0,0,1-.89-2.85v-.8a4.72,4.72,0,0,1,.41-2.05,3.09,3.09,0,0,1,1.18-1.36,3.26,3.26,0,0,1,1.77-.48,3,3,0,0,1,2.14.75,3.09,3.09,0,0,1,.94,2.06h-1.11a2.42,2.42,0,0,0-.62-1.46,1.94,1.94,0,0,0-1.35-.45,2,2,0,0,0-1.65.79,3.59,3.59,0,0,0-.61,2.23v.81a3.62,3.62,0,0,0,.58,2.17,1.81,1.81,0,0,0,1.59.8,2.17,2.17,0,0,0,1.41-.41,2.29,2.29,0,0,0,.65-1.46Z" />
                                <path class="cls-12"
                                    d="M427.61,117.94l2.19-4.2h1.25L428.16,119v3.12h-1.1V119l-2.89-5.25h1.27Z" /><text
                                    class="cls-13" transform="translate(283.12 58.56)">Q<tspan class="cls-14" x="8.1"
                                        y="0">U</tspan>
                                    <tspan x="15.61" y="0">AL</tspan>
                                    <tspan class="cls-15" x="29.63" y="0">I</tspan>
                                    <tspan class="cls-16" x="32.67" y="0">T</tspan>
                                    <tspan x="39.79" y="0">Y OF</tspan>
                                    <tspan x="9.25" y="14.13">SE</tspan>
                                    <tspan class="cls-17" x="22.93" y="14.13">R</tspan>
                                    <tspan class="cls-18" x="30.08" y="14.13">VICE</tspan>
                                </text><text class="cls-13" transform="translate(292.55 164.99)">
                                    <tspan class="cls-19">P</tspan>
                                    <tspan class="cls-20" x="6.64" y="0">A</tspan>
                                    <tspan x="13.58" y="0">TIE</tspan>
                                    <tspan class="cls-15" x="30.5" y="0">N</tspan>
                                    <tspan class="cls-21" x="38.73" y="0">T</tspan>
                                    <tspan x="-16.5" y="14.13">S</tspan>
                                    <tspan class="cls-22" x="-9.51" y="14.13">A</tspan>
                                    <tspan class="cls-18" x="-2.57" y="14.13">TIS</tspan>
                                    <tspan class="cls-23" x="14.65" y="14.13">F</tspan>
                                    <tspan class="cls-24" x="20.18" y="14.13">A</tspan>
                                    <tspan class="cls-25" x="27.8" y="14.13">C</tspan>
                                    <tspan class="cls-18" x="35.3" y="14.13">TION</tspan>
                                </text><text class="cls-26"
                                    transform="matrix(0.93, 0.36, -0.36, 0.93, 397.1, 21.23)">H</text><text class="cls-26"
                                    transform="translate(403.94 23.89) rotate(24.81)">E</text><text class="cls-26"
                                    transform="translate(409.24 26.32) rotate(28.33)">A</text><text class="cls-26"
                                    transform="matrix(0.85, 0.53, -0.53, 0.85, 415.14, 29.52)">L</text><text
                                    class="cls-26" transform="translate(418.68 31.66) rotate(34.24)">T</text><text
                                    class="cls-26"
                                    transform="matrix(0.79, 0.62, -0.62, 0.79, 423.75, 35.09)">H</text><text
                                    class="cls-26" transform="matrix(0.76, 0.65, -0.65, 0.76, 429.51, 39.66)">
                                </text><text class="cls-26"
                                    transform="translate(431.46 41.27) rotate(43.32)">A</text><text class="cls-26"
                                    transform="translate(436.29 45.82) rotate(46.98)">U</text><text class="cls-26"
                                    transform="matrix(0.64, 0.77, -0.77, 0.64, 440.83, 50.69)">T</text><text
                                    class="cls-26" transform="matrix(0.58, 0.81, -0.81, 0.58, 444.74, 55.4)">H</text><text
                                    class="cls-26"
                                    transform="matrix(0.53, 0.85, -0.85, 0.53, 449.01, 61.33)">O</text><text
                                    class="cls-26" transform="translate(452.74 67.37) rotate(61.95)">R</text><text
                                    class="cls-26" transform="translate(455.69 72.98) rotate(64.47)">I</text><text
                                    class="cls-26"
                                    transform="matrix(0.39, 0.92, -0.92, 0.39, 456.86, 75.33)">T</text><text
                                    class="cls-26" transform="translate(459.29 81.03) rotate(70.35)">Y</text><text
                                    class="cls-26" transform="translate(461.34 86.9) rotate(72.79)"> </text><text
                                    class="cls-26" transform="translate(462.14 89.27) rotate(75.46)">G</text><text
                                    class="cls-26" transform="translate(463.9 96.06) rotate(79.3)">U</text><text
                                    class="cls-26" transform="translate(465.09 102.63) rotate(81.95)">I</text><text
                                    class="cls-26" transform="matrix(0.09, 1, -1, 0.09, 465.53, 105.36)">D</text><text
                                    class="cls-26" transform="translate(466.15 112.09) rotate(88.15)">E</text><text
                                    class="cls-26" transform="translate(466.34 117.91) rotate(91.3)">L</text><text
                                    class="cls-26" transform="translate(466.19 123.46) rotate(93.61)">I</text><text
                                    class="cls-26" transform="translate(466.07 126.22) rotate(96.4)">N</text><text
                                    class="cls-26"
                                    transform="matrix(-0.17, 0.98, -0.98, -0.17, 465.23, 133.52)">E</text><text
                                    class="cls-26" transform="translate(464.22 139.26) rotate(103.3)">S</text><text
                                    class="cls-26" transform="translate(462.78 145.22) rotate(105.7)"> </text><text
                                    class="cls-26" transform="translate(462.14 147.66) rotate(108.29)">A</text><text
                                    class="cls-26"
                                    transform="matrix(-0.38, 0.93, -0.93, -0.38, 460.06, 154)">N</text><text
                                    class="cls-26"
                                    transform="matrix(-0.44, 0.9, -0.9, -0.44, 457.28, 160.8)">D</text><text
                                    class="cls-26" transform="translate(454.25 166.86) rotate(118.77)"> </text><text
                                    class="cls-26"
                                    transform="matrix(-0.52, 0.85, -0.85, -0.52, 453.08, 169.08)">R</text><text
                                    class="cls-26"
                                    transform="matrix(-0.57, 0.82, -0.82, -0.57, 449.79, 174.49)">E</text><text
                                    class="cls-26" transform="translate(446.5 179.3) rotate(128.24)">G</text><text
                                    class="cls-26" transform="translate(442.17 184.79) rotate(132.03)">U</text><text
                                    class="cls-26"
                                    transform="matrix(-0.71, 0.7, -0.7, -0.71, 437.69, 189.74)">L</text><text
                                    class="cls-26" transform="translate(433.7 193.7) rotate(138.86)">A</text><text
                                    class="cls-26" transform="translate(429.16 197.68) rotate(142.05)">T</text><text
                                    class="cls-26" transform="translate(424.27 201.45) rotate(144.52)">I</text><text
                                    class="cls-26"
                                    transform="matrix(-0.84, 0.54, -0.54, -0.84, 422.05, 203.1)">O</text><text
                                    class="cls-26" transform="translate(416.12 206.92) rotate(151.24)">N</text><text
                                    class="cls-26" transform="translate(409.68 210.44) rotate(154.97)">S</text>
                                <circle class="cls-27" cx="115.69" cy="87.28" r="28.17" />
                                <path class="cls-12"
                                    d="M100.27,90.83a4.4,4.4,0,0,1-1.85.34,3.29,3.29,0,0,1-3.48-3.56,3.5,3.5,0,0,1,3.67-3.7,3.85,3.85,0,0,1,1.67.31l-.21.74a3.46,3.46,0,0,0-1.42-.29,2.62,2.62,0,0,0-2.75,2.9,2.55,2.55,0,0,0,2.7,2.83,3.63,3.63,0,0,0,1.49-.29Z" />
                                <path class="cls-12" d="M101.41,84h.91V90.3h3v.76h-3.92Z" />
                                <path class="cls-12" d="M107.25,84v7h-.91V84Z" />
                                <path class="cls-12"
                                    d="M108.84,91.06V84h1l2.25,3.57a19.32,19.32,0,0,1,1.27,2.28h0c-.08-.94-.11-1.79-.11-2.89V84h.86v7h-.92L111,87.49a23.39,23.39,0,0,1-1.32-2.35h0c0,.89.07,1.74.07,2.91v3Z" />
                                <path class="cls-12" d="M116.62,84v7h-.91V84Z" />
                                <path class="cls-12"
                                    d="M123.12,90.83a4.42,4.42,0,0,1-1.86.34,3.29,3.29,0,0,1-3.47-3.56,3.5,3.5,0,0,1,3.67-3.7,3.85,3.85,0,0,1,1.67.31l-.22.74a3.38,3.38,0,0,0-1.42-.29,2.61,2.61,0,0,0-2.74,2.9,2.55,2.55,0,0,0,2.7,2.83,3.58,3.58,0,0,0,1.48-.29Z" />
                                <path class="cls-12"
                                    d="M125.44,88.85l-.73,2.21h-.94l2.39-7h1.1l2.4,7h-1l-.75-2.21Zm2.31-.71-.69-2c-.16-.46-.26-.88-.37-1.28h0c-.1.42-.22.84-.35,1.27l-.69,2Z" />
                                <path class="cls-12" d="M130.69,84h.91V90.3h3v.76h-3.92Z" />
                                <circle class="cls-28" cx="175.53" cy="116.76" r="34.94" />
                                <path class="cls-12" d="M145.48,118.44h-.65v-5h.65Z" />
                                <path class="cls-12"
                                    d="M150.48,118.44h-.66l-2.49-3.82v3.82h-.66v-5h.66l2.5,3.83v-3.83h.65Z" />
                                <path class="cls-12" d="M154.36,116.25h-2.07v2.19h-.65v-5h3.06V114h-2.41v1.7h2.07Z" />
                                <path class="cls-12"
                                    d="M157.3,116.44h-1.16v2h-.66v-5h1.64a2,2,0,0,1,1.29.38,1.51,1.51,0,0,1,.2,1.91,1.56,1.56,0,0,1-.7.52l1.16,2.1v0h-.7Zm-1.16-.54h1a1.12,1.12,0,0,0,.77-.25.86.86,0,0,0,.29-.67.91.91,0,0,0-.27-.71,1.24,1.24,0,0,0-.79-.25h-1Z" />
                                <path class="cls-12"
                                    d="M162.5,117.14h-2.07l-.47,1.3h-.67l1.89-5h.57l1.89,5H163Zm-1.87-.53h1.68l-.84-2.32Z" />
                                <path class="cls-12"
                                    d="M165.77,116.23a3.07,3.07,0,0,1-1.22-.59,1.13,1.13,0,0,1-.39-.87,1.22,1.22,0,0,1,.47-1,1.89,1.89,0,0,1,1.22-.38,2.06,2.06,0,0,1,.91.2,1.55,1.55,0,0,1,.62.54,1.45,1.45,0,0,1,.21.76h-.65a.91.91,0,0,0-.29-.71,1.17,1.17,0,0,0-.8-.26,1.23,1.23,0,0,0-.76.22.71.71,0,0,0-.27.59.64.64,0,0,0,.26.51,2.37,2.37,0,0,0,.88.38,4.11,4.11,0,0,1,1,.39,1.62,1.62,0,0,1,.52.48,1.29,1.29,0,0,1,.16.66,1.15,1.15,0,0,1-.46,1,2,2,0,0,1-1.26.37,2.31,2.31,0,0,1-.95-.2,1.67,1.67,0,0,1-.68-.53A1.33,1.33,0,0,1,164,117h.66a.82.82,0,0,0,.33.71,1.34,1.34,0,0,0,.88.26,1.26,1.26,0,0,0,.79-.21.67.67,0,0,0,.28-.57.7.7,0,0,0-.26-.57A3,3,0,0,0,165.77,116.23Z" />
                                <path class="cls-12" d="M171.86,114h-1.59v4.42h-.65V114H168v-.53h3.83Z" />
                                <path class="cls-12"
                                    d="M174.41,116.44h-1.16v2h-.66v-5h1.64a2,2,0,0,1,1.29.38,1.51,1.51,0,0,1,.2,1.91,1.56,1.56,0,0,1-.7.52l1.16,2.1v0h-.7Zm-1.16-.54h1a1.12,1.12,0,0,0,.77-.25.82.82,0,0,0,.29-.67.91.91,0,0,0-.27-.71,1.24,1.24,0,0,0-.79-.25h-1Z" />
                                <path class="cls-12"
                                    d="M180.37,113.49v3.36a1.59,1.59,0,0,1-.45,1.15,1.76,1.76,0,0,1-1.18.5h-.17a1.88,1.88,0,0,1-1.3-.44,1.6,1.6,0,0,1-.49-1.21v-3.37h.65v3.35a1.14,1.14,0,1,0,2.28,0v-3.35Z" />
                                <path class="cls-12"
                                    d="M185,116.87a1.83,1.83,0,0,1-.58,1.21,1.94,1.94,0,0,1-1.3.43,1.77,1.77,0,0,1-1.41-.63,2.55,2.55,0,0,1-.52-1.69v-.47a2.84,2.84,0,0,1,.24-1.21,1.83,1.83,0,0,1,1.75-1.09,1.79,1.79,0,0,1,1.26.44,1.86,1.86,0,0,1,.56,1.22h-.66a1.43,1.43,0,0,0-.37-.86,1.1,1.1,0,0,0-.79-.27,1.16,1.16,0,0,0-1,.47,2.08,2.08,0,0,0-.36,1.31v.48a2.17,2.17,0,0,0,.34,1.29,1.1,1.1,0,0,0,.94.47,1.26,1.26,0,0,0,.83-.24,1.33,1.33,0,0,0,.39-.86Z" />
                                <path class="cls-12" d="M189.25,114h-1.59v4.42H187V114h-1.59v-.53h3.83Z" />
                                <path class="cls-12"
                                    d="M193.47,113.49v3.36A1.63,1.63,0,0,1,193,118a1.79,1.79,0,0,1-1.19.5h-.17a1.89,1.89,0,0,1-1.3-.44,1.6,1.6,0,0,1-.49-1.21v-3.37h.65v3.35a1,1,0,0,0,1.14,1.13,1,1,0,0,0,1.14-1.13v-3.35Z" />
                                <path class="cls-12"
                                    d="M196.31,116.44h-1.16v2h-.66v-5h1.64a2,2,0,0,1,1.29.38,1.36,1.36,0,0,1,.45,1.11,1.29,1.29,0,0,1-.25.8,1.56,1.56,0,0,1-.7.52l1.17,2.1v0h-.7Zm-1.16-.54h1a1.14,1.14,0,0,0,.78-.25.85.85,0,0,0,.28-.67.91.91,0,0,0-.27-.71,1.22,1.22,0,0,0-.79-.25h-1Z" />
                                <path class="cls-12"
                                    d="M201.52,117.14h-2.08l-.46,1.3h-.68l1.89-5h.58l1.89,5H202Zm-1.88-.53h1.68l-.84-2.32Z" />
                                <path class="cls-12" d="M204,117.9h2.35v.54h-3v-5H204Z" />
                                <circle class="cls-29" cx="115.69" cy="154.58" r="33.01" /><text class="cls-30"
                                    transform="translate(84.19 154.8)">OPER<tspan class="cls-31" x="23.95" y="0">A</tspan>
                                    <tspan class="cls-32" x="29.6" y="0">TIO</tspan>
                                    <tspan class="cls-33" x="44.49" y="0">N</tspan>
                                    <tspan x="51.4" y="0">AL</tspan>
                                </text>
                                <circle class="cls-34" cx="29.23" cy="115.45" r="24.35" />
                                <circle class="cls-34" cx="56.74" cy="145.43" r="13.68" />
                                <circle class="cls-34" cx="72.58" cy="117.06" r="16.98" /><text class="cls-35"
                                    transform="translate(7.95 117.07)">ADMINISTR<tspan class="cls-36" x="46.26" y="0">A
                                    </tspan>
                                    <tspan class="cls-37" x="51.46" y="0">TIVE</tspan>
                                </text>
                                <path class="cls-12"
                                    d="M134.19,14.73A103.79,103.79,0,1,1,58.39,189.4h-9.6a111,111,0,1,0,.15-141.94h9.61A103.45,103.45,0,0,1,134.19,14.73Z" />
                                <path class="cls-12"
                                    d="M503.17,118.42A122.12,122.12,0,0,1,474.72,197H464.09a114.68,114.68,0,0,0,.17-156.89h10.62A122.18,122.18,0,0,1,503.17,118.42Z" />
                                <text class="cls-38" transform="translate(52.87 264.59)">FOCUSED DO<tspan class="cls-39"
                                        x="94.77" y="0">M</tspan>
                                    <tspan class="cls-40" x="108.74" y="0">AINS</tspan>
                                </text><text class="cls-41" transform="translate(275.35 264.59)">RESU<tspan
                                        class="cls-42" x="38.64" y="0">L</tspan>
                                    <tspan class="cls-43" x="45.12" y="0">T</tspan>
                                    <tspan x="54.51" y="0">S OPTIMIS</tspan>
                                    <tspan class="cls-44" x="130.6" y="0">A</tspan>
                                    <tspan class="cls-45" x="140" y="0">TION</tspan>
                                </text><text class="cls-46" transform="translate(525.93 264.59)">A<tspan class="cls-45"
                                        x="10.29" y="0">CHIEVEME</tspan>
                                    <tspan class="cls-47" x="87.55" y="0">N</tspan>
                                    <tspan class="cls-48" x="98.66" y="0">T</tspan>
                                </text>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-4"></div>
                        <div class="col-lg-8 col-md-4 text-center">
                            <h1 class="mb-30 xservice-content">The Tawqeet Process</h1>
                            <h4 class="mb-30"><i>A comprehensive audit of the healthcare facility is conducted by
                                    healthcare experts across all functional domains. The processes are evaluated based on
                                    preset checklists derived from combined Key Performance Indicators (KPIs) and
                                    international best practices. GAP analysis on the areas for improvement is presented to
                                    the facility management and the Corrective Action Plan is developed in concurrence with
                                    the facility. Implementation involves staff training and changes/improvisation in the
                                    existing work mechanisms. The entire process is overlooked by an assigned “client
                                    manager”.</i></h4>

                        </div>
                        <div class="col-lg-2 col-md-4"></div>
                        <img src="{{ asset('public/front/assets/img/towqeet-result.svg') }}" style=" width: 100%; ">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Area -->
    <section class="repair-services-area ptb-100 pb-0 bg-fbf9f8">
        <div class="container">
            <div class="section-title text-left">
                <span>Enhance your Medical Facility Standards with Tawqeet!</span>
                <h3>Tawqeet Packages</h3>
                <div style="width: 65%;">
                    <p>Select the right Tawqeet plan which meets the requirement of your medical facility. Tawqeet packages
                        are now custom made to suite your facility size and requirements, choose the right one to suit your
                        budget and requirements, depending on the necessity, you could always convert or amend the package.
                    </p>
                </div>
                <div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="hplpackages">
                                <h3>Tawqeet for facility Audit Compliance</h3>
                                <p>This package is a one off solution for a situational audit, when the need arises for
                                    compliance, like: periodic inspection by the regulatory body/ change of location /
                                    addition of specialty / change of internal plan. The Tawqeet process implementation will
                                    make certain, adequate staff training, ensuring that your facility is audit ready and
                                    compliant with the regulatory requirements.</p>
                                <br>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="hplpackages">
                                <h3>Tawqeet for Facility Enhancement </h3>
                                <p>This package helps your facility redesign your existing work processes, optimize
                                    resources, improve efficiency and business productivity, while also satisfying the
                                    regulatory requirements. This package helps you in a complete facility overhaul
                                    addressing all the domains of the facility operations. Also this keeps facility ready
                                    for sudden, surprise audit from regulatory body anytime</p>
                                <br>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="hplpackages">
                                <h3>Full Tawqeet Compliance Program</h3>
                                <p>This comprehensive package is standout feature of Tawqeet program is ensuring
                                    sustainability of the enhancement is done. This process overlooked by a dedicated client
                                    manager and implemented by our expert team. Each domain will be optimized compliance and
                                    ensuring improvements, makes your facility, all time ready for any surprise regulatory
                                    audits. </p>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-4">
                            <div class="tawqeetpackages  dmbg-2">
                                <h2>Tawqeet for Medical Centers</h2><br>
                                <p>You can request for the above mentioned Tawqeet Packages and get your medical center
                                    audited across Clinical, Operational, Infrastructural, Administrative domains. The audit
                                    helps you identify the gaps in the existing process and improvise or eliminate them, to
                                    ensure better care delivery at your facility and improve business outcomes. Not only
                                    would the facility adopt international best practises but also be compliant for the
                                    regulatory standards. </p>
                                <div class="">

                                    <p>DOH Audit Compliance</p>
                                    <hr>
                                    <p>Jawda Compliance</p>
                                    <hr>
                                    <p>Resource Efficiency</p>
                                    <hr>
                                    <p>Operational Enhancement</p>
                                    <hr>

                                    <a href="https://wa.me/971555595200?&text=Request Tawqeet for Medical center"
                                        class="btn btn-primary btnx">Get Started</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-4">
                            <div class="tawqeetpackages dmbg-3">
                                <h2>Tawqeet for Homecare Centers</h2><br>
                                <p>Get Tawqeet Packages and get your Home Care audited across the focus domains including
                                    billing and submissions will be cross verified for the accuracy. Home Care Tawqeet
                                    analysis is applicable to both your corporate office and the patient care area. The gap
                                    analysis compliances are not only covering the internal operations but also Health
                                    Authority & Thasneef requirements to ensure better care delivery at your facility and
                                    improve business outcomes.</p>
                                <div class="">
                                    <p>Tasneef Compliance</p>
                                    <hr>
                                    <p>DOH Audit Compliance</p>
                                    <hr>
                                    <p>Jawda Compliance</p>
                                    <hr>
                                    <p>Operational Enhancement</p>
                                    <hr>

                                    <a href="https://wa.me/971555595200?&text=Request Tawqeet for homecare center"
                                        class="btn btn-primary btnx">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-50">
        <div class="col-md-8 offset-md-2 section-title text-center">
            <div class="  repair-services-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="">

                            <h3>Send us an Email!</h3>
                            <form id="contactForm" name="contactForm" class="">
                                <div class="row">
                                    <div class="col-lg-3  form-group">
                                        <input id="fullName" name="fullName" type="text" placeholder="Your Name*"
                                            required class="form-control">
                                    </div>

                                    <div class="col-lg-3 form-group">
                                        <input id="Email" name="Email" type="email" placeholder="Email*"
                                            required class="form-control">
                                    </div>

                                    <div class="col-lg-3 form-group">
                                        <input id="Phone" name="Phone" type="text" placeholder="Phone Number*"
                                            class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="browser-default custom-select form-group form-control"
                                            name="service">
                                            <option selected>Select package type</option>
                                            <option value="Digital Marketing">Tawqeet for Medical Center</option>
                                            <option value="Healthcare Professional Licensing">Towqeet for Home Care Center
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea id="Message" name="Message" class="form-control required" rows="3" placeholder="Enter Message"></textarea>
                                </div>
                                <p class="success"
                                    style="display:none;color:#066d77;text-align:center;width:100%;position:absolute;">We
                                    have received your inquiry, one of your representatives will contact you shorly</p><br>
                                <button type="submit" class="btn btn-primary">Send Message Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <div id="myButton"></div>
@endsection

@section('custom_js')
    <!-- Chat -->
    <script type="text/javascript" src="{{ asset('public/front/assets/js/chat-hmc.js') }}"></script>
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
