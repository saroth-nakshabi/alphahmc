{{-- Meta Pixel 831972843129414, moved from Tag Manager GTM-ND5F4WS v39 (tags 21, 29-35) into site code
     so the site no longer loads GTM (page speed, 2026-10-23). A 1:1 port, so Meta numbers stay the same:
     - starts on the window load event, as before (GTM itself loaded after the load event);
     - order: init, PageView, the ViewContent groups in GTM tag order, then Lead;
     - each rule is the GTM trigger regex, unanchored and case-sensitive, tested against the full URL
       ({{Page URL}}: host, path and query, no #fragment); a URL can match more than one group;
     - Lead fires on visiting a consultation page (tag 35 fired on page view, not on a form submit).
     Owned by the Ads session: change these rules only together with it. --}}
<script>
(function () {
    var started = false;
    function startMetaPixel() {
        if (started) return;
        started = true;
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments); };
            if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = [];
            t = b.createElement(e); t.async = !0; t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s);
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '831972843129414');
        fbq('track', 'PageView');

        var url = location.href.split('#')[0];
        var viewContent = [
            [/clinic-setup|medical-centre-setup|doh-healthcare-facility|drugstore-pharma.*licens|telehealth-facility|school-clinic|feasibility-study|facility-acquisition|growth-expansion|post-acquisition|facility-licensing-setup|healthcare-feasibility/, 'Facility Setup & Investor Services', 'facility-setup-investor'],
            [/compliance-audit|dha-audit|doh-inspection|adhics|jawda|regulatory-readiness|hospital-compliance|licensing-audit|phc-kpi|clinical-cost|kpi-performance|audit-consultation|regulatory-quality/, 'Compliance & Audit Services', 'compliance-audit'],
            [/jci-accreditation|nabh-accreditation|cbahi|iso-certification|isqua|malaffi|nabidh|healthcare-accreditations/, 'Accreditation Services', 'accreditation'],
            [/digital-transformation|cybersecurity|hipaa|healthcare-software|healthtech|telehealth-digital|infrastructure-planning|engineering-design|facility-fit-out|construction-project|architectural|medical-engineering|design-and-build|bio-medical|information-security/, 'Digital Health & Engineering', 'digital-engineering'],
            [/professional-licensing|activate-your-uae|license-transfer|physician-recruitment|staffing-solutions|nursing-allied|professional-resourcing|training-education/, 'Healthcare Professional Licensing', 'healthcare-professional'],
            [/pharmaceutical|medical-product-import|product-classification|equipment-procurement|equipment-moh|fanr|radiation|pharmaceutical-product-services/, 'Pharma & Medical Equipment', 'pharma-equipment']
        ];
        viewContent.forEach(function (rule) {
            if (rule[0].test(url)) {
                fbq('track', 'ViewContent', { content_name: rule[1], content_category: rule[2], content_type: 'service' });
            }
        });
        if (/plan-your-project|contact|book-a-consultation|alpha-blueprint/.test(url)) {
            fbq('track', 'Lead', { content_category: 'consultation-request', content_type: 'service' });
        }
    }
    if (document.readyState === 'complete') { startMetaPixel(); } else { window.addEventListener('load', startMetaPixel); }
})();
</script>
