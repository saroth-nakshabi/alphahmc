@extends('front/layout-2')

@push('page_title', 'Cookie Policy | Alpha Health Group')

@section('meta_description', 'Alpha Health Group\'s Cookie Policy: which cookies and browser storage our website uses, what they do and how you can change your choice.')

@push('og_tags')
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="Cookie Policy | Alpha Health Group" />
    <meta property="og:description" content="Learn how Alpha Health Group uses cookies and similar technologies on its website." />
    <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('custom_css')
<style>
    :root {
        --navy: #003358;
        --teal: #009095;
        --amber: #f59e0b;
        --muted: #64748b;
        --bg: #f8fafc;
        --border: #e2e8f0;
    }
    body { font-family: 'Outfit', sans-serif; background: #fff; }

    /* HERO */
    .cookie-hero {
        background: linear-gradient(135deg, #003358 0%, #004f70 60%, #006b6f 100%);
        padding: 160px 0 90px;
        position: relative;
        overflow: hidden;
    }
    .cookie-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 70% 25%, rgba(0,144,149,0.2) 0%, transparent 55%);
        pointer-events: none;
    }
    .cookie-hero-badge {
        display: inline-block;
        background: rgba(255,255,255,0.13);
        border: 1px solid rgba(255,255,255,0.28);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 6px 18px;
        border-radius: 50px;
        margin-bottom: 22px;
    }
    .cookie-hero h1 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(2rem, 4.5vw, 3.4rem);
        color: #fff;
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 20px;
    }
    .cookie-hero p.lead {
        color: rgba(255,255,255,0.82);
        font-size: 1.05rem;
        max-width: 620px;
        line-height: 1.75;
    }
    .cookie-hero-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 32px;
    }
    .cookie-hero-pill {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.22);
        color: rgba(255,255,255,0.92);
        padding: 7px 18px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .cookie-hero-pill i { color: #4dd9dc; margin-right: 6px; }

    /* SIDEBAR TOC */
    .cookie-toc {
        background: var(--bg);
        border-radius: 16px;
        padding: 32px 30px;
        border: 1px solid var(--border);
        margin-bottom: 50px;
        position: sticky;
        top: 120px;
    }
    .cookie-toc h4 {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--teal);
        margin-bottom: 18px;
    }
    .cookie-toc ol { padding-left: 18px; margin: 0; }
    .cookie-toc li { margin-bottom: 8px; }
    .cookie-toc a {
        color: var(--navy);
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 500;
        transition: color 0.2s;
    }
    .cookie-toc a:hover { color: var(--teal); }

    /* BODY */
    .cookie-body { padding: 80px 0 100px; }

    .cookie-section { margin-bottom: 58px; padding-top: 16px; }
    .cookie-section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(245,158,11,0.1);
        color: #b45309;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 50px;
        margin-bottom: 14px;
    }
    .cookie-section h2 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(1.35rem, 2.5vw, 1.85rem);
        color: var(--navy);
        font-weight: 700;
        margin-bottom: 18px;
        line-height: 1.3;
    }
    .cookie-section p, .cookie-section li {
        font-size: 1rem;
        line-height: 1.9;
        color: #374151;
    }
    .cookie-section ul { padding-left: 22px; margin-bottom: 16px; }
    .cookie-section li { margin-bottom: 8px; }

    .cookie-divider {
        border: none;
        border-top: 2px solid #f1f5f9;
        margin: 56px 0 0;
    }

    /* INFO BOX */
    .cookie-info-box {
        background: #eef9f9;
        border-left: 4px solid var(--teal);
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        margin: 24px 0;
    }
    .cookie-info-box p { margin: 0; color: #0d4a4e; font-weight: 500; }

    /* COOKIE TYPE CARDS */
    .cookie-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
        gap: 20px;
        margin-top: 28px;
    }
    .cookie-type-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 26px 24px;
        transition: box-shadow 0.25s, transform 0.25s;
        position: relative;
        overflow: hidden;
    }
    .cookie-type-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
    }
    .cookie-type-card.necessary::before  { background: #22c55e; }
    .cookie-type-card.analytics::before  { background: #3b82f6; }
    .cookie-type-card.functional::before { background: var(--teal); }
    .cookie-type-card.marketing::before  { background: #a855f7; }

    .cookie-type-card:hover {
        box-shadow: 0 10px 28px rgba(0,51,88,0.09);
        transform: translateY(-3px);
    }
    .cookie-type-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.95rem;
        margin-bottom: 14px;
    }
    .cookie-type-card.necessary  .cookie-type-icon { background: #22c55e; }
    .cookie-type-card.analytics  .cookie-type-icon { background: #3b82f6; }
    .cookie-type-card.functional .cookie-type-icon { background: var(--teal); }
    .cookie-type-card.marketing  .cookie-type-icon { background: #a855f7; }

    .cookie-type-card h5 {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 6px;
    }
    .cookie-type-badge {
        display: inline-block;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 2px 10px;
        border-radius: 50px;
        margin-bottom: 12px;
    }
    .badge-required  { background: #dcfce7; color: #15803d; }
    .badge-consent   { background: #dbeafe; color: #1d4ed8; }
    .badge-optional  { background: #f3e8ff; color: #7e22ce; }

    .cookie-type-card p {
        font-size: 0.87rem;
        line-height: 1.65;
        color: var(--muted);
        margin: 0;
    }

    /* COOKIE TABLE */
    .cookie-table-wrap { overflow-x: auto; margin: 24px 0; }
    .cookie-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }
    .cookie-table th {
        background: var(--navy);
        color: #fff;
        padding: 12px 16px;
        text-align: left;
        font-weight: 700;
        white-space: nowrap;
    }
    .cookie-table th:first-child { border-radius: 8px 0 0 0; }
    .cookie-table th:last-child  { border-radius: 0 8px 0 0; }
    .cookie-table td {
        padding: 11px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
        color: #374151;
    }
    .cookie-table tr:last-child td { border-bottom: none; }
    .cookie-table tr:nth-child(even) td { background: var(--bg); }
    .cookie-table .tag-essential  { color: #15803d; font-weight: 700; }
    .cookie-table .tag-analytics  { color: #1d4ed8; font-weight: 700; }
    .cookie-table .tag-functional { color: #0369a1; font-weight: 700; }
    .cookie-table .tag-marketing  { color: #7e22ce; font-weight: 700; }

    /* CTA BOX */
    .cookie-cta-box {
        background: linear-gradient(135deg, #003358 0%, #006b6f 100%);
        border-radius: 20px;
        padding: 48px;
        color: #fff;
        margin-top: 60px;
        text-align: center;
    }
    .cookie-cta-box h3 {
        font-family: 'Libre Baskerville', serif;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .cookie-cta-box p {
        color: rgba(255,255,255,0.8);
        font-size: 1rem;
        margin-bottom: 28px;
    }
    .cookie-cta-box a.btn-cp {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        color: var(--navy);
        padding: 13px 30px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        margin: 6px;
        cursor: pointer;
        border: none;
    }
    .cookie-cta-box a.btn-cp:hover,
    .cookie-cta-box button.btn-cp:hover {
        background: var(--teal);
        color: #fff;
    }
    .cookie-cta-box button.btn-cp {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,0.15);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,0.4);
        padding: 13px 30px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        margin: 6px;
    }
    .cookie-cta-box button.btn-cp:hover {
        background: rgba(255,255,255,0.25);
        color: #fff;
    }

    @media (max-width: 991px) {
        .cookie-toc { display: none; }
        .cookie-hero { padding: 110px 20px 70px; }
        .cookie-cta-box { padding: 32px 22px; }
    }
</style>
@endsection

@section('content')

@php $privacyEmail = 'info@alphatsm.com'; @endphp

{{-- HERO --}}
<section class="cookie-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <span class="cookie-hero-badge">Cookie Policy</span>
                <h1>Cookie Policy</h1>
                <p class="lead">
                    Which cookies and browser storage alphahmc.com uses, what they do, and how you control them.
                </p>
                <div class="cookie-hero-pills">
                    <span class="cookie-hero-pill">Last updated: 19 October 2026</span>
                    <span class="cookie-hero-pill">Applies to all visitors of alphahmc.com</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- BODY --}}
<div class="cookie-body">
    <div class="container">
        <div class="row">

            {{-- Sidebar ToC --}}
            <div class="col-lg-3 d-none d-lg-block">
                <nav class="cookie-toc" aria-label="On this page">
                    <h4>On this page</h4>
                    <ol>
                        <li><a href="#c1">What cookies are</a></li>
                        <li><a href="#c2">Types we use</a></li>
                        <li><a href="#c3">Cookies and storage we use</a></li>
                        <li><a href="#c4">Your choices</a></li>
                        <li><a href="#c5">Browser controls</a></li>
                        <li><a href="#c6">Changes</a></li>
                        <li><a href="#c7">Contact</a></li>
                    </ol>
                </nav>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-9">

                <div class="cookie-info-box">
                    <p>
                        This page explains the cookies and similar technologies on our website. How we handle personal
                        data in general is set out in our <a href="{{ route('front.privacy-policy') }}">privacy policy</a>.
                    </p>
                </div>

                <div class="cookie-section" id="c1">
                    <span class="cookie-section-label">Section 1</span>
                    <h2>What cookies are</h2>
                    <p>
                        Cookies are small text files a website stores in your browser. Websites also use browser storage
                        (localStorage and sessionStorage) for the same kind of purpose. Some last only until you close the
                        browser; others stay for a set time. First-party cookies are set by alphahmc.com; third-party
                        cookies are set by other services, such as Google, that run on our pages.
                    </p>
                    <hr class="cookie-divider">
                </div>

                <div class="cookie-section" id="c2">
                    <span class="cookie-section-label">Section 2</span>
                    <h2>Types we use</h2>
                    <div class="cookie-type-grid">
                        <div class="cookie-type-card necessary">
                            <h5>Strictly necessary</h5>
                            <span class="cookie-type-badge badge-required">Always on</span>
                            <p>Keep the site working and secure: your session, form security, spam protection and remembering your cookie choice.</p>
                        </div>
                        <div class="cookie-type-card analytics">
                            <h5>Analytics</h5>
                            <span class="cookie-type-badge badge-consent">Controlled by your choice</span>
                            <p>Google Analytics measures which pages people visit and how they reach us, so we can improve the site.</p>
                        </div>
                        <div class="cookie-type-card marketing">
                            <h5>Advertising</h5>
                            <span class="cookie-type-badge badge-consent">Controlled by your choice</span>
                            <p>Google Ads measures whether a visit from one of our ads led to an inquiry or a WhatsApp message.</p>
                        </div>
                    </div>
                    <hr class="cookie-divider">
                </div>

                <div class="cookie-section" id="c3">
                    <span class="cookie-section-label">Section 3</span>
                    <h2>Cookies and storage we use</h2>
                    <div class="cookie-table-wrap">
                        <table class="cookie-table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Set by</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Purpose</th>
                                    <th scope="col">Lasts</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>alpha_main_website_session</code></td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Keeps your session while you move between pages and submit forms.</td>
                                    <td>2 hours</td>
                                </tr>
                                <tr>
                                    <td><code>XSRF-TOKEN</code></td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Protects forms against cross-site request forgery.</td>
                                    <td>2 hours</td>
                                </tr>
                                <tr>
                                    <td><code>cookie_consent</code> (browser storage)</td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Remembers whether you accepted or rejected analytics and advertising.</td>
                                    <td>30 days</td>
                                </tr>
                                <tr>
                                    <td>Session storage items</td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Remember, for this visit only, that you sent an inquiry or closed a message, so we do not show it again.</td>
                                    <td>Until you close the tab</td>
                                </tr>
                                <tr>
                                    <td>reCAPTCHA data</td>
                                    <td>Google reCAPTCHA</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Set on pages with a form, to tell people from spam bots.</td>
                                    <td>Set by Google</td>
                                </tr>
                                <tr>
                                    <td><code>_ga</code>, <code>_ga_*</code></td>
                                    <td>Google Analytics</td>
                                    <td><span class="tag-analytics">Analytics</span></td>
                                    <td>Count visits and pages, and tell returning visitors from new ones.</td>
                                    <td>2 years</td>
                                </tr>
                                <tr>
                                    <td><code>_gcl_au</code></td>
                                    <td>Google Ads</td>
                                    <td><span class="tag-marketing">Advertising</span></td>
                                    <td>Links an inquiry or WhatsApp message to the ad click that led to it.</td>
                                    <td>90 days</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-3" style="font-size:0.88rem; color: var(--muted);">
                        Google may also set its own cookies on google.com domains when its services load. These are covered by
                        <a href="https://policies.google.com/technologies/cookies" target="_blank" rel="noopener">Google's cookie policy</a>.
                    </p>
                    <hr class="cookie-divider">
                </div>

                <div class="cookie-section" id="c4">
                    <span class="cookie-section-label">Section 4</span>
                    <h2>Your choices</h2>
                    <p>Our cookie banner offers two choices:</p>
                    <ul>
                        <li><strong>Accept All:</strong> allows analytics and advertising cookies.</li>
                        <li><strong>Reject All:</strong> keeps only the strictly necessary cookies and storage.</li>
                    </ul>
                    <p>
                        For visitors in the EU, EEA, UK and Switzerland, analytics and advertising stay off until you click
                        Accept All. For visitors elsewhere, they are on until you click Reject All. Google tags follow your
                        choice through Google Consent Mode.
                    </p>
                    <p>Your choice is saved for 30 days. Use the button at the end of this page to change it.</p>
                    <hr class="cookie-divider">
                </div>

                <div class="cookie-section" id="c5">
                    <span class="cookie-section-label">Section 5</span>
                    <h2>Browser controls</h2>
                    <p>You can also block or delete cookies in your browser settings:</p>
                    <ul>
                        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" rel="noopener">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/en-ae/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Apple Safari</a></li>
                        <li><a href="https://support.microsoft.com/en-us/windows/delete-and-manage-cookies-168dab11-0753-043d-7c16-ede5947fc64d" target="_blank" rel="noopener">Microsoft Edge</a></li>
                    </ul>
                    <p>If you block strictly necessary cookies, forms on the site may not work.</p>
                    <p>Our site does not respond to "Do Not Track" browser signals. Use the cookie banner instead.</p>
                    <hr class="cookie-divider">
                </div>

                <div class="cookie-section" id="c6">
                    <span class="cookie-section-label">Section 6</span>
                    <h2>Changes</h2>
                    <p>We update this page when the cookies on our site change. The date at the top shows the latest version.</p>
                    <hr class="cookie-divider">
                </div>

                <div class="cookie-section" id="c7">
                    <span class="cookie-section-label">Section 7</span>
                    <h2>Contact</h2>
                    <ul>
                        <li><strong>Email:</strong> <a href="mailto:{{ $privacyEmail }}">{{ $privacyEmail }}</a></li>
                        <li><strong>Phone:</strong> <a href="tel:+97142724064">+971 4 272 4064</a></li>
                        <li><strong>Address:</strong> 1101, 11th Floor, Damas Tower, Al Maktoum Road, Dubai, United Arab Emirates</li>
                    </ul>
                </div>

                {{-- CTA --}}
                <div class="cookie-cta-box">
                    <h3>Change your cookie choice</h3>
                    <p>Open the cookie banner again and pick Accept All or Reject All.</p>
                    <button type="button" class="btn-cp" onclick="try { localStorage.removeItem('cookie_consent'); } catch (e) {} var b = document.getElementById('cookie-consent-banner'); if (b) { b.style.display = 'flex'; }">
                        Change cookie choice
                    </button>
                    <a href="mailto:{{ $privacyEmail }}" class="btn-cp">Email us</a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    document.querySelectorAll('.cookie-toc a[href^="#"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - 130, behavior: 'smooth' });
            }
        });
    });
</script>
@endsection
