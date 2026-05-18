@extends('front/layout')

@section('meta_title', 'About Page')
@section('meta_description', 'About Page Description')
@section('meta_keywords', 'About,Alpha,Education')

@section('meta_tags')
    <!-- Additional meta tags (if necessary) -->
    <meta property="og:title" content="About Page - My Website">
    <meta property="og:description" content="This is the about page of My Website.">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('custom_css')
@endSection

@section('content')
    <section class="breadcum">
        <div class="container">
            <div class="breadcum-content">
                <h2 class="title">{{ $category->name }}</h2>
                <h4 class="para"><a href="index.html">Home</a> / Services</h4>
            </div>
        </div>
    </section>
    {{-- <section class="my-60 @">
        <div class="container-xl">
            <nav>
                <div class="services-grid-nav">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <div class="nav-left-content">
                            <div class="content-icon">
                                <button class="nav-link active" id="nav-all-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all"
                                    aria-selected="false" tabindex="-1">
                                    <i class="my-icon icon-colum"></i>
                                </button>
                            </div>
                        </div>
                        <div class="nav-right-content">
                            <form class="default-shorting" method="GET" action="">
                                <input type="text" name="search" placeholder="Search Our Courses"
                                    value="{{ $search ?? '' }}">
                                <button class="search-icon" type="submit">
                                    <i class="my-icon icon-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="row">
                <div class="col-lg-12">
                    @if (isset($services) && count($services) > 0)
                        <div class="row">
                            @foreach ($services as $service)
                                <div class="col-lg-4">
                                    <div class="services-cards-content" data-name="marketing">
                                        <div class="services-img">
                                            <a href="{{ route('view_service', $service->slug) }}">
                                                <img src="{{ asset('public/uploads/service_images/' . $service->images->first()->image) }}"
                                                    alt="services-img" />
                                            </a>
                                        </div>
                                        <div class="services-content">
                                            <h3 class="services-title text">
                                                <a href="{{ route('view_service', $service->slug) }}">{{ $service->name }}
                                                </a>
                                            </h3>
                                            <div class="time-leson">
                                                @if ($service->schedules?->first())
                                                    <div class="leson">
                                                        <div class="leson-icon"><i class="fa fa-calendar"></i>
                                                        </div>
                                                        {{ date('jS M Y', strtotime(convertToUserTimezone($service->schedules?->first()->day))) }}
                                                    </div>
                                                    <div class="time">
                                                        <div class="time-icon"><i class="my-icon icon-time-hour"></i>
                                                        </div>
                                                        {{ convertToUserTimezone($service->schedules?->first()->time) }}

                                                    </div>
                                                @else

                                                @endif

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12 pagination-button">
                                {{ $services->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section> --}}


@endsection

@section('custom_js')
@endSection
