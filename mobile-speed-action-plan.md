# Mobile Speed Action Plan — AlphaHMC

> Source: PageSpeed Insights (Mobile) audit of `/services/healthcare-professional-licensing-qatar`.
> Hard constraint: **no change to design, layout, or working elements** — we only change how assets are *delivered* (HTTP headers, file size, file location), never how they look or behave.
> Risk key: 🟢 zero visual/behavioral risk · 🟡 safe but needs testing · 🔴 touches a working element, defer + test in isolation.

## Tier 1 — Safe, high-impact (Phase 1)

### 1. Cache-lifetime headers → ~971 KiB 🟢  ✅ DONE (mod_headers/expires/deflate added to public/.htaccess; Cache-Control verified served)
- Cause: no `Cache-Control`/`Expires` in `.htaccess`; server defaults to 4h TTL, so repeat visitors re-download everything.
- Fix: add `mod_expires` + `mod_headers` (and `mod_deflate` gzip) blocks to `public/.htaccess`; 1-year cache for images/fonts/CSS/JS.
- Safe because: CSS is cache-busted via `?v=N`; uploads use unique `time()_uuid` filenames.
- Verify: `mod_expires`/`mod_headers`/`mod_deflate` enabled on cPanel host (IfModule-guarded so it degrades gracefully).

### 2. Minify local CSS → ~18 KiB 🟢  ✅ DONE (scripts/minify-css.php; 6 files, 48 KiB/34% saved; calc() spacing preserved; links repointed to .min.css + version bumped)
- Files: `front-global`, `style`, `service-pages-shared`, `service-detail`, `service-category`, `slide-menu`.
- Fix: generate `.min.css`, keep readable sources, repeatable `scripts/minify-css.php`; bump `?v=`.
- gzip (item 1) already covers most of the real over-the-wire cost.

### 3. Self-host render-blocking CDN CSS → ~300 ms 🟢  ✅ DONE (Bootstrap)
- Cause: `bootstrap.min.css` (jsDelivr) blocks render ~1,950 ms via the extra cross-origin connection.
- DONE: Bootstrap 5.2.3 self-hosted at `public/front/assets/css/vendor/bootstrap-5.2.3.min.css`, byte-identical to CDN (SRI hash verified), same blocking position → no FOUC. NOTE: the legacy repo `bootstrap.min.css` is v4.1.3 — left untouched.
- DEFERRED to Phase 2: self-hosting font-awesome / line-awesome / bootstrap-icons / swiper / aos. Rationale: these are ALREADY loaded async (preload+onload), so not render-blocking; self-hosting the icon fonts means rewiring relative `../webfonts/`/`../fonts/` paths + ~15 font binaries, where any slip breaks icons (visible). Low reward (already CDN-cached) vs real regression risk.

### 4. Make flagged links crawlable (SEO) 🟢  ✅ DONE
- Cause: `href="javascript:void(0)"` on "Who We Are" (`group_about`) and the contact modal icon.
- DONE: `group_about` → `route('front.new-about')`; contact email icons (service / service_group / service_category) → `route('contact')`. JS / Bootstrap modal data-API already `preventDefault()`, so click behavior unchanged.

### 6. `font-display: swap` on icon fonts → ~30 ms 🟢
- DEFERRED to Phase 2 (tied to self-hosting the icon fonts — see #3). Google Fonts already swap.

## Tier 2 — Safe but more work (Phase 2)

### 5. Improve image delivery → ~466 KiB 🟡  🔧 IN PROGRESS
- Cause: originals served full-res for small boxes (e.g. 1734×986 shown 676×421); high-quality re-encode also bloats them.
- APPROACH CHOSEN: render-time helper `App\Support\Img::thumb($path, $width)` — generates a cached, down-scaled copy (same format, never upscaled) on first render, serves the static file after; the .htaccess 1-yr cache applies to it. Resize-only (NOT WebP) → zero browser-compat risk, no `<img>`→`<picture>` markup change. Gracefully falls back to the ORIGINAL on any error → can never break an image. No upload-controller changes needed (new uploads optimise on first view).
- IMPLEMENTED WITH NATIVE GD, not intervention/image: Intervention v4.1 uses PHP 8.3+ typed constants and the runtime (Apache) is **PHP 8.2.12** → Intervention parse-errors there (CLI is 8.5 so it falsely passed). Helper rewritten with bundled GD (imagecreatefrom*/imagecopyresampled/imagejpeg|imagepng); PNG alpha preserved. Works on 8.2.
- ✅ DONE + VERIFIED ON REAL RUNTIME (PHP 8.2 + GD via Apache): derivatives generate on render (e.g. magazine 1659 KB→59 KB, agent 988 KB→143 KB, ~85–95%/image); cache-hit + missing-file fallback correct; `<img>` src point at `uploads/cache/`. Wired into `service.blade.php` (magazine 900px, agent 300px, related-service/project/blog cards 800px), preserving all fallbacks + external-URL magazine branch. `public/uploads/cache` gitignored.
- ENV: enabled GD in Apache (`extension=gd`, `C:\xampp\php\php.ini` line 931) + Apache restarted. PROD (cPanel) — MUST verify GD is enabled; if absent, images just serve unoptimised (no breakage).
- PRE-EXISTING LOCAL QUIRK (not from this work): in local HTTP, `asset('public/...')` resolves to a double `…/public/public/…` URL (request root includes `/public`) that 404s locally because files sit at single `public/uploads`. This is the nested-`public/public/` setup that works on cPanel. Affects ALL upload images equally, before & after this change — not a regression; means local HTTP image *display* can't be the visual check, but server-side generation is confirmed working.
- POSSIBLE FUTURE: photographic PNGs stay PNG (e.g. agent 300px = 143 KB); converting opaque photos to JPEG/WebP would shrink further. Deferred to keep zero format-change risk.
- ✅ EXTENDED SITE-WIDE (all 200, verified, derivatives generating): `projects` (case studies), `index-2` (home — hero slider 1920 incl. matching preload, announcement bg, category/blog/project/update cards, client logos), `service_category` + `service_group` (hero, service/group cards, agent, related project/blog/service, magazine), `news-media` + `new-blog` (hero, featured, blog cards), `brands` + `brand_details` (hero + logos). Widths: hero/slider 1600–1920, feature 1000–1200, cards 600–800, logos 300–400.
- LEFT FULL-SIZE ON PURPOSE: `og:image` meta + lightbox `href`s (social/zoom need full res), non-upload defaults/placeholders, external-URL branches.
- MINOR UN-WIRED (low value, optional later): home category-dropdown hover-preview `@php` (interaction-only), magazine slider `data-img` thumbnails, single-announcement `<style>`-block bg (leaked loop var).
- UNRELATED PRE-EXISTING: `/blog` (legacy `HomeController@blog`, NOT touched here) returns 500 — separate issue to investigate.

## Tier 3 — Higher risk, defer + test in isolation (Phase 3)

### 7. Reduce unused CSS → ~72 KiB 🟡
- NOTE: Line Awesome (`la-`) and Bootstrap Icons (`bi-`) ARE in use (verified 62 `la-` usages / 9 front views) — do NOT delete whole libraries. Self-host+cache (#3) removes the network penalty; optional glyph subsetting needs a full icon audit.

### 8. Optimize DOM size → 1,263 elements 🔴
- Cause: mega-menu renders every service/blog/project into the DOM on every page (`service-content-2`, ~218 children).
- Option: AJAX-lazy-load menu content on open. Risky — hover/filter/mobile-accordion depend on those nodes existing. Defer with full menu regression test.

### 9. Forced reflow 🔴 / low value
- Dominated by third-party swiper/aos. Monitor only.

## Execution order
1. Phase 1: items 1, 2, 3, 4, 6.
2. Phase 2: item 5.
3. Phase 3: items 7, 8, 9 (dedicated testing).

## Conventions when implementing
- `asset('public/...')` paths (nested `/public/public/` on cPanel).
- `layout-2.blade.php` + `.htaccess` are host-drift sensitive — merge with live, never overwrite blindly.
- Image work likely needs no DB change; any column add → consolidated phpMyAdmin-ready SQL. Deploy zip only on request.
