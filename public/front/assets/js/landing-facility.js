// Landing pages (front/landing/facility.blade.php): floor comparison, project plan, header state,
// phone dock, copy buttons and the proposal form. From prototype version 5 (Saroth, 30 Oct 2026).
// Conversions: WhatsApp links are counted by tracking.js like everywhere else; the form reports one
// gtagReportInquirySent() after the server confirms, and tracking.js picks the label from lead_type.
(function () {
    'use strict';

    var $ = function (s, r) { return (r || document).querySelector(s); };
    var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

    // ── Floor comparison ─────────────────────────────────────────
    var cmp = $('#lp-compare'), rng = $('#lp-compare-range');
    if (cmp && rng) {
        rng.addEventListener('input', function () { cmp.style.setProperty('--pos', rng.value + '%'); });
    }

    // ── Project plan: select a phase, or let scrolling light them in order ──
    var phases = $$('.lp-phase');
    var plan = $('#plan');
    var userPicked = false;
    function showPhase(i) {
        phases.forEach(function (b, j) {
            b.setAttribute('aria-expanded', String(i === j));
            b.classList.toggle('is-on', j <= i);
        });
        var b = phases[i];
        $('#lp-d-wk').textContent = b.dataset.label;
        $('#lp-d-t').textContent = b.dataset.title;
        $('#lp-d-p').textContent = b.dataset.text;
    }
    phases.forEach(function (b, i) {
        b.addEventListener('click', function () { userPicked = true; showPhase(i); });
    });
    if (phases.length) showPhase(0);

    var header = $('#lp-header');
    function onScroll() {
        if (header) header.classList.toggle('is-scrolled', window.scrollY > 8);
        if (plan && phases.length && !userPicked) {
            var r = plan.getBoundingClientRect(), vh = window.innerHeight;
            if (r.top < vh && r.bottom > 0) {
                var t = Math.min(1, Math.max(0, (vh * 0.75 - r.top) / (r.height * 0.8)));
                showPhase(Math.min(phases.length - 1, Math.floor(t * phases.length)));
            }
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // ── Header links follow the section in view ──
    var navLinks = $$('.lp-pill-nav a');
    if (navLinks.length && 'IntersectionObserver' in window) {
        var secs = navLinks.map(function (a) { return document.getElementById(a.getAttribute('href').slice(1)); });
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) navLinks.forEach(function (a, i) { a.classList.toggle('is-on', secs[i] === e.target); });
            });
        }, { rootMargin: '-45% 0px -50% 0px' });
        secs.forEach(function (s) { if (s) io.observe(s); });
    }

    // ── Phone dock appears once the hero buttons scroll away ──
    var dock = $('#lp-dock'), heroCta = $('#lp-hero-cta');
    if (dock && heroCta && 'IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            dock.classList.toggle('is-shown', !entries[0].isIntersecting);
        }, { threshold: 0 }).observe(heroCta);
    }

    // ── Copy buttons ──
    $$('.lp-copy').forEach(function (b) {
        var label = b.textContent;
        b.addEventListener('click', function () {
            var done = function (text) { b.textContent = text; setTimeout(function () { b.textContent = label; }, 1800); };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(b.dataset.copy).then(function () { done('Copied'); }, select);
            } else {
                select();
            }
            function select() {
                var n = b.previousElementSibling.querySelector('.lp-num');
                var range = document.createRange(); range.selectNodeContents(n);
                var sel = window.getSelection(); sel.removeAllRanges(); sel.addRange(range);
                done('Selected');
            }
        });
    });

    // ── Proposal form ──
    var form = $('#lp-form');
    if (!form) return;
    var btn = $('#lp-submit'), alertBox = $('#lp-alert'), doneBox = $('#lp-done');
    var btnLabel = btn.textContent;
    var PHONE = /^\+[\d\s\-]{7,17}$/;   // same rule as the server (MainHomeController::submitInquiry)
    var fields = { name: '#lp-name', phone: '#lp-phone', email: '#lp-email', message: '#lp-message', consent: '#lp-consent' };

    // Load reCAPTCHA when someone starts on the form, not on page load.
    form.addEventListener('focusin', function () { if (window.ahgRecaptcha) window.ahgRecaptcha.load(); }, { once: true });

    function setError(key, msg) {
        var input = $(fields[key]), err = $('#lp-e-' + key);
        if (!input || !err) return !msg;
        input.setAttribute('aria-invalid', msg ? 'true' : 'false');
        err.textContent = msg || '';
        return !msg;
    }

    function validate() {
        var name = $('#lp-name').value.trim();
        var phone = $('#lp-phone').value.trim();
        var email = $('#lp-email').value.trim();
        var checks = [
            ['name', name.length < 2 ? 'Enter your full name.' : ''],
            ['phone', !PHONE.test(phone) ? 'Enter a mobile number with country code, for example +971 50 000 0000.' : ''],
            ['email', email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ? 'Enter a valid email address, or leave it empty.' : ''],
            ['consent', !$('#lp-consent').checked ? 'Tick the box so the facility team can contact you.' : ''],
        ];
        var first = null;
        checks.forEach(function (c) { if (!setError(c[0], c[1]) && !first) first = c[0]; });
        if (first) $(fields[first]).focus();
        return !first;
    }

    // Where the visitor came from: the Ref code and first-touch click data from the WhatsApp Ref script
    // (window.ahgRef.formSource), so the lead keeps its Ref, gclid and UTM tags (Ref click log brief, 30 Oct).
    function source() {
        if (window.ahgRef && typeof window.ahgRef.formSource === 'function') return window.ahgRef.formSource();
        return JSON.stringify({ page_url: location.href.split('#')[0] });
    }

    function busy(on) {
        btn.disabled = on;
        btn.setAttribute('aria-busy', on ? 'true' : 'false');
        btn.textContent = on ? 'Sending' : btnLabel;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        alertBox.hidden = true;
        if (!validate()) return;
        busy(true);

        var token = window.ahgRecaptcha ? window.ahgRecaptcha.token('inquiry') : Promise.resolve('');
        token.then(function (t) {
            $('#lp-recaptcha').value = t || '';
            $('#lp-source').value = source();
            return fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });
        }).then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (d) { return { ok: r.ok, d: d }; });
        }).then(function (res) {
            if (res.ok && res.d.success) {
                // One conversion per confirmed request; tracking.js sends "Facility form" on facility pages.
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({ event: 'inquiry_sent' });
                if (typeof window.ahgGa4Event === 'function') window.ahgGa4Event('inquiry_sent');
                if (typeof window.gtagReportInquirySent === 'function') window.gtagReportInquirySent();
                $$('.lp-field, .lp-two, #lp-submit', form).forEach(function (el) { el.hidden = true; });
                doneBox.hidden = false;
                doneBox.focus();
                return;
            }
            var errors = (res.d && res.d.errors) || {};
            var shown = false;
            Object.keys(errors).forEach(function (k) {
                if (fields[k]) { setError(k, errors[k][0]); shown = true; }
            });
            if (!shown) {
                alertBox.textContent = (res.d && res.d.message) || 'We could not send your request. Please try again, or message us on WhatsApp.';
                alertBox.hidden = false;
            }
            busy(false);
        }).catch(function () {
            alertBox.textContent = 'We could not send your request. Check your connection and try again, or message us on WhatsApp.';
            alertBox.hidden = false;
            busy(false);
        });
    });
})();
