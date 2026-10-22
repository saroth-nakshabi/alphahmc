// Builds the merged stylesheets in public/front/assets/css/bundle/ and their manifest.
//
//   base.min.css      Bootstrap 5.2.3 (unused selectors removed) + style.min.css + slide-menu.min.css
//   service.min.css   service-pages-shared + service-detail + service-category   (service.blade.php)
//   category.min.css  service-pages-shared + service-category                    (service_category.blade.php)
//   package.min.css   service-pages-shared + service-group                       (service_group.blade.php)
//
// Files are concatenated in their original order, so the cascade is unchanged; relative url()s are
// rewritten to absolute /public/... paths. Edit the source files, then rebuild and re-run critical.mjs:
//
//   npm i --no-save purgecss@6.0.0 puppeteer-core@23.11.1
//   node scripts/css/build.mjs
//   node scripts/css/critical.mjs            (needs the pages live or on a local copy, see that file)
//
// Bootstrap is purged against the front Blade views, app/ PHP, the site JS, the Bootstrap/Swiper JS bundles
// and scripts/css/db-classes.txt (class names used in HTML stored in the database; refresh it when
// editors add new markup). Component families toggled at runtime are kept whole (SAFELIST).
import fs from 'fs';
import path from 'path';
import crypto from 'crypto';
import { PurgeCSS } from 'purgecss';

const ROOT = path.resolve(path.dirname(new URL(import.meta.url).pathname.replace(/^\/(\w:)/, '$1')), '../..');
const PUB = path.join(ROOT, 'public');
const OUT = path.join(PUB, 'front/assets/css/bundle');

const BUNDLES = {
  base: ['front/assets/css/vendor/bootstrap-5.2.3.min.css', 'front-new/assets/css/style.min.css', 'front-new/assets/css/slide-menu.min.css'],
  service: ['front/assets/css/service-pages-shared.min.css', 'front/assets/css/service-detail.min.css', 'front/assets/css/service-category.min.css'],
  category: ['front/assets/css/service-pages-shared.min.css', 'front/assets/css/service-category.min.css'],
  package: ['front/assets/css/service-pages-shared.min.css', 'front/assets/css/service-group.css'],
};
const PURGE = new Set(['front/assets/css/vendor/bootstrap-5.2.3.min.css']);

// Kept whole: state classes Bootstrap JS adds, and components that DB content or plugins may use.
const SAFELIST = {
  standard: ['show', 'showing', 'hide', 'hiding', 'fade', 'active', 'disabled', 'collapsing', 'was-validated', 'is-valid', 'is-invalid',
    'visually-hidden', 'visually-hidden-focusable', 'sr-only', 'img-fluid', 'img-thumbnail', 'figure', 'figure-img', 'figure-caption',
    'lead', 'small', 'mark', 'blockquote', 'blockquote-footer', 'list-unstyled', 'list-inline', 'list-inline-item', 'clearfix'],
  // Other families need no entry: the Bootstrap JS bundle is part of the content, so its state classes are kept.
  greedy: [/^modal/, /^collapse/, /^accordion/, /^dropdown/, /^tooltip/, /^bs-tooltip/],
};

const walk = (dir, re, out = []) => {
  if (!fs.existsSync(dir)) return out;
  for (const f of fs.readdirSync(dir)) {
    const p = path.join(dir, f);
    if (fs.statSync(p).isDirectory()) { if (f !== 'node_modules' && f !== 'bundle') walk(p, re, out); } else if (re.test(f)) out.push(p);
  }
  return out;
};

function absolutizeUrls(css, srcRel) {
  const dir = path.posix.dirname('/public/' + srcRel);
  return css.replace(/url\(\s*(['"]?)([^'")]+)\1\s*\)/g, (m, q, u) =>
    /^(data:|https?:|\/|#)/.test(u) ? m : `url(${q}${path.posix.normalize(path.posix.join(dir, u))}${q})`);
}

async function purged(srcRel) {
  const content = [
    // Only views rendered inside the front layout (dashboard, mail and Breeze views use their own CSS).
    ...['front', 'errors', 'partials', 'components', 'vendor'].flatMap(d => walk(path.join(ROOT, 'resources/views', d), /\.php$/)),
    ...walk(path.join(ROOT, 'app'), /\.php$/),
    ...walk(path.join(PUB, 'front/assets/js'), /\.js$/),
    ...walk(path.join(PUB, 'front-new/assets/js'), /\.js$/),
    ...walk(path.join(ROOT, 'scripts/css/vendor-js'), /\.js$/),
    path.join(ROOT, 'scripts/css/db-classes.txt'),
  ];
  const [res] = await new PurgeCSS().purge({
    content, css: [path.join(PUB, srcRel)], safelist: SAFELIST,
    // Blade/JS tokens: class names, including ones next to quotes, braces or dots.
    defaultExtractor: s => s.match(/[A-Za-z0-9_-]+/g) || [],
    fontFace: false, keyframes: false, variables: false,
  });
  return res.css;
}

fs.mkdirSync(OUT, { recursive: true });
const manifest = {};
const cache = {};
for (const [name, files] of Object.entries(BUNDLES)) {
  const parts = [];
  for (const f of files) {
    if (!(f in cache)) cache[f] = absolutizeUrls(PURGE.has(f) ? await purged(f) : fs.readFileSync(path.join(PUB, f), 'utf8'), f);
    parts.push(`/* ${f} */\n` + cache[f].trim());
  }
  const css = parts.join('\n') + '\n';
  fs.writeFileSync(path.join(OUT, `${name}.min.css`), css);
  manifest[`${name}.min.css`] = crypto.createHash('md5').update(css).digest('hex').slice(0, 10);
  console.log(`${name}.min.css  ${(css.length / 1024).toFixed(1)} KB`);
}
fs.writeFileSync(path.join(OUT, 'manifest.json'), JSON.stringify(manifest, null, 2) + '\n');
const bs = path.join(PUB, 'front/assets/css/vendor/bootstrap-5.2.3.min.css');
console.log(`bootstrap ${(fs.statSync(bs).size / 1024).toFixed(1)} KB -> ${(cache['front/assets/css/vendor/bootstrap-5.2.3.min.css'].length / 1024).toFixed(1)} KB`);
