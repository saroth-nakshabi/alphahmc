@extends('front.layout-2')

{{-- Reviewer bio page (brief "alphahmc-reviewers-and-linking", 30 Oct 2026). Copy is the reviewers row,
     word for word from the SEO agent's bios file. Until the headshot arrives the photo slot shows initials,
     and the schema has no image. No email or mobile: the contact block is the company WhatsApp and the
     inquiry form, as on other pages. --}}
@php
    $rvTitle = $reviewer->name . ', ' . $reviewer->title;
    $person = array_filter([
        '@type'      => 'Person',
        '@id'        => $reviewer->personId(),
        'name'       => $reviewer->name,
        'jobTitle'   => $reviewer->title,
        'worksFor'   => ['@id' => url('/') . '/#organization'],
        'sameAs'     => $reviewer->linkedin ? [$reviewer->linkedin] : null,
        'knowsAbout' => rtrim(trim((string) $reviewer->reviews), '.') ?: null,
        'url'        => $reviewer->url(),
        'image'      => $reviewer->photo ? asset('public/' . $reviewer->photo) : null,
    ]);
    $waLink = \App\Support\WaContext::link();
@endphp

@section('meta_description', \Illuminate\Support\Str::limit($reviewer->bio, 155))
@push('page_title'){{ \App\Support\Seo::title($rvTitle, $rvTitle, 'Alpha Health Group') }}@endpush
@push('meta')
    <script type="application/ld+json">{!! json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'ProfilePage',
        'url'        => $reviewer->url(),
        'mainEntity' => $person,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
<div class="rv-page">
    <div class="container">
        <nav class="rv-crumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
            <a href="{{ route('front.new-about') }}#reviewers">Our reviewers</a><span aria-hidden="true">/</span>
            <span aria-current="page">{{ $reviewer->name }}</span>
        </nav>

        <div class="rv-grid">
            <div class="rv-photo">
                @if($reviewer->photo)
                    <img src="{{ asset('public/' . $reviewer->photo) }}" alt="{{ $reviewer->name }}" width="240" height="240">
                @else
                    <span class="rv-initials" aria-hidden="true">{{ $reviewer->initials() }}</span>
                @endif
            </div>
            <div class="rv-body">
                <h1>{{ $reviewer->name }}</h1>
                <p class="rv-title">{{ $reviewer->title }}</p>

                <dl class="rv-facts">
                    @if($reviewer->qualifications)<div><dt>Qualifications</dt><dd>{{ $reviewer->qualifications }}</dd></div>@endif
                    @if($reviewer->experience)<div><dt>Experience</dt><dd>{{ $reviewer->experience }}</dd></div>@endif
                    @if($reviewer->reviews)<div><dt>Reviews</dt><dd>{{ ucfirst($reviewer->reviews) }}</dd></div>@endif
                </dl>

                @if($reviewer->bio)<p class="rv-bio">{{ $reviewer->bio }}</p>@endif

                @if($reviewer->linkedin)
                    <p class="rv-linkedin"><a href="{{ $reviewer->linkedin }}" target="_blank" rel="noopener me">{{ $reviewer->name }} on LinkedIn</a></p>
                @endif
            </div>
        </div>

        <section class="rv-contact" aria-labelledby="rv-contact-h">
            <h2 id="rv-contact-h">Talk to our team</h2>
            <p>Questions about licensing, facility setup or quality go to our consultants on WhatsApp or through the inquiry form.</p>
            <div class="rv-actions">
                <a class="rv-btn rv-btn-primary" href="{{ $waLink }}" target="_blank" rel="noopener">Message us on WhatsApp</a>
                <button type="button" class="rv-btn rv-btn-ghost" data-bs-toggle="modal" data-bs-target="#inquiryModal">Send an inquiry</button>
            </div>
        </section>
    </div>
</div>
@endsection

@section('custom_css')
<style>
    .rv-page { padding: 140px 0 80px; background: #fff; }
    .rv-crumb { display: flex; flex-wrap: wrap; gap: 8px; font-size: .88rem; color: #5b6b6d; margin-bottom: 36px; }
    .rv-crumb a { color: inherit; }
    .rv-grid { display: grid; grid-template-columns: 240px minmax(0, 1fr); gap: 48px; align-items: start; }
    .rv-photo img, .rv-initials { width: 240px; height: 240px; border-radius: 50%; object-fit: cover; display: block; }
    .rv-initials { display: grid; place-items: center; background: #006e72; color: #fff; font-size: 4.5rem; font-weight: 600; letter-spacing: .02em; }
    .rv-body h1 { font-size: clamp(2rem, 1.5rem + 2vw, 3rem); line-height: 1.15; margin: 0 0 8px; }
    .rv-title { font-size: 1.15rem; color: #006e72; font-weight: 600; margin: 0 0 28px; }
    .rv-facts { margin: 0 0 24px; border-top: 1px solid #d9e4e4; }
    .rv-facts div { display: grid; grid-template-columns: 160px minmax(0, 1fr); gap: 16px; padding: 14px 0; border-bottom: 1px solid #d9e4e4; }
    .rv-facts dt { font-weight: 600; color: #0e2427; }
    .rv-facts dd { margin: 0; color: #3c5457; }
    .rv-bio { font-size: 1.08rem; line-height: 1.7; color: #0e2427; max-width: 65ch; }
    .rv-linkedin a { color: #006e72; text-decoration: underline; text-underline-offset: 3px; font-weight: 500; }
    .rv-contact { margin-top: 64px; padding: 32px; border-radius: 16px; background: #f3f7f7; }
    .rv-contact h2 { font-size: 1.5rem; margin: 0 0 8px; }
    .rv-contact p { margin: 0 0 18px; color: #3c5457; max-width: 60ch; }
    .rv-actions { display: flex; flex-wrap: wrap; gap: 12px; }
    .rv-btn { display: inline-flex; align-items: center; min-height: 46px; padding: 0 22px; border-radius: 999px; font-weight: 600; font-size: .95rem; text-decoration: none; border: 1px solid transparent; cursor: pointer; }
    .rv-btn-primary { background: #006e72; color: #fff; }
    .rv-btn-primary:hover { background: #00474e; color: #fff; }
    .rv-btn-ghost { background: transparent; border-color: #0e2427; color: #0e2427; }
    .rv-btn-ghost:hover { background: #0e2427; color: #fff; }
    @media (max-width: 767px) {
        .rv-page { padding-top: 110px; }
        .rv-grid { grid-template-columns: 1fr; gap: 24px; }
        .rv-photo img, .rv-initials { width: 140px; height: 140px; font-size: 2.8rem; }
        .rv-facts div { grid-template-columns: 1fr; gap: 4px; }
    }
</style>
@endsection

@push('inquiry_modal')
    @include('front.partials.inquiry-modal')
@endpush
