# alphahmc.com: Google tag map (page speed brief, step 7)

Read from the live HTML of https://alphahmc.com/ and the repo on 21 October 2026. Nothing has been changed. The Google Ads agent decides what to remove.

## What loads on every page

| # | Script request (mobile home, 21 Oct) | Size | Loaded by | Sets |
|---|---|---|---|---|
| 1 | `gtm.js?id=GTM-ND5F4WS` | 133 KB | `/google` admin row (`googletags.tags`), GTM snippet | Tag Manager container |
| 2 | `gtag/js?id=G-KBQ7ZEQ196` | 195 KB | `/google` admin row, standalone gtag.js snippet | `gtag('js')` + `gtag('config','G-KBQ7ZEQ196')` |
| 3 | (no extra file) | | `/google` admin row, second inline block | `gtag('js')` again + `gtag('config','AW-695565753')` |
| 4 | `gtag/js?id=G-KBQ7ZEQ196&cx=c&gtm=…` | 194 KB | GTM container (a Google tag for GA4 inside GTM) | GA4 config again |
| 5 | `gtag/js?id=AW-695565753&cx=c&gtm=…` | 194 KB | GTM container, tag 20 (Google tag AW-695565753) | Ads config again |

About 715 KB of Google script per first visit; rows 2 and 4 load the same GA4 property twice, and the Ads ID is configured twice (rows 3 and 5).

Order in the page head (`resources/views/front/layout-2.blade.php`): Consent Mode V2 defaults first (defines `window.gtag` as a `dataLayer.push` stub, with `OPT_IN_REGIONS`), then `@foreach($globaltags)`, then `@foreach($googletags)` which prints rows 1 to 3. The `noscript_tags` field prints the GTM `<noscript>` iframe after `<body>`.

## What depends on each path

| Caller | Code | Calls | Needs |
|---|---|---|---|
| Inquiry sent (modal) | `partials/inquiry-modal.blade.php:540-545` | `gtag('event','ahg_inquiry_submitted')`, `gtagReportInquirySent()` | `gtag` + AW config |
| Contact form sent | `front/contact.blade.php:1113-1120` | `gtagReportInquirySent()` | `gtag` + AW config |
| `gtagReportInquirySent` | `layout-2` conversion block | `gtag('event','conversion',{send_to:'AW-695565753/wF9YCMud6cMcELn71csC'})` | AW config |
| `gtagReportWhatsAppClick` | same | `send_to:'AW-695565753/__2wCM6d6cMcELn71csC'`, value 1 AED | AW config |
| `gtagReportCallClick` | same | `send_to:'AW-695565753/QcGMCNGd6cMcELn71csC'`, value 1 AED | AW config |
| `trackConversion()` (phone, email, WhatsApp, inquiry opened/submitted, contact submitted) | same | `dataLayer.push({event:'ahg_*'})` for GTM triggers, and `gtag('event','ahg_*')` sent to every configured destination | GTM triggers; GA4 + AW configs |
| WhatsApp float button | `layout-2` `onclick` | `dataLayer.push({event:'ahg_whatsapp_c…'})` | GTM trigger |

Points to check in GTM before removing anything:

- `gtag('event', 'ahg_*')` with no `send_to` goes to every destination configured on the page, and the same `ahg_*` names are also pushed as GTM events. If GTM has GA4 event tags on those triggers, GA4 may receive each event twice.
- With rows 2 and 4 both active, GA4 may count two `page_view`s per page, depending on the GTM tag's "send page view" setting.
- The three `send_to: AW-695565753/...` conversions only need some Google tag with the AW ID loaded on the page. That can be row 3 (page) or row 5 (GTM tag 20). The `gtag` stub in the consent block forwards the calls to whichever one is loaded.

## Options for the Ads agent (not applied)

1. Keep GTM as the single loader: remove rows 2 and 3 from the `/google` row (the consent block already defines `gtag`), and make sure GTM fires GA4 + AW Google tags on all pages. Saves about 195 KB per first visit. The `send_to` conversions and `ahg_*` events keep working through GTM's Google tags. Verify with the Playwright conversion check.
2. Keep the page snippet as the loader: remove the GA4 and AW Google tags from GTM instead (keep GTM for Meta Pixel and the WhatsApp source tags). Same saving.

Either way, edit the `/google` row only through the admin page (`googletags` fields are JSON-cast; raw SQL breaks them).
