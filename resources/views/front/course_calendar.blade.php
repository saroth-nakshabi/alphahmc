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
                <h2 class="title">Service Calendar</h2>
                <h4 class="para"><a href="index.html">Home</a> / Service Calendar</h4>
            </div>
        </div>
    </section>
    <section class="my-60 @">
        <div class="container-xl">
            <div class="about-us-content v3">
                <div class="row align-items-center">
                    <div>View all of our service dates and book any service from the calendar directly by selecting the service
                    </div>
                    {{-- @if (isset($services) && count($services) > 0)
                        @foreach ($services as $service)
                            <div class="col-lg-12 mt-3">
                                <h5 class="fw-semibold text-muted">{{ $service->name }}</h5>
                                @if ($service->upComingSchedules?->count() > 0)
                                    <div class="d-flex flex-wrap">
                                        @foreach ($service->upComingSchedules as $schedule)
                                            <a href="{{ route('view_service', $service->slug) }}"
                                                class="border p-3 rounded text-center m-2">
                                                <p class="font-semibold text-gray-800">
                                                    {{ date('l', strtotime(convertToUserTimezone($schedule->day))) }}<br>{{ date('jS F', strtotime(convertToUserTimezone($schedule->day))) }}
                                                </p>
                                                <p class="text-success">{{ convertToUserTimezone($schedule->time) }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray mt-2">To be anounced</p>
                                @endif
                            </div>
                        @endforeach
                    @endif --}}
                    <ul class="service-calendar-accordion" id="accordionFlushExample">
                        @if (isset($services) && count($services) > 0)
                            @foreach ($services as $service)
                                <li class="accordion-item">
                                    <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                        <button class="accordion-button {{ $loop->iteration == 1 ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#accordian_id_{{ $service->id }}"
                                            aria-expanded="{{ $loop->iteration == 1 ? 'true' : 'false' }}"
                                            aria-controls="accordian_id_{{ $service->id }}">
                                            {{ $service->name }}
                                        </button>
                                    </h2>
                                    <div id="accordian_id_{{ $service->id }}"
                                        class="accordion-collapse collapse {{ $loop->iteration == 1 ? 'show' : '' }}"
                                        aria-labelledby="heading-{{ $loop->iteration }}"
                                        data-bs-parent="#accordionFlushExample" style="">
                                        <div class="accordion-body">
                                            @if ($service->upComingSchedules?->count() > 0)
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($service->upComingSchedules as $schedule)
                                                        <a href="{{ route('view_service', $service->slug) }}"
                                                            class="border p-3 rounded text-center m-2">
                                                            <p class="font-semibold text-gray-800">
                                                                {{ date('l', strtotime(convertToUserTimezone($schedule->day))) }}<br>{{ date('jS F', strtotime(convertToUserTimezone($schedule->day))) }}
                                                            </p>
                                                            <p class="text-success">
                                                                {{ convertToUserTimezone($schedule->time) }}</p>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray mt-2">To be anounced</p>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="text-gray">- No data available -</div>
                        @endif
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('custom_js')
@endSection
