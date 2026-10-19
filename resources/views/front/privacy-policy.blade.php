@extends('front/layout-2')

@push('page_title', 'Privacy Policy | Alpha Health Group')

@section('meta_description', 'How Alpha Health Group collects, uses, shares and protects personal data on alphahmc.com, in WhatsApp messages and in our Google and Meta integrations.')

@push('og_tags')
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="Privacy Policy | Alpha Health Group" />
    <meta property="og:description" content="How Alpha Health Group collects, uses, shares and protects personal data." />
    <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('custom_css')
<link rel="stylesheet" href="{{ asset('public/front/assets/css/legal-pages.css') }}?v=2">
@endsection

@section('content')

@php
    $privacyEmail = 'info@alphatsm.com';
    $effective = '19 October 2026';
@endphp

<section class="gdpr-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <span class="gdpr-badge">Privacy</span>
                <h1>Privacy Policy</h1>
                <p class="lead">
                    How Alpha Health Group collects, uses, shares and protects personal data when you use
                    alphahmc.com, message us on WhatsApp, use our services, or when our tools connect to
                    Google and Meta services.
                </p>
                <div class="gdpr-meta-pills">
                    <span class="gdpr-meta-pill">Effective: {{ $effective }}</span>
                    <span class="gdpr-meta-pill">Last updated: {{ $effective }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="gdpr-body">
    <div class="container">
        <div class="row">

            <div class="col-lg-3 d-none d-lg-block">
                <nav class="gdpr-toc" aria-label="On this page">
                    <h4>On this page</h4>
                    <ol>
                        <li><a href="#who-we-are">Who we are</a></li>
                        <li><a href="#data-we-collect">Data we collect</a></li>
                        <li><a href="#how-we-use">How we use it</a></li>
                        <li><a href="#ai-planner">AI project planner</a></li>
                        <li><a href="#google-user-data">Google user data</a></li>
                        <li><a href="#whatsapp">WhatsApp messaging</a></li>
                        <li><a href="#cookies">Cookies and analytics</a></li>
                        <li><a href="#sharing">Who we share it with</a></li>
                        <li><a href="#transfers">International transfers</a></li>
                        <li><a href="#retention">How long we keep it</a></li>
                        <li><a href="#security">Security</a></li>
                        <li><a href="#rights">Your rights</a></li>
                        <li><a href="#deletion">Deleting your data</a></li>
                        <li><a href="#children">Children</a></li>
                        <li><a href="#changes">Changes</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-9">

                <div class="gdpr-info-box">
                    <p>
                        This policy applies the UAE Federal Decree-Law No. 45 of 2021 on the Protection of Personal Data
                        (PDPL) and, for visitors and clients in the European Union, the European Economic Area, the United
                        Kingdom and Switzerland, the General Data Protection Regulation (GDPR and UK GDPR). It also sets out
                        how we handle data received through the Google API Services and the WhatsApp Business Platform.
                    </p>
                </div>

                <div class="gdpr-section" id="who-we-are">
                    <span class="gdpr-section-label">Section 1</span>
                    <h2>Who we are</h2>
                    <p>
                        Alpha Health Group is the trading name used by <strong>Alpha Health Consultancies</strong> and
                        <strong>NextHealth Technologies LLC</strong> (together "Alpha Health Group", "we", "us"). We provide
                        healthcare consultancy, facility licensing and healthcare professional licensing services in the
                        UAE and the GCC. Both companies act as controllers of the personal data described in this policy.
                    </p>
                    <ul>
                        <li><strong>Address:</strong> 1101, 11th Floor, Damas Tower, Al Maktoum Road, Dubai, United Arab Emirates</li>
                        <li><strong>Email:</strong> <a href="mailto:{{ $privacyEmail }}">{{ $privacyEmail }}</a></li>
                        <li><strong>Phone:</strong> <a href="tel:+97142724064">+971 4 272 4064</a></li>
                    </ul>
                    <p>
                        This policy covers the website alphahmc.com, our inquiry forms and AI project planner, our WhatsApp
                        Business account, our consultancy and licensing services, and the internal tools we use to manage
                        our Google Ads account and Google Business Profile.
                    </p>
                </div>

                <div class="gdpr-section" id="data-we-collect">
                    <span class="gdpr-section-label">Section 2</span>
                    <h2>Data we collect</h2>
                    <h3>Data you give us</h3>
                    <ul>
                        <li><strong>Inquiry and contact forms:</strong> your name, email address, phone number, the service you are interested in, your message and, if you choose one, a preferred meeting date and time.</li>
                        <li><strong>AI project planner:</strong> your answers about your project (for example the type of facility, its location, the services you need and anything you write in your own words) and, if you ask us to contact you, your name, email address and phone number.</li>
                        <li><strong>Reviews:</strong> your name, email address, position, company, rating and review. If we approve a review, we publish your name, position, company, rating and review text. We never publish your email address.</li>
                        <li><strong>Licensing and consultancy clients:</strong> the documents and details you send us so we can deliver the service, such as your passport, qualifications, experience certificates, professional licenses, good standing certificate and CV, or, for facility projects, details of the facility and its owners.</li>
                        <li><strong>WhatsApp messages:</strong> see <a href="#whatsapp">section 6</a>.</li>
                    </ul>
                    <h3>Data collected automatically</h3>
                    <ul>
                        <li><strong>Technical data:</strong> your IP address, browser and device type, and the pages you visit. Our server keeps session records with your IP address and browser details.</li>
                        <li><strong>Analytics and advertising data:</strong> how you use the site and whether you contacted us after clicking an ad, collected by Google tags as described in <a href="#cookies">section 7</a>.</li>
                    </ul>
                    <h3>Health information</h3>
                    <p>
                        We do not ask for patient health records, and our website and messaging channels are not designed to
                        receive them. Please do not send us patient health information.
                    </p>
                </div>

                <div class="gdpr-section" id="how-we-use">
                    <span class="gdpr-section-label">Section 3</span>
                    <h2>How we use your data, and our legal basis</h2>
                    <div class="legal-table-wrap">
                        <table class="legal-table">
                            <thead><tr><th scope="col">Purpose</th><th scope="col">Legal basis</th></tr></thead>
                            <tbody>
                                <tr><td>Reply to your inquiry, arrange a consultation and send you a confirmation email</td><td>Steps you asked us to take before a contract; your consent</td></tr>
                                <tr><td>Deliver our services, including preparing and submitting licensing applications on your instructions</td><td>Performance of a contract</td></tr>
                                <tr><td>Generate your project plan in the AI project planner</td><td>Your request; your consent</td></tr>
                                <tr><td>Publish reviews you submit</td><td>Your consent</td></tr>
                                <tr><td>Send information about our services</td><td>Your consent, which you can withdraw at any time</td></tr>
                                <tr><td>Measure site use and the results of our advertising</td><td>Your consent where the law requires it; otherwise our legitimate interest in improving the site and our advertising</td></tr>
                                <tr><td>Manage our own Google Ads account and Google Business Profile</td><td>Our legitimate interest in running our business</td></tr>
                                <tr><td>Block spam and protect the site and our systems</td><td>Our legitimate interest in security</td></tr>
                                <tr><td>Meet legal, tax and regulatory obligations</td><td>Legal obligation</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p>We do not sell personal data, and we do not use it to make decisions that have legal or similarly significant effects on you by automated means alone.</p>
                </div>

                <div class="gdpr-section" id="ai-planner">
                    <span class="gdpr-section-label">Section 4</span>
                    <h2>AI project planner</h2>
                    <p>
                        When you use the planner at <a href="{{ url('/plan-your-project') }}">alphahmc.com/plan-your-project</a>,
                        we send your project answers, including anything you type in your own words, to Google's Gemini API to
                        generate your plan. We do not send your name, email address or phone number to Gemini.
                    </p>
                    <p>
                        Google's Gemini API terms allow Google to use content submitted through some service tiers to improve
                        its products, and human reviewers may read it. Do not include personal, patient or confidential
                        information in your answers. The plan is guidance to start a conversation with our team, not a
                        decision about you.
                    </p>
                </div>

                <div class="gdpr-section" id="google-user-data">
                    <span class="gdpr-section-label">Section 5</span>
                    <h2>Google user data</h2>
                    <p>
                        Our internal tools connect to Google accounts that Alpha Health Group owns or manages. Access starts
                        only when an authorised member of our team signs in with Google and grants permission. The tools are
                        not offered to the public.
                    </p>
                    <h3>What we access</h3>
                    <ul>
                        <li><strong>Google Ads API</strong> (scope <code>https://www.googleapis.com/auth/adwords</code>): account and campaign settings, ad groups, keywords, ads, budgets, search term reports, and performance and conversion statistics. The tools can also create and change campaigns, ads, keywords and budgets in our account.</li>
                        <li><strong>Google Business Profile API</strong> (scope <code>https://www.googleapis.com/auth/business.manage</code>): our business information, posts, performance statistics, and reviews of our business, including the reviewer's public name and review text. The tools can also post updates and reply to reviews.</li>
                        <li><strong>Google account details:</strong> the name and email address of the Google account that grants access, so we know which team member connected it.</li>
                    </ul>
                    <h3>How we use it</h3>
                    <p>
                        We use this data only to manage, report on and improve our own advertising and business profile: for
                        example to review campaign results, adjust keywords and budgets, and reply to reviews.
                    </p>
                    <h3>How we store and share it</h3>
                    <p>
                        Access tokens and the data we retrieve are kept on systems controlled by Alpha Health Group and are
                        available only to authorised team members. We do not sell Google user data or share it with third
                        parties, except where needed to provide these features, to comply with the law, or as part of a merger
                        or acquisition with notice to you.
                    </p>
                    <h3>Limited Use</h3>
                    <p>
                        Alpha Health Group's use and transfer to any other app of information received from Google APIs will
                        adhere to the <a href="https://developers.google.com/terms/api-services-user-data-policy" target="_blank" rel="noopener">Google API Services User Data Policy</a>,
                        including the Limited Use requirements. We do not use Google user data to serve advertisements, to
                        build profiles for advertising, or to determine creditworthiness, and we do not use it to train
                        generalised AI or machine learning models. Our team does not read this data unless you have agreed,
                        it is needed for security or to comply with the law, or it is aggregated and anonymised for internal
                        operations.
                    </p>
                    <h3>Revoking access and deletion</h3>
                    <p>
                        A Google account holder can remove our access at any time at
                        <a href="https://myaccount.google.com/permissions" target="_blank" rel="noopener">myaccount.google.com/permissions</a>.
                        When access is removed, we delete the stored access tokens. To ask us to delete Google user data we
                        hold, follow <a href="#deletion">section 13</a>.
                    </p>
                </div>

                <div class="gdpr-section" id="whatsapp">
                    <span class="gdpr-section-label">Section 6</span>
                    <h2>WhatsApp messaging</h2>
                    <p>
                        We use the WhatsApp Business Platform (Cloud API) from Meta to talk to clients and people who contact
                        us. The "Message us on WhatsApp" buttons on our site open a chat with a prefilled message naming the
                        page you were on. Nothing is sent until you press send.
                    </p>
                    <h3>What we receive</h3>
                    <ul>
                        <li>Your WhatsApp phone number and profile name</li>
                        <li>The messages, documents and images you send us, and our replies</li>
                        <li>Message details such as the time sent and whether a message was delivered and read</li>
                    </ul>
                    <h3>How we use it</h3>
                    <p>
                        We use your messages to answer your inquiry, deliver the service you asked for, and send you updates
                        about your application or appointment. We send you information about our services only if you have
                        agreed to receive it.
                    </p>
                    <h3>Messages we start</h3>
                    <p>
                        We only start a WhatsApp conversation with you if you have contacted us or given us your number and
                        agreed to hear from us on WhatsApp. You can ask us to stop at any time by replying "STOP" or by emailing
                        <a href="mailto:{{ $privacyEmail }}">{{ $privacyEmail }}</a>, and we will stop messaging you.
                    </p>
                    <h3>Meta's role</h3>
                    <p>
                        Meta processes these messages for us as our service provider under the WhatsApp Business terms.
                        Your use of WhatsApp itself is covered by
                        <a href="https://www.whatsapp.com/legal/privacy-policy" target="_blank" rel="noopener">WhatsApp's privacy policy</a>.
                        To delete the data we hold from WhatsApp conversations, follow <a href="#deletion">section 13</a>.
                    </p>
                </div>

                <div class="gdpr-section" id="cookies">
                    <span class="gdpr-section-label">Section 7</span>
                    <h2>Cookies and analytics</h2>
                    <p>
                        We use Google Tag Manager, Google Analytics and Google Ads tags to measure how the site is used and
                        which ads lead to inquiries. These tags follow Google Consent Mode:
                    </p>
                    <ul>
                        <li>For visitors in the EU, EEA, UK and Switzerland, analytics and advertising storage stays off until you click Accept.</li>
                        <li>For visitors elsewhere, it is on until you click Reject.</li>
                    </ul>
                    <p>
                        You can change your choice at any time through the cookie banner or by clearing your browser storage.
                        We remember your choice in your browser for 30 days. Our site also uses strictly necessary cookies,
                        such as a session cookie and a form security cookie, which the site needs to work.
                        Details are in our <a href="{{ route('front.cookie-policy') }}">cookie policy</a>.
                    </p>
                    <p>
                        Our forms are protected by Google reCAPTCHA, which checks your device and behaviour to block spam.
                        Google's <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms" target="_blank" rel="noopener">Terms of Service</a> apply.
                    </p>
                </div>

                <div class="gdpr-section" id="sharing">
                    <span class="gdpr-section-label">Section 8</span>
                    <h2>Who we share your data with</h2>
                    <h3>Service providers</h3>
                    <ul>
                        <li><strong>Microsoft Azure:</strong> hosts the website and its database in the UAE North (Dubai) region</li>
                        <li><strong>Google:</strong> Tag Manager, Analytics, Ads, reCAPTCHA, Gemini API and web fonts</li>
                        <li><strong>Meta:</strong> the WhatsApp Business Platform</li>
                        <li><strong>Resend:</strong> delivers the emails our website sends</li>
                        <li><strong>Our email hosting provider:</strong> hosts our team's mailboxes</li>
                        <li><strong>Content delivery networks</strong> (cdnjs, jsDelivr and the jQuery CDN): serve the site's scripts and styles and receive your IP address when your browser loads them</li>
                    </ul>
                    <h3>Authorities and verification bodies, on your instructions</h3>
                    <p>
                        To deliver a licensing or facility service, we submit your documents and details to the bodies the
                        service requires, such as the Dubai Health Authority (DHA), the Department of Health Abu Dhabi (DOH),
                        the Ministry of Health and Prevention (MOHAP), the Sharjah Health Authority (SHA), other GCC health
                        regulators, the DataFlow Group for primary source verification, and exam providers such as Prometric
                        and Pearson VUE. Each of these bodies handles your data under its own privacy terms.
                    </p>
                    <h3>Others</h3>
                    <p>
                        Our professional advisers under confidentiality duties; authorities when the law requires it; and a
                        buyer or successor if our business is sold or merged, in which case we will tell you first.
                    </p>
                </div>

                <div class="gdpr-section" id="transfers">
                    <span class="gdpr-section-label">Section 9</span>
                    <h2>International transfers</h2>
                    <p>
                        Our website and its database are hosted in the UAE. Google, Meta and Resend may process data in other
                        countries, including the United States. We rely on the conditions for cross-border transfers in the
                        PDPL and, for data covered by the GDPR, on these providers' data processing terms, including standard
                        contractual clauses where they apply.
                    </p>
                </div>

                <div class="gdpr-section" id="retention">
                    <span class="gdpr-section-label">Section 10</span>
                    <h2>How long we keep your data</h2>
                    <ul>
                        <li><strong>Inquiries, planner submissions and WhatsApp conversations:</strong> 2 years from our last contact with you</li>
                        <li><strong>Client and licensing files:</strong> 7 years after the engagement ends, to meet legal and accounting requirements</li>
                        <li><strong>Reviews:</strong> while the review is published, or until you ask us to remove it</li>
                        <li><strong>Google user data:</strong> while we need it to manage our advertising and business profile; tokens are deleted when access is revoked</li>
                        <li><strong>Analytics data:</strong> up to 14 months</li>
                    </ul>
                    <p>After these periods, we delete the data or make it anonymous.</p>
                </div>

                <div class="gdpr-section" id="security">
                    <span class="gdpr-section-label">Section 11</span>
                    <h2>Security</h2>
                    <ul>
                        <li>All traffic to alphahmc.com is encrypted with HTTPS.</li>
                        <li>The website database runs on Microsoft Azure in the UAE, on disks that are encrypted at rest.</li>
                        <li>Our team's access to inquiries and client data is limited by role and permission.</li>
                        <li>Our forms use spam filtering and rate limits.</li>
                    </ul>
                    <p>No system is completely secure. If you think your data with us has been put at risk, email <a href="mailto:{{ $privacyEmail }}">{{ $privacyEmail }}</a>.</p>
                </div>

                <div class="gdpr-section" id="rights">
                    <span class="gdpr-section-label">Section 12</span>
                    <h2>Your rights</h2>
                    <p>Under the PDPL, and the GDPR where it applies, you can ask us to:</p>
                    <ul>
                        <li>give you a copy of the personal data we hold about you</li>
                        <li>correct data that is wrong or incomplete</li>
                        <li>delete your data</li>
                        <li>restrict how we use it, or object to our use of it, including for marketing</li>
                        <li>give you your data in a format you can pass to another organisation</li>
                        <li>stop processing based on your consent, by withdrawing it at any time</li>
                    </ul>
                    <p>
                        Email <a href="mailto:{{ $privacyEmail }}">{{ $privacyEmail }}</a>. We may ask you to confirm your
                        identity, and we reply within 30 days. There is no charge.
                    </p>
                    <p>
                        If you are not satisfied with our reply, you can complain to the UAE Data Office or, if you are in the
                        EU, EEA or UK, to the data protection authority where you live.
                    </p>
                </div>

                <div class="gdpr-section" id="deletion">
                    <span class="gdpr-section-label">Section 13</span>
                    <h2>How to delete your data</h2>
                    <ol>
                        <li>Email <a href="mailto:{{ $privacyEmail }}?subject=Data%20deletion%20request">{{ $privacyEmail }}</a> with the subject "Data deletion request", or send "Delete my data" to us on WhatsApp.</li>
                        <li>Include your name and the email address or phone number you used with us.</li>
                        <li>We confirm your request, delete your data from our website database, inbox and WhatsApp records, and tell you when it is done, within 30 days.</li>
                    </ol>
                    <p>
                        We keep only what the law requires us to keep, such as invoices, and we tell you what we kept and why.
                        To remove our access to a Google account, see <a href="#google-user-data">section 5</a>.
                    </p>
                </div>

                <div class="gdpr-section" id="children">
                    <span class="gdpr-section-label">Section 14</span>
                    <h2>Children</h2>
                    <p>Our services are for healthcare professionals and organisations. We do not knowingly collect data from anyone under 18, and we delete it if we find we have.</p>
                </div>

                <div class="gdpr-section" id="changes">
                    <span class="gdpr-section-label">Section 15</span>
                    <h2>Changes to this policy</h2>
                    <p>We update this policy when our services or the law change. The date at the top shows the latest version. If a change affects how we use data you have already given us, we will tell you before it applies.</p>
                </div>

                <div class="gdpr-contact-box" id="contact">
                    <h3>Questions about your data?</h3>
                    <p>Email us and our team will reply within 30 days.</p>
                    <a href="mailto:{{ $privacyEmail }}" class="btn-contact">Email {{ $privacyEmail }}</a>
                    <a href="{{ route('contact') }}" class="btn-contact">Contact us</a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script>
    document.querySelectorAll('.gdpr-toc a[href^="#"], .gdpr-section a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - 130, behavior: 'smooth' });
            history.replaceState(null, '', this.getAttribute('href'));
        });
    });
</script>
@endsection
