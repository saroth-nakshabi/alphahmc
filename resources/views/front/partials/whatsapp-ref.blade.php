{{-- WhatsApp Ref code: was GTM tag 36 "Alpha Ads - WhatsApp Ref code" (container v37), moved into the
     site on 2026-10-21 so it no longer waits for Tag Manager. Copied verbatim except the comment on
     conversions. Keep the storage key, 90-day expiry, "Ref AHx-XXXXXX" text and capture listener:
     the CRM parser and returning visitors depend on them. Must run before any other click handler.
     30 Oct 2026 (brief "alphahmc-ref-click-log", agreed with the Ads session): the first-touch record also
     keeps the click ID type, utm_source, utm_medium and the landing path; each WhatsApp tap logs the Ref with
     its click to /api/ref-log (sendBeacon), and window.ahgRef gives forms the same Ref. --}}
<script>
// Inquiry source on every WhatsApp message (added by the Alpha Ads agent).
// Each visitor gets a short ID kept for 90 days; the first visit that carries ad
// or campaign parameters sets the source. A WhatsApp tap adds "Ref AHA-XXXXXX" to the
// message: the prefix says the channel without naming it, so customers see a plain code.
//   AHA Google Ads   AHO organic search   AHM Meta Ads   AHS Meta organic
//   AHC other tagged campaign   AHR referral   AHD direct
// The code, channel, campaign, keyword and click ID also go to Analytics.
// Conversions are not sent here: the site's gtagReportWhatsAppClick() in layout-2 does that.
// Meta Ads are told apart from Meta organic only by utm_medium (paid, cpc, ppc, cpm, ads)
// in the ad URL; fbclid alone is added to organic Facebook links too.
(function () {
  var KEY = 'alpha_inquiry_source', DAY = 86400000;
  var PREFIX = { 'Google Ads': 'AHA', 'Search (organic)': 'AHO', 'Meta Ads': 'AHM', 'Meta organic': 'AHS',
    'Other campaign': 'AHC', 'Referral': 'AHR', 'Direct': 'AHD' };
  var p = new URLSearchParams(location.search), src = null;
  try { src = JSON.parse(localStorage.getItem(KEY) || 'null'); } catch (e) {}
  if (src && Date.now() - src.at > 90 * DAY) src = null;
  // Visitors stored before the prefixes keep their ID ("AH-7K2M9Q" becomes "7K2M9Q").
  if (src && !src.id) src.id = String(src.code || '').replace(/^AH-/, '');
  if (src && !PREFIX[src.channel]) src.channel = 'Other campaign';

  var us = (p.get('utm_source') || '').toLowerCase(), um = (p.get('utm_medium') || '').toLowerCase();
  var gclick = p.get('gclid') || p.get('gbraid') || p.get('wbraid');
  var tagged = gclick || us || p.get('fbclid');
  if (tagged || !src) {
    var ref = document.referrer;
    var own = ref && ref.indexOf(location.hostname) !== -1;
    var meta = /^(facebook|fb|instagram|ig|meta|threads|messenger)$/.test(us)
      || (!us && (p.get('fbclid') || /\/\/([a-z0-9-]+\.)*(facebook|instagram|threads)\.(com|net)|\/\/fb\.me/.test(ref)));
    var paid = /^(paid|paid_social|paidsocial|cpc|ppc|cpm|ads?)$/.test(um);
    var channel = gclick ? 'Google Ads'
      : meta ? (paid ? 'Meta Ads' : 'Meta organic')
      : (paid && (!us || us === 'google')) ? 'Google Ads'
      : us ? 'Other campaign'
      : /\/\/(www\.)?(google|bing|yahoo|duckduckgo)\./.test(ref) ? 'Search (organic)'
      : (ref && !own) ? 'Referral' : 'Direct';
    if (!(src && own && !tagged)) {
      src = {
        id: (src && src.id) || Math.random().toString(36).slice(2, 8).toUpperCase(),
        channel: channel, campaign: p.get('utm_campaign') || '', adgroup: p.get('utm_content') || '',
        term: p.get('utm_term') || '', gclid: gclick || '', at: Date.now(),
        click_type: p.get('gclid') ? 'gclid' : p.get('gbraid') ? 'gbraid' : p.get('wbraid') ? 'wbraid' : '',
        utm_source: p.get('utm_source') || '', utm_medium: p.get('utm_medium') || '', landing: location.pathname
      };
      try { localStorage.setItem(KEY, JSON.stringify(src)); } catch (e) {}
    }
  }
  var code = PREFIX[src.channel] + '-' + src.id;

  // ad_user_data consent at capture: the visitor's cookie choice, else "denied" in the opt-in regions of the
  // consent block (EU, EEA, UK, Switzerland; read from the browser time zone, as the page cannot see the
  // country) and "not_required" elsewhere. Without consent the click IDs are not sent.
  var OPT_IN_ZONES = /^(Europe\/(Vienna|Brussels|Sofia|Zagreb|Nicosia|Prague|Copenhagen|Tallinn|Helsinki|Mariehamn|Paris|Berlin|Busingen|Athens|Budapest|Dublin|Rome|Riga|Vilnius|Luxembourg|Malta|Amsterdam|Warsaw|Lisbon|Bucharest|Bratislava|Ljubljana|Madrid|Stockholm|Oslo|Vaduz|London|Belfast|Guernsey|Jersey|Isle_of_Man|Zurich)|Atlantic\/(Reykjavik|Canary|Madeira|Azores)|Africa\/Ceuta|Asia\/(Nicosia|Famagusta)|Arctic\/Longyearbyen)$/;
  function adConsent() {
    var saved = typeof window.getConsentWithExpiry === 'function' ? window.getConsentWithExpiry() : null;
    if (saved === 'accepted') return 'granted';
    if (saved === 'rejected') return 'denied';
    var zone = '';
    try { zone = Intl.DateTimeFormat().resolvedOptions().timeZone || ''; } catch (e) {}
    return OPT_IN_ZONES.test(zone) ? 'denied' : 'not_required';
  }
  function sourceData() {
    var consent = adConsent(), withClick = consent !== 'denied' && src.gclid, type = src.click_type || 'gclid';
    return {
      ref: code, ad_consent: consent,
      gclid: withClick && type === 'gclid' ? src.gclid : '', gbraid: withClick && type === 'gbraid' ? src.gclid : '',
      wbraid: withClick && type === 'wbraid' ? src.gclid : '',
      utm_source: src.utm_source || '', utm_medium: src.utm_medium || '', utm_campaign: src.campaign || '',
      utm_term: src.term || '', utm_content: src.adgroup || '', landing_path: src.landing || '',
      click_at: withClick ? src.at : null
    };
  }
  // Forms put this Ref in their submission (inquiries.source), so staff can copy it into the CRM.
  // formSource(): the JSON a form stores in inquiries.source (no name, phone or email in it).
  function formSource() {
    var d = sourceData(), p2 = new URLSearchParams(location.search);
    var out = { ref_id: code, channel: src.channel, ad_consent: d.ad_consent, gclid: d.gclid, gbraid: d.gbraid, wbraid: d.wbraid,
      utm_source: d.utm_source, utm_medium: d.utm_medium, campaign: d.utm_campaign, term: d.utm_term, adgroup: d.utm_content,
      landing_path: d.landing_path, page_url: location.href.split('#')[0] };
    ['gclid', 'gbraid', 'wbraid', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'].forEach(function (k) {
      if (p2.get(k) && (d.ad_consent !== 'denied' || k.indexOf('utm_') === 0)) out['url_' + k] = p2.get(k).slice(0, 200);
    });
    Object.keys(out).forEach(function (k) { if (out[k] === '' || out[k] == null) delete out[k]; else out[k] = String(out[k]).slice(0, 500); });
    return JSON.stringify(out);
  }
  window.ahgRef = { code: code, channel: src.channel, data: sourceData, formSource: formSource };

  document.addEventListener('click', function (e) {
    var link = e.target.closest && e.target.closest('a[href*="wa.me/"], a[href*="api.whatsapp.com"]');
    if (!link) return;
    try {
      var url = new URL(link.href), text = url.searchParams.get('text') || '';
      if (!/Ref AH[A-Z]?-/.test(text)) {
        url.searchParams.set('text', (text ? text + '\n\n' : '') + 'Ref ' + code);
        link.href = url.toString();
      }
    } catch (err) {}
    var params = { ref_code: code, source_channel: src.channel, source_campaign: src.campaign, source_adgroup: src.adgroup,
      source_term: src.term, click_id: src.gclid, link_number: (link.href.match(/wa\.me\/(\d+)/) || [])[1] || '', send_to: 'G-KBQ7ZEQ196' };
    if (typeof window.gtag === 'function') window.gtag('event', 'whatsapp_ref', params);
    // Ref click log: survives the page leaving for WhatsApp and never delays the tap.
    try {
      var body = new Blob([JSON.stringify(sourceData())], { type: 'application/json' });
      if (!(navigator.sendBeacon && navigator.sendBeacon('/api/ref-log', body))) {
        fetch('/api/ref-log', { method: 'POST', body: body, keepalive: true, headers: { 'Content-Type': 'application/json' } }).catch(function () {});
      }
    } catch (err) {}
  }, true);
})();
</script>