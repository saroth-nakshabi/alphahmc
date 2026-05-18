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
                <h2 class="title">Accreditation</h2>
                <h4 class="para"><a href="index.html">Home</a> / Accreditation</h4>
            </div>
        </div>
    </section>
    <section class="my-100 @">
        <div class="container-xl">
            <div class="about-us-content v3">
                <div class="row">
                    @if (isset($authorities) && $authorities->count() > 0)
                        @foreach ($authorities as $authority)
                            <div
                                class="col-12 d-flex flex-column align-items-center rounded-3 bg-gradient p-4 p-md-5 flex-md-row bg-light rounded">
                                <div class="mb-3 mb-md-0 flex-shrink-0">
                                    <img loading="lazy" width="150" height="150" decoding="async"
                                        src="{{ asset('public/uploads/authority_images/' . $authority->logo) }}"
                                        style="color: transparent;">
                                </div>
                                <div class="mb-3 w-100 bg-opacity-30 d-md-none"></div>
                                <div class="mx-3 d-flex flex-column border-start ps-md-3">
                                    <h4 class="fw-bold text-secondary">{{ $authority->name }}</h4>
                                    <p class="mt-2 text-muted">
                                        {{ nl2br($authority->description) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
@endSection
