# AlphaHMC Homepage — UI/UX & Conversion Audit

**Scope:** Full homepage, header → footer. Read-only audit (no changes made).
**Method:** 8-dimension expert audit. 6 dimensions (Hero, Content Flow, Conversion, Accessibility, Navigation/IA, Mobile) produced by parallel specialist agents against the live rendered page + source; 2 dimensions (Content/Copy, Visual Design) authored from direct code review (their agents were cut short by a billing limit and not re-run).
**Audience model:** B2B — UAE/GCC healthcare facility decision-makers (owners, administrators, DOH/DHA/MOH compliance leads) and healthcare professionals.
**Primary conversion goals:** (1) submit an inquiry / contact, (2) explore a service or category, (3) build enough trust to start an engagement.

> Note: the audit ran against the local build, which contains some test data (e.g. a "Test Project", a category whose description is the raw slug). On production these specific strings may differ, but the safeguards recommended still apply.

---

## 1. Executive summary

The homepage is **visually polished but its conversion funnel is structurally broken**. A first-time facility decision-maker gets an attractive hero and a clever service-finder, but:

- The **primary call-to-action is dead** — all five hero buttons link to `#`, labelled a generic "Know More".
- There is **no persistent "Contact / Book a Consultation" button** anywhere in the sticky header; the only always-on path is a WhatsApp chat bubble.
- There is **no inquiry form or modal on the page at all** — yet the analytics code is listening for one (`#inquiryModal` / `#inquiryForm`), so the tracked primary conversion can never fire.
- **Trust signals are largely absent** at the decision points (no accreditation logos, stats, or testimonials near CTAs).
- The page is **let down by visible test/placeholder content** and an **empty "Alpha Updates" column** in a prime proof section.
- Several **CTAs and descriptions are hidden until hover**, so they're invisible/untappable on mobile — where much B2B browsing happens.

The good news: most of these are **high-impact, low-to-medium effort fixes**. Nailing the top 8 issues below would materially lift conversion before any redesign.

---

## 2. Top critical issues (fix first)

| # | Issue | Impact |
|---|-------|--------|
| 1 | **All 5 hero CTAs are `href="#"`** (generic "Know More") | Dead primary CTA in the highest-attention zone |
| 2 | **No persistent contact CTA in the sticky header** ("Contact Us" is 2 clicks deep in the Explore menu / footer) | Ready-to-convert users can't act at the moment of intent |
| 3 | **No on-page inquiry form/modal exists**, but analytics tracks one; lead capture relies solely on AVA → WhatsApp | The #1 conversion goal has no structured path; tracking is phantom | - done
| 4 | **No trust signals near conversion** (no DOH/DHA/MOH/JCIA, stats, or testimonials) | B2B buyers won't engage without credibility proof |
| 5 | **Test/placeholder data renders live** ("Test Project goes here"; category description = raw slug `description-for-healthcare-professional-resourcing`) | Destroys trust in a quality/compliance consultancy |
| 6 | **"Alpha Updates" empty-state column sits beside Case Studies** | A dead 40% column signals a low-activity company |
| 7 | **No inquiry/contact CTA anywhere mid-page**; the scroll ends on Announcements | Warmest intent (right after case-study proof) leaks |
| 8 | **Expert Solutions cards are non-link `<div>`s with hover-only CTAs** — on touch they show only a title with nothing tappable | The primary "explore a service" path is severed on mobile |
| 9 | **All top-level nav is hidden behind one "EXPLORE" toggle** + no header Contact CTA | First-time visitors can't orient or reach Contact without hunting |
| 10 | **Mobile footer nav is `display:none`** (About AHG + Resources, incl. Contact Us) | With header nav also hidden, mobile loses its secondary-nav safety net |
| 11 | **Accessibility blockers**: no landmarks/skip link, no visible focus ring, unlabeled icon buttons, 5× `<h1>` in the hero | Keyboard/screen-reader users can't navigate or operate the funnel |
| 12 | **`.recommended-section` clips between 577–991px** (hard-coded `width:60%;margin-left:335px`, only reset ≤576px) | Expert Solutions grid pushed off-screen on large phones/small tablets |
| 13 | **Malformed hero markup** (`#btnImageMode` closed with `</div>` not `</button>`) | Invalid DOM around an interactive control; cross-browser risk |

---

## 3. Conversion funnel analysis

**Current functioning paths:** explore-service (dropdown → service page), content browsing (blog/case-study cards), and the AVA WhatsApp chat. **Everything else leaks.**

- **Top of funnel (hero):** highest attention, zero conversion — the CTA is dead and generic. This is the single biggest leak.
- **Always-on intent:** there's no header contact button, so a user who decides to engage at scroll-depth 60% has to hunt — open the Explore menu or scroll to the footer. Most won't.
- **Proof → action gap:** Case Studies are the natural "I'm convinced, now what?" moment, but there's no CTA there. The page then ends on Announcements (low intent), not a contact prompt.
- **Structured lead capture missing:** the only capture is an off-site, informal WhatsApp handoff — which formal healthcare/government buyers may distrust, and which leaves no documented inquiry trail. The analytics team is clearly *expecting* a form (`#inquiryModal`/`#inquiryForm`) that was never built.
- **Trust deficit throughout:** with no accreditation marks, numbers, or testimonials adjacent to CTAs, even the clicks that happen convert poorly.

**Recommended funnel shape:**
1. Hero CTA → live, specific ("Book a Consultation" / "Explore Licensing Support").
2. Persistent header **"Get a Consultation"** button (every scroll position, mobile included).
3. Compact **trust strip** (accreditation logos + 3–4 hard numbers) right under the hero.
4. A **contact/inquiry band after Case Studies** (post-proof), and end the page on a contact prompt rather than announcements.
5. A real **inquiry form/modal** (wire it to the existing analytics contract) alongside the chat widget.

---

## 4. Findings by dimension

### A. Hero & Above-the-Fold
- **[HIGH]** All 5 hero CTAs render `href="#"` (slider `button_link` unset → `?? '#'` fallback). → Make the fallback a real route (contact/all-services) and/or require `button_link` in admin.
- **[HIGH]** CTA label is a generic, identical "Know More" on every slide. → Use per-slide, outcome-led labels ("Talk to a Compliance Expert", "Explore Licensing Services").
- **[HIGH]** `#btnImageMode` is malformed (closed with `</div>`, plus a dangling `</div>`). → Close with `</button>`, remove the stray div.
- **[MED]** Hero mode-toggle buttons are icon-only with no label/aria. → Add visible micro-labels ("Video"/"Slides") + `aria-label`, or reconsider the switcher.
- **[MED]** 7s auto-rotation, heavy entrance animation, no pause/prev/next. → Add pause-on-hover + prev/next; honour `prefers-reduced-motion`.
- **[MED]** Value prop implies but doesn't *name* the audience; no above-the-fold trust markers. → Name the audience (hospitals, clinics, operators) + surface 1–2 authority markers.
- **[MED]** Default service-preview card shows a hard-coded service behind a dead "EXPLORE FULL SERVICE" link. → Link it to the real service or hide until a service is chosen.
- **[LOW]** Hero locked to `100vh` pushes the whole funnel below the fold. → Drop to ~85–90vh so the next section peeks and invites scroll.

### B. Content Sections — Flow & Scannability
- **[HIGH]** Live **placeholder/test data** in Case Studies ("Test Project goes here") and the Expert Solutions grid (raw slug as description). → Filter non-production items; never render placeholder strings.
- **[HIGH]** **Alpha Updates empty-state** column shown beside Case Studies. → Collapse to full-width Case Studies (or hide the row) when there are no updates; only split when both columns have real content.
- **[MED]** Three consecutive "browse our content" clusters (Knowledge Base blogs, Alpha Updates blogs — *same source* — and Case Studies projects) feel redundant. → Differentiate (thought-leadership vs. company news vs. outcome-proof) or consolidate.
- **[HIGH]** **No mid-page contact CTA**; scroll ends on Announcements. → Add a conversion band after Case Studies.
- **[MED]** Section H2s use inconsistent fonts/weights (winky-sans vs Libre Baskerville vs Outfit; a code comment even flags an intended unified treatment that isn't fully applied). → Standardize all mid-page H2s.
- **[MED]** Bento grid: several Expert-Solutions tiles fall back to the same `service-details-bg.jpg`, so tiles look identical and tile size implies a false priority. → Give each category a distinct image or use a uniform grid; map biggest tiles to highest-value services.
- **[LOW]** "Our Clients" social proof sits late (just above Announcements). → Move it higher (under Expert Solutions or beside Case Studies).
- **[LOW]** "Load More" persists when 8 of 12 already show (only +4). → Cap the curated grid + a single "View all services" link, or label the remaining count.

### C. Conversion Funnel & CTAs
- **[HIGH]** Dead hero CTAs (see A1). 
- **[HIGH]** No persistent header contact CTA. → Add a high-contrast "Get a Consultation" button to the header right side.
- **[HIGH]** No on-page inquiry form/modal; analytics listens for `#inquiryModal`/`#inquiryForm` that don't exist. → Build a short inquiry form/modal matching the analytics contract.
- **[HIGH]** Minimal trust signals at decision points; clients marquee silently hides if none are featured. → Add accreditation/stats strip + a testimonial; give the marquee a curated fallback.
- **[MED]** Card CTAs hover-revealed → invisible on touch. → Show CTA + key text by default on coarse-pointer/small viewports; make whole card tappable.
- **[MED]** Service-selector default "EXPLORE FULL SERVICE" = `href="#"` until interaction; two-step gate adds friction. → Fix default link/hide; offer a "Browse all services" escape hatch.
- **[MED]** Footer phone is plain text (not `tel:`), and differs from the AVA WhatsApp number. → Make it a `tel:` link; reconcile to one consistent contact identity.
- **[LOW]** Announcement CTAs also fall back to "Know More" / `#`. → Real route + specific verb; validate link before "featured".
- **[LOW]** AVA chat (WhatsApp) is the sole reliable conversion and may feel informal to enterprise/government buyers. → Pair with a formal form + response-time expectation.

### D. Accessibility (WCAG 2.1 AA)
*Good practices present:* alt text on logo/client/case images, footer social aria-labels, carousel `aria-labelledby`/`aria-hidden` on duplicates, several `prefers-reduced-motion` guards.
- **[HIGH]** No `<main>`, `<nav>`, `<header>` landmarks and **no skip link**. → Add landmarks + a focus-visible skip link + a `.visually-hidden` utility.
- **[HIGH]** **No visible keyboard focus indicator** anywhere (`outline:none` on `.pro-select:focus` with no replacement). → Add a global `:focus-visible` ring (e.g. `outline:3px solid #066D77`).
- **[HIGH]** Unlabeled icon-only controls: hero mode toggles, header search button, announcement dots/arrows. → Add `aria-label` + `aria-hidden` on icons; `aria-pressed`/`aria-current` for state.
- **[HIGH]** Header search input has only a placeholder (no label); Explore toggle lacks `aria-expanded`/`aria-controls`. → Add labels and state.
- **[HIGH]** Service-preview contrast: teal `#1ea7a1` eyebrow + white text on a 15%-white glass panel over arbitrary images is unreliable (<4.5:1 in places). → Darken the scrim; use `#066D77` for the eyebrow/CTA.
- **[MED]** **5× `<h1>`** (one per hero slide). → One `<h1>`; demote the rest.
- **[MED]** Hero auto-advances 7s with no pause and **no reduced-motion guard** (unlike the other carousels). → Add pause + reduced-motion handling.
- **[MED]** Knowledge Base carousel is focusable but has no keyboard (arrow-key) control or visible prev/next. → Add keydown handlers / visible buttons.
- **[MED]** Step dropdowns lack programmatic labels (visible "Select Category/Service" text isn't linked). → `aria-labelledby` to the step text.
- **[LOW]** Decorative inline icons (arrows/chevrons) not consistently `aria-hidden`; mega-menu uses `href="#"` placeholders populated by JS. → Hide decorative icons; render real hrefs server-side.
- **[LOW]** Client marquee lacks pause-on-hover/focus for users without the OS reduced-motion flag.

### E. Navigation & Information Architecture *(agent-audited)*
- **[HIGH]** The entire top-level navigation is hidden behind a single **"EXPLORE" mega-menu toggle** — the bar shows only EXPLORE (left), logo (center), search (right). Services/About/Insights/Case Studies/Contact are all concealed until the user clicks. A first-time visitor can't see scope or orient without an extra interaction. → Surface 3–5 visible top-level links on desktop (Services, Case Studies, Knowledge Base, About) alongside EXPLORE for the deep taxonomy.
- **[HIGH]** **No header Contact CTA** — "Contact Us" lives only inside EXPLORE › Our Group and in the footer. Inquiry is goal #1 with no persistent visible entry point. → Add a distinct "Talk to a Consultant" button on the header right side (desktop + mobile).
- **[HIGH]** All 5 hero CTAs are dead `href="#"` "Know More" (corroborates Hero/Conversion). → Real per-slide destinations + specific labels.
- **[HIGH]** **Mobile footer wayfinding gutted** — `.quick-links-grid` (both About AHG + Resources = 8 links incl. Contact Us, All Services, Knowledge Base) is `display:none` ≤768px. With the header nav also hidden behind EXPLORE, mobile has almost no secondary-nav safety net. → Use a tap-to-expand accordion / stacked list instead of hiding.
- **[MED]** EXPLORE main-category links are `href="#"` (JS filters in place only) — not bookmarkable, shareable, or SEO-crawlable, and a 3-level drill-down (main › sub › service) before any real page. → Give each main category a real overview/landing page; cut taps-to-destination.
- **[MED]** **No visible breadcrumb** anywhere (a `BreadcrumbList` exists only as JSON-LD). With no visible top-level nav, deep-page users have weak orientation. → Render a visible breadcrumb mirroring the existing schema (low effort).
- **[LOW]** Search gets co-equal header space with EXPLORE but collapses to an unlabeled 48px icon on mobile; pairing prominent search with hidden nav assumes users know what to type. → Lead with browsable nav; keep search as a power-user shortcut; add an `aria-label`.

### F. Mobile UX & Responsiveness *(agent-audited)*
- **[HIGH]** **Expert Solutions cards (`.article-card`) are plain `<div>`s, not links** — and their description *and* only CTA (`.btn-premium-read-more`) are `max-height:0; opacity:0` revealed **only on `:hover`**, with no touch override and no wrapping anchor/click handler. On phones these tiles render as a bare title with **nothing tappable** — the primary "explore a service" path is severed on the largest traffic segment. → Add `@media (hover:none)` to reveal the description+CTA, **and** make the whole card a link (wrap in `<a>` or stretched-link overlay).
- **[HIGH]** Footer About AHG + Resources columns `display:none` ≤768px removes every in-site link incl. **Contact Us** on mobile (see E). → Don't hide; accordion/stack; at minimum keep Contact Us + All Services.
- **[MED]** Knowledge Base carousel card description + "Read More" pill are also hover-only (the 575px query only resizes, doesn't reveal). Less severe — the whole card *is* an anchor, so taps still navigate — but mobile users see only image+title. → `@media (hover:none)` reveal.
- **[MED]** `100vh` hero uses large-viewport height: on mobile it's taller than the visible viewport (address bar), so the headline/CTA can sit partly out of frame on first paint and the service finder is a full swipe away. → Use `100svh` with a `100vh` fallback (or cap ~88vh/640px). *(Note: the responsive 991/576px hero queries target a different hero `.hero-auto-slider`, not `.hero-video-modern`.)*
- **[MED]** **`.recommended-section` is hard-coded `width:60%; margin-left:335px`** (with a dev note flagging it as a hack), only reset at ≤576px. Between **577–991px** (large phones / small tablets) it keeps the 335px left margin + 60% width, pushing the Expert Solutions grid off-screen / into a narrow strip. → Replace with a centered max-width container, or extend the `width:100%;margin-left:0` reset up to 991px.
- **[LOW]** ibc carousel click-suppression (`moved>8`) only runs in the pointer handler, which doesn't fire for touch, so a stray tap mid-swipe could trigger navigation. Low impact (browser tap/scroll disambiguation usually handles it). → Add a `touchmove` threshold guard if observed in testing.
- **[GOOD]** Already mobile-right (keep): native `<select>`s (large targets, no iOS zoom), on-select scroll-to-preview + flash with reduced-motion fallback, 4-at-a-time Load More, touch-aware carousels with reduced-motion guards.

### G. Content & Copywriting *(authored from code review)*
- **[MED]** The service-selector subtitle is **broken filler** ("...find the perfect match your goals,tailored to your needs. from experts..."): missing words, missing space, lowercase sentence starts, run-ons. → Rewrite to one tight, proofed sentence. Damaging next to the hero for a *quality/compliance* brand.
- **[MED]** Generic, repeated CTA copy ("Know More" ×5, "EXPLORE FULL SERVICE", "Load More"). → Specific, benefit-led verbs.
- **[MED]** Weak trust/credibility language overall — the copy describes services but rarely quantifies outcomes or cites authority (DOH/DHA/JCIA, # facilities, years). → Add proof-oriented microcopy near CTAs.
- **[LOW]** "Alpha Updates" vs "Knowledge Base" labels don't clearly signal *different* content (both are blogs). → Rename/reposition for distinct meaning.
- **[LOW]** Headings are now largely unified (good), but confirm copy tone is consistent (some sections are punchy, others verbose).

### H. Visual Design & Aesthetics *(authored from code review)*
- **[MED]** **Repeated fallback hero image** across Expert-Solutions tiles makes multiple cards look identical — the flagship section's weakest visual point. → Distinct imagery per category.
- **[MED]** **Translucent panel contrast** (Knowledge Base `rgb(255 255 255 / 55%)` panel, service-preview 15% glass) trades aesthetics for legibility on light/busy images. → Strengthen the scrim behind text, or raise panel opacity where text contrast is at risk.
- **[LOW–GOOD]** Recent work unified section-heading font/size/weight (Libre Baskerville) and accent spans (Outfit teal) — this is a real improvement; keep extending it to the few stragglers (service-display-title, announcement titles).
- **[LOW]** Card systems differ between sections (radius, shadow, hover behaviour: lift vs zoom vs reveal). → Converge on one card language for cohesion.
- **[LOW]** Spacing rhythm varies between sections (some 130px padding, others ~60–80px). → Adopt a consistent vertical rhythm scale.

---

## 5. Prioritized action plan

### Quick wins (hours, high impact)
1. Replace hero CTA `?? '#'` with a real route + specific labels; same for announcement CTAs.
2. Add a persistent **"Get a Consultation"** button to the sticky header (desktop + mobile).
3. Fix `#btnImageMode` markup (`</button>` + remove stray `</div>`).
4. Make card CTAs/descriptions visible by default on touch (`@media (hover: none)`) **and wrap `.article-card` in its `<a>`/stretched-link** so the whole tile is tappable.
5. Fix the service-selector default "EXPLORE FULL SERVICE" link (point to the shown service or hide).
6. Exclude test/placeholder projects & slug-only category descriptions from the homepage.
7. Collapse Alpha Updates to full-width Case Studies when empty.
8. Wrap footer phone in `tel:` + reconcile the two phone numbers.
9. Proofread/rewrite the broken service-selector subtitle.
10. Add a global `:focus-visible` ring; add `aria-label`s to icon buttons.
11. Footer nav on mobile: replace `display:none` with an accordion (keep at least Contact Us + All Services).
12. Fix `.recommended-section` clipping in the 577–991px range (centered max-width, or extend the reset to 991px).
13. Switch hero `100vh` → `100svh` (fallback `100vh`) so the headline/CTA stay in-frame on mobile.

### High-impact (days)
11. Build a real **inquiry form/modal** wired to the existing `#inquiryModal`/`#inquiryForm` analytics contract.
12. Add a **trust strip** (accreditation logos + 3–4 stats) under the hero + at least one testimonial.
13. Add a **contact/inquiry CTA band after Case Studies**; end the page on a conversion prompt.
14. Surface visible top-level nav (or at least keep Contact always visible); reconsider hiding footer columns on mobile.
15. Add `<main>`/`<nav>`/`<header>` landmarks + skip link; reduce hero to a single `<h1>`.

### Strategic (review with team)
16. Rethink the hero: shorter height, manual controls, audience-named value prop, reduced-motion.
17. Differentiate/consolidate the three content-link clusters (Knowledge Base / Alpha Updates / Case Studies).
18. Unify the card design language and spacing rhythm across all sections.
19. Distinct imagery for every featured category; map bento tile size to value.
20. Full keyboard support for carousels + pause controls on auto-motion.

---

*Audit complete. No code was changed. Hand this to the team; tell me which items to implement and I'll execute them.*
