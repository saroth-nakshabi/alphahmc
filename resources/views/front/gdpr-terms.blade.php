@extends('front/layout-2')

@push('page_title', 'GDPR & Data Protection Policy | Alpha Health Group')

@section('meta_description', 'Alpha Health Group\'s GDPR and Data Protection Policy — how we collect, use, store, and protect your personal data in compliance with GDPR and UAE PDPL regulations.')

@push('og_tags')
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="GDPR & Data Protection Policy | Alpha Health Group" />
    <meta property="og:description" content="How Alpha Health Group collects, processes, and protects personal data under GDPR and UAE PDPL." />
    <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('custom_css')
<style>
    :root {
        --navy: #003358;
        --red:  #e50303;
        --teal: #009095;
        --muted: #64748b;
        --bg: #f8fafc;
    }

    body { font-family: 'Outfit', sans-serif; background: #fff; }

    /* ── HERO ───────────────────────────────────── */
    .gdpr-hero {
        background: linear-gradient(135deg, #003358 0%, #00527a 100%);
        padding: 160px 0 90px;
        position: relative;
        overflow: hidden;
    }
    .gdpr-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 75% 30%, rgba(0,144,149,0.18) 0%, transparent 55%);
        pointer-events: none;
    }
    .gdpr-badge {
        display: inline-block;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 6px 18px;
        border-radius: 50px;
        margin-bottom: 22px;
    }
    .gdpr-hero h1 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(2rem, 4.5vw, 3.4rem);
        color: #fff;
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 20px;
    }
    .gdpr-hero p.lead {
        color: rgba(255,255,255,0.8);
        font-size: 1.05rem;
        max-width: 620px;
        line-height: 1.75;
    }
    .gdpr-meta-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 32px;
    }
    .gdpr-meta-pill {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.9);
        padding: 7px 18px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .gdpr-meta-pill i { color: #4dd9dc; margin-right: 6px; }

    /* ── TABLE OF CONTENTS ──────────────────────── */
    .gdpr-toc {
        background: var(--bg);
        border-radius: 16px;
        padding: 32px 36px;
        border: 1px solid #e2e8f0;
        margin-bottom: 50px;
        position: sticky;
        top: 120px;
    }
    .gdpr-toc h4 {
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--teal);
        margin-bottom: 18px;
    }
    .gdpr-toc ol {
        padding-left: 18px;
        margin: 0;
    }
    .gdpr-toc li {
        margin-bottom: 8px;
    }
    .gdpr-toc a {
        color: var(--navy);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        transition: color 0.2s;
    }
    .gdpr-toc a:hover { color: var(--red); }

    /* ── MAIN CONTENT ───────────────────────────── */
    .gdpr-body {
        padding: 80px 0 100px;
    }

    .gdpr-section {
        margin-bottom: 60px;
        padding-top: 20px;
    }

    .gdpr-section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff0f0;
        color: var(--red);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 50px;
        margin-bottom: 14px;
    }

    .gdpr-section h2 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(1.4rem, 2.5vw, 1.9rem);
        color: var(--navy);
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .gdpr-section p,
    .gdpr-section li {
        font-size: 1rem;
        line-height: 1.85;
        color: #374151;
    }

    .gdpr-section ul,
    .gdpr-section ol {
        padding-left: 22px;
        margin-bottom: 16px;
    }

    .gdpr-section li { margin-bottom: 8px; }

    .gdpr-divider {
        border: none;
        border-top: 2px solid #f1f5f9;
        margin: 60px 0 0;
    }

    /* ── HIGHLIGHT BOXES ────────────────────────── */
    .gdpr-info-box {
        background: #eef9f9;
        border-left: 4px solid var(--teal);
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        margin: 24px 0;
    }
    .gdpr-info-box p { margin: 0; color: #0d4a4e; font-weight: 500; }

    .gdpr-warn-box {
        background: #fff8e1;
        border-left: 4px solid #f59e0b;
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        margin: 24px 0;
    }
    .gdpr-warn-box p { margin: 0; color: #78350f; font-weight: 500; }

    /* ── RIGHTS GRID ────────────────────────────── */
    .rights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-top: 28px;
    }
    .rights-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 26px 24px;
        transition: box-shadow 0.25s, transform 0.25s;
    }
    .rights-card:hover {
        box-shadow: 0 12px 30px rgba(0,51,88,0.08);
        transform: translateY(-3px);
    }
    .rights-icon {
        width: 46px;
        height: 46px;
        background: linear-gradient(135deg, #003358, #00527a);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        margin-bottom: 16px;
    }
    .rights-card h5 {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 8px;
    }
    .rights-card p {
        font-size: 0.88rem;
        line-height: 1.65;
        color: var(--muted);
        margin: 0;
    }

    /* ── CONTACT BOX ────────────────────────────── */
    .gdpr-contact-box {
        background: linear-gradient(135deg, #003358 0%, #00527a 100%);
        border-radius: 20px;
        padding: 48px;
        color: #fff;
        margin-top: 60px;
        text-align: center;
    }
    .gdpr-contact-box h3 {
        font-family: 'Libre Baskerville', serif;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .gdpr-contact-box p {
        color: rgba(255,255,255,0.8);
        font-size: 1rem;
        margin-bottom: 28px;
    }
    .gdpr-contact-box a.btn-contact {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        color: var(--navy);
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        margin: 6px;
    }
    .gdpr-contact-box a.btn-contact:hover {
        background: var(--red);
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .gdpr-toc { display: none; }
        .gdpr-hero { padding: 110px 20px 70px; }
    }
</style>
@endsection

@section('content')

{{-- HERO --}}
<section class="gdpr-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <span class="gdpr-badge"><i class="fas fa-shield-alt me-2"></i>Data Protection</span>
                <h1>GDPR & Data<br>Protection Policy</h1>
                <p class="lead">
                    This policy explains how Alpha Health Group collects, processes, stores and protects
                    personal data in full compliance with the EU General Data Protection Regulation (GDPR)
                    and UAE Federal Decree-Law No. 45 of 2021 on Personal Data Protection (UAE PDPL).
                </p>
                <div class="gdpr-meta-pills">
                    <span class="gdpr-meta-pill"><i class="fas fa-calendar-check"></i>Effective: 1 January 2025</span>
                    <span class="gdpr-meta-pill"><i class="fas fa-sync-alt"></i>Last Reviewed: {{ date('F Y') }}</span>
                    <span class="gdpr-meta-pill"><i class="fas fa-flag"></i>Applies: UAE & Global</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- BODY --}}
<div class="gdpr-body">
    <div class="container">
        <div class="row">

            {{-- Sidebar ToC --}}
            <div class="col-lg-3 d-none d-lg-block">
                <nav class="gdpr-toc">
                    <h4>On This Page</h4>
                    <ol>
                        <li><a href="#s1">Data Controller</a></li>
                        <li><a href="#s2">Data We Collect</a></li>
                        <li><a href="#s3">Lawful Basis</a></li>
                        <li><a href="#s4">How We Use Your Data</a></li>
                        <li><a href="#s5">Sharing Your Data</a></li>
                        <li><a href="#s6">International Transfers</a></li>
                        <li><a href="#s7">Data Retention</a></li>
                        <li><a href="#s8">Your Rights</a></li>
                        <li><a href="#s9">Cookies</a></li>
                        <li><a href="#s10">Security</a></li>
                        <li><a href="#s11">Children's Data</a></li>
                        <li><a href="#s12">Policy Updates</a></li>
                        <li><a href="#s13">Contact Us</a></li>
                    </ol>
                </nav>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-9">

                <div class="gdpr-info-box">
                    <p><i class="fas fa-info-circle me-2"></i>
                        Alpha Health Group is committed to protecting your privacy. This policy applies to all personal data
                        processed through our website, consultancy services, and client engagements. Please read it carefully.
                    </p>
                </div>

                {{-- 1. Data Controller --}}
                <div class="gdpr-section" id="s1">
                    <span class="gdpr-section-label"><i class="fas fa-building"></i>Section 1</span>
                    <h2>Data Controller</h2>
                    <p>
                        The data controller responsible for your personal information is:
                    </p>
                    <ul>
                        <li><strong>Company Name:</strong> Alpha Health Group</li>
                        <li><strong>Address:</strong> UAE (registered offices in Abu Dhabi & Dubai)</li>
                        <li><strong>Email:</strong> <a href="mailto:info@alphahmc.com">info@alphahmc.com</a></li>
                        <li><strong>Phone:</strong> +971 4 272 4064</li>
                    </ul>
                    <p>
                        For all data protection enquiries or to exercise your rights, please contact our
                        <strong>Data Protection Officer (DPO)</strong> at
                        <a href="mailto:dpo@alphahmc.com">dpo@alphahmc.com</a>.
                    </p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 2. Data We Collect --}}
                <div class="gdpr-section" id="s2">
                    <span class="gdpr-section-label"><i class="fas fa-database"></i>Section 2</span>
                    <h2>Personal Data We Collect</h2>
                    <p>We may collect and process the following categories of personal data:</p>

                    <strong>Identity & Contact Data</strong>
                    <ul>
                        <li>Full name, job title, and employer / facility name</li>
                        <li>Email address, telephone number, and postal address</li>
                    </ul>

                    <strong>Professional & Engagement Data</strong>
                    <ul>
                        <li>Details of your healthcare facility (type, size, licensing status)</li>
                        <li>Correspondence, inquiries, and service requests submitted to us</li>
                        <li>Records of training, accreditation, or consultancy projects</li>
                    </ul>

                    <strong>Technical & Usage Data</strong>
                    <ul>
                        <li>IP address, browser type, and device information</li>
                        <li>Pages visited, time spent on site, and referral source</li>
                        <li>Cookie identifiers and session tokens (see Section 9)</li>
                    </ul>

                    <strong>Marketing Preferences</strong>
                    <ul>
                        <li>Your preferences for receiving communications from us</li>
                        <li>Responses to surveys and feedback forms</li>
                    </ul>

                    <div class="gdpr-warn-box">
                        <p><i class="fas fa-exclamation-triangle me-2"></i>
                            We do <strong>not</strong> collect or process special category data (e.g., health records, biometric
                            data, racial or ethnic origin) through this website. Consultancy engagements may involve
                            de-identified or aggregated facility data only, governed by separate contractual terms.
                        </p>
                    </div>
                    <hr class="gdpr-divider">
                </div>

                {{-- 3. Lawful Basis --}}
                <div class="gdpr-section" id="s3">
                    <span class="gdpr-section-label"><i class="fas fa-balance-scale"></i>Section 3</span>
                    <h2>Lawful Basis for Processing</h2>
                    <p>Under GDPR Article 6, we rely on the following lawful bases:</p>
                    <ul>
                        <li><strong>Consent (Art. 6(1)(a)):</strong> Where you have freely given, specific, and informed consent — e.g., subscribing to our newsletter.</li>
                        <li><strong>Contract (Art. 6(1)(b)):</strong> Processing necessary to perform a contract with you, or at your request prior to entering a contract.</li>
                        <li><strong>Legal Obligation (Art. 6(1)(c)):</strong> Where processing is required to comply with UAE regulatory or GDPR obligations.</li>
                        <li><strong>Legitimate Interests (Art. 6(1)(f)):</strong> To maintain and improve our website, prevent fraud, and communicate professionally with existing clients. We have balanced our interests against your rights and confirmed these do not override your fundamental freedoms.</li>
                    </ul>
                    <hr class="gdpr-divider">
                </div>

                {{-- 4. How We Use Data --}}
                <div class="gdpr-section" id="s4">
                    <span class="gdpr-section-label"><i class="fas fa-cogs"></i>Section 4</span>
                    <h2>How We Use Your Personal Data</h2>
                    <p>We use collected data for the following purposes:</p>
                    <ul>
                        <li>Responding to service inquiries and providing consultancy proposals</li>
                        <li>Delivering contracted healthcare management and accreditation services</li>
                        <li>Sending relevant healthcare industry updates, insights, and newsletters (with consent)</li>
                        <li>Administering, maintaining, and improving our website and digital services</li>
                        <li>Complying with UAE DOH, DHA, MOH licensing, and other regulatory requirements</li>
                        <li>Conducting analytics to understand how our services are used and improve them</li>
                        <li>Preventing fraudulent or unauthorised activity</li>
                    </ul>
                    <p>
                        We will only use your personal data for the purposes for which it was collected, unless we
                        reasonably consider that we need to use it for another reason compatible with the original purpose
                        and the law permits this.
                    </p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 5. Sharing --}}
                <div class="gdpr-section" id="s5">
                    <span class="gdpr-section-label"><i class="fas fa-share-alt"></i>Section 5</span>
                    <h2>Sharing Your Personal Data</h2>
                    <p>
                        We do <strong>not sell, rent, or trade</strong> personal data. We may share data with:
                    </p>
                    <ul>
                        <li><strong>Service Providers:</strong> IT hosting, CRM, email delivery, and analytics providers acting as data processors under binding contracts.</li>
                        <li><strong>Regulatory Authorities:</strong> UAE government bodies (DOH, DHA, MOH, HAAD) where required by law or licensing.</li>
                        <li><strong>Professional Advisers:</strong> Legal, accounting, or insurance advisers under strict confidentiality obligations.</li>
                        <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or asset sale, personal data may be transferred; we will notify affected individuals in advance.</li>
                    </ul>
                    <p>All third parties are required to respect data security and process personal data only for specified, lawful purposes.</p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 6. International Transfers --}}
                <div class="gdpr-section" id="s6">
                    <span class="gdpr-section-label"><i class="fas fa-globe"></i>Section 6</span>
                    <h2>International Data Transfers</h2>
                    <p>
                        Your data is primarily stored and processed in the UAE. Where data is transferred outside the UAE
                        or European Economic Area (EEA), we ensure appropriate safeguards are in place, including:
                    </p>
                    <ul>
                        <li>European Commission adequacy decisions</li>
                        <li>Standard Contractual Clauses (SCCs) approved by the European Commission</li>
                        <li>Binding Corporate Rules (BCRs) where applicable</li>
                        <li>UAE PDPL Article 22 transfer requirements</li>
                    </ul>
                    <hr class="gdpr-divider">
                </div>

                {{-- 7. Retention --}}
                <div class="gdpr-section" id="s7">
                    <span class="gdpr-section-label"><i class="fas fa-clock"></i>Section 7</span>
                    <h2>Data Retention</h2>
                    <p>
                        We retain personal data only for as long as necessary to fulfil the purposes for which it was
                        collected, including satisfying legal, accounting, or reporting requirements.
                    </p>
                    <ul>
                        <li><strong>Website enquiries:</strong> 2 years from last contact</li>
                        <li><strong>Contractual / client records:</strong> 7 years from project completion (UAE commercial law)</li>
                        <li><strong>Newsletter / marketing:</strong> Until consent is withdrawn or 3 years of inactivity</li>
                        <li><strong>Website analytics (cookies):</strong> Up to 13 months</li>
                    </ul>
                    <p>
                        Upon expiry, data is securely deleted or anonymised. You may request early deletion subject to
                        legal obligations — see Section 8.
                    </p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 8. Rights --}}
                <div class="gdpr-section" id="s8">
                    <span class="gdpr-section-label"><i class="fas fa-user-shield"></i>Section 8</span>
                    <h2>Your Data Subject Rights</h2>
                    <p>
                        Under GDPR and the UAE PDPL, you have the following rights regarding your personal data.
                        Requests are free of charge and responded to within <strong>30 days</strong>.
                    </p>
                    <div class="rights-grid">
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-eye"></i></div>
                            <h5>Right of Access</h5>
                            <p>Request a copy of the personal data we hold about you and information on how it is processed.</p>
                        </div>
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-edit"></i></div>
                            <h5>Right to Rectification</h5>
                            <p>Ask us to correct inaccurate or incomplete personal data without undue delay.</p>
                        </div>
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-trash-alt"></i></div>
                            <h5>Right to Erasure</h5>
                            <p>Request deletion of your personal data where there is no compelling reason for its continued processing.</p>
                        </div>
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-ban"></i></div>
                            <h5>Right to Restriction</h5>
                            <p>Ask us to suspend processing your data in certain circumstances — e.g., while accuracy is contested.</p>
                        </div>
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-download"></i></div>
                            <h5>Right to Portability</h5>
                            <p>Receive your data in a structured, machine-readable format and transmit it to another controller.</p>
                        </div>
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-hand-paper"></i></div>
                            <h5>Right to Object</h5>
                            <p>Object to processing based on legitimate interests or for direct marketing purposes at any time.</p>
                        </div>
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-undo"></i></div>
                            <h5>Withdraw Consent</h5>
                            <p>Where processing relies on consent, you may withdraw it at any time without affecting prior lawful processing.</p>
                        </div>
                        <div class="rights-card">
                            <div class="rights-icon"><i class="fas fa-gavel"></i></div>
                            <h5>Right to Complain</h5>
                            <p>Lodge a complaint with your local supervisory authority (e.g., UAE TDRA or EU lead supervisory authority).</p>
                        </div>
                    </div>
                    <hr class="gdpr-divider">
                </div>

                {{-- 9. Cookies --}}
                <div class="gdpr-section" id="s9">
                    <span class="gdpr-section-label"><i class="fas fa-cookie-bite"></i>Section 9</span>
                    <h2>Cookies &amp; Tracking Technologies</h2>
                    <p>Our website uses cookies to distinguish you from other users, enhance your experience, and analyse traffic. We use:</p>
                    <ul>
                        <li><strong>Strictly Necessary Cookies:</strong> Essential for site functionality (session management, security). Cannot be disabled.</li>
                        <li><strong>Performance / Analytics Cookies:</strong> Google Analytics to understand usage patterns (anonymised IP). Require consent.</li>
                        <li><strong>Functional Cookies:</strong> Remember your preferences (language, region). Require consent.</li>
                        <li><strong>Marketing Cookies:</strong> Used to deliver relevant advertisements. Require consent.</li>
                    </ul>
                    <p>
                        You can manage cookie preferences via the cookie consent banner displayed on your first visit,
                        or through your browser settings. Note that disabling certain cookies may affect site functionality.
                    </p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 10. Security --}}
                <div class="gdpr-section" id="s10">
                    <span class="gdpr-section-label"><i class="fas fa-lock"></i>Section 10</span>
                    <h2>Security Measures</h2>
                    <p>
                        We implement appropriate technical and organisational measures to protect your personal data
                        against unauthorised access, loss, or destruction, including:
                    </p>
                    <ul>
                        <li>TLS/SSL encryption for all data in transit</li>
                        <li>Encryption of sensitive data at rest</li>
                        <li>Role-based access controls and least-privilege principles</li>
                        <li>Regular security assessments and penetration testing</li>
                        <li>Staff data protection training and confidentiality agreements</li>
                        <li>Incident response plan with 72-hour breach notification (GDPR Art. 33)</li>
                    </ul>
                    <p>
                        Despite these measures, no internet transmission is completely secure. If you suspect a security
                        incident, please notify us immediately at
                        <a href="mailto:security@alphahmc.com">security@alphahmc.com</a>.
                    </p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 11. Children --}}
                <div class="gdpr-section" id="s11">
                    <span class="gdpr-section-label"><i class="fas fa-child"></i>Section 11</span>
                    <h2>Children's Personal Data</h2>
                    <p>
                        Our services are directed exclusively at healthcare professionals and organisations. We do not
                        knowingly collect personal data from individuals under the age of 18. If we become aware that
                        data from a minor has been collected without appropriate consent, we will delete it promptly.
                    </p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 12. Updates --}}
                <div class="gdpr-section" id="s12">
                    <span class="gdpr-section-label"><i class="fas fa-sync-alt"></i>Section 12</span>
                    <h2>Policy Updates</h2>
                    <p>
                        We review this policy at least annually and whenever significant changes occur in our data
                        processing activities or applicable law. Material changes will be communicated by:
                    </p>
                    <ul>
                        <li>Posting a notice on this page with the updated effective date</li>
                        <li>Email notification to registered users where the change materially affects their rights</li>
                    </ul>
                    <p>
                        We encourage you to review this page periodically. Your continued use of our services after
                        a policy update constitutes acceptance of the revised terms.
                    </p>
                    <hr class="gdpr-divider">
                </div>

                {{-- 13. Contact --}}
                <div class="gdpr-section" id="s13">
                    <span class="gdpr-section-label"><i class="fas fa-envelope"></i>Section 13</span>
                    <h2>Contact &amp; Complaints</h2>
                    <p>
                        To exercise any of your rights, submit a data subject request, or raise a concern, please
                        contact our Data Protection Officer:
                    </p>
                    <ul>
                        <li><strong>Email:</strong> <a href="mailto:dpo@alphahmc.com">dpo@alphahmc.com</a></li>
                        <li><strong>Post:</strong> Data Protection Officer, Alpha Health Group, UAE</li>
                        <li><strong>Response time:</strong> Within 30 calendar days</li>
                    </ul>
                    <p>
                        If you are not satisfied with our response, you have the right to lodge a complaint with:
                    </p>
                    <ul>
                        <li><strong>UAE:</strong> Telecommunications and Digital Government Regulatory Authority (TDRA)</li>
                        <li><strong>EU/EEA:</strong> The supervisory authority in your EU member state of residence</li>
                    </ul>
                </div>

                {{-- Contact CTA --}}
                <div class="gdpr-contact-box">
                    <h3>Questions About Your Data?</h3>
                    <p>
                        Our Data Protection Officer is ready to assist you with any enquiries, requests,
                        or concerns about how we handle your personal information.
                    </p>
                    <a href="mailto:dpo@alphahmc.com" class="btn-contact">
                        <i class="fas fa-envelope"></i> Email the DPO
                    </a>
                    <a href="{{ route('contact') }}" class="btn-contact">
                        <i class="fas fa-phone"></i> Contact Us
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    // Smooth scroll for ToC links
    document.querySelectorAll('.gdpr-toc a[href^="#"]').forEach(function(a) {
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
