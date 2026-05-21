@extends('front/layout-2')

@push('page_title', 'Terms of Service | Alpha Health Group')

@section('meta_description', 'Read Alpha Health Group\'s Terms of Service governing healthcare consultancy, accreditation support, and advisory services across the GCC and Middle East region.')

@push('og_tags')
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="Terms of Service | Alpha Health Group" />
    <meta property="og:description" content="Terms and conditions governing Alpha Health Group\'s healthcare consultancy and advisory services in the GCC and Middle East." />
    <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('custom_css')
<style>
    :root {
        --navy: #003358;
        --teal: #009095;
        --red:  #e50303;
        --muted: #64748b;
        --bg: #f8fafc;
        --border: #e2e8f0;
    }
    body { font-family: 'Outfit', sans-serif; background: #fff; }

    /* HERO */
    .tos-hero {
        background: linear-gradient(135deg, #003358 0%, #005a80 60%, #009095 100%);
        padding: 160px 0 90px;
        position: relative;
        overflow: hidden;
    }
    .tos-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(0,144,149,0.22) 0%, transparent 55%);
        pointer-events: none;
    }
    .tos-badge {
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
    .tos-hero h1 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(2rem, 4.5vw, 3.4rem);
        color: #fff;
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 20px;
    }
    .tos-hero p.lead {
        color: rgba(255,255,255,0.82);
        font-size: 1.05rem;
        max-width: 640px;
        line-height: 1.75;
    }
    .tos-meta-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 32px;
    }
    .tos-meta-pill {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.22);
        color: rgba(255,255,255,0.92);
        padding: 7px 18px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .tos-meta-pill i { color: #4dd9dc; margin-right: 6px; }

    /* SIDEBAR TOC */
    .tos-toc {
        background: var(--bg);
        border-radius: 16px;
        padding: 32px 30px;
        border: 1px solid var(--border);
        margin-bottom: 50px;
        position: sticky;
        top: 120px;
        max-height: calc(100vh - 160px);
        overflow-y: auto;
    }
    .tos-toc h4 {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--teal);
        margin-bottom: 18px;
    }
    .tos-toc ol { padding-left: 18px; margin: 0; }
    .tos-toc li { margin-bottom: 8px; }
    .tos-toc a {
        color: var(--navy);
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 500;
        transition: color 0.2s;
    }
    .tos-toc a:hover { color: var(--teal); }

    /* BODY */
    .tos-body { padding: 80px 0 100px; }

    .tos-section {
        margin-bottom: 58px;
        padding-top: 16px;
    }
    .tos-section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0,144,149,0.08);
        color: var(--teal);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 50px;
        margin-bottom: 14px;
    }
    .tos-section h2 {
        font-family: 'Libre Baskerville', serif;
        font-size: clamp(1.35rem, 2.5vw, 1.85rem);
        color: var(--navy);
        font-weight: 700;
        margin-bottom: 18px;
        line-height: 1.3;
    }
    .tos-section p, .tos-section li {
        font-size: 1rem;
        line-height: 1.9;
        color: #374151;
    }
    .tos-section ul, .tos-section ol {
        padding-left: 22px;
        margin-bottom: 16px;
    }
    .tos-section li { margin-bottom: 8px; }

    .tos-divider {
        border: none;
        border-top: 2px solid #f1f5f9;
        margin: 56px 0 0;
    }

    /* INFO / WARN / CRITICAL BOXES */
    .tos-info-box {
        background: #eef9f9;
        border-left: 4px solid var(--teal);
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        margin: 24px 0;
    }
    .tos-info-box p { margin: 0; color: #0d4a4e; font-weight: 500; }

    .tos-warn-box {
        background: #fff8e1;
        border-left: 4px solid #f59e0b;
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        margin: 24px 0;
    }
    .tos-warn-box p { margin: 0; color: #78350f; font-weight: 500; }

    .tos-critical-box {
        background: #fff0f0;
        border-left: 4px solid var(--red);
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        margin: 24px 0;
    }
    .tos-critical-box p { margin: 0; color: #7f1d1d; font-weight: 600; }

    /* HIGHLIGHT GRID */
    .tos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 18px;
        margin-top: 26px;
    }
    .tos-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 24px 22px;
        transition: box-shadow 0.25s, transform 0.25s;
    }
    .tos-card:hover {
        box-shadow: 0 10px 28px rgba(0,51,88,0.09);
        transform: translateY(-3px);
    }
    .tos-card-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #003358, #009095);
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.95rem;
        margin-bottom: 14px;
    }
    .tos-card h5 {
        font-size: 0.93rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 8px;
    }
    .tos-card p {
        font-size: 0.87rem;
        line-height: 1.65;
        color: var(--muted);
        margin: 0;
    }

    /* CTA BOX */
    .tos-contact-box {
        background: linear-gradient(135deg, #003358 0%, #005a80 100%);
        border-radius: 20px;
        padding: 48px;
        color: #fff;
        margin-top: 60px;
        text-align: center;
    }
    .tos-contact-box h3 {
        font-family: 'Libre Baskerville', serif;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .tos-contact-box p {
        color: rgba(255,255,255,0.8);
        font-size: 1rem;
        margin-bottom: 28px;
    }
    .tos-contact-box a.btn-contact {
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
    }
    .tos-contact-box a.btn-contact:hover {
        background: var(--teal);
        color: #fff;
    }

    @media (max-width: 991px) {
        .tos-toc { display: none; }
        .tos-hero { padding: 110px 20px 70px; }
        .tos-contact-box { padding: 32px 22px; }
    }
</style>
@endsection

@section('content')

{{-- HERO --}}
<section class="tos-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <span class="tos-badge"><i class="fas fa-file-contract me-2"></i>Legal</span>
                <h1>Terms of Service</h1>
                <p class="lead">
                    These Terms govern all engagements between Alpha Health Group and its clients, partners, and website users across the GCC and Middle East region. By accessing our services, you agree to be bound by these Terms.
                </p>
                <div class="tos-meta-pills">
                    <span class="tos-meta-pill"><i class="fas fa-calendar-check"></i>Effective: 1 January 2025</span>
                    <span class="tos-meta-pill"><i class="fas fa-sync-alt"></i>Last Reviewed: {{ date('F Y') }}</span>
                    <span class="tos-meta-pill"><i class="fas fa-map-marker-alt"></i>Jurisdiction: UAE & GCC</span>
                    <span class="tos-meta-pill"><i class="fas fa-language"></i>Governing Language: English</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- BODY --}}
<div class="tos-body">
    <div class="container">
        <div class="row">

            {{-- Sidebar ToC --}}
            <div class="col-lg-3 d-none d-lg-block">
                <nav class="tos-toc">
                    <h4>On This Page</h4>
                    <ol>
                        <li><a href="#t1">Definitions</a></li>
                        <li><a href="#t2">Acceptance of Terms</a></li>
                        <li><a href="#t3">Nature of Services</a></li>
                        <li><a href="#t4">No Medical Advice</a></li>
                        <li><a href="#t5">Regulatory Compliance</a></li>
                        <li><a href="#t6">Client Obligations</a></li>
                        <li><a href="#t7">Fees & Payment</a></li>
                        <li><a href="#t8">Intellectual Property</a></li>
                        <li><a href="#t9">Confidentiality</a></li>
                        <li><a href="#t10">Limitation of Liability</a></li>
                        <li><a href="#t11">Indemnification</a></li>
                        <li><a href="#t12">Force Majeure</a></li>
                        <li><a href="#t13">Termination</a></li>
                        <li><a href="#t14">Dispute Resolution</a></li>
                        <li><a href="#t15">Governing Law</a></li>
                        <li><a href="#t16">Amendments</a></li>
                        <li><a href="#t17">Contact</a></li>
                    </ol>
                </nav>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-9">

                <div class="tos-info-box">
                    <p><i class="fas fa-info-circle me-2"></i>
                        These Terms of Service ("Terms") constitute a legally binding agreement between Alpha Health Group LLC ("the Company", "we", "us", "our") and any individual, organisation, healthcare facility, or entity ("Client", "User", "you") that engages our services or accesses this website. Please read these Terms carefully before proceeding.
                    </p>
                </div>

                {{-- 1. Definitions --}}
                <div class="tos-section" id="t1">
                    <span class="tos-section-label"><i class="fas fa-book"></i>Section 1</span>
                    <h2>Definitions</h2>
                    <p>In these Terms, the following definitions apply:</p>
                    <ul>
                        <li><strong>"Company"</strong> means Alpha Health Group LLC, registered in the United Arab Emirates, with offices in Abu Dhabi and Dubai.</li>
                        <li><strong>"Services"</strong> means all healthcare consultancy, accreditation support, facility management advisory, regulatory compliance guidance, capacity-building, training, and related professional services provided by the Company.</li>
                        <li><strong>"Client"</strong> means any healthcare facility, organisation, government entity, or individual that engages the Company for Services under a signed engagement letter, proposal, or contract.</li>
                        <li><strong>"Deliverables"</strong> means any reports, assessments, frameworks, plans, presentations, or documentation produced by the Company in the course of providing Services.</li>
                        <li><strong>"Confidential Information"</strong> means any non-public information disclosed by either party in connection with the Services.</li>
                        <li><strong>"GCC"</strong> means the Gulf Cooperation Council member states: UAE, Saudi Arabia, Qatar, Kuwait, Bahrain, and Oman.</li>
                        <li><strong>"Regulatory Authority"</strong> means any applicable government body including but not limited to UAE DOH, DHA, MOH, Saudi CBAHI, Qatar MOPH, JCI, and ACHSI.</li>
                    </ul>
                    <hr class="tos-divider">
                </div>

                {{-- 2. Acceptance --}}
                <div class="tos-section" id="t2">
                    <span class="tos-section-label"><i class="fas fa-handshake"></i>Section 2</span>
                    <h2>Acceptance of Terms</h2>
                    <p>
                        By engaging the Company's Services — whether through a signed contract, verbal agreement, purchase order, email confirmation, or use of this website — you unconditionally accept these Terms in their entirety. If you do not agree to any part of these Terms, you must immediately cease use of our website and refrain from engaging our Services.
                    </p>
                    <p>
                        Where Services are engaged on behalf of a legal entity, the individual accepting these Terms warrants that they have full authority to bind that entity. The Company reserves the right to request written evidence of such authority.
                    </p>
                    <div class="tos-warn-box">
                        <p><i class="fas fa-exclamation-triangle me-2"></i>
                            These Terms apply in addition to, and do not replace, any specific terms set out in a signed engagement letter or service agreement. In the event of conflict, the signed engagement letter shall prevail.
                        </p>
                    </div>
                    <hr class="tos-divider">
                </div>

                {{-- 3. Nature of Services --}}
                <div class="tos-section" id="t3">
                    <span class="tos-section-label"><i class="fas fa-briefcase-medical"></i>Section 3</span>
                    <h2>Nature of Services</h2>
                    <p>
                        Alpha Health Group provides professional advisory and consultancy services to healthcare organisations operating in the GCC and broader Middle East region. Our Services may include:
                    </p>
                    <div class="tos-grid">
                        <div class="tos-card">
                            <div class="tos-card-icon"><i class="fas fa-certificate"></i></div>
                            <h5>Accreditation Support</h5>
                            <p>Gap assessments, mock surveys, documentation support and readiness programmes for JCI, CBAHI, ACHSI and local accreditation bodies.</p>
                        </div>
                        <div class="tos-card">
                            <div class="tos-card-icon"><i class="fas fa-hospital"></i></div>
                            <h5>Facility Management</h5>
                            <p>Operational advisory, departmental optimisation, staffing models, and healthcare infrastructure planning.</p>
                        </div>
                        <div class="tos-card">
                            <div class="tos-card-icon"><i class="fas fa-balance-scale"></i></div>
                            <h5>Regulatory Compliance</h5>
                            <p>Guidance on UAE DOH, DHA, MOH, Saudi MOH, and other GCC regulatory licensing and compliance requirements.</p>
                        </div>
                        <div class="tos-card">
                            <div class="tos-card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            <h5>Training & Capacity Building</h5>
                            <p>Workshops, clinical governance training, leadership development and competency frameworks for healthcare professionals.</p>
                        </div>
                        <div class="tos-card">
                            <div class="tos-card-icon"><i class="fas fa-search"></i></div>
                            <h5>Feasibility & Planning</h5>
                            <p>Healthcare facility feasibility studies, market entry strategies, business plans and investment advisory for GCC markets.</p>
                        </div>
                        <div class="tos-card">
                            <div class="tos-card-icon"><i class="fas fa-project-diagram"></i></div>
                            <h5>Project Management</h5>
                            <p>End-to-end project management for greenfield and brownfield healthcare facilities from design to operational readiness.</p>
                        </div>
                    </div>
                    <p class="mt-4">
                        All Services are provided on a best-efforts basis by qualified consultants. Specific scope, timelines, and deliverables are defined in the relevant engagement letter or statement of work.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 4. No Medical Advice --}}
                <div class="tos-section" id="t4">
                    <span class="tos-section-label"><i class="fas fa-stethoscope"></i>Section 4</span>
                    <h2>No Medical Advice — Important Disclaimer</h2>

                    <div class="tos-critical-box">
                        <p><i class="fas fa-exclamation-circle me-2"></i>
                            Alpha Health Group provides <strong>management consultancy and advisory services only</strong>. Nothing communicated by the Company — whether verbally, in writing, through Deliverables, or via this website — constitutes clinical medical advice, diagnosis, or treatment recommendation. The Company does not practice medicine and does not hold itself out as a clinical provider.
                        </p>
                    </div>

                    <p>
                        All clinical decisions, patient care protocols, and medical treatment decisions remain the sole and exclusive responsibility of licensed medical professionals at the relevant healthcare facility. Healthcare facilities engaging our Services acknowledge and agree that:
                    </p>
                    <ul>
                        <li>Implementation of any recommendations is subject to the facility's own clinical governance processes and the professional judgement of licensed clinicians.</li>
                        <li>The Company bears no responsibility for clinical outcomes, patient safety incidents, or adverse events arising from the implementation or non-implementation of any recommendation.</li>
                        <li>Regulatory approvals, clinical risk assessments, and safety validations remain the sole responsibility of the client facility and its licensed personnel.</li>
                        <li>Any accreditation outcome — whether successful or unsuccessful — is determined exclusively by the relevant accreditation body and is not guaranteed by the Company.</li>
                    </ul>
                    <hr class="tos-divider">
                </div>

                {{-- 5. Regulatory Compliance --}}
                <div class="tos-section" id="t5">
                    <span class="tos-section-label"><i class="fas fa-shield-alt"></i>Section 5</span>
                    <h2>Regulatory Compliance</h2>
                    <p>
                        Our consultancy guidance is based on the regulatory frameworks and standards applicable in the GCC and Middle East at the time of engagement. Regulatory requirements change frequently across jurisdictions. Accordingly:
                    </p>
                    <ul>
                        <li>The Company makes reasonable efforts to ensure guidance reflects current regulations but does not warrant that all information is complete, current, or applicable to every jurisdiction without independent verification.</li>
                        <li>Clients are solely responsible for independently verifying all regulatory requirements with the relevant Regulatory Authority prior to implementation.</li>
                        <li>The Company shall not be liable for any regulatory penalties, licence revocations, enforcement actions, or compliance failures arising from a Client's reliance on the Company's guidance without independent verification.</li>
                        <li>Regulatory landscapes across Saudi Arabia, Qatar, Kuwait, Bahrain, Oman, and the UAE differ materially. Guidance provided for one jurisdiction is not automatically applicable to another.</li>
                        <li>The Client is responsible for maintaining all required licences, permits, and regulatory approvals for its facilities throughout any engagement period.</li>
                    </ul>
                    <div class="tos-warn-box">
                        <p><i class="fas fa-exclamation-triangle me-2"></i>
                            In the event of a conflict between the Company's advice and the requirements of a Regulatory Authority, the Regulatory Authority's requirements shall always prevail. The Client must notify the Company immediately of any regulatory directive or enforcement notice that may affect the scope of the engagement.
                        </p>
                    </div>
                    <hr class="tos-divider">
                </div>

                {{-- 6. Client Obligations --}}
                <div class="tos-section" id="t6">
                    <span class="tos-section-label"><i class="fas fa-tasks"></i>Section 6</span>
                    <h2>Client Obligations</h2>
                    <p>To enable effective delivery of Services, the Client agrees to:</p>
                    <ul>
                        <li>Provide the Company with accurate, complete, and up-to-date information relevant to the engagement, and promptly notify the Company of any changes.</li>
                        <li>Grant the Company timely access to facilities, personnel, documentation, and systems necessary to perform the Services, as agreed in the engagement scope.</li>
                        <li>Designate a responsible point of contact with sufficient authority and knowledge to engage with the Company's consultants and make decisions.</li>
                        <li>Review and respond to Deliverables, queries, and draft documents within agreed timeframes. Delays caused by the Client's failure to respond may extend project timelines and may attract additional fees.</li>
                        <li>Ensure that all information shared with the Company is provided in compliance with applicable data protection, employment, and confidentiality laws.</li>
                        <li>Inform the Company immediately of any material changes to the facility's regulatory status, ownership, leadership, or scope of services that may affect the engagement.</li>
                        <li>Not misrepresent the nature, scope, or outcome of the Company's engagement to any Regulatory Authority, accreditation body, or third party.</li>
                        <li>Settle all invoices within the agreed payment terms. Failure to do so may result in suspension of Services.</li>
                    </ul>
                    <p>
                        The Company's ability to deliver agreed outcomes is contingent upon the Client fulfilling the above obligations. The Company shall not be responsible for delays, failures, or suboptimal outcomes attributable to the Client's non-compliance with these obligations.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 7. Fees & Payment --}}
                <div class="tos-section" id="t7">
                    <span class="tos-section-label"><i class="fas fa-file-invoice-dollar"></i>Section 7</span>
                    <h2>Fees &amp; Payment Terms</h2>
                    <p>
                        All fees are as specified in the relevant engagement letter, proposal, or statement of work and are quoted in UAE Dirhams (AED) unless otherwise agreed. The following conditions apply:
                    </p>
                    <ul>
                        <li><strong>Invoicing:</strong> Invoices are issued in accordance with the agreed payment schedule set out in the engagement letter.</li>
                        <li><strong>Payment Terms:</strong> All invoices are payable within thirty (30) days of the invoice date unless otherwise stated.</li>
                        <li><strong>Late Payment:</strong> Invoices unpaid after the due date attract interest at a rate of 2% per month on the outstanding balance, compounded monthly, without prejudice to the Company's other rights.</li>
                        <li><strong>Expenses:</strong> Out-of-pocket expenses (travel, accommodation, printing, etc.) reasonably incurred in performing the Services will be invoiced at cost, with prior client approval where exceeding AED 500 per item.</li>
                        <li><strong>VAT:</strong> All fees are subject to applicable Value Added Tax (VAT) in accordance with UAE Federal Tax Law No. 7 of 2017 and equivalent regulations in GCC member states.</li>
                        <li><strong>Withholding Tax:</strong> Where applicable, any withholding tax obligations are the sole responsibility of the Client. The Company's invoiced amount shall not be reduced by any withholding deduction.</li>
                        <li><strong>Non-Refundable Retainers:</strong> Where a retainer or mobilisation fee has been paid, such amounts are non-refundable unless otherwise expressly agreed in writing.</li>
                        <li><strong>Suspension of Services:</strong> The Company reserves the right to suspend Services without liability if payment remains outstanding for more than 15 days after the due date.</li>
                    </ul>
                    <hr class="tos-divider">
                </div>

                {{-- 8. Intellectual Property --}}
                <div class="tos-section" id="t8">
                    <span class="tos-section-label"><i class="fas fa-copyright"></i>Section 8</span>
                    <h2>Intellectual Property</h2>
                    <p>
                        Unless otherwise expressly agreed in writing:
                    </p>
                    <ul>
                        <li>All methodologies, frameworks, tools, templates, know-how, and pre-existing materials used or developed by the Company remain the exclusive intellectual property of Alpha Health Group.</li>
                        <li>Upon full payment of all fees, the Client is granted a non-exclusive, non-transferable, royalty-free licence to use Deliverables for their internal purposes only, within the facility or organisation for which the Services were provided.</li>
                        <li>Clients may not reproduce, distribute, sub-licence, sell, or publicly share Deliverables — in whole or in part — without the Company's prior written consent.</li>
                        <li>The Company retains the right to use anonymised or aggregated insights from all engagements for the purposes of professional development, research, and the improvement of its services.</li>
                        <li>Any intellectual property created jointly by the Company and the Client shall be subject to a separate written agreement addressing ownership, usage rights, and commercialisation.</li>
                    </ul>
                    <div class="tos-info-box">
                        <p><i class="fas fa-info-circle me-2"></i>
                            Nothing in these Terms transfers ownership of the Company's brand, trademarks, service marks, logos, or corporate identity. Use of the Company's name or logo in any marketing, press release, or publication requires prior written approval.
                        </p>
                    </div>
                    <hr class="tos-divider">
                </div>

                {{-- 9. Confidentiality --}}
                <div class="tos-section" id="t9">
                    <span class="tos-section-label"><i class="fas fa-lock"></i>Section 9</span>
                    <h2>Confidentiality</h2>
                    <p>
                        Both parties acknowledge that in the course of an engagement, each may receive Confidential Information of the other. Each party agrees to:
                    </p>
                    <ul>
                        <li>Keep all Confidential Information strictly confidential and not disclose it to any third party without the disclosing party's prior written consent.</li>
                        <li>Use Confidential Information solely for the purposes of the engagement.</li>
                        <li>Restrict access to Confidential Information to those employees, contractors, or agents who have a genuine need to know and who are bound by equivalent confidentiality obligations.</li>
                    </ul>
                    <p>
                        These obligations do not apply to information that: (a) is or becomes publicly available through no breach of these Terms; (b) was already known to the receiving party prior to disclosure; (c) is independently developed without reference to Confidential Information; or (d) is required to be disclosed by law or court order (in which case prior written notice shall be given to the disclosing party where legally permissible).
                    </p>
                    <p>
                        Confidentiality obligations survive termination of any engagement for a period of <strong>five (5) years</strong>.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 10. Limitation of Liability --}}
                <div class="tos-section" id="t10">
                    <span class="tos-section-label"><i class="fas fa-shield-alt"></i>Section 10</span>
                    <h2>Limitation of Liability</h2>

                    <div class="tos-critical-box">
                        <p><i class="fas fa-exclamation-circle me-2"></i>
                            This section is critically important. Please read it carefully as it limits the Company's liability to you.
                        </p>
                    </div>

                    <p>To the fullest extent permitted by applicable law:</p>
                    <ul>
                        <li><strong>Cap on Liability:</strong> The Company's total aggregate liability to the Client — whether in contract, tort (including negligence), breach of statutory duty, or otherwise — shall not exceed the total fees actually paid by the Client to the Company in the three (3) months immediately preceding the event giving rise to the claim.</li>
                        <li><strong>Excluded Categories:</strong> In no event shall the Company be liable for: (i) loss of profit; (ii) loss of revenue; (iii) loss of business or contracts; (iv) loss of anticipated savings; (v) loss of data; (vi) loss of goodwill; (vii) regulatory fines or penalties imposed on the Client; (viii) accreditation failure; (ix) clinical outcomes or patient harm; or (x) any indirect, consequential, special, or punitive loss, even if the Company has been advised of the possibility of such losses.</li>
                        <li><strong>Third-Party Services:</strong> The Company is not responsible for the acts, omissions, or services of any third party recommended or introduced in connection with an engagement, including accreditation bodies, regulatory authorities, IT vendors, or subcontractors.</li>
                        <li><strong>Website Reliance:</strong> Information published on this website is for general informational purposes only. The Company accepts no liability for decisions made in reliance on website content without a formal engagement.</li>
                        <li><strong>Force Majeure:</strong> The Company shall not be liable for any failure or delay in performance caused by circumstances beyond its reasonable control (see Section 12).</li>
                        <li><strong>Client-Provided Information:</strong> The Company shall not be liable for any inaccuracy, deficiency, or failure in Deliverables arising from incomplete, inaccurate, or misleading information provided by the Client.</li>
                    </ul>
                    <p>
                        Nothing in these Terms limits or excludes liability for: (a) death or personal injury caused by negligence; (b) fraud or fraudulent misrepresentation; or (c) any other liability that cannot be excluded or limited by applicable law.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 11. Indemnification --}}
                <div class="tos-section" id="t11">
                    <span class="tos-section-label"><i class="fas fa-gavel"></i>Section 11</span>
                    <h2>Indemnification</h2>
                    <p>
                        The Client agrees to indemnify, defend, and hold harmless Alpha Health Group, its directors, officers, employees, consultants, and agents from and against any and all claims, losses, damages, liabilities, fines, penalties, costs, and expenses (including reasonable legal fees) arising from or relating to:
                    </p>
                    <ul>
                        <li>The Client's breach of any provision of these Terms or any engagement agreement.</li>
                        <li>The Client's misuse, unauthorised use, or misrepresentation of any Deliverable or Company advice.</li>
                        <li>Any clinical decision made or clinical action taken by the Client's personnel in connection with or following the Company's advisory Services.</li>
                        <li>Any regulatory violation, patient harm, facility incident, or enforcement action arising at the Client's facility, irrespective of whether the Company provided advisory Services in the relevant area.</li>
                        <li>The Client's failure to comply with applicable UAE, GCC, or international laws, regulations, or licensing requirements.</li>
                        <li>Any third-party claim arising from the Client's use or disclosure of Confidential Information or Deliverables in breach of these Terms.</li>
                        <li>Inaccurate, incomplete, or misleading information provided by the Client that influenced the Company's Deliverables or recommendations.</li>
                    </ul>
                    <hr class="tos-divider">
                </div>

                {{-- 12. Force Majeure --}}
                <div class="tos-section" id="t12">
                    <span class="tos-section-label"><i class="fas fa-cloud-bolt"></i>Section 12</span>
                    <h2>Force Majeure</h2>
                    <p>
                        Neither party shall be in breach of these Terms, nor liable for any delay or failure in performance, to the extent that such delay or failure arises from causes beyond that party's reasonable control, including but not limited to:
                    </p>
                    <ul>
                        <li>Acts of God, natural disasters, floods, fires, earthquakes, or epidemics/pandemics</li>
                        <li>Acts of government, war, civil unrest, embargoes, or sanctions</li>
                        <li>Strikes, labour disputes, or industrial action not involving the affected party's own workforce</li>
                        <li>Failure of telecommunications networks, utilities, or internet infrastructure</li>
                        <li>Changes in applicable law or regulatory requirements that materially affect performance</li>
                    </ul>
                    <p>
                        The party affected by a Force Majeure event shall notify the other party in writing as soon as reasonably practicable and shall take all reasonable steps to mitigate the effects of the event. If a Force Majeure event continues for more than sixty (60) days, either party may terminate the affected engagement by giving thirty (30) days' written notice, without liability to the other.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 13. Termination --}}
                <div class="tos-section" id="t13">
                    <span class="tos-section-label"><i class="fas fa-times-circle"></i>Section 13</span>
                    <h2>Termination</h2>
                    <p><strong>Termination for Convenience:</strong> Either party may terminate an engagement by providing thirty (30) days' written notice. In such case, the Client shall pay for all Services rendered up to the termination date, plus any non-cancellable costs already committed by the Company.</p>
                    <p><strong>Termination for Cause:</strong> The Company may terminate an engagement immediately upon written notice if:</p>
                    <ul>
                        <li>The Client materially breaches these Terms or the engagement agreement and fails to remedy the breach within ten (10) business days of written notice.</li>
                        <li>The Client becomes insolvent, enters administration, receivership, or liquidation, or is unable to pay its debts as they fall due.</li>
                        <li>The Client engages in fraudulent, illegal, or unethical conduct that, in the Company's reasonable opinion, would bring the Company into disrepute.</li>
                        <li>The Client fails to pay any undisputed invoice within 30 days of the due date.</li>
                    </ul>
                    <p><strong>Consequences of Termination:</strong> On termination for any reason, the Client shall promptly return or destroy all Confidential Information of the Company. All payment obligations accrued prior to termination remain enforceable. Provisions of these Terms that by their nature survive termination — including confidentiality, intellectual property, limitation of liability, indemnification, and dispute resolution — shall continue to apply.</p>
                    <hr class="tos-divider">
                </div>

                {{-- 14. Dispute Resolution --}}
                <div class="tos-section" id="t14">
                    <span class="tos-section-label"><i class="fas fa-handshake"></i>Section 14</span>
                    <h2>Dispute Resolution</h2>
                    <p>
                        The parties shall attempt to resolve any dispute arising from or related to these Terms or any engagement through good-faith negotiation. The following escalation process applies:
                    </p>
                    <ol>
                        <li><strong>Negotiation (30 days):</strong> Senior representatives of both parties shall meet (in person or virtually) within 15 business days of a written notice of dispute to attempt resolution.</li>
                        <li><strong>Mediation (30 days):</strong> If negotiation fails, either party may refer the dispute to mediation administered by the Dubai International Arbitration Centre (DIAC) or a mutually agreed mediator.</li>
                        <li><strong>Arbitration:</strong> If mediation fails or is not pursued, the dispute shall be finally resolved by binding arbitration in Dubai, UAE, under the DIAC Arbitration Rules. The arbitral tribunal shall consist of one (1) arbitrator. The language of arbitration shall be English. The arbitral award shall be final and binding on both parties.</li>
                    </ol>
                    <p>
                        Nothing in this clause prevents either party from seeking urgent injunctive or interim relief from a competent court to protect its rights or confidential information pending resolution of the dispute.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 15. Governing Law --}}
                <div class="tos-section" id="t15">
                    <span class="tos-section-label"><i class="fas fa-balance-scale"></i>Section 15</span>
                    <h2>Governing Law &amp; Jurisdiction</h2>
                    <p>
                        These Terms and all engagements between the Company and the Client shall be governed by and construed in accordance with the laws of the <strong>United Arab Emirates</strong>, including but not limited to:
                    </p>
                    <ul>
                        <li>UAE Federal Civil Transactions Law (Federal Law No. 5 of 1985, as amended)</li>
                        <li>UAE Federal Commercial Transactions Law (Federal Law No. 18 of 1993, as amended)</li>
                        <li>UAE Federal Companies Law (Federal Law No. 32 of 2021)</li>
                        <li>Applicable Abu Dhabi or Dubai local laws and regulations</li>
                    </ul>
                    <p>
                        Subject to the arbitration clause in Section 14, the parties submit to the non-exclusive jurisdiction of the courts of the Emirate of Abu Dhabi for any interim, urgent, or enforcement proceedings. For engagements governed by the laws of another GCC jurisdiction (e.g., Saudi Arabia, Qatar), such governing law shall be as expressly stated in the relevant engagement letter.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 16. Amendments --}}
                <div class="tos-section" id="t16">
                    <span class="tos-section-label"><i class="fas fa-edit"></i>Section 16</span>
                    <h2>Amendments to These Terms</h2>
                    <p>
                        The Company reserves the right to amend these Terms at any time by publishing a revised version on this website with an updated effective date. Material changes will be notified to active clients by email at least 14 days before the change takes effect.
                    </p>
                    <p>
                        Your continued use of our Services after the effective date of any amendment constitutes acceptance of the revised Terms. If you do not accept the revised Terms, you must notify the Company in writing and cease use of the Services. Any engagements already underway at the time of amendment shall continue to be governed by the Terms in force at the commencement of that engagement, unless both parties agree otherwise in writing.
                    </p>
                    <hr class="tos-divider">
                </div>

                {{-- 17. Contact --}}
                <div class="tos-section" id="t17">
                    <span class="tos-section-label"><i class="fas fa-envelope"></i>Section 17</span>
                    <h2>Contact &amp; Legal Notices</h2>
                    <p>All formal notices under these Terms must be given in writing and addressed to:</p>
                    <ul>
                        <li><strong>Alpha Health Group LLC</strong></li>
                        <li><strong>Registered Office:</strong> UAE (Abu Dhabi &amp; Dubai)</li>
                        <li><strong>Legal enquiries:</strong> <a href="mailto:legal@alphahmc.com">legal@alphahmc.com</a></li>
                        <li><strong>General enquiries:</strong> <a href="mailto:info@alphahmc.com">info@alphahmc.com</a></li>
                        <li><strong>Phone:</strong> +971 3 780 2818</li>
                    </ul>
                    <p>
                        Notices sent by email are deemed received at the time of confirmed transmission (excluding weekends and UAE public holidays). Notices sent by registered post are deemed received five (5) business days after posting.
                    </p>
                </div>

                {{-- CTA --}}
                <div class="tos-contact-box">
                    <h3>Questions About Our Terms?</h3>
                    <p>
                        Our legal and compliance team is available to clarify any aspect of these Terms before you engage our Services.
                    </p>
                    <a href="mailto:legal@alphahmc.com" class="btn-contact">
                        <i class="fas fa-envelope"></i> Email Legal Team
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
    document.querySelectorAll('.tos-toc a[href^="#"]').forEach(function(a) {
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
