// WhatsApp chat window for the whole site (front/partials/wa-chat.blade.php). Opening it counts nothing; "Send" is a
// real wa.me link click, so the Ref code (whatsapp-ref) and the WhatsApp conversion (tracking.js) apply as on any
// WhatsApp link. Landing pages open it after a delay (data-auto-open); other pages show a small bubble instead.
(function () {
    'use strict';
    var root = document.getElementById('ahg-wa');
    if (!root) return;
    var $ = function (id) { return document.getElementById(id); };
    var win = $('ahg-wa-window'), btn = $('ahg-wa-button'), text = $('ahg-wa-text'), send = $('ahg-wa-send');
    var teaser = $('ahg-wa-teaser');
    var SEEN = 'ahg_wa_chat_seen';   // per session: auto-open or bubble only once

    function seen() { try { return sessionStorage.getItem(SEEN) === '1'; } catch (e) { return false; } }
    function markSeen() { try { sessionStorage.setItem(SEEN, '1'); } catch (e) {} }

    function setOpen(open, focus) {
        win.hidden = !open;
        btn.setAttribute('aria-expanded', String(open));
        if (open) {
            if (teaser) teaser.hidden = true;
            markSeen();
            if (focus && text) { text.focus(); text.setSelectionRange(text.value.length, text.value.length); }
        } else if (focus) {
            btn.focus();
        }
    }

    // Keep the send link's text current before the tap: the Ref script and the tracker read the link itself.
    function syncSend() {
        try {
            var url = new URL(send.href);
            url.searchParams.set('text', text.value.trim());
            send.href = url.toString();
        } catch (e) {}
    }

    btn.addEventListener('click', function () { setOpen(win.hidden, true); });
    $('ahg-wa-close').addEventListener('click', function () { setOpen(false, true); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !win.hidden) setOpen(false, true); });
    $('ahg-wa-compose').addEventListener('submit', function (e) { e.preventDefault(); });
    if (text) {
        text.addEventListener('input', syncSend);
        text.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send.click(); }
        });
        syncSend();
    }
    send.addEventListener('click', function () { setTimeout(function () { setOpen(false, false); }, 300); });

    // No WhatsApp on this device: the page's own form (landing pages), the inquiry modal, or the contact page.
    $('ahg-wa-inquiry').addEventListener('click', function () {
        setOpen(false, false);
        var fallback = root.getAttribute('data-fallback');
        var toggle = document.getElementById('lp-form-toggle');
        if (fallback === 'landing' && toggle) {
            var link = document.querySelector('a[href="#contact"]');
            if (link) link.click(); else toggle.click();
            return;
        }
        var contactForm = document.getElementById('mainContactForm');
        if (contactForm) { contactForm.scrollIntoView({ behavior: 'smooth', block: 'center' }); var f = contactForm.querySelector('input:not([type=hidden])'); if (f) setTimeout(function () { f.focus({ preventScroll: true }); }, 500); return; }
        var modal = document.getElementById('inquiryModal');
        if (modal && window.bootstrap && bootstrap.Modal) { bootstrap.Modal.getOrCreateInstance(modal).show(); return; }
        window.location.href = root.getAttribute('data-contact-url');
    });

    // Landing pages: open the window after a few seconds. Other pages: a small bubble after 10 seconds.
    var autoOpen = parseInt(root.getAttribute('data-auto-open'), 10) || 0;
    if (!seen()) {
        if (autoOpen > 0) {
            setTimeout(function () { if (!seen() && win.hidden) setOpen(true, false); }, autoOpen);
        } else if (teaser && root.getAttribute('data-teaser') === '1') {
            setTimeout(function () { if (!seen() && win.hidden) { teaser.hidden = false; markSeen(); } }, 10000);
            $('ahg-wa-teaser-open').addEventListener('click', function () { setOpen(true, true); });
            $('ahg-wa-teaser-close').addEventListener('click', function () { teaser.hidden = true; btn.focus(); });
        }
    }
})();
