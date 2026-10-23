// Site conversion tracking: Google Ads conversion helpers, GA4 events and the global click/submit trackers.
// Moved out of front/layout-2.blade.php unchanged (page speed step 5, 2026-10-23) so browsers cache it.
// Owned with the Ads session: change only together with it.
    (function () {
        'use strict';

        // ── Google Ads conversion functions (exposed globally for inline calls) ──
        window.gtagReportInquirySent = function () {
            if (typeof gtag === 'function') gtag('event', 'conversion', { send_to: 'AW-695565753/wF9YCMud6cMcELn71csC' });
        };
        window.gtagReportWhatsAppClick = function () {
            if (typeof gtag === 'function') gtag('event', 'conversion', { send_to: 'AW-695565753/__2wCM6d6cMcELn71csC', value: 1.0, currency: 'AED' });
        };
        window.gtagReportCallClick = function () {
            if (typeof gtag === 'function') gtag('event', 'conversion', { send_to: 'AW-695565753/QcGMCNGd6cMcELn71csC', value: 1.0, currency: 'AED' });
        };

        // ── Helper: push event to dataLayer + fire gtag directly ──────
        function trackConversion(eventName, params) {
            var payload = Object.assign({ event: eventName, page_url: window.location.href }, params || {});

            // 1. dataLayer push (GTM picks this up automatically)
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push(payload);

            // 2. gtag event (for GA4 / Google Ads when configured via GTM)
            if (typeof gtag === 'function') {
                gtag('event', eventName, params || {});
            }
        }

        // ── GA4 events moved from GTM tags 14-16 (container v37, 21 Oct 2026) ─────────
        // Same names, no parameters, same conditions as the GTM triggers: Just Links clicks
        // whose URL contains "wa.me" (whatsapp_click) or starts with "tel:" (call_click);
        // inquiry_sent fires from the inquiry modal. None is a GA4 key event.
        window.ahgGa4Event = function (name) {
            if (typeof gtag === 'function') gtag('event', name, { send_to: 'G-KBQ7ZEQ196' });
        };
        document.addEventListener('click', function (e) {
            var link = e.target.closest('a[href]');
            if (!link) return;
            if (link.href.indexOf('wa.me') !== -1) window.ahgGa4Event('whatsapp_click');
            else if (link.href.indexOf('tel:') === 0) window.ahgGa4Event('call_click');
        });

        // ── Global click tracker (event delegation, works on all pages) ─
        document.addEventListener('click', function (e) {
            var el = e.target.closest('a, button');
            if (!el) return;

            var href    = el.getAttribute('href') || '';
            // Read lazily: innerText forces a layout, so only the events that send the label pay for it.
            var labelOf = function () { return el.getAttribute('data-track-label') || el.innerText.trim().substring(0, 60); };
            var ctx = window._ahgPage || {};

            // ── Phone call ────────────────────────────────────────────
            if (href.indexOf('tel:') === 0) {
                trackConversion('ahg_phone_call', {
                    phone_number: href.replace('tel:', '').replace(/\s/g, ''),
                    service_name: ctx.service_name || '',
                    element_label: labelOf(),
                });
                gtagReportCallClick();
                return;
            }

            // ── Email (mailto:) ───────────────────────────────────────
            if (href.indexOf('mailto:') === 0) {
                trackConversion('ahg_email_click', {
                    email_address: href.replace('mailto:', ''),
                    service_name: ctx.service_name || '',
                    element_label: labelOf(),
                });
                return;
            }

            // ── WhatsApp ──────────────────────────────────────────────
            if (href.indexOf('wa.me') !== -1 || href.indexOf('whatsapp') !== -1) {
                trackConversion('ahg_whatsapp_click', {
                    service_name: ctx.service_name || '',
                    element_label: labelOf(),
                });
                gtagReportWhatsAppClick();
                return;
            }

            // ── Inquiry / Contact modal triggers ──────────────────────
            var modalTarget = el.getAttribute('data-bs-target') || '';
            if (modalTarget === '#inquiryModal') {
                // The modal stays on the page, so send this after the next paint: the modal opens
                // without waiting for gtag's event processing (INP). Same event, same parameters.
                requestAnimationFrame(function () {
                    setTimeout(function () {
                        trackConversion('ahg_inquiry_opened', {
                            trigger_element: labelOf() || el.className,
                            service_name: ctx.service_name || '',
                        });
                    }, 0);
                });
                return;
            }
        });

        // ── Form submits ───────────────────────────────────────────────
        document.addEventListener('submit', function (e) {
            var form = e.target;
            var ctx  = window._ahgPage || {};

            if (form.id === 'inquiryForm') {
                trackConversion('ahg_inquiry_submitted', {
                    service_name: ctx.service_name || '',
                    form_id: 'inquiryForm',
                });
            }

            // ── Contact page form ──────────────────────────────────────
            if (form.id === 'contactForm' || form.id === 'mainContactForm' || form.classList.contains('contact-form')) {
                trackConversion('ahg_contact_submitted', {
                    form_id: form.id || 'contact_form',
                });
                // The Ads "Inquiry Sent" conversion is reported once by the contact page's own submit
                // handler (front/contact.blade.php); calling it here too sent it twice.
            }
        });

        // ── Normalize tel: hrefs — strip spaces so mobile dialers work ──
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('a[href^="tel:"]').forEach(function (el) {
                var raw   = el.getAttribute('href') || '';
                var clean = raw.replace(/\s/g, '');
                if (clean !== raw) el.setAttribute('href', clean);
            });
        });

    })();
    
