@extends('front/layout-2')

@section('content')
<style>
    .ft-page { background: #f8fafc; padding: 56px 0 84px; font-family: 'Outfit', sans-serif; }
    .ft-section { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .ft-section + .ft-section { margin-top: 54px; }
    .ft-section-head { margin-bottom: 22px; border-bottom: 1px solid #e7ebee; padding-bottom: 16px; }
    .ft-section-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 0.7rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
        color: #066D77; margin-bottom: 8px;
    }
    .ft-section-title {
        font-family: 'Libre Baskerville', Georgia, serif;
        font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 700; color: #0B1F3A; margin: 0; line-height: 1.2;
    }
    .ft-section-count { font-size: 0.85rem; color: #6b7a82; margin-top: 6px; }

    /* Service cards — same style as the category page (.svc-pill) + dual CTA */
    .svc-pill-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); grid-auto-rows: 1fr; gap: 18px; align-items: stretch; }
    .svc-pill {
        display: flex; gap: 16px; align-items: stretch; height: 100%;
        background: #fff; border: 1px solid #eef0f2; border-radius: 14px; overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.04); min-height: 196px;
        transition: transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s cubic-bezier(.22,1,.36,1), border-color .35s ease;
    }
    .svc-pill:hover { transform: translateY(-4px); box-shadow: 0 16px 38px rgba(6,109,119,0.14); border-color: #cfe6e8; }
    .svc-pill-media { flex: 0 0 130px; width: 130px; overflow: hidden; background: #f3f5f6; }
    .svc-pill-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s cubic-bezier(.22,1,.36,1); }
    .svc-pill:hover .svc-pill-media img { transform: scale(1.07); }
    .svc-pill-body { flex: 1; min-width: 0; padding: 16px 18px 16px 0; display: flex; flex-direction: column; }
    .svc-pill-title {
        font-size: 1rem; font-weight: 700; color: #16242a; line-height: 1.32; margin: 0 0 8px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .svc-pill-desc {
        font-size: .82rem; color: #5b6b72; line-height: 1.5; margin: 0 0 14px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .svc-pill-actions { margin-top: auto; display: flex; flex-wrap: wrap; gap: 8px; }
    .svc-pill-more, .svc-pill-inquire {
        display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
        font-size: .74rem; font-weight: 600; letter-spacing: .2px;
        padding: 8px 14px; border-radius: 8px; text-decoration: none; line-height: 1;
        transition: background .25s ease, color .25s ease, border-color .25s ease, transform .25s ease;
    }
    .svc-pill-more { color: #066D77; border: 1px solid #d3e6e7; background: transparent; }
    .svc-pill-more i { font-size: .66rem; transition: transform .3s ease; }
    .svc-pill-more:hover { background: #066D77; color: #fff; border-color: #066D77; }
    .svc-pill-more:hover i { transform: translateX(3px); }
    .svc-pill-inquire { color: #fff; border: 1px solid #066D77; background: #066D77; }
    .svc-pill-inquire:hover { background: #08818c; transform: translateY(-1px); }
    .ft-empty { text-align: center; padding: 60px 20px; color: #9aa5ad; }

    @media (max-width: 768px) {
        .svc-pill-grid { grid-template-columns: 1fr; }
        .svc-pill-media { flex-basis: 104px; width: 104px; }
    }
</style>

@include('front.partials.page-hero', [
    'heroEyebrow' => 'Tailored to Your Facility',
    'heroTitle'   => 'Services by',
    'heroSubtitle'=> 'Facility Type',
    'heroDesc'    => 'Browse our healthcare consultancy services grouped by the type of facility you operate — from hospitals and medical centers to pharmacies, labs, and telehealth.',
    'breadcrumb'  => ['Home' => route('home'), 'All Services' => route('front.all-services'), 'By Facility Type' => null],
])

<div class="ft-page">
    @forelse($facilityTypes as $ft)
        <section class="ft-section">
            <div class="ft-section-head">
                <span class="ft-section-eyebrow"><i class="fa-solid fa-hospital"></i> Facility Type</span>
                <h2 class="ft-section-title">{{ $ft->name }}</h2>
                <div class="ft-section-count">{{ $ft->services->count() }} {{ Str::plural('service', $ft->services->count()) }} available</div>
            </div>

            <div class="svc-pill-grid">
                @foreach($ft->services as $svc)
                    @php
                        $svcImg = $svc->hero_image
                            ? asset('public/uploads/service_images/' . $svc->hero_image)
                            : asset('public/front/assets/img/hero/service-details-bg.jpg');
                        $svcDesc = trim(strip_tags($svc->description ?? ($svc->overview ?? '')));
                    @endphp
                    <div class="svc-pill">
                        <div class="svc-pill-media">
                            <img src="{{ $svcImg }}" alt="{{ $svc->name }}" loading="lazy">
                        </div>
                        <div class="svc-pill-body">
                            <h3 class="svc-pill-title">{{ $svc->name }}</h3>
                            @if($svcDesc !== '')
                                <p class="svc-pill-desc">{{ Str::limit($svcDesc, 160) }}</p>
                            @endif
                            <div class="svc-pill-actions">
                                <a href="{{ route('front.service', $svc->slug) }}" class="svc-pill-more">
                                    Explore Services <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <button type="button" class="svc-pill-inquire ft-inquiry"
                                        data-service="{{ $svc->name }}" data-id="{{ $svc->id }}">
                                    <i class="fa-regular fa-calendar-check"></i> Inquire Now
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="ft-section">
            <div class="ft-empty">No facility-type services available yet. Please connect services to the “By Facility Type” categories from the dashboard.</div>
        </div>
    @endforelse
</div>
@endsection

@push('inquiry_modal')
    @include('front.partials.inquiry-modal')
@endpush

@push('scripts')
<script>
    // "Inquire Now" → default Book a Consultation modal with the chosen service pre-filled.
    (function () {
        function openInquiry(serviceName, serviceId) {
            if (typeof window.ahgPrefillInquiry === 'function') {
                window.ahgPrefillInquiry({ serviceId: serviceId, serviceName: serviceName });
            }
            var modalEl = document.getElementById('inquiryModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else if (modalEl && window.jQuery) {
                window.jQuery(modalEl).modal('show');
            }
        }
        document.body.addEventListener('click', function (e) {
            var btn = e.target.closest('.ft-inquiry');
            if (!btn) return;
            e.preventDefault();
            openInquiry(btn.getAttribute('data-service'), btn.getAttribute('data-id'));
        });
    })();
</script>
@endpush
