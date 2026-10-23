// Inquiry modal behaviour (partials/inquiry-modal.blade.php). Moved out of the partial unchanged
// (page speed step 5, 2026-10-23) so browsers cache it.
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
        if (!key || !window.ahgRecaptcha) return Promise.resolve('');
        return window.ahgRecaptcha.token('inquiry');
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
                    // Conversion tracking. ahg_inquiry_submitted comes once from the global submit
                    // tracker in layout-2 (with service_name); sending it here too counted it twice.
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({ event: 'inquiry_sent' });
                    if (typeof window.ahgGa4Event === 'function') window.ahgGa4Event('inquiry_sent');   // was GTM tag 14
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
