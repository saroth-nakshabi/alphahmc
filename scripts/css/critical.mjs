// Extracts the inline critical CSS: resources/critical/<template>/<bundle>.css
//
// For each template it loads sample pages at five widths, keeps every rule from the site's own
// stylesheets that matches an element on the first screen (or a hidden element there, such as a
// closed menu or modal, so the rule that hides it is kept), and writes those rules per bundle in
// their original order. The layout inlines each part where its stylesheet sits, then loads the full
// file without blocking, so the cascade is the same before and after the full file arrives.
//
//   node scripts/css/critical.mjs [origin]      default origin https://alphahmc.com
//
// Re-run after any change to the source stylesheets or to the first screen of these templates.
import fs from 'fs';
import path from 'path';
import zlib from 'zlib';
import puppeteer from 'puppeteer-core';

const ROOT = path.resolve(path.dirname(new URL(import.meta.url).pathname.replace(/^\/(\w:)/, '$1')), '../..');
const ORIGIN = (process.argv[2] || 'https://alphahmc.com').replace(/\/$/, '');
const CHROME = process.env.CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const FOLD = 1.5; // first screen plus a margin, in viewport heights

const TEMPLATES = {
  service: ['/services/dha-license-healthcare-professionals-dubai', '/services/doh-license-healthcare-professionals-abu-dhabi',
    '/services/mohap-license-healthcare-professionals', '/services/healthcare-professional-license-transfer'],
  package: ['/service-packages/healthcare-professional-licensing-uae'],
  category: ['/service-category/facility-licensing-setup'],
  home: ['/'],
  blog: [],
};
const VIEWPORTS = [[360, 740, true], [412, 915, true], [768, 1024, false], [1366, 768, false], [1920, 1080, false]];

// Which bundle a stylesheet belongs to. Source files and the built bundles both map here, so the
// script works before and after the bundles are deployed.
function bundleOf(href, template) {
  const p = new URL(href).pathname.replace(/^\/public\//, '/');
  if (/\/bundle\/base\.min\.css$|bootstrap-5\.2\.3\.min\.css$|front-new\/assets\/css\/(style|slide-menu)\.min\.css$/.test(p)) return 'base';
  if (/front-global\.min\.css$/.test(p)) return 'front-global';
  if (/\/front\/assets\/css\/home\.css$/.test(p)) return 'home';
  if (/\/bundle\/(service|category|package)\.min\.css$/.test(p)) return p.match(/bundle\/(\w+)\.min/)[1];
  if (/service-pages-shared\.min\.css$|service-detail\.min\.css$|service-category\.min\.css$|service-group\.css$/.test(p)) return template;
  return null;
}

async function sampleUrls() {
  const xml = await (await fetch(ORIGIN + '/sitemap.xml')).text();
  const all = [...xml.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => new URL(m[1]).pathname);
  const take = (re, n) => all.filter(u => re.test(u)).slice(0, n);
  TEMPLATES.service.push(...take(/^\/services\//, 3));
  TEMPLATES.package.push(...take(/^\/service-packages\//, 2));
  TEMPLATES.category.push(...take(/^\/service-category\//, 3));
  TEMPLATES.blog.push(...take(/^\/blog_single\//, 3));
  for (const k of Object.keys(TEMPLATES)) TEMPLATES[k] = [...new Set(TEMPLATES[k])];
}

// Runs in the page: returns the kept rules of every mapped stylesheet.
function collect({ fold, sheetBundles }) {
  const vh = innerHeight * fold;
  const keep = new Set();
  for (const el of document.querySelectorAll('*')) {
    const rendered = el.getClientRects().length > 0;
    if (rendered) { if (el.getBoundingClientRect().top < vh) keep.add(el); }
    else if (el.parentElement && el.parentElement.getClientRects().length > 0 && el.parentElement.getBoundingClientRect().top < vh) keep.add(el);
  }
  keep.add(document.documentElement);
  // Replaced elements anywhere on the page: unstyled, an image or video below the fold renders at its
  // natural width and widens the phone viewport until the full CSS arrives (divs cannot overflow).
  for (const el of document.querySelectorAll('img,picture,video,iframe,svg,canvas,embed,object,table')) keep.add(el);
  // Elements wider than the screen with the full CSS (and their ancestors): kept so the page width is
  // the same before and after the full CSS arrives (no zoom change on phones).
  for (const el of document.querySelectorAll('body *')) {
    const r = el.getBoundingClientRect();
    if (r.width && (r.right > innerWidth + 1 || r.left < -1)) for (let a = el; a && a !== document.body; a = a.parentElement) keep.add(a);
  }
  const PSEUDO = /::?(hover|focus-visible|focus-within|focus|active|visited|link|before|after|placeholder|selection|marker|first-letter|first-line|backdrop|-webkit-[\w-]+|-moz-[\w-]+)(\([^()]*\))?/g;
  const matches = sel => {
    const base = sel.replace(PSEUDO, '').trim() || '*';
    try { for (const el of document.querySelectorAll(base)) if (keep.has(el)) return true; return false; } catch (e) { return true; }
  };
  const abs = (text, href) => text.replace(/url\(\s*(['"]?)([^'")]+)\1\s*\)/g, (m, q, u) =>
    /^(data:|#)/.test(u) ? m : `url("${new URL(u, href).pathname}")`);
  const out = [];
  const html = document.documentElement.innerHTML;
  const walk = (rules, href, bundle, sheetIdx, trail, wrappers, sheet = new URL(href).pathname) => {
    for (let i = 0; i < rules.length; i++) {
      const r = rules[i];
      const pos = trail.concat(i);
      if (r instanceof CSSStyleRule) {
        const sels = r.selectorText.split(/,(?![^(]*\))/).map(s => s.trim()).filter(matches);
        if (sels.length) out.push({ bundle, sheet, sheetIdx, pos, wrappers, sels, body: abs(r.style.cssText, href) });
      } else if (r instanceof CSSMediaRule || r instanceof CSSSupportsRule) {
        const w = r instanceof CSSMediaRule ? '@media ' + r.media.mediaText : '@supports ' + r.conditionText;
        walk(r.cssRules, href, bundle, sheetIdx, pos, wrappers.concat(w), sheet);
      } else if (r instanceof CSSKeyframesRule || r instanceof CSSFontFaceRule) {
        const name = r instanceof CSSKeyframesRule ? r.name : null;
        // Keyframes started from JS (element.style.animation) are named in the page's scripts.
        out.push({ bundle, sheet, sheetIdx, pos, wrappers, raw: abs(r.cssText, href), name, inHtml: !!name && html.includes(name) });
      }
    }
  };
  const sheets = [...document.styleSheets];
  sheets.forEach((s, idx) => {
    const bundle = s.href && sheetBundles[s.href];
    if (!bundle) return;
    try { walk(s.cssRules, s.href, bundle, idx, [], []); } catch (e) { /* cross-origin */ }
  });
  return out;
}

const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new' });
await sampleUrls();
const result = {};
const sheetOrder = new Map(); // source file -> position among the page's stylesheets
for (const [template, urls] of Object.entries(TEMPLATES)) {
  const rules = new Map(); // key -> rule, selectors merged across pages and widths
  for (const url of urls) {
    for (const [w, h, mobile] of VIEWPORTS) {
      const page = await browser.newPage();
      await page.setViewport({ width: w, height: h, isMobile: mobile, hasTouch: mobile });
      await page.setRequestInterception(true);
      page.on('request', r => /googletagmanager|google-analytics|googleadservices|doubleclick|facebook|recaptcha/.test(r.url()) ? r.abort() : r.continue());
      await page.goto(ORIGIN + url, { waitUntil: 'networkidle2', timeout: 90000 });
      // The cookie banner shows 1.5 s after DOMContentLoaded for first-time visitors; wait for it.
      await new Promise(r => setTimeout(r, 2500));
      const hrefs = await page.evaluate(() => [...document.styleSheets].map(s => s.href).filter(Boolean));
      const sheetBundles = Object.fromEntries(hrefs.map(h => [h, bundleOf(h, template)]).filter(([, b]) => b));
      const found = await page.evaluate(collect, { fold: FOLD, sheetBundles });
      for (const r of found) {
        // Keyed by bundle, source file and position in it: the same on every page and width.
        const id = `${r.bundle}|${r.sheet}|${r.pos.join('.')}`;
        if (!sheetOrder.has(template + r.sheet)) sheetOrder.set(template + r.sheet, r.sheetIdx);
        r.template = template;
        const prev = rules.get(id);
        if (!prev) rules.set(id, { ...r, sels: r.sels ? [...r.sels] : null });
        else if (r.inHtml) prev.inHtml = true;
        else if (r.sels) for (const sel of r.sels) if (!prev.sels.includes(sel)) prev.sels.push(sel);
      }
      await page.close();
    }
    process.stdout.write(`${template} ${url}\n`);
  }
  result[template] = rules;
}
await browser.close();

// Serialize: rules in source order, consecutive rules under the same @media/@supports share one block.
const cmp = (a, b) => {
  const s = sheetOrder.get(a.template + a.sheet) - sheetOrder.get(b.template + b.sheet);
  if (s) return s;
  for (let i = 0; i < Math.max(a.pos.length, b.pos.length); i++) {
    const d = (a.pos[i] ?? -1) - (b.pos[i] ?? -1);
    if (d) return d;
  }
  return 0;
};
const report = [];
for (const [template, rules] of Object.entries(result)) {
  const byBundle = {};
  for (const r of rules.values()) (byBundle[r.bundle] = byBundle[r.bundle] || []).push(r);
  const dir = path.join(ROOT, 'resources/critical', template);
  fs.rmSync(dir, { recursive: true, force: true });
  fs.mkdirSync(dir, { recursive: true });
  let total = 0;
  for (const [bundle, list] of Object.entries(byBundle)) {
    list.sort(cmp);
    const used = list.filter(r => r.body).map(r => r.body).join(' ');
    // @keyframes only when a kept rule names it; @font-face always.
    const kept = list.filter(r => !r.name || r.inHtml || used.includes(r.name));
    let css = '';
    let open = [];
    for (const r of kept) {
      let common = 0;
      while (common < open.length && common < r.wrappers.length && open[common] === r.wrappers[common]) common++;
      css += '}'.repeat(open.length - common);
      for (const w of r.wrappers.slice(common)) css += w + '{';
      open = r.wrappers.slice();
      css += r.raw ? r.raw : `${r.sels.join(',')}{${r.body}}`;
    }
    css += '}'.repeat(open.length);
    css = css.replace(/\s*\n\s*/g, ' ');
    fs.writeFileSync(path.join(dir, `${bundle}.css`), css + '\n');
    const gz = zlib.gzipSync(css).length;
    total += gz;
    report.push(`${template}/${bundle}.css  ${(css.length / 1024).toFixed(1)} KB, ${(gz / 1024).toFixed(1)} KB gzip, ${kept.length} rules`);
  }
  report.push(`${template} total ${(total / 1024).toFixed(1)} KB gzip`);
}
console.log(report.join('\n'));
