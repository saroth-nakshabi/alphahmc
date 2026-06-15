{{-- ============================================================
     Reusable global inquiry modal.
     Use on any page: @push('inquiry_modal') @include('front.partials.inquiry-modal') @endpush
     Trigger from anywhere: data-bs-toggle="modal" data-bs-target="#inquiryModal"
     IDs (#inquiryModal / #inquiryForm) match the site's conversion tracking,
     so ahg_inquiry_opened / ahg_inquiry_submitted fire automatically.
     ============================================================ --}}
@once
@php
    $inquiryServices = \App\Models\Service::published()->orderBy('name')->get(['id', 'name']);
@endphp

<div class="modal fade ahg-inquiry-modal" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close ahg-im-close" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="ahg-im-head">
                <span class="ahg-im-eyebrow">Get in touch</span>
                <h2 class="ahg-im-title" id="inquiryModalTitle">Book a Consultation</h2>
                <p class="ahg-im-sub">Share a few details and our healthcare consulting team will get back to you shortly.</p>
            </div>

            <div class="ahg-im-alert d-none" id="inquiryAlert" role="alert"></div>

            <form id="inquiryForm" action="{{ route('front.inquiry.submit') }}" method="POST" novalidate>
                @csrf
                <div class="ahg-im-grid">
                    <div class="ahg-im-field">
                        <label for="im-name">Full Name <span>*</span></label>
                        <input type="text" id="im-name" name="name" required autocomplete="name" placeholder="Your name">
                    </div>
                    <div class="ahg-im-field">
                        <label for="im-phone">Mobile Number <span>*</span></label>
                        <input type="tel" id="im-phone" name="phone" required autocomplete="tel" placeholder="e.g. +971 50 000 0000">
                    </div>
                    <div class="ahg-im-field ahg-im-col2">
                        <label for="im-email">Email <span>*</span></label>
                        <input type="email" id="im-email" name="email" required autocomplete="email" placeholder="you@company.com">
                    </div>
                    <div class="ahg-im-field ahg-im-col2">
                        <label for="im-service">Service <span class="ahg-im-opt">(optional)</span></label>
                        <select id="im-service" name="service_id">
                            <option value="">Not sure / general enquiry</option>
                            @foreach ($inquiryServices as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ahg-im-field">
                        <label for="im-meeting-date">Preferred date <span class="ahg-im-opt">(optional)</span></label>
                        <input type="date" id="im-meeting-date" name="meeting_date">
                    </div>
                    <div class="ahg-im-field">
                        <label for="im-meeting-time">Preferred time <span class="ahg-im-opt">(optional)</span></label>
                        <input type="time" id="im-meeting-time" name="meeting_time" value="10:00">
                    </div>
                    <div class="ahg-im-field ahg-im-col2">
                        <label for="im-message">Message <span class="ahg-im-opt">(optional)</span></label>
                        <textarea id="im-message" name="message" rows="3" placeholder="Tell us briefly about your facility or requirement..."></textarea>
                    </div>
                </div>
                <button type="submit" class="ahg-im-submit" id="inquirySubmitBtn">
                    <span class="ahg-im-submit-label">Submit Inquiry</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* sit above the floating navbar (z-index:9999) so the dialog/close are never covered */
    .ahg-inquiry-modal { z-index: 10600; }
    .ahg-inquiry-modal .modal-dialog { max-width: 560px; }
    .ahg-inquiry-modal .modal-content {
        border: none; border-radius: 18px; position: relative;
        padding: 30px 34px 30px; box-shadow: 0 30px 80px rgba(6,38,42,0.28);
        /* keep the dialog inside the viewport and scroll the form internally,
           so the close button stays reachable instead of overflowing off-screen */
        max-height: calc(100dvh - 24px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    .ahg-im-close {
        position: sticky; top: 0; float: right; margin: -6px -8px 0 0; z-index: 5;
        width: 38px; height: 38px; border-radius: 50%; background: #fff; opacity: 1;
        box-shadow: 0 2px 10px rgba(0,0,0,0.12);
    }
    .ahg-im-head { margin-bottom: 22px; }
    .ahg-im-eyebrow {
        display: inline-block; text-transform: uppercase; font-size: 0.7rem; font-weight: 700;
        letter-spacing: 1.6px; color: #066D77; background: rgba(6,109,119,0.08);
        padding: 5px 14px; border-radius: 100px; margin-bottom: 12px;
    }
    .ahg-im-title { font-family: 'Libre Baskerville', serif; font-size: 1.7rem; color: #14252a; margin: 0 0 8px; }
    .ahg-im-sub { font-size: 0.92rem; color: #5b6b73; line-height: 1.55; margin: 0; }
    .ahg-im-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 22px; }
    .ahg-im-col2 { grid-column: 1 / -1; }
    .ahg-im-field label { display: block; font-size: 0.8rem; font-weight: 600; color: #14252a; margin-bottom: 6px; }
    .ahg-im-field label span { color: #e11d48; }
    .ahg-im-field label .ahg-im-opt { color: #94a3b8; font-weight: 400; }
    .ahg-im-field input, .ahg-im-field select, .ahg-im-field textarea {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 12px 14px;
        font-size: 0.92rem; color: #14252a; background: #fff; transition: border-color .2s ease, box-shadow .2s ease;
    }
    .ahg-im-field input:focus, .ahg-im-field select:focus, .ahg-im-field textarea:focus {
        outline: none; border-color: #066D77; box-shadow: 0 0 0 3px rgba(6,109,119,0.12);
    }
    .ahg-im-field textarea { resize: vertical; min-height: 80px; }
    .ahg-im-submit {
        display: inline-flex; align-items: center; gap: 10px; border: none; cursor: pointer;
        background: #066D77; color: #fff; font-weight: 700; font-size: 0.92rem; letter-spacing: .3px;
        padding: 13px 28px; border-radius: 100px; transition: background .25s ease, box-shadow .25s ease, transform .2s ease;
        box-shadow: 0 10px 24px rgba(6,109,119,0.28);
    }
    .ahg-im-submit:hover { background: #055863; box-shadow: 0 14px 30px rgba(6,109,119,0.38); }
    .ahg-im-submit:disabled { opacity: .65; cursor: not-allowed; }
    .ahg-im-submit i { font-size: 0.8rem; transition: transform .3s ease; }
    .ahg-im-submit:hover i { transform: translateX(4px); }
    .ahg-im-alert { border-radius: 10px; padding: 12px 16px; font-size: 0.9rem; margin-bottom: 18px; }
    .ahg-im-alert.is-success { background: #e6f9f0; color: #1a8a4a; border: 1px solid #a3e6c3; }
    .ahg-im-alert.is-error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    @media (max-width: 575px) {
        .ahg-inquiry-modal .modal-content { padding: 22px 18px 22px; max-height: calc(100dvh - 16px); }
        .ahg-im-grid { grid-template-columns: 1fr; gap: 12px; margin-bottom: 16px; }
        .ahg-im-head { margin-bottom: 16px; }
        .ahg-im-title { font-size: 1.3rem; }
        .ahg-im-sub { font-size: 0.86rem; }
        .ahg-im-field input, .ahg-im-field select, .ahg-im-field textarea { padding: 10px 12px; }
        .ahg-im-field textarea { min-height: 60px; rows: 2; }
        .ahg-im-submit { width: 100%; justify-content: center; }
    }
</style>

<script>
(function () {
    // Drop the floating navbar (z-index:9999) below the modal backdrop while the
    // inquiry modal is open, so it never covers the dialog/close button.
    var modalEl = document.getElementById('inquiryModal');
    var navEl = document.getElementById('main-navbar');
    if (modalEl && navEl && !modalEl.dataset.navBound) {
        modalEl.dataset.navBound = '1';
        modalEl.addEventListener('show.bs.modal',   function () { navEl.style.zIndex = '1000'; });
        modalEl.addEventListener('hidden.bs.modal',  function () { navEl.style.zIndex = ''; });
    }

    // Don't let visitors pick a past consultation date.
    var mDate = document.getElementById('im-meeting-date');
    if (mDate) mDate.min = new Date().toISOString().split('T')[0];

    // Global prefill helper — pages can call this before opening the modal to
    // carry the visitor's chosen service / category into the form.
    window.ahgPrefillInquiry = function (opts) {
        opts = opts || {};
        var svc = document.getElementById('im-service');
        var msg = document.getElementById('im-message');
        if (opts.serviceId && svc) {
            var found = Array.prototype.some.call(svc.options, function (o) { return o.value == opts.serviceId; });
            if (found) svc.value = String(opts.serviceId);
        }
        // Category-only: there's no category in the service list, so record it as the requirement.
        if (!opts.serviceId && opts.categoryName && msg) {
            var note = 'I am interested in: ' + opts.categoryName + ' (category).';
            if (msg.value.indexOf(note) === -1) {
                msg.value = note + (msg.value ? '\n' + msg.value : '');
            }
        }
    };

    var form = document.getElementById('inquiryForm');
    if (!form || form.dataset.bound) return;
    form.dataset.bound = '1';
    var alertBox = document.getElementById('inquiryAlert');
    var btn = document.getElementById('inquirySubmitBtn');
    var label = btn ? btn.querySelector('.ahg-im-submit-label') : null;

    function showAlert(type, msg) {
        if (!alertBox) return;
        alertBox.className = 'ahg-im-alert is-' + type;
        alertBox.textContent = msg;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (alertBox) alertBox.className = 'ahg-im-alert d-none';
        if (btn) { btn.disabled = true; if (label) label.textContent = 'Submitting...'; }

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
                showAlert('success', res.d.message || 'Thank you! Your inquiry has been submitted.');
                form.reset();
                // conversion tracking (also fired by the global submit listener)
                if (typeof gtag === 'function') { gtag('event', 'ahg_inquiry_submitted', { form_id: 'inquiryForm' }); }
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({ event: 'ahg_inquiry_submitted', form_id: 'inquiryForm' });
            } else {
                var msg = res.d.message || 'Please check the form and try again.';
                if (res.d.errors) { msg = Object.values(res.d.errors).map(function (v) { return v[0]; }).join(' '); }
                showAlert('error', msg);
            }
        })
        .catch(function () { showAlert('error', 'Something went wrong. Please try again or contact us directly.'); })
        .finally(function () { if (btn) { btn.disabled = false; if (label) label.textContent = 'Submit Inquiry'; } });
    });
})();
</script>
@endonce
