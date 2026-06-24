{{-- ============================================================
     Reusable global inquiry modal.
     Use on any page: @push('inquiry_modal') @include('front.partials.inquiry-modal') @endpush
     Trigger from anywhere: data-bs-toggle="modal" data-bs-target="#inquiryModal"
     IDs (#inquiryModal / #inquiryForm) match the site's conversion tracking,
     so ahg_inquiry_opened / ahg_inquiry_submitted fire automatically.
     ============================================================ --}}
@once
@php
    $inqServices = \App\Models\Service::published()->orderBy('name')->get(['id', 'name']);

    // Use the page-level unified WA number set by layout-2; fall back to AppSetting.
    $waLink    = $pageWaLink ?? ('https://wa.me/' .
        (\App\Models\AppSetting::where('key','whatsapp_default_number')->value('value') ?? '97142724064') .
        '?text=' . rawurlencode("Hi, I'd like to enquire about Alpha Health Group's services."));
    $rcSiteKey = env('RECAPTCHA_V3_SITE_KEY', '');
@endphp

{{-- Optional: load reCAPTCHA v3 only when a key is configured --}}
@if($rcSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $rcSiteKey }}" async defer></script>
@endif

<div class="modal fade ahg-inquiry-modal" id="inquiryModal"
     tabindex="-1"
     aria-labelledby="inquiryModalTitle"
     aria-hidden="true"
     data-recaptcha-key="{{ $rcSiteKey }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close ahg-im-close" data-bs-dismiss="modal" aria-label="Close"></button>

            {{-- ── WhatsApp-first CTA (Priority 1) ── --}}
            <div id="ahgWaBanner" class="ahg-wa-banner">
                <div class="ahg-wa-banner-body">
                    <span class="ahg-wa-banner-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 1.895.523 3.665 1.432 5.18L2 22l4.981-1.398A9.944 9.944 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 01-4.073-1.112l-.292-.17-3.017.847.848-3.073-.186-.303A7.955 7.955 0 014 12c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8zm4.29-5.835c-.232-.116-1.373-.677-1.585-.754-.213-.077-.367-.116-.522.116-.155.232-.598.754-.733.91-.135.154-.27.174-.502.058-.232-.116-.98-.361-1.867-1.15-.69-.615-1.156-1.375-1.292-1.607-.135-.232-.015-.358.101-.474.104-.104.232-.27.348-.406.116-.135.154-.232.232-.386.077-.155.038-.29-.019-.406-.058-.116-.522-1.259-.715-1.722-.188-.45-.38-.389-.522-.396l-.444-.008a.852.852 0 00-.618.29c-.212.232-.81.79-.81 1.926 0 1.136.83 2.233.945 2.388.116.155 1.634 2.494 3.96 3.498.553.24 1.03.382 1.382.49.58.18 1.107.154 1.524.093.465-.069 1.373-.56 1.567-1.1.193-.54.193-1.004.135-1.1-.058-.096-.213-.155-.445-.27z"/></svg>
                    </span>
                    <div class="ahg-wa-banner-text">
                        <span class="ahg-wa-banner-title">Prefer an instant reply?</span>
                        <span class="ahg-wa-banner-sub">Chat directly with our consultants</span>
                    </div>
                    <a href="{{ $waLink }}" class="ahg-wa-cta" target="_blank" rel="noopener" data-wa-link>
                        Chat on WhatsApp
                    </a>
                </div>
            </div>

            <div id="ahgOrDivider" class="ahg-or-divider">
                <span>or fill the form for a scheduled consultation</span>
            </div>

            {{-- ── Modal header ── --}}
            <div class="ahg-im-head">
                <h2 class="ahg-im-title" id="inquiryModalTitle">Book a Consultation</h2>
                <p class="ahg-im-sub">Share a few details and our healthcare consulting team will get back to you shortly.</p>
            </div>

            <div class="ahg-im-alert d-none" id="inquiryAlert" role="alert"></div>

            {{-- ── Inquiry form ── --}}
            <form id="inquiryForm" action="{{ route('front.inquiry.submit') }}" method="POST" novalidate>
                @csrf

                {{-- Honeypot: bots fill this, humans never see it --}}
                <div class="ahg-hp-trap" aria-hidden="true">
                    <label for="ahg_website_url">Website</label>
                    <input type="text" id="ahg_website_url" name="website_url"
                           tabindex="-1" autocomplete="off" placeholder="https://">
                </div>

                {{-- reCAPTCHA v3 token (populated by JS before submit) --}}
                <input type="hidden" id="im-recaptcha-token" name="g-recaptcha-response" value="">

                <div class="ahg-im-grid">
                    <div class="ahg-im-field">
                        <label for="im-name">Full Name <span>*</span></label>
                        <input type="text" id="im-name" name="name" required autocomplete="name" placeholder="Your name">
                    </div>
                    <div class="ahg-im-field">
                        <label for="im-phone">Mobile Number <span id="im-phone-star">*</span></label>
                        <input type="tel" id="im-phone" name="phone" required autocomplete="tel" placeholder="+971 50 000 0000">
                    </div>
                    <div class="ahg-im-field ahg-im-col2">
                        <label for="im-email">Email <span id="im-email-star" style="display:none;color:#e11d48">*</span><span id="im-email-opt" class="ahg-im-opt">(optional)</span></label>
                        <input type="email" id="im-email" name="email" autocomplete="email" placeholder="you@provider.com">
                    </div>
                    <div class="ahg-im-field ahg-im-col2">
                        <label for="im-service">Service <span class="ahg-im-opt">(optional)</span></label>
                        <select id="im-service" name="service_id">
                            <option value="">Not sure / general enquiry</option>
                            @foreach ($inqServices as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- ── Schedule toggle ── --}}
                    <div class="ahg-im-col2 ahg-sched-row">
                        <label class="ahg-sched-label" for="ahgSchedCheck">
                            <span class="ahg-sched-sw">
                                <input type="checkbox" id="ahgSchedCheck" style="position:absolute;opacity:0;width:0;height:0;">
                                <span class="ahg-sched-knob"></span>
                            </span>
                            <span class="ahg-sched-text">Schedule a meeting</span>
                        </label>
                    </div>

                    {{-- ── Date + time (hidden by default, !important removed so display:none works) ── --}}
                    <div class="ahg-im-field ahg-im-col2 ahg-im-datetime" id="ahgSchedFields" style="display:none;">
                        <div>
                            <label for="im-meeting-date">Preferred date</label>
                            <input type="date" id="im-meeting-date" name="meeting_date">
                        </div>
                        <div>
                            <label for="im-meeting-time">Preferred time</label>
                            <input type="time" id="im-meeting-time" name="meeting_time" value="10:00">
                        </div>
                    </div>
                    <div class="ahg-im-field ahg-im-col2">
                        <label for="im-message">Message <span class="ahg-im-opt">(optional)</span></label>
                        <textarea id="im-message" name="message" rows="2" placeholder="Tell us briefly about your facility or requirement..."></textarea>
                    </div>
                </div>
                <div class="ahg-im-consent">
                    <label class="ahg-im-check">
                        <input type="checkbox" id="im-consent" name="consent" value="1" required>
                        <span>I agree to be contacted by Alpha Health Group regarding this inquiry and accept the
                            <a href="{{ url('/alpha-privacy-policy') }}" target="_blank" rel="noopener">privacy policy</a>.</span>
                    </label>
                </div>
                <button type="submit" class="ahg-im-submit" id="inquirySubmitBtn">
                    <span class="ahg-im-submit-label">Submit Inquiry</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            {{-- ── Thank-you panel (shown after successful submission) ── --}}
            <div id="inqThanks" class="ahg-im-thanks d-none">
                <div class="ahg-im-thanks-check">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3 class="ahg-im-thanks-title">Inquiry Received!</h3>
                <p class="ahg-im-thanks-msg">Our consulting team will reach out within 1 business day. For a faster response, chat with us on WhatsApp.</p>
                <a href="{{ $waLink }}" class="ahg-wa-big-btn" target="_blank" rel="noopener" data-wa-link>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 1.895.523 3.665 1.432 5.18L2 22l4.981-1.398A9.944 9.944 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 01-4.073-1.112l-.292-.17-3.017.847.848-3.073-.186-.303A7.955 7.955 0 014 12c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8zm4.29-5.835c-.232-.116-1.373-.677-1.585-.754-.213-.077-.367-.116-.522.116-.155.232-.598.754-.733.91-.135.154-.27.174-.502.058-.232-.116-.98-.361-1.867-1.15-.69-.615-1.156-1.375-1.292-1.607-.135-.232-.015-.358.101-.474.104-.104.232-.27.348-.406.116-.135.154-.232.232-.386.077-.155.038-.29-.019-.406-.058-.116-.522-1.259-.715-1.722-.188-.45-.38-.389-.522-.396l-.444-.008a.852.852 0 00-.618.29c-.212.232-.81.79-.81 1.926 0 1.136.83 2.233.945 2.388.116.155 1.634 2.494 3.96 3.498.553.24 1.03.382 1.382.49.58.18 1.107.154 1.524.093.465-.069 1.373-.56 1.567-1.1.193-.54.193-1.004.135-1.1-.058-.096-.213-.155-.445-.27z"/></svg>
                    Get faster updates on WhatsApp
                </a>
                <button class="ahg-im-newreq-btn" id="inqResetBtn">Submit another inquiry</button>
            </div>

        </div>
    </div>
</div>

<style>
    .ahg-inquiry-modal { z-index: 10600; }
    .ahg-inquiry-modal .modal-dialog { max-width: 540px; }
    .ahg-inquiry-modal .modal-content {
        border: none; border-radius: 16px; position: relative;
        padding: 22px 28px 22px; box-shadow: 0 24px 70px rgba(6,38,42,0.26);
        max-height: calc(100dvh - 24px); overflow-y: auto; -webkit-overflow-scrolling: touch;
    }
    /* Perfectly round close button — override Bootstrap padding/sizing */
    .ahg-im-close {
        position: sticky; top: 0; float: right; margin: -4px -6px 0 0; z-index: 5;
        width: 32px; height: 32px; min-width: 32px; padding: 0 !important;
        border-radius: 50% !important; opacity: 1; flex-shrink: 0;
        background: #f1f5f9 url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%2314252a' d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center / 12px auto no-repeat;
        box-shadow: none; border: none;
    }
    .ahg-im-close:hover { background-color: #e2e8f0; }

    /* ── WhatsApp banner — flat teal, no gradient ── */
    .ahg-wa-banner {
        background: #066D77;
        border-radius: 10px;
        padding: 11px 14px;
        margin-bottom: 0;
    }
    .ahg-wa-banner-body { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .ahg-wa-banner-icon { color: #fff; flex-shrink: 0; display: flex; align-items: center; }
    .ahg-wa-banner-icon svg { width: 24px; height: 24px; }
    .ahg-wa-banner-text { flex: 1; min-width: 0; }
    .ahg-wa-banner-title { display: block; font-weight: 700; font-size: .84rem; color: #fff; line-height: 1.3; }
    .ahg-wa-banner-sub   { display: block; font-size: .73rem; color: rgba(255,255,255,.82); margin-top: 1px; }
    .ahg-wa-cta {
        margin-left: auto; background: #fff; color: #066D77 !important;
        font-weight: 700; font-size: .78rem; padding: 6px 14px;
        border-radius: 100px; text-decoration: none; white-space: nowrap;
        display: inline-flex; align-items: center; gap: 5px; flex-shrink: 0;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 2px 8px rgba(0,0,0,.12);
    }
    .ahg-wa-cta:hover { transform: scale(1.04); box-shadow: 0 4px 12px rgba(0,0,0,.18); }

    /* ── Divider ── */
    .ahg-or-divider {
        text-align: center; margin: 10px 0 2px; position: relative;
        color: #94a3b8; font-size: .72rem;
    }
    .ahg-or-divider::before {
        content: ''; position: absolute; top: 50%; left: 0; right: 0;
        height: 1px; background: #e2e8f0;
    }
    .ahg-or-divider span { background: #fff; padding: 0 10px; position: relative; }

    /* ── Head ── */
    .ahg-im-head { margin-bottom: 14px; margin-top: 10px; }
    .ahg-im-eyebrow {
        display: inline-block; text-transform: uppercase; font-size: .67rem; font-weight: 700;
        letter-spacing: 1.4px; color: #066D77; background: rgba(6,109,119,.08);
        padding: 3px 12px; border-radius: 100px; margin-bottom: 8px;
    }
    .ahg-im-title { font-family: 'Libre Baskerville', serif; font-size: 1.4rem; color: #14252a; margin: 0 0 4px; }
    .ahg-im-sub { font-size: .84rem; color: #5b6b73; line-height: 1.5; margin: 0; }

    /* ── Form grid ── */
    .ahg-im-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px; }
    .ahg-im-col2 { grid-column: 1 / -1; }

    /* ── Date+time always side-by-side (no !important so display:none can hide it) ── */
    .ahg-im-datetime { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .ahg-im-datetime > div { display: flex; flex-direction: column; }

    /* ── Schedule meeting toggle switch ── */
    .ahg-sched-row { display: flex; align-items: center; margin-top: -2px; }
    .ahg-sched-label { display: inline-flex; align-items: center; gap: 9px; cursor: pointer; user-select: none; }
    .ahg-sched-text { font-size: .78rem; font-weight: 600; color: #5b6b73; transition: color .2s; }
    .ahg-sched-label:hover .ahg-sched-text { color: #066D77; }
    .ahg-sched-sw {
        position: relative; display: inline-block;
        width: 34px; height: 18px; flex-shrink: 0;
    }
    .ahg-sched-knob {
        position: absolute; inset: 0; border-radius: 18px;
        background: #cbd5e1; transition: background .2s;
    }
    .ahg-sched-knob::after {
        content: ''; position: absolute; top: 2px; left: 2px;
        width: 14px; height: 14px; border-radius: 50%;
        background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2);
        transition: transform .2s;
    }
    #ahgSchedCheck:checked ~ .ahg-sched-knob { background: #066D77; }
    #ahgSchedCheck:checked ~ .ahg-sched-knob::after { transform: translateX(16px); }

    /* ── Fields ── */
    .ahg-im-field label { display: block; font-size: .77rem; font-weight: 600; color: #14252a; margin-bottom: 4px; }
    .ahg-im-field label span { color: #e11d48; }
    .ahg-im-field label .ahg-im-opt { color: #94a3b8; font-weight: 400; }
    .ahg-im-field input, .ahg-im-field select, .ahg-im-field textarea,
    .ahg-im-datetime input {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 9px 12px;
        font-size: .88rem; color: #14252a; background: #fff; transition: border-color .2s, box-shadow .2s;
    }
    .ahg-im-field input:focus, .ahg-im-field select:focus, .ahg-im-field textarea:focus,
    .ahg-im-datetime input:focus {
        outline: none; border-color: #066D77; box-shadow: 0 0 0 3px rgba(6,109,119,.1);
    }
    .ahg-im-field textarea { resize: vertical; min-height: 52px; }

    /* ── Submit ── */
    .ahg-im-submit {
        display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer;
        background: #066D77; color: #fff; font-weight: 700; font-size: .88rem; letter-spacing: .3px;
        padding: 11px 24px; border-radius: 100px; transition: background .25s, box-shadow .25s, transform .2s;
        box-shadow: 0 8px 20px rgba(6,109,119,.26);
    }
    .ahg-im-submit:hover { background: #055863; box-shadow: 0 12px 26px rgba(6,109,119,.36); }
    .ahg-im-submit:disabled { opacity: .65; cursor: not-allowed; }
    .ahg-im-submit i { font-size: .76rem; transition: transform .3s; }
    .ahg-im-submit:hover i { transform: translateX(4px); }

    /* ── Consent ── */
    .ahg-im-consent { margin-bottom: 14px; }
    .ahg-im-check { display: flex; align-items: flex-start; gap: 9px; font-size: .78rem; color: #5b6b73; line-height: 1.45; cursor: pointer; }
    .ahg-im-check input { width: 16px; height: 16px; margin: 2px 0 0; flex-shrink: 0; accent-color: #066D77; cursor: pointer; }
    .ahg-im-check a { color: #066D77; text-decoration: underline; }

    /* ── Alert ── */
    .ahg-im-alert { border-radius: 8px; padding: 10px 14px; font-size: .86rem; margin-bottom: 14px; }
    .ahg-im-alert.is-success { background: #e6f9f0; color: #1a8a4a; border: 1px solid #a3e6c3; }
    .ahg-im-alert.is-error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* ── Honeypot trap ── */
    .ahg-hp-trap {
        position: absolute; left: -99999px; top: auto;
        width: 0; height: 0; overflow: hidden; opacity: 0; pointer-events: none;
    }

    /* ── Thank-you panel ── */
    .ahg-im-thanks { text-align: center; padding: 12px 8px 8px; }
    .ahg-im-thanks-check {
        width: 56px; height: 56px; background: #e6f9f0; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px; color: #1a8a4a;
    }
    .ahg-im-thanks-title { font-family: 'Libre Baskerville', serif; font-size: 1.4rem; color: #14252a; margin: 0 0 8px; }
    .ahg-im-thanks-msg { font-size: .86rem; color: #5b6b73; margin: 0 0 22px; line-height: 1.6; }
    .ahg-wa-big-btn {
        display: inline-flex; align-items: center; gap: 9px;
        background: #25D366; color: #fff !important;
        font-weight: 700; font-size: .9rem;
        padding: 12px 24px; border-radius: 100px; text-decoration: none;
        margin-bottom: 12px; box-shadow: 0 6px 18px rgba(37,211,102,.32);
        transition: transform .2s, box-shadow .2s;
    }
    .ahg-wa-big-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(37,211,102,.42); }
    .ahg-im-newreq-btn {
        display: block; background: none; border: none;
        color: #94a3b8; font-size: .78rem; cursor: pointer;
        text-decoration: underline; margin: 6px auto 0; padding: 0;
    }

    /* ── Mobile ── */
    @media (max-width: 575px) {
        .ahg-inquiry-modal .modal-content { padding: 16px 15px 18px; max-height: calc(100dvh - 12px); border-radius: 14px; }
        .ahg-im-grid { grid-template-columns: 1fr; gap: 10px; margin-bottom: 12px; }
        .ahg-im-datetime { grid-template-columns: 1fr 1fr !important; gap: 8px; } /* always 2-col */
        .ahg-im-head { margin-bottom: 12px; margin-top: 8px; }
        .ahg-im-title { font-size: 1.2rem; }
        .ahg-im-sub { font-size: .82rem; }
        .ahg-im-submit { width: 100%; justify-content: center; }
        .ahg-wa-banner-body { gap: 8px; }
        .ahg-wa-cta { margin-left: 0; width: 100%; justify-content: center; }
    }
</style>

<script>
(function () {
    var modalEl  = document.getElementById('inquiryModal');
    var form     = document.getElementById('inquiryForm');
    var alertBox = document.getElementById('inquiryAlert');
    var btn      = document.getElementById('inquirySubmitBtn');
    var label    = btn ? btn.querySelector('.ahg-im-submit-label') : null;
    var thanksEl = document.getElementById('inqThanks');
    var waBanner = document.getElementById('ahgWaBanner');
    var orDiv    = document.getElementById('ahgOrDivider');
    var imHead   = modalEl ? modalEl.querySelector('.ahg-im-head') : null;
    var navEl    = document.getElementById('main-navbar');

    // ── Navbar z-index ──────────────────────────────────────
    if (modalEl && navEl && !modalEl.dataset.navBound) {
        modalEl.dataset.navBound = '1';
        modalEl.addEventListener('show.bs.modal',  function () { navEl.style.zIndex = '1000'; });
        modalEl.addEventListener('hidden.bs.modal', function () { navEl.style.zIndex = ''; });
    }

    // ── Show/hide helpers ────────────────────────────────────
    function showThanks() {
        if (form)     form.style.display     = 'none';
        if (waBanner) waBanner.style.display = 'none';
        if (orDiv)    orDiv.style.display    = 'none';
        if (imHead)   imHead.style.display   = 'none';
        if (alertBox) alertBox.className     = 'ahg-im-alert d-none';
        if (thanksEl) thanksEl.classList.remove('d-none');
    }

    window.ahgResetInqModal = function () {
        if (form)     form.style.display     = '';
        if (waBanner) waBanner.style.display = '';
        if (orDiv)    orDiv.style.display    = '';
        if (imHead)   imHead.style.display   = '';
        if (alertBox) alertBox.className     = 'ahg-im-alert d-none';
        if (thanksEl) thanksEl.classList.add('d-none');
        sessionStorage.removeItem('ahg_inq_sent');
        // Reset either/or required state back to default (phone required)
        if (typeof syncRequired === 'function') syncRequired();
        // Reset schedule toggle to hidden
        if (schedCheck)  schedCheck.checked = false;
        if (schedFields) schedFields.style.display = 'none';
    };

    // "Submit another inquiry" button
    var resetBtn = document.getElementById('inqResetBtn');
    if (resetBtn) resetBtn.addEventListener('click', window.ahgResetInqModal);

    // ── On modal open: show thanks if already submitted ──────
    if (modalEl && !modalEl.dataset.alertBound) {
        modalEl.dataset.alertBound = '1';
        modalEl.addEventListener('show.bs.modal', function () {
            if (sessionStorage.getItem('ahg_inq_sent')) {
                showThanks();
            } else {
                window.ahgResetInqModal();
            }
        });
    }

    // ── Either phone OR email required — dynamic ─────────────
    var phoneInput  = document.getElementById('im-phone');
    var emailInput  = document.getElementById('im-email');
    var phoneStar   = document.getElementById('im-phone-star');
    var emailStar   = document.getElementById('im-email-star');
    var emailOpt    = document.getElementById('im-email-opt');

    function syncRequired() {
        var hasEmail = emailInput && emailInput.value.trim() !== '';
        var hasPhone = phoneInput && phoneInput.value.trim() !== '';

        // Phone: required only when email is empty
        if (phoneInput) phoneInput.required = !hasEmail;
        if (phoneStar)  phoneStar.style.display  = hasEmail ? 'none' : '';

        // Email: required only when phone is empty
        if (emailInput) emailInput.required = !hasPhone;
        if (emailStar)  emailStar.style.display  = (!hasPhone && hasEmail) ? '' : (hasPhone ? 'none' : 'none');
        if (emailOpt)   emailOpt.style.display   = hasPhone ? '' : (hasEmail ? 'none' : '');
    }

    // Phone format validation: must start with + followed by digits
    function validatePhone() {
        if (!phoneInput) return;
        var val = phoneInput.value.trim();
        if (val && !/^\+[\d\s\-]{7,17}$/.test(val)) {
            phoneInput.setCustomValidity('Enter an international number starting with + (e.g. +971 50 000 0000)');
        } else {
            phoneInput.setCustomValidity('');
        }
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function () { validatePhone(); syncRequired(); });
        phoneInput.addEventListener('blur',  validatePhone);
    }
    if (emailInput) emailInput.addEventListener('input', syncRequired);

    // ── Min date ─────────────────────────────────────────────
    var mDate = document.getElementById('im-meeting-date');
    if (mDate) mDate.min = new Date().toISOString().split('T')[0];

    // ── Schedule meeting toggle ───────────────────────────────
    var schedCheck  = document.getElementById('ahgSchedCheck');
    var schedFields = document.getElementById('ahgSchedFields');

    if (schedCheck && schedFields) {
        schedCheck.addEventListener('change', function () {
            schedFields.style.display = this.checked ? '' : 'none';
            // Clear values when hiding
            if (!this.checked) {
                var dInp = document.getElementById('im-meeting-date');
                var tInp = document.getElementById('im-meeting-time');
                if (dInp) dInp.value = '';
                if (tInp) tInp.value = '10:00';
            }
        });
    }

    // ── Global prefill helper ────────────────────────────────
    window.ahgPrefillInquiry = function (opts) {
        opts = opts || {};
        var svc = document.getElementById('im-service');
        var msg = document.getElementById('im-message');

        if (svc) {
            var oldCat = svc.querySelector('option[data-ahg-cat]');
            if (oldCat) oldCat.remove();
        }
        if (opts.serviceId && svc) {
            var found = Array.prototype.some.call(svc.options, function (o) { return o.value == opts.serviceId; });
            if (found) svc.value = String(opts.serviceId);
        }
        if (!opts.serviceId && opts.categoryName) {
            var lbl = opts.contextLabel || 'category';
            if (svc) {
                var opt = document.createElement('option');
                opt.value = ''; opt.text = opts.categoryName + ' (' + lbl + ')';
                opt.setAttribute('data-ahg-cat', '1');
                svc.insertBefore(opt, svc.options[1] || null);
                opt.selected = true;
            }
            if (msg) {
                var note = 'I am interested in: ' + opts.categoryName + ' (' + lbl + ').';
                if (msg.value.indexOf(note) === -1) {
                    msg.value = note + (msg.value ? '\n' + msg.value : '');
                }
            }
        }
        // Optional: override WhatsApp number for a specific service's agent
        if (opts.waPhone) {
            document.querySelectorAll('[data-wa-link]').forEach(function (el) {
                var h = el.getAttribute('href') || '';
                el.setAttribute('href', h.replace(/wa\.me\/[0-9]+/, 'wa.me/' + opts.waPhone.replace(/[^0-9]/g, '')));
            });
        }
    };

    // ── reCAPTCHA v3 token helper ────────────────────────────
    function getRecaptchaToken() {
        var key = modalEl && modalEl.dataset.recaptchaKey;
        if (!key || typeof grecaptcha === 'undefined') return Promise.resolve('');
        return new Promise(function (resolve) {
            grecaptcha.ready(function () {
                grecaptcha.execute(key, { action: 'inquiry' })
                    .then(resolve)
                    .catch(function () { resolve(''); });
            });
        });
    }

    // ── showAlert ────────────────────────────────────────────
    function showAlert(type, msg) {
        if (!alertBox) return;
        alertBox.className = 'ahg-im-alert is-' + type;
        alertBox.textContent = msg;
    }

    // ── Form submit ──────────────────────────────────────────
    if (!form || form.dataset.bound) return;
    form.dataset.bound = '1';

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var consent = document.getElementById('im-consent');
        if (consent && !consent.checked) {
            showAlert('error', 'Please tick the consent box so we can contact you.');
            return;
        }
        if (alertBox) alertBox.className = 'ahg-im-alert d-none';
        if (btn)  { btn.disabled = true; if (label) label.textContent = 'Submitting...'; }

        getRecaptchaToken().then(function (token) {
            var rcInput = document.getElementById('im-recaptcha-token');
            if (rcInput) rcInput.value = token;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
            .then(function (res) {
                if (res.ok && res.d.success) {
                    // Conversion tracking
                    if (typeof gtag === 'function') gtag('event', 'ahg_inquiry_submitted', { form_id: 'inquiryForm' });
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({ event: 'ahg_inquiry_submitted', form_id: 'inquiryForm' });
                    window.dataLayer.push({ event: 'inquiry_sent' });
                    // Google Ads conversion — fires only on confirmed AJAX success
                    if (typeof gtagReportInquirySent === 'function') gtagReportInquirySent();

                    // Lock this session against re-submission
                    sessionStorage.setItem('ahg_inq_sent', '1');
                    form.reset();

                    // Show thank-you panel — do NOT auto-close (eliminates re-open confusion)
                    showThanks();
                } else {
                    var msg = res.d.message || 'Please check the form and try again.';
                    if (res.d.errors) msg = Object.values(res.d.errors).map(function (v) { return v[0]; }).join(' ');
                    showAlert('error', msg);
                    if (btn) { btn.disabled = false; if (label) label.textContent = 'Submit Inquiry'; }
                }
            })
            .catch(function () {
                showAlert('error', 'Something went wrong. Please try again or contact us directly.');
                if (btn) { btn.disabled = false; if (label) label.textContent = 'Submit Inquiry'; }
            });
        });
    });
})();
</script>
@endonce
