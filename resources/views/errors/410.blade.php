@extends('front/layout-2')

{{-- 410 Gone for pages removed on purpose (old URLs brief, 22 Oct 2026). abort(410, 'job') shows the
     job version; any other message shows the removed-page version. Both offer WhatsApp. --}}
@php
    $isJob = isset($exception) && $exception->getMessage() === 'job';
    $goneTitle = $isJob ? 'This job is no longer open' : 'This page has been removed';
    $goneText = $isJob
        ? 'The role you were looking for has closed. See our healthcare professional resourcing services, or message our team on WhatsApp.'
        : 'Alpha Health Group no longer offers this service on this site. See all our healthcare consultancy services, or message our team on WhatsApp.';
    $goneLink = $isJob
        ? ['url' => route('front.service-category', 'healthcare-professional-resourcing'), 'label' => 'View resourcing services']
        : ['url' => route('front.all-services'), 'label' => 'View all services'];
    $goneWa = \App\Support\WaContext::link();
@endphp

@push('page_title', $goneTitle . ' | Alpha Health Group')
@push('meta')
    <meta name="robots" content="noindex">
@endpush

@section('custom_css')
    @include('errors.partials.styles')
    <style>.error-section { --error-mark: '410'; }</style>
@endSection

@section('content')

    <section class="error-section">
        <div class="container">
            <div class="error-inner">

                <div class="error-icon-wrap">
                    <i class="fa-solid {{ $isJob ? 'fa-briefcase' : 'fa-circle-info' }}"></i>
                </div>

                <h1 class="error-title">{{ $goneTitle }}</h1>
                <div class="error-divider"></div>
                <p class="error-desc">{{ $goneText }}</p>

                <div class="error-actions">
                    <a href="{{ $goneLink['url'] }}" class="btn-primary-brand">
                        {{ $goneLink['label'] }}
                    </a>
                    <a href="{{ $goneWa }}" class="btn-outline-brand" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp"></i>
                        Message us on WhatsApp
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
