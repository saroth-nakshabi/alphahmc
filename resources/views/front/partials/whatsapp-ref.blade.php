{{-- WhatsApp Ref code: was GTM tag 36 "Alpha Ads - WhatsApp Ref code" (container v37), moved into the
     site on 2026-10-21 so it no longer waits for Tag Manager. Copied verbatim except the comment on
     conversions. Keep the storage key, 90-day expiry, "Ref AHx-XXXXXX" text and capture listener:
     the CRM parser and returning visitors depend on them. Must run before any other click handler. --}}
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
        term: p.get('utm_term') || '', gclid: gclick || '', at: Date.now()
      };
      try { localStorage.setItem(KEY, JSON.stringify(src)); } catch (e) {}
    }
  }
  var code = PREFIX[src.channel] + '-' + src.id;

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
  }, true);
})();
</script>