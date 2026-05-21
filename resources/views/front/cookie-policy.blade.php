@extends('front/layout-2')

@push('page_title', 'Cookie Policy | Alpha Health Group')

@section('meta_description', 'Alpha Health Group\'s Cookie Policy — what cookies we use on our website, how they work, and how you can manage your cookie preferences.')

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

{{-- HERO --}}
<section class="cookie-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <span class="cookie-hero-badge"><i class="fas fa-cookie-bite me-2"></i>Cookie Policy</span>
                <h1>Cookie Policy</h1>
                <p class="lead">
                    This policy explains how Alpha Health Group uses cookies and similar technologies on our website, what data they collect, and how you can manage your preferences.
                </p>
                <div class="cookie-hero-pills">
                    <span class="cookie-hero-pill"><i class="fas fa-calendar-check"></i>Effective: 1 January 2025</span>
                    <span class="cookie-hero-pill"><i class="fas fa-sync-alt"></i>Last Reviewed: {{ date('F Y') }}</span>
                    <span class="cookie-hero-pill"><i class="fas fa-flag"></i>Applies: All website visitors</span>
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
                <nav class="cookie-toc">
                    <h4>On This Page</h4>
                    <ol>
                        <li><a href="#c1">What Are Cookies?</a></li>
                        <li><a href="#c2">Why We Use Cookies</a></li>
                        <li><a href="#c3">Types of Cookies</a></li>
                        <li><a href="#c4">Cookies We Use</a></li>
                        <li><a href="#c5">Third-Party Cookies</a></li>
                        <li><a href="#c6">Managing Preferences</a></li>
                        <li><a href="#c7">Browser Controls</a></li>
                        <li><a href="#c8">Do Not Track</a></li>
                        <li><a href="#c9">Policy Updates</a></li>
                        <li><a href="#c10">Contact Us</a></li>
                    </ol>
                </nav>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-9">

                <div class="cookie-info-box">
                    <p><i class="fas fa-cookie-bite me-2"></i>
                        When you visit our website, we may place small data files called "cookies" on your device. This policy explains exactly what cookies we use, why we use them, and the choices available to you. Our use of cookies complies with applicable laws including the UAE Federal Decree-Law No. 45 of 2021 on Personal Data Protection (PDPL) and EU ePrivacy Directive.
                    </p>
                </div>

                {{-- 1. What Are Cookies --}}
                <div class="cookie-section" id="c1">
                    <span class="cookie-section-label"><i class="fas fa-question-circle"></i>Section 1</span>
                    <h2>What Are Cookies?</h2>
                    <p>
                        Cookies are small text files placed on your computer, tablet, or mobile device when you visit a website. They are widely used to make websites work efficiently, to remember your preferences, and to provide basic reporting information to website owners.
                    </p>
                    <p>
                        Cookies cannot run programmes, carry viruses, or harm your device. They are unique to your browser and are not transferable between devices. Cookies may be:
                    </p>
                    <ul>
                        <li><strong>Session cookies</strong> — temporary files that expire when you close your browser.</li>
                        <li><strong>Persistent cookies</strong> — remain on your device for a set period or until manually deleted.</li>
                        <li><strong>First-party cookies</strong> — set directly by Alpha Health Group.</li>
                        <li><strong>Third-party cookies</strong> — set by third-party services operating on our website (e.g., analytics providers).</li>
                    </ul>
                    <hr class="cookie-divider">
                </div>

                {{-- 2. Why We Use Cookies --}}
                <div class="cookie-section" id="c2">
                    <span class="cookie-section-label"><i class="fas fa-question-circle"></i>Section 2</span>
                    <h2>Why We Use Cookies</h2>
                    <p>We use cookies to:</p>
                    <ul>
                        <li>Ensure our website functions correctly and securely</li>
                        <li>Remember your preferences and personalise your visit</li>
                        <li>Understand how visitors use our website so we can improve it</li>
                        <li>Measure the effectiveness of our content and service information</li>
                        <li>Deliver relevant information about our healthcare consultancy services</li>
                        <li>Maintain session security and prevent fraudulent activity</li>
                    </ul>
                    <p>We do <strong>not</strong> use cookies to collect or process sensitive personal health data, nor do we sell cookie data to any third party.</p>
                    <hr class="cookie-divider">
                </div>

                {{-- 3. Types of Cookies --}}
                <div class="cookie-section" id="c3">
                    <span class="cookie-section-label"><i class="fas fa-layer-group"></i>Section 3</span>
                    <h2>Types of Cookies We Use</h2>

                    <div class="cookie-type-grid">
                        <div class="cookie-type-card necessary">
                            <div class="cookie-type-icon"><i class="fas fa-lock"></i></div>
                            <h5>Strictly Necessary</h5>
                            <span class="cookie-type-badge badge-required">Always Active</span>
                            <p>Essential for the website to function. They enable core features like page navigation, security, and session management. The website cannot function properly without these cookies.</p>
                        </div>
                        <div class="cookie-type-card analytics">
                            <div class="cookie-type-icon"><i class="fas fa-chart-bar"></i></div>
                            <h5>Performance &amp; Analytics</h5>
                            <span class="cookie-type-badge badge-consent">Consent Required</span>
                            <p>Help us understand how visitors interact with our website by collecting anonymous data on pages visited, time spent, and traffic sources. Improves overall website performance.</p>
                        </div>
                        <div class="cookie-type-card functional">
                            <div class="cookie-type-icon"><i class="fas fa-sliders-h"></i></div>
                            <h5>Functional</h5>
                            <span class="cookie-type-badge badge-consent">Consent Required</span>
                            <p>Allow the website to remember choices you make (such as language or region preferences) and provide enhanced, personalised features to improve your experience.</p>
                        </div>
                        <div class="cookie-type-card marketing">
                            <div class="cookie-type-icon"><i class="fas fa-bullhorn"></i></div>
                            <h5>Marketing</h5>
                            <span class="cookie-type-badge badge-optional">Optional</span>
                            <p>Used to track visitors across websites and deliver relevant advertisements for our healthcare services. These do not store personal health data.</p>
                        </div>
                    </div>
                    <hr class="cookie-divider">
                </div>

                {{-- 4. Cookies We Use --}}
                <div class="cookie-section" id="c4">
                    <span class="cookie-section-label"><i class="fas fa-list"></i>Section 4</span>
                    <h2>Specific Cookies We Use</h2>
                    <p>The following table lists the main cookies placed by our website:</p>

                    <div class="cookie-table-wrap">
                        <table class="cookie-table">
                            <thead>
                                <tr>
                                    <th>Cookie Name</th>
                                    <th>Provider</th>
                                    <th>Type</th>
                                    <th>Purpose</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>XSRF-TOKEN</code></td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Prevents cross-site request forgery attacks. Ensures form submissions are secure.</td>
                                    <td>Session</td>
                                </tr>
                                <tr>
                                    <td><code>alphahmc_session</code></td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Maintains your user session and enables navigation across pages without re-authenticating.</td>
                                    <td>2 hours</td>
                                </tr>
                                <tr>
                                    <td><code>cookie_consent</code></td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-essential">Necessary</span></td>
                                    <td>Stores your cookie consent preferences so you are not shown the banner on every visit.</td>
                                    <td>6 months</td>
                                </tr>
                                <tr>
                                    <td><code>_ga</code></td>
                                    <td>Google Analytics</td>
                                    <td><span class="tag-analytics">Analytics</span></td>
                                    <td>Distinguishes unique users. Generates statistical data on how visitors use the website (IP anonymised).</td>
                                    <td>2 years</td>
                                </tr>
                                <tr>
                                    <td><code>_ga_*</code></td>
                                    <td>Google Analytics</td>
                                    <td><span class="tag-analytics">Analytics</span></td>
                                    <td>Used to persist session state across Google Analytics 4 measurement sessions.</td>
                                    <td>2 years</td>
                                </tr>
                                <tr>
                                    <td><code>_gid</code></td>
                                    <td>Google Analytics</td>
                                    <td><span class="tag-analytics">Analytics</span></td>
                                    <td>Distinguishes users. Used to throttle request rate.</td>
                                    <td>24 hours</td>
                                </tr>
                                <tr>
                                    <td><code>_gat</code></td>
                                    <td>Google Analytics</td>
                                    <td><span class="tag-analytics">Analytics</span></td>
                                    <td>Used to throttle the request rate to Google Analytics servers.</td>
                                    <td>1 minute</td>
                                </tr>
                                <tr>
                                    <td><code>lang_pref</code></td>
                                    <td>Alpha Health Group</td>
                                    <td><span class="tag-functional">Functional</span></td>
                                    <td>Stores your language or region preference for your next visit.</td>
                                    <td>1 year</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="mt-3" style="font-size:0.88rem; color: var(--muted);">
                        <i class="fas fa-info-circle me-1"></i> This list may not be exhaustive and is subject to change as we update or add services. We will update this table accordingly.
                    </p>
                    <hr class="cookie-divider">
                </div>

                {{-- 5. Third-Party Cookies --}}
                <div class="cookie-section" id="c5">
                    <span class="cookie-section-label"><i class="fas fa-external-link-alt"></i>Section 5</span>
                    <h2>Third-Party Cookies</h2>
                    <p>
                        Some cookies on our website are placed by third-party services. We do not control these cookies and their use is governed by the privacy policies of the respective providers. These include:
                    </p>
                    <ul>
                        <li><strong>Google Analytics</strong> — website traffic analysis. <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google Privacy Policy</a></li>
                        <li><strong>Google Tag Manager</strong> — tag management for analytics and marketing pixels.</li>
                        <li><strong>LinkedIn Insight Tag</strong> (if active) — professional audience analytics and retargeting.</li>
                        <li><strong>Meta Pixel</strong> (if active) — audience analytics for healthcare service promotion on Facebook and Instagram.</li>
                    </ul>
                    <p>
                        We ensure that all third-party providers we engage are bound by data processing agreements and comply with applicable data protection laws. We do not share identifiable patient data, clinical records, or health-related personal data with any third-party advertising platform.
                    </p>
                    <hr class="cookie-divider">
                </div>

                {{-- 6. Managing Preferences --}}
                <div class="cookie-section" id="c6">
                    <span class="cookie-section-label"><i class="fas fa-sliders-h"></i>Section 6</span>
                    <h2>Managing Your Cookie Preferences</h2>
                    <p>
                        When you first visit our website, you are presented with a cookie consent banner. You may choose to:
                    </p>
                    <ul>
                        <li><strong>Accept All Cookies</strong> — enables all cookie categories including analytics and marketing.</li>
                        <li><strong>Reject Non-Essential</strong> — only strictly necessary cookies will be placed.</li>
                    </ul>
                    <p>You can revisit your preferences at any time:</p>
                    <hr class="cookie-divider">
                </div>

                {{-- 7. Browser Controls --}}
                <div class="cookie-section" id="c7">
                    <span class="cookie-section-label"><i class="fas fa-globe"></i>Section 7</span>
                    <h2>Browser Cookie Controls</h2>
                    <p>
                        Most web browsers allow you to control cookies through their settings. You can typically:
                    </p>
                    <ul>
                        <li>View all cookies stored on your device and delete them individually or in bulk</li>
                        <li>Block all cookies or only third-party cookies</li>
                        <li>Clear all cookies when you close your browser</li>
                        <li>Set up notifications when a website tries to place a cookie</li>
                    </ul>
                    <p>Instructions for common browsers:</p>
                    <ul>
                        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" rel="noopener">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/en-ae/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Apple Safari</a></li>
                        <li><a href="https://support.microsoft.com/en-us/windows/delete-and-manage-cookies-168dab11-0753-043d-7c16-ede5947fc64d" target="_blank" rel="noopener">Microsoft Edge</a></li>
                    </ul>
                    <div class="cookie-info-box">
                        <p><i class="fas fa-exclamation-circle me-2"></i>
                            Please note: restricting or deleting strictly necessary cookies may impair the functionality of our website, including contact forms and secure page navigation.
                        </p>
                    </div>
                    <hr class="cookie-divider">
                </div>

                {{-- 8. Do Not Track --}}
                <div class="cookie-section" id="c8">
                    <span class="cookie-section-label"><i class="fas fa-user-secret"></i>Section 8</span>
                    <h2>Do Not Track Signals</h2>
                    <p>
                        Some browsers include a "Do Not Track" (DNT) feature that sends a signal to websites requesting that your browsing not be tracked. There is currently no universally accepted standard for how websites should respond to DNT signals. Our website does not currently respond to DNT browser signals; however, you can manage your preferences directly using the cookie consent options described in Section 6.
                    </p>
                    <hr class="cookie-divider">
                </div>

                {{-- 9. Policy Updates --}}
                <div class="cookie-section" id="c9">
                    <span class="cookie-section-label"><i class="fas fa-sync-alt"></i>Section 9</span>
                    <h2>Policy Updates</h2>
                    <p>
                        We may update this Cookie Policy from time to time to reflect changes in technology, regulation, or our service offering. The "Last Reviewed" date at the top of this page will be updated accordingly. Where changes are material, we will display a prominent notice on our website. Your continued use of the website following any update constitutes acceptance of the revised policy.
                    </p>
                    <hr class="cookie-divider">
                </div>

                {{-- 10. Contact --}}
                <div class="cookie-section" id="c10">
                    <span class="cookie-section-label"><i class="fas fa-envelope"></i>Section 10</span>
                    <h2>Contact Us</h2>
                    <p>If you have questions about this Cookie Policy or how we handle your data, please contact:</p>
                    <ul>
                        <li><strong>Alpha Health Group — Data Protection Officer</strong></li>
                        <li><strong>Email:</strong> <a href="mailto:dpo@alphahmc.com">dpo@alphahmc.com</a></li>
                        <li><strong>Address:</strong> UAE (Abu Dhabi &amp; Dubai offices)</li>
                        <li><strong>Phone:</strong> +971 3 780 2818</li>
                    </ul>
                    <p>
                        For broader data protection information, see our <a href="{{ route('front.gdpr-terms') }}">GDPR &amp; Data Protection Policy</a>.
                    </p>
                </div>

                {{-- CTA --}}
                <div class="cookie-cta-box">
                    <h3>Manage Your Cookie Preferences</h3>
                    <p>You can update your cookie consent at any time. Your preference will be saved for 6 months.</p>
                    <button class="btn-cp" onclick="document.getElementById('cookie-consent-banner') && (document.getElementById('cookie-consent-banner').style.display='flex'); localStorage.removeItem('cookie_consent');">
                        <i class="fas fa-sliders-h"></i> Update Preferences
                    </button>
                    <a href="mailto:dpo@alphahmc.com" class="btn-cp">
                        <i class="fas fa-envelope"></i> Contact DPO
                    </a>
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
