<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * SEO brief "alphahmc-meta-descriptions" (Saroth approved 4 Nov 2026): 161 stored meta descriptions over 160
 * characters replaced word for word from proposals/alphahmc-meta-short.csv (blogs, services, case studies, brands,
 * categories, package 13; the three single pages are in their Blade views). Case studies and brands get the text
 * stored, so it is no longer cut from the page body. Titles, page text and Updated dates are unchanged.
 * Package 13: the visible placeholders "Test Service Header", "Test Intro Description" and the heading "Test Service
 * Group Description" are removed (the header falls back to the package name; intro and description keep their real text).
 * The previous values are saved to storage/app/migration-backups; down() restores them.
 */
return new class extends Migration
{
    private const BACKUP = 'migration-backups/2026_11_04_190000_meta_descriptions.json';

    public function up(): void
    {
        $saved = Storage::disk('local')->exists(self::BACKUP)
            ? json_decode(Storage::disk('local')->get(self::BACKUP), true) : [];

        foreach (json_decode(self::TEXT, true) as $table => $pages) {
            foreach ($pages as $slug => $description) {
                $row = DB::table($table)->where('slug', $slug)->first(['id', 'meta_description']);
                if (!$row || $row->meta_description === $description) {
                    continue;
                }
                $saved["$table.meta_description.$row->id"] ??= $row->meta_description;
                DB::table($table)->where('id', $row->id)->update(['meta_description' => $description]);
            }
        }

        $group = DB::table('service_groups')->where('id', 13)->first(['id', 'service_header', 'overview', 'description']);
        if ($group) {
            $row = [];
            if (trim((string) $group->service_header) === 'Test Service Header') {
                $row['service_header'] = null;
            }
            $overview = preg_replace('~^(<p>)\s*Test Intro Description\s*~', '$1', (string) $group->overview);
            if ($overview !== $group->overview) {
                $row['overview'] = $overview;
            }
            // The description opens with a placeholder heading, followed by the real paragraph: drop only the heading.
            $description = preg_replace('~^\s*<h3>\s*Test Service Group Description(?:&nbsp;|\s)*</h3>\s*~', '', (string) $group->description);
            if ($description !== $group->description) {
                $row['description'] = $description;
            }
            foreach ($row as $column => $value) {
                $saved["service_groups.$column.13"] ??= $group->$column;
            }
            if ($row) {
                DB::table('service_groups')->where('id', 13)->update($row);
            }
        }

        Storage::disk('local')->put(self::BACKUP, json_encode($saved, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function down(): void
    {
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        foreach (json_decode(Storage::disk('local')->get(self::BACKUP), true) as $key => $value) {
            [$table, $column, $id] = explode('.', $key);
            DB::table($table)->where('id', $id)->update([$column => $value]);
        }
    }

    /** From proposals/alphahmc-meta-short.csv: table => slug => new description (120 to 158 characters). */
    private const TEXT = <<<'JSON'
{
 "service_groups": {
  "healthcare-it-digital-strategy-consulting": "Healthcare IT and digital strategy consulting in the UAE and GCC: align technology investments with clinical outcomes, regulation and operational efficiency."
 },
 "categories": {
  "healthcare-management-outsourcing": "Healthcare management outsourcing in the UAE: compliance, KPI management and clinical operations, in custom packages aligned with DOH, DHA and JAWDA.",
  "hie-integration": "UAE Health Information Exchange (HIE) integration: one interface to connect your healthcare software with the UAE national health data networks.",
  "healthcare-accreditations-certifications": "Healthcare accreditations and certifications: gap assessments, implementation, audit preparation and continuous quality improvement for healthcare facilities."
 },
 "services": {
  "healthcare-compliance-audit": "Healthcare compliance audits across DHA, DOH, MOH and SHA: gap analysis, corrective action planning and pre-inspection readiness for UAE facilities.",
  "dha-audit-preparation-inspection-consultation": "DHA audit preparation and inspection consultation for Dubai medical facilities: pre-inspection audits, documentation review and operational readiness support.",
  "jci-accreditation-consulting-across-uae-gcc": "JCI accreditation consulting in the UAE: gap analysis, implementation and certification support for hospitals and clinics, with 25+ years in GCC healthcare.",
  "malaffi-integration-compliance-abu-dhabi": "Malaffi integration for Abu Dhabi hospitals and clinics: system readiness, HL7 setup, data mapping and compliance testing, with 20+ years of DOH experience.",
  "hospital-infrastructure-planning-engineering-design": "Hospital engineering design and healthcare facility planning across the UAE and GCC: master planning, MEP design, clinical workflow and regulatory compliance.",
  "regulatory-readiness-compliance-assurance": "Regulatory readiness for DHA, DOH, MOH and SHA inspections: gap assessments, mock inspections, compliance scoring and staff training for UAE facilities.",
  "healthcare-facility-management-consulting-uae": "Healthcare facility management consulting for UAE hospitals: operations, workflows and compliance with DOH, DHA and JCI standards across Abu Dhabi and Dubai.",
  "healthcare-business-plan-feasibility-study-uae": "Healthcare business plans and feasibility studies for the UAE and GCC: market analysis, financial modeling, regulatory pathways and operational readiness.",
  "healthcare-facility-acquisition-due-diligence": "Healthcare facility acquisition due diligence in Abu Dhabi and the UAE: regulatory checks, equipment audit, insurance screening and DOH compliance review.",
  "doh-inspection-preparation-services": "DOH inspection preparation for medical centers in Abu Dhabi: compliance assessments, mock inspections, corrective action plans and staff readiness training.",
  "healthcare-facility-infrastructure-assessment": "Healthcare facility infrastructure assessments across the UAE and GCC: MEP evaluation, compliance gap analysis, equipment readiness and capital planning.",
  "healthcare-growth-expansion-strategy": "Healthcare growth and expansion strategy across the UAE and GCC: market feasibility, service line growth, operational scaling and investment advisory.",
  "healthcare-kpi-performance-management-consulting": "Healthcare KPI consulting and performance management in Dubai and Abu Dhabi: dashboards, benchmarking and balanced scorecards aligned with DOH, DHA and JCI.",
  "hipaa-compliance-consulting-services": "HIPAA compliance consulting across the UAE and GCC: gap assessments, security policies, risk analysis and data privacy frameworks for hospitals and clinics.",
  "hospital-compliance-doh-consultation": "Hospital compliance readiness and DOH regulatory consultation in Abu Dhabi: gap assessments, mock inspections, corrective action planning and governance.",
  "hospital-digital-transformation-consulting": "Hospital digital transformation consulting across the UAE and GCC: systems assessment, vendor coordination, digital workflow adoption and technology roadmaps.",
  "hospital-insurance-payer-contracting": "Hospital insurance contracting, payer negotiation and empanelment in Dubai and Abu Dhabi, helping healthcare facilities build stronger insurer partnerships.",
  "hospital-planning-requirement-mapping": "Hospital planning and requirement mapping for UAE and GCC projects: clinical planning, capacity analysis, departmental mapping and regulatory alignment.",
  "healthcare-insurance-pricing-tariff-structuring": "Healthcare insurance pricing and tariff structuring for UAE hospitals and clinics: procedure cost analysis, payer rate benchmarking and revenue optimization.",
  "jawda-audit-consulting-services": "Jawda audit consulting for Abu Dhabi facilities: gap assessments, KPI optimization, data certification readiness and mock inspections. 25+ years with DOH.",
  "medical-engineering-equipment-planning": "Medical equipment planning and biomedical engineering consultancy for hospitals and clinics across the UAE and GCC, with healthcare infrastructure assessment.",
  "medical-equipment-moh-licensing-consulting": "Medical equipment MOH licensing consulting: MOHAP licensing, registration and compliance support for UAE healthcare facilities, suppliers and distributors.",
  "medical-equipment-procurement-consulting": "Medical equipment procurement consulting for UAE and GCC facilities: specification review, vendor evaluation, budget optimisation and regulatory advice.",
  "medical-product-import-regulatory-approval": "Medical product import approval in the UAE and GCC: product registration, import permits and authority approvals, with 25+ years of regulatory experience.",
  "pharmaceutical-product-registration-services-uae": "Pharmaceutical product registration in the UAE: MOHAP, DOH and GCC submissions, CTD dossiers and market authorization, with 25+ years of experience.",
  "pharmaceutical-regulatory-market-access-consulting": "Pharmaceutical regulatory and market access consulting across the UAE and GCC: product registration, pricing advice, compliance and authority coordination.",
  "product-classification-regulatory-assessment": "Product classification and regulatory assessment across the UAE and GCC for pharma, medical device and healthcare companies, with 25+ years of experience.",
  "telehealth-facility-licensing-uae": "Telehealth facility licensing in the UAE: telemedicine approval consulting and DOH, DHA and MOH regulatory compliance support for virtual care providers.",
  "doh-clinical-cost-analysis-reporting": "DOH clinical cost analysis and reporting for Abu Dhabi hospitals and clinics: patient-level costing, Shafafiya submission readiness and cost allocation.",
  "healthcare-cybersecurity-compliance": "Healthcare cybersecurity compliance in the UAE: ADHICS v2.0 gap assessments, policies, incident response planning, AAMEN compliance and DOH audit readiness.",
  "healthcare-software-regulatory-advisory": "Healthcare software regulatory advisory in the UAE and GCC: SaMD classification, ISO 13485 guidance, ADHICS and DOH, DHA, MoHAP and EDE submissions.",
  "healthtech-registration-regulatory-approvals": "HealthTech registration and regulatory approvals in the UAE: DOH, DHA and MoHAP compliance, NABIDH integration, ADHICS alignment and regulatory submissions.",
  "hospital-cybersecurity-consulting-uae": "Hospital cybersecurity consulting in the UAE: risk assessments, compliance alignment, ransomware prevention and governance frameworks for hospitals.",
  "post-acquisition-healthcare-integration-services": "Post-acquisition healthcare integration in the UAE and GCC: regulatory alignment, governance transition and operational continuity for hospitals and clinics.",
  "school-clinic-licensing-uae": "School clinic licensing for private and international schools in Dubai, Abu Dhabi and the UAE: assessments, documentation and authority coordination.",
  "telehealth-digital-platform-approval": "Telehealth and digital platform approval in the UAE: classification, compliance assessment, clinical workflow validation and DHA, DOH and MoHAP submissions.",
  "healthcare-engineering-design-compliance-approval": "Healthcare engineering design compliance and approval in the UAE and GCC: engineering validation, MEP review and DOH, DHA, MOH and SHA approval coordination.",
  "cbahi-accreditation-consulting": "CBAHI accreditation consulting for Saudi hospitals and medical centers: gap analysis, patient safety systems and survey readiness from a 20+ year GCC partner.",
  "construction-project-management-supervision": "Healthcare project management in the UAE: budget, timeline, DOH and DHA approvals and on-site supervision to deliver licensing-ready hospitals and clinics.",
  "healthcare-design-and-build": "Turnkey healthcare design and build in the UAE: planning, DOH and DHA approvals, construction and fit out for fully operational facilities.",
  "healthcare-payer-relationship-management": "Healthcare payer relationship management in the UAE for hospital groups: contract strategy, network positioning and renewal advice.",
  "healthcare-insurance-compliance-consulting": "Healthcare insurance compliance consulting in the UAE to reduce claim rejections: align policies with payer and DOH, DHA and MOH requirements.",
  "iso-certification-consulting-for-healthcare-facilities": "ISO certification consulting for healthcare facilities in the GCC: ISO 9001, 15189, 45001, 27001 and 7101 consulting and audit preparation.",
  "healthcare-staffing-solutions": "Managed healthcare staffing in the UAE: recruitment, DataFlow credentialing, DOH, DHA and MOHAP licensing, onboarding and deployment.",
  "licensed-nursing-allied-health-recruitment": "Nurse and allied health recruitment in the UAE: DataFlow-verified, PQR-aligned, license-eligible candidates ready for DOH, DHA or MOHAP licensing.",
  "physician-recruitment-specialist-placement": "Physician recruitment and specialist placement in the UAE: consultants, specialists and surgeons, with DataFlow verification, privileging and licensing.",
  "isqua-accreditation-consulting-audit-preparation": "ISQua accreditation consulting and audit preparation for UAE and GCC healthcare organizations: gap analysis, standards interpretation and framework design.",
  "medical-facility-fit-out": "Medical facility fit-out in the UAE: clinic, hospital, lab and surgery fit-out by Alpha Health Group, aligned to DHA and DOH standards.",
  "nabh-accreditation-consulting-audit-preparation": "NABH accreditation consulting and audit preparation for hospitals and clinics: gap assessment, quality systems, patient safety and mock audits.",
  "radiation-equipment-fanr-registration-uae": "FANR radiation equipment registration in the UAE: device classification, documentation, shielding checks and submissions for X-ray and CT scanner approval.",
  "radiation-protection-program-development": "Radiation protection program development for UAE healthcare facilities, aligned to FANR: ALARA safety controls, exposure management and inspection readiness.",
  "consumer-product-registration-dubai": "Consumer product registration in Dubai with Dubai Municipality: classification, safety documentation and submission for UAE market entry.",
  "personal-care-product-registration-dubai": "Personal care product registration in Dubai: classification, ingredient safety, labeling and Dubai Municipality approval for skincare and haircare brands."
 },
 "blogs": {
  "technical-requirements-and-compliance-roadmap-for-healthcare-facilities": "Technical requirements and compliance roadmap for Dubai healthcare facilities: NABIDH integration, DHA compliance steps and technical implementation.",
  "complete-implementation-guide-for-healthcare-facilities": "Implementation guide for Malaffi integration in Abu Dhabi: DOH compliance, technical implementation, data quality standards and consent management.",
  "your-strategic-roadmap-to-international-healthcare-excellence": "A roadmap to JCI accreditation for UAE hospitals and clinics: the process, benefits and costs of achieving Joint Commission International accreditation.",
  "complete-audit-preparation-guide-for-dubai-medical-facilities": "Audit preparation guide for Dubai medical facilities: how to prepare for DHA licensing inspections through documentation and operational readiness.",
  "a-strategic-necessity-for-facility-longevity": "Why compliance audits matter for facility longevity: they identify regulatory gaps across DHA, DOH and MOH frameworks and deliver corrective action plans.",
  "a-strategic-guide-for-investors": "A strategic guide for investors on DOH facility licensing in Abu Dhabi: regulatory planning, engineering validation and operational readiness to avoid delays.",
  "complete-dha-licensing-guide-for-healthcare-entrepreneurs": "DHA licensing guide for healthcare entrepreneurs: the roadmap to medical center licensing in Dubai, from initial approval and design to staff credentialing.",
  "the-hidden-risk-in-healthcare-real-estate-development-across-uae-and-gcc": "The hidden risk in healthcare real estate development across the UAE and GCC: why healthcare facilities are among the most regulated construction categories.",
  "a-strategic-guide-to-regulatory-readiness-in-uae-healthcare": "A strategic guide to regulatory readiness in UAE healthcare: why inspections evaluate clinical quality and patient safety, not administrative checkpoints.",
  "why-hospital-engineering-design-determines-operational-success-in-uae-healthcare-facilities": "Why hospital engineering design determines operational success in UAE healthcare facilities, as facility development accelerates across Abu Dhabi and Dubai.",
  "strategic-imperatives-for-2026-and-beyond": "Strategic imperatives for healthcare facility management, which has moved from administrative support to a driver of clinical quality and performance.",
  "why-most-healthcare-business-plans-fail-investor-due-diligence-in-the-uae-and-how-to-fix-yours": "Why most healthcare business plans fail investor due diligence in the UAE, and how to fix yours in a sector driven by population growth and medical tourism.",
  "why-adhics-v20-compliance-is-critical-for-abu-dhabi-healthcare-facilities-in-2024": "Why ADHICS v2.0 compliance is critical for Abu Dhabi healthcare facilities: how cybersecurity threats have evolved since DOH introduced ADHICS in 2019.",
  "why-healthcare-facility-acquisitions-in-the-uae-fail-and-how-due-diligence-prevents-it": "Why healthcare facility acquisitions in the UAE fail and how due diligence prevents it, as investor interest and private development grow in Abu Dhabi.",
  "how-to-navigate-healthcare-facility-licensing-in-sri-lanka-a-complete-guide-for-2026": "How to navigate healthcare facility licensing in Sri Lanka: planning, regulatory approvals and healthcare standards for starting or expanding a facility.",
  "dha-health-facility-guidelines-what-investors-must-know-in-2026": "DHA Health Facility Guidelines in 2026: what investors need to know about the five-part framework, Sheryan portal, NABIDH and the licensing process.",
  "doh-facility-licensing-requirements-for-abu-dhabi-a-complete-guide-for-healthcare-investors": "DOH facility licensing in Abu Dhabi: a guide to requirements, the TAMM portal, Malaffi compliance, design standards and the step-by-step licensing process.",
  "doh-healthcare-facility-inspection-preparation-a-practical-checklist-for-abu-dhabi": "DOH inspection preparation checklist for Abu Dhabi: staff licensing, infection control, medication safety, Malaffi and the 2026 competency framework.",
  "how-to-get-your-healthcare-professional-license-in-saudi-arabia-a-complete-scfhs-licensing-guide-for-2026": "How to get a Saudi healthcare professional license: SCFHS registration, DataFlow verification, Mumaris Plus, exams and classification for doctors and nurses.",
  "how-to-obtain-your-healthcare-professional-license-in-qatar-a-practitioners-guide-to-dhp-licensing-in-2026": "Qatar DHP healthcare professional licensing in 2026: DataFlow PSV, Prometric exams, credential evaluation and GCC license transferability.",
  "how-to-obtain-a-healthcare-professional-license-in-oman-a-practitioners-guide-to-moh-and-omsb-requirements": "Healthcare professional licensing in Oman: MOH requirements, OMSB credentialing, DataFlow verification, the Prometric exam and Royal Decree 43/2025.",
  "why-healthcare-kpi-frameworks-fail-and-how-uae-hospitals-can-build-systems-that-actually-drive-performance": "Why healthcare KPI frameworks fail and how UAE hospitals can build performance management systems that align measurement with strategy and accountability.",
  "how-healthcare-organisations-can-build-a-sustainable-growth-strategy-in-the-uae-and-gcc": "How healthcare organizations can build a sustainable growth strategy in the UAE and GCC: market feasibility, regulatory planning and service line expansion.",
  "why-healthcare-facility-infrastructure-assessments-are-non-negotiable-in-the-uae-and-gcc": "Why healthcare facility infrastructure assessments matter in the UAE and GCC: their role in regulatory compliance, acquisition due diligence and operations.",
  "the-complete-guide-to-medical-equipment-licensing-in-the-uae-what-suppliers-distributors-and-facilities-must-know": "Medical equipment licensing in the UAE: MOHAP registration, import approvals and common compliance pitfalls for suppliers, distributors and facilities.",
  "why-medical-equipment-planning-is-the-most-overlooked-risk-in-uae-healthcare-projects": "Why medical equipment planning is an overlooked risk in UAE healthcare projects, and how biomedical engineering consultancy protects timelines and budgets.",
  "why-proactive-licensing-audit-gap-assessments-are-critical-for-uae-healthcare-facilities": "Why proactive licensing audit gap assessments matter for UAE healthcare facilities, and how compliance reviews reduce regulatory risk and penalties.",
  "jawda-compliance-in-abu-dhabi-what-healthcare-facilities-need-to-know-to-improve-quality-scores": "Jawda compliance in Abu Dhabi: how the framework works within Muashir, common compliance gaps to avoid and how facilities can sustain quality performance.",
  "why-uae-healthcare-providers-are-losing-revenue-to-outdated-tariff-structures-and-how-to-fix-it": "Why UAE healthcare providers lose revenue to outdated tariff structures, and how cost analysis and payer benchmarking strengthen insurance negotiations.",
  "how-early-stage-hospital-planning-reduces-project-risk-and-accelerates-regulatory-approval-in-the-uae": "How early-stage hospital planning and requirement mapping reduce project risk and help accelerate regulatory approvals across the UAE and GCC.",
  "why-uae-healthcare-providers-lose-revenue-on-insurance-contracts-and-how-to-fix-it": "Why UAE healthcare providers lose revenue on insurance contracts and how tariff negotiation, empanelment management and payer network growth recover margins.",
  "why-uae-hospitals-need-a-structured-digital-transformation-strategy-in-2026": "Why UAE hospitals need a digital transformation strategy: how phased planning, vendor-neutral consulting and regulatory alignment modernize operations.",
  "why-hospital-compliance-readiness-is-a-strategic-imperative-not-an-administrative-task": "Why hospital compliance readiness is a strategic imperative in Abu Dhabi: gap assessments, mock inspections, governance frameworks and DOH regulations.",
  "why-healthcare-organizations-in-the-uae-cannot-afford-to-ignore-hipaa-compliance": "Why HIPAA compliance matters for UAE healthcare organizations: what gap assessments reveal and how to build privacy governance that protects patient data.",
  "how-to-successfully-expand-healthcare-services-and-specialties-in-the-uae": "How to expand healthcare services and specialties in the UAE: feasibility, licensing, staffing, infrastructure and financial planning.",
  "how-to-get-a-healthcare-professional-license-in-the-uae-a-complete-regulatory-guide": "How to get a healthcare professional license in the UAE: DHA, DOH and MOHAP eligibility, DataFlow verification, exam preparation and license transfers.",
  "telehealth-licensing-in-the-uae-what-healthcare-providers-need-to-know-before-launching-virtual-care-services": "Telehealth licensing in the UAE: DOH, DHA and MOH requirements, clinical governance, data compliance and common pitfalls for providers launching virtual care.",
  "how-product-classification-shapes-regulatory-success-in-the-uae-healthcare-market": "How product classification shapes regulatory success in the UAE: assessment and classification pitfalls for pharmaceutical and medical device entry.",
  "how-pharmaceutical-companies-can-navigate-uae-regulatory-approvals-and-achieve-faster-market-access": "How pharmaceutical companies navigate UAE regulatory approvals: multi-authority registration, pricing, reimbursement and post-market compliance in the GCC.",
  "pharmaceutical-product-registration-in-the-uae-a-regulatory-roadmap-for-market-entry": "Pharmaceutical product registration in the UAE: MOHAP requirements, CTD dossiers, GCC-DR pathways and common pitfalls for pharma companies entering the GCC.",
  "how-to-navigate-medical-product-import-approval-in-the-uae-a-regulatory-roadmap-for-manufacturers-and-distributors": "Medical product import approval in the UAE: regulatory frameworks, registration dossiers, delays and post-market duties for manufacturers and distributors.",
  "how-to-navigate-pharmaceutical-distribution-licensing-in-the-uae-a-regulatory-roadmap-for-importers-and-distributors": "Pharmaceutical distribution licensing in the UAE: MOHAP registration, DHA and DOH approvals, GDP warehouse standards and common challenges for distributors.",
  "the-strategic-imperative-of-medical-equipment-procurement-in-uae-healthcare": "Medical equipment procurement consulting helps UAE healthcare organizations reduce costs, avoid regulatory delays and improve clinical outcomes in hospitals.",
  "navigating-medical-and-pharmaceutical-product-registration-in-the-uae-and-gcc-what-manufacturers-need-to-know": "Medical and pharmaceutical product registration in the UAE and GCC: product classification, dossier preparation, market entry planning and post-market duties.",
  "how-to-get-your-telehealth-platform-approved-in-the-uae-a-practical-guide-for-2026": "How to get your telehealth platform approved in the UAE: DHA, DOH and MoHAP requirements, technical standards, governance, NABIDH and Malaffi integration.",
  "how-to-license-a-school-clinic-in-the-uae-a-complete-guide-for-school-operators": "How to license a school clinic in the UAE: regulatory requirements, common pitfalls and a practical roadmap for private and international schools in Dubai.",
  "why-healthcare-acquisitions-fail-without-a-post-integration-strategy-and-how-to-get-it-right-in-the-uae": "Why healthcare acquisitions in the UAE and GCC need a post-integration strategy, and the five pillars of successful integration for hospitals and clinics.",
  "medical-facility-change-of-type-in-the-uae-a-regulatory-roadmap-for-healthcare-operators": "Medical facility change of type in the UAE: DHA, DOH and MOHAP approval processes, documents required and the common reasons applications are rejected.",
  "why-hospital-cybersecurity-has-become-a-patient-safety-priority-in-the-uae": "Why hospital cybersecurity is a patient safety priority in the UAE, and how risk assessments, ADHICS and governance frameworks protect healthcare facilities.",
  "how-to-register-your-healthtech-platform-in-the-uae-a-regulatory-roadmap-for-2026": "How to register your HealthTech platform in the UAE: DOH, DHA and MoHAP approvals, ADHICS cybersecurity and NABIDH integration for digital health companies.",
  "healthcare-software-compliance-in-the-uae-why-regulatory-advisory-is-not-optional": "Healthcare software compliance in the UAE: SaMD classification, ISO 13485, IEC 62304, ADHICS v2.0 and EDE requirements for HealthTech companies in the GCC.",
  "adhics-v20-compliance-what-every-healthcare-facility-in-abu-dhabi-needs-to-know-in-2026": "ADHICS v2.0 compliance in Abu Dhabi: 11 security domains, gap assessment, policy requirements, AAMEN submission and DOH audit preparation.",
  "why-abu-dhabi-phcs-cannot-afford-to-treat-kpi-reporting-as-a-checkbox-exercise": "Why Abu Dhabi PHCs need proactive KPI management, and how benchmarking and Jawda-aligned reporting move them past reactive compliance to lasting performance.",
  "why-clinical-cost-analysis-is-now-a-strategic-priority-for-abu-dhabi-healthcare-providers": "Clinical cost analysis for Abu Dhabi providers: DOH clinical costing requirements, patient-level cost allocation, Shafafiya submission and DRG alignment.",
  "alpha-health-group-launches-cras-for-healthcare-education-in-srilanka": "Alpha Health Group launches CRAS to advance aesthetic healthcare education in Sri Lanka through structured practical training and clinical skill development.",
  "cbahi-accreditation-in-saudi-arabia-what-healthcare-leaders-must-get-right-before-the-surveyors-arrive": "CBAHI accreditation in Saudi Arabia: why it is mandatory in KSA, the gaps that cause survey failures and a path to survey readiness for hospitals.",
  "why-healthcare-construction-project-management-decides-whether-your-facility-opens-on-time": "Healthcare construction project management in the UAE: how specialized project management protects budget, timeline, DOH and DHA approvals and commissioning.",
  "fanr-licensing-in-the-uae-what-healthcare-facilities-get-wrong-before-they-even-apply": "FANR licensing in the UAE: the main reasons FANR applications get rejected and how hospitals and imaging centers can secure radiation approval faster.",
  "why-healthcare-architectural-design-in-the-uae-is-a-compliance-decision-not-a-cosmetic-one": "Healthcare architectural design in the UAE is a compliance and operations decision, not a cosmetic one. Workflow-led design speeds DOH and DHA approval.",
  "healthcare-design-and-build-services-in-the-uae-why-turnkey-delivery-protects-your-opening-date": "Healthcare design and build services in the UAE: why turnkey delivery reduces authority rejections, controls costs and keeps licensed facilities on schedule.",
  "why-payer-relationship-management-is-now-the-biggest-revenue-lever-in-uae-healthcare": "Payer relationship management in UAE healthcare: where margin leaks, how providers cut rejections, win better contracts and meet DHA and DOH rules.",
  "healthcare-insurance-compliance-in-the-uae-why-your-biggest-revenue-leak-is-a-compliance-problem": "Healthcare insurance compliance in the UAE: why claim rejections and audit penalties stem from compliance gaps, and how to align policies and controls.",
  "iso-certification-for-healthcare-facilities-a-practical-roadmap-for-gcc-hospitals-and-labs": "ISO certification for healthcare facilities: how GCC hospitals and labs choose the right ISO standard, avoid audit failures and prepare for certification.",
  "eligibility-letter-in-hand-but-no-job-offer-here-is-why-activating-your-uae-license-comes-first": "UAE eligibility letter but no job offer: why activating your DOH, DHA or MOHAP license first puts you ahead of other candidates and keeps you career-ready.",
  "how-to-get-your-uae-healthcare-license-in-2026-the-complete-guide-for-doctors-nurses-and-allied-professionals": "How to get your UAE healthcare license in 2026: compare DHA, DOH, MOHAP and SHA, understand DataFlow and the exam, and choose the right authority first time.",
  "why-healthcare-staffing-in-the-uae-fails-after-the-offer-and-how-to-fix-it": "UAE healthcare staffing fails in credentialing and licensing, not sourcing. See how a managed staffing workflow cuts rejections and speeds deployment.",
  "why-clinical-recruitment-in-the-uae-fails-without-verification-first": "Why clinical recruitment in the UAE fails without verification first, and how verification-first nurse and allied health hiring cuts DataFlow delays.",
  "how-to-transfer-your-healthcare-license-between-uae-authorities-without-starting-over": "How to transfer your healthcare license between UAE authorities in 2026: DHA, DOH, MOHAP and SHA, exam exemption and DataFlow PSV portability explained.",
  "why-uae-physician-recruitment-fails-after-the-offer-and-how-to-fix-it": "Why UAE physician recruitment fails after the offer: verification, privileging and licensing gaps, and how facilities cut time-to-fill and retain clinicians.",
  "why-a-strong-radiation-protection-program-is-now-non-negotiable-for-uae-healthcare-facilities": "Why a radiation protection program built to FANR rules is essential for UAE healthcare facilities, what it must include and how it protects staff.",
  "why-radiation-equipment-registration-in-the-uae-fails-before-it-starts": "Why radiation equipment registration in the UAE stalls, what a complete FANR file requires and how correct sequencing speeds X-ray and CT scanner approval.",
  "nabh-accreditation-in-2025-why-preparation-not-paperwork-decides-the-outcome": "NABH accreditation: the NABH 6th edition standards, common assessment failures and a structured path to accreditation for hospitals and clinics.",
  "why-medical-facility-fit-out-in-the-uae-is-a-compliance-project-not-just-an-interior-job": "Medical fit-out in the UAE is a compliance and engineering project: the three failure points that delay licensing and how integrated delivery avoids them.",
  "isqua-accreditation-what-gcc-healthcare-leaders-must-know-before-audit-day": "ISQua accreditation for UAE and GCC healthcare organizations: what it involves, the 2025 dual accreditation rule and how to prepare for a clean audit outcome.",
  "how-to-register-consumer-products-in-dubai-without-costly-delays": "How consumer product registration works in Dubai, why applications get rejected and how to clear Dubai Municipality approval faster for UAE market entry.",
  "cosmetic-product-registration-in-dubai-what-brands-must-know-before-they-launch": "Cosmetic product registration in Dubai: Dubai Municipality requirements, common rejection causes and how to secure faster approval for UAE market access.",
  "dietary-supplement-registration-in-dubai-what-brands-must-get-right-before-market-entry": "Dietary supplement registration in Dubai: a practical guide to Dubai Municipality requirements, common rejection reasons and how to get approval faster.",
  "how-to-register-personal-care-products-in-dubai-without-costly-delays": "How to register skincare, haircare and hygiene products with Dubai Municipality: the process, documents, common rejection causes and tips for faster approval."
 },
 "projects": {
  "ihlad-abu-dhabi": "Wellness and longevity centre setup: Alpha Health Group delivered the regulatory, engineering, compliance and technology framework for a longevity centre.",
  "dna-health-corp": "Turnkey wellness and preventive care centre: how Alpha Health Group delivered the complete turnkey development of DNA Wellness Medical Complex.",
  "nhs-medical-center": "Visa and occupational screening complex: how Alpha Health Group developed NHS Medical Center, an 80,000+ sq. ft. medical complex, from start to finish.",
  "broadway-vascular": "Specialised vascular surgical facility: how Alpha Health Group delivered Broadway Vascular Surgery Center as a complete turnkey development.",
  "sanimed-group": "General and genetic laboratory network: Alpha Health Group's turnkey development of Sanimed International Lab, a multi-branch clinical laboratory network.",
  "reem-hospital": "Hospital expansion and capacity upgrade: Alpha Health Group led several post-operational expansion projects for Al Reem Hospital, a 200+ bed facility.",
  "bone-care": "License reactivation and surgical expansion: how Alpha Health Group managed the reactivation of Bonecare Day Surgery Centre, a dormant, expired-license site.",
  "smch": "Hospital DOH approval and quality governance: Alpha Health Group led the regulatory compliance framework, managed DOH approval and set up quality governance.",
  "vfs-global": "VIP visa medical screening facility: how Alpha Health Group delivered the turnkey development of VFS Global's boutique medical screening centre in Dubai.",
  "healthcare-solutions": "Medical storage and handling facility: Alpha Health Group provided planning, regulatory structuring and compliance for Aramax Healthcare Logistics Solutions.",
  "beijing-therapy": "Multi-branch TCM network licensing: Alpha Health Group is the long-term regulatory and compliance partner for Beijing Well Being Medical Group.",
  "royal-spanish-center": "Multi-branch eye day surgery network: Alpha Health Group has partnered with Royal Spanish Center for over four years on engineering design and approval.",
  "revital-hospital": "Rehabilitation hospital ADHICS and JAWDA work for Revital Rehabilitation Hospital, a 52-bed specialised rehabilitation facility, by Alpha Health Group.",
  "skin111": "How Alpha Health Group delivered SKIN111, a turnkey aesthetic and wellness clinic in Abu Dhabi: planning, engineering, construction and DOH licensing."
 },
 "brands": {
  "alpha-health-consultancies": "Alpha Health Consultancies is a UAE-based healthcare consultancy specializing in healthcare management, professional licensing and regulatory compliance.",
  "alpha-training-strategic-management": "Alpha Training & Strategic Management is a medical education and professional training provider operating under Alpha Health Group.",
  "alpha-medical-engineering": "Alpha Medical Engineering is a specialized healthcare engineering and consultancy division operating under Alpha Health Group in the UAE.",
  "chartered-bureau-pvt-ltd": "Chartered Bureau is a Sri Lanka-based travel and tourism company specializing in medical and educational tourism, air ticketing and customized travel.",
  "royal-wellness-center": "Royal Wellness Center is a multi-specialty aesthetic and wellness medical center in Sri Lanka, offering cosmetic, dermatology and hair restoration services.",
  "ihr-hub": "iHRHub is a global recruitment and workforce solutions company operating under Chartered Business Assurance, specializing in recruiting skilled professionals.",
  "chartered-business-assurance": "Chartered Business Assurance is a healthcare-focused consulting and business assurance organization operating across the UAE, UK and Sri Lanka.",
  "college-of-royal-aesthetic-of-sri-lanka": "The College of Royal Aesthetic of Sri Lanka (CRAS) is a TVEC-approved educational institution specializing in aesthetic medicine and cosmetology.",
  "nexthealth": "NextHealth is a HealthTech software platform developed under Alpha Health Group, focused on transforming healthcare operations through intelligent automation.",
  "eclinic": "eClinic is a digital healthcare ecosystem and telemedicine platform that connects patients with licensed healthcare professionals across multiple specialties.",
  "cambvue": "CambVUE is an online digital learning and education management platform developed for the College of Royal Aesthetic of Sri Lanka.",
  "certinext": "CertiNEXT is a digital technology platform focused on AI-powered exam proctoring and assessment management, designed to support educational institutions."
 }
}
JSON;
};
