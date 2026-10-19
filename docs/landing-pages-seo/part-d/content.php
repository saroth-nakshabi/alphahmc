<?php
/**
 * Part D content (landing-pages-seo-brief.md D1 to D3): B2B ad landing pages.
 * Data only; build.php turns it into SQL with copy checks.
 *
 * meta     ready: D2 titles and descriptions.
 * fixes    ready: sentence-level replacements of claims and unbacked figures, applied with
 *          REPLACE() and a guard, so production text that has since changed is left alone.
 * held     contains [confirm]: D1 change of plan and relocation, D3 investor FAQs.
 */

$C = '[confirm]';

/* ── D2 titles and descriptions (ready) ───────────────────────────── */
$meta = [
    81 => ['Drug Store and Pharma Distribution License UAE',
           'Licensing for companies that import, store or distribute pharmaceuticals in the UAE: MOHAP, DHA and DOH approvals and GDP warehouse readiness.'],
    // Title before D1 is live; the D1 title is in $held.
    68 => ['Healthcare Specialty Expansion in the UAE',
           'Add a clinical department, change your floor plan or relocate: feasibility, DHA and DOH approvals and the licensing file, planned with you.'],
    93 => ['Medical Facility Change of Type: DHA, DOH, MOHAP',
           'Convert a clinic to a medical center or reclassify your facility: layouts, scope of services and the amendment file for DHA, DOH or MOHAP.'],
    57 => ['ADHICS v2.0 Audit and Implementation Abu Dhabi',
           'ADHICS v2.0 gap assessment and implementation for DOH-licensed hospitals, medical centers and day surgery centers in Abu Dhabi.'],
    // The brief's "Typically 2 to 6 weeks." is held until the timeline is confirmed.
    76 => ['Healthcare Licensing Audit and Gap Assessment UAE',
           'A structured review of your facility against DHA, DOH or MOHAP licensing requirements before inspection or renewal.'],
    7  => ['Medical Centre Setup and DHA Licensing in Dubai',
           'Open a medical center or clinic in Dubai: DHA facility licensing, layouts, staffing and approvals, planned with investors from day one.'],
    2  => ['Clinic Setup and Licensing in the UAE',
           'Set up a clinic in Dubai, Abu Dhabi or the Northern Emirates: DHA, DOH and MOHAP facility licensing, layouts and approvals with one team.'],
    9  => ['DOH Healthcare Facility Licensing in Abu Dhabi',
           'Open a clinic, medical center or hospital in Abu Dhabi: DOH facility licensing, engineering approval and inspection readiness.'],
];

/* ── D3 sentence fixes (ready): [service id, field, exact current text, new text] ──
   Removes compliance claims, "compliant", unbacked proof figures (200+, 20/25 years,
   since 2004, ISO certified, 47%, 10 business days, 6-12 months) and "eliminate" promises. */
$fixes = [
    [68, 'info_two', 'our approach is built on 25 years of experience supporting over 200 healthcare facilities across the region.', 'we plan each expansion against the authority requirements before anything is filed.'],
    [68, 'info_three', 'Trusted by 200+ facilities across the UAE for regulatory-aligned healthcare growth.', 'Feasibility, licensing and operational planning for new services and specialties.'],
    [68, 'info_four', 'With 25+ years supporting 200+ healthcare facilities across the UAE, Alpha Health Group brings regulatory precision, market intelligence, and operational depth to every expansion project.', 'Alpha Health Group brings regulatory, market and operational planning to every expansion project.'],

    [93, 'info_one', 'Seamless Facility Transitions. Zero Compliance Gaps.', 'Change Your Facility Type With a Clear Approval Path'],
    [93, 'info_one', 'We eliminate the operational risk of healthcare facility reclassification by managing every regulatory touchpoint, from initial feasibility assessment through final license activation.', 'We manage every regulatory step of a facility reclassification, from the initial feasibility assessment to final license activation.'],
    [93, 'info_two', 'Alpha Health Group provides structured, compliance-first consulting for medical facility change of type', 'Alpha Health Group provides structured consulting for medical facility change of type'],
    [93, 'info_two', 'With over 200 healthcare facilities established and managed across the UAE and GCC, we bring institutional knowledge of how DHA, DOH, and MOHAP evaluate facility reclassification applications.', 'We know how DHA, DOH and MOHAP evaluate facility reclassification applications, and where they most often raise queries.'],
    [93, 'info_two', 'to resolve compliance gaps before they become rejection triggers.', 'to close gaps against the requirements before they lead to a rejection.'],
    [93, 'info_three', 'ISO 9001:2015 certified. DOH approved. 200+ facilities established. Trusted across every UAE health authority.', 'Facility change of type and reclassification with DHA, DOH and MOHAP.'],

    [81, 'overview', 'healthcare product distribution compliance consulting across Dubai, Abu Dhabi, and the wider UAE, helping distributors, importers, and drugstore operators secure regulatory clearance and build compliant supply chain operations from day one.', 'healthcare product distribution consulting across Dubai, Abu Dhabi and the wider UAE, helping distributors, importers and drug store operators secure regulatory clearance and set up their supply chain correctly from day one.'],
    [81, 'info_one', 'from initial application to operational compliance, reducing approval timelines and eliminating regulatory risk.', 'from the initial application to an operating license, avoiding the delays that come from incomplete files.'],
    [81, 'info_two', 'Our consulting approach prioritises measurable outcomes: faster approvals, zero non-conformities at inspection, and operational compliance that holds through audit cycles.', 'Our consulting approach focuses on what you can measure: approval progress, inspection findings closed before the authority visit, and operations that hold up through audit cycles.'],
    [81, 'info_two', 'our regulatory consulting ensures your licensing foundation supports sustainable, compliant growth.', 'we set up your licensing foundation to support the next stage of growth.'],
    [81, 'info_three', 'Trusted by 200+ healthcare businesses across the UAE for regulatory licensing and compliance delivery.', 'Licensing support for drug stores, importers and pharmaceutical distributors across the UAE.'],
    [81, 'info_four', 'With 200+ healthcare facilities licensed across the UAE and GCC, Alpha Health Group eliminates regulatory uncertainty from your pharmaceutical distribution setup.', 'Alpha Health Group takes the regulatory guesswork out of your drug store or pharmaceutical distribution setup.'],
    [81, 'info_four', 'Our consultants handle authority coordination, documentation, and compliance readiness so you secure approvals faster and operate without interruption.', 'Our consultants handle authority coordination, documentation and inspection readiness, so approvals move without avoidable delays.'],

    [57, 'content', 'Certified ADHICS consultants specializing in DOH compliance with a proven track record', 'ADHICS consultants for DOH-licensed healthcare facilities in Abu Dhabi'],
    [57, 'overview', 'Our specialized team ensures full compliance with updated AI governance controls, IoMT security frameworks, cloud healthcare protocols, and enhanced incident response workflows mandated by DOH\'s latest information security standards.', 'Our team helps you implement the updated AI governance controls, IoMT security frameworks, cloud healthcare protocols and incident response workflows in DOH\'s latest information security standard.'],
    [57, 'info_one', 'Secure Your DOH Compliance Today', 'Prepare for Your ADHICS Audit'],
    [57, 'info_two', 'Healthcare data breaches in the UAE increased 47% year-over-year, prompting DOH to release ADHICS v2.0 with stricter controls', 'DOH released ADHICS v2.0 with stricter controls'],
    [57, 'info_two', 'Our ADHICS audit and implementation service delivers end-to-end compliance support.', 'Our ADHICS audit and implementation service covers the full cycle, from gap assessment to audit readiness.'],
    [57, 'info_three', '20+ years implementing DOH standards across Abu Dhabi\'s leading healthcare institutions, delivering measurable compliance outcomes.', 'Gap assessment, implementation and audit preparation for DOH-licensed facilities.'],
    [57, 'info_four', 'Ensure DOH compliance before audit pressure hits.', 'Close the gaps before the DOH audit.'],
    [57, 'info_four', 'Our consultants deliver actionable gap assessments within 10 business days, providing clear implementation roadmaps', 'Our consultants deliver an actionable gap assessment with a clear implementation roadmap'],

    [76, 'overview', 'prepare your facility for full licensing compliance with DHA, DOH, and MOHAP requirements, reducing risk and accelerating approvals.', 'prepare your facility for its DHA, DOH or MOHAP inspection, with fewer surprises on the day.'],
    [76, 'info_one', 'Close Compliance Gaps Before They Cost You', 'Find Licensing Gaps Before the Inspector Does'],
    [76, 'info_two', 'review of your facility\'s compliance posture against the applicable regulatory framework.', 'review of your facility against the applicable regulatory framework.'],
    // Stored with &nbsp; and a link on "200+"; the original is the exact stored HTML.
    [76, 'info_two', 'this structured approach has consistently delivered zero non-conformity outcomes and accelerated approval timelines across&nbsp;<a title="200+" href="/about" target="_blank" rel="noopener">200+</a> healthcare facilities we have supported.', 'this structured approach gives you a prioritised list of gaps to close before the authority visits.'],
    [76, 'info_three', 'Identify Your Compliance Gaps Today', 'Book Your Licensing Gap Assessment'],
    [76, 'info_three', 'Trusted by 200+ UAE healthcare facilities for regulatory compliance, licensing readiness, and zero non-conformity outcomes.', 'A structured review against DHA, DOH and MOHAP licensing requirements, before inspection or renewal.'],
    [76, 'info_four', 'Alpha Health Group\'s licensing audit gap assessments have supported 200+ facilities in achieving full compliance.', 'Alpha Health Group\'s licensing audit gap assessment gives you a clear, prioritised plan before the authority inspects.'],

    [7, 'info_one', 'Launch Your Medical Facility Compliant', 'Open Your Medical Centre in Dubai'],
    [7, 'info_two', 'Our turnkey setup service eliminates this risk.', 'Our turnkey setup service manages that complexity for you.'],
    [7, 'info_two', 'ensure fit-out compliance with infection control and medical gas standards', 'check the fit-out against infection control and medical gas standards'],

    [2, 'info_one', 'Launch Your Clinic Without Compliance Risk', 'Set Up and License Your Clinic in the UAE'],
    [2, 'info_two', 'your facility is operationally ready with compliant systems, trained staff credentials verified, and inspections passed.', 'your facility is ready to operate: systems in place, staff credentials verified and inspections passed.'],
    [2, 'info_two', 'This integrated approach eliminates the typical back-and-forth between architects, engineers, and regulators that often extends timelines by 6-12 months.', 'This integrated approach cuts the back-and-forth between architects, engineers and regulators that often delays a launch.'],
    [2, 'info_four', 'With 20 years of UAE healthcare consulting experience, Alpha Health Group gives facility owners', 'Alpha Health Group gives facility owners'],

    [9, 'content', 'Specialised & market leaders in DOH regulatory consultation & licensing consultant, trusted by healthcare providers across the UAE for regulatory excellence', 'DOH facility licensing consultants for healthcare providers in Abu Dhabi'],
    [9, 'overview', 'and EMR onboarding to ensure first-time approval success.', 'and EMR onboarding, so your facility is ready for its DOH inspection.'],
    [9, 'info_one', 'We eliminate delays and rejection risk through structured planning and proven execution.', 'Structured planning and preparation reduce the delays and rejections that slow DOH approvals.'],
    [9, 'info_three', 'Delivering DOH-compliant facility launches across Abu Dhabi since 2004.', 'DOH facility licensing for new and expanding facilities across Abu Dhabi.'],
    [9, 'info_four', 'Partner with the team that has guided 200+ facilities through Abu Dhabi\'s regulatory landscape.', 'Work with a team that knows Abu Dhabi\'s licensing process.'],
    [9, 'info_four', 'Reduce approval timelines, eliminate rework, and launch with full compliance confidence.', 'Reduce approval delays, avoid rework and launch with confidence.'],

    [8, 'content', 'Ensure full regulatory compliance across DHA, DOH, MOH, and SHA frameworks.', 'Compliance audits against DHA, DOH, MOH and SHA requirements.'],
    [8, 'info_one', 'reducing compliance risk while strengthening your operational framework.', 'reducing regulatory risk while strengthening your operational framework.'],
    [8, 'info_three', 'Supporting over 200 UAE healthcare facilities with 20+ years of regulatory expertise across DOH, DHA, MOH, and international accreditation frameworks.', 'Regulatory audits across DOH, DHA, MOH and international accreditation frameworks.'],
    [8, 'info_four', 'Our audits deliver clarity, prioritization, and actionable plans that bring you into full compliance with UAE healthcare authorities.', 'Our audits deliver clarity, priorities and an actionable plan to close the gaps against UAE health authority requirements.'],
];

/* FAQ sentence fixes (ready): [faq id, exact current text, new text] */
$faqFixes = [
    [295, 'non-compliant engineering layouts', 'engineering layouts that do not meet the guidelines'],
    [220, 'warehouse compliance readiness', 'warehouse readiness'],
    [192, 'Pre-renewal gap assessments identify and resolve non-conformities before the authority review, significantly improving first-attempt approval rates.', 'Pre-renewal gap assessments identify non-conformities so you can resolve them before the authority review.'],
    [11, 'compliant facility design', 'a facility design that meets DOH standards'],
];

/* ── Held until [confirm] is resolved ─────────────────────────────── */
$held = [
    // D1: append to the specialty expansion page's main text.
    'd1_info_two_append' => "<h2>Change of plan approval</h2>
<p>When a licensed facility changes its floor plan or layout, the health authority must approve the change before the work starts. DHA, DOH and MOHAP each ask for updated engineering layouts and an updated scope of services. $C what each authority requires and in what order. We prepare the engineering drawings and the change of plan file with you and follow it through to approval.</p>
<h2>Moving your facility to a new location</h2>
<p>Relocating a licensed facility needs the authority's approval for the new premises. Engineering plans must be approved before construction begins, so start the relocation approval before fit-out at the new site. $C what relocation approval involves for each authority.</p>",
    'd1_title' => 'Add a Specialty or Move Your Facility in the UAE',
    'd1_faqs' => [
        ['How long does a change of plan approval take?', "$C a typical range, from real cases. It depends on the authority, how much of the layout changes and how complete the engineering drawings are. We give you an estimate once we have seen the current and proposed layouts, and tell you what to prepare first."],
        ['What documents does a relocation need?', "Usually updated engineering layouts for the new premises, the scope of services and the authority's relocation application. $C the standard list for DHA, DOH and MOHAP. We prepare the drawings and the file with you and track the application through to approval."],
        ['Can we keep operating during the change?', "$C whether a facility can keep operating during a change of plan or a relocation, and under what conditions. We plan the timing with you so that patient services are disrupted as little as possible while the approval is in progress."],
    ],
    // D3: investor questions on the setup pages.
    'd3_faqs' => [
        2 => [
            ['How do I open a clinic in Dubai?', "The usual sequence is business registration, DHA initial approval, engineering plan approval, fit-out and equipment, staff licensing, the final inspection and the operational license. $C the order and step names. Engineering plans must be approved before construction begins, so the layout comes before the fit-out."],
            ['Can you set up a dental clinic?', "Yes. A dental clinic follows the same facility licensing path, with its own equipment, layout and infection control requirements for dental services. $C the dental-specific requirements to state. We check your layout and service list against the authority's standards before you commit to fit-out."],
        ],
        7 => [
            ['How do I get a polyclinic license in Dubai?', "$C the polyclinic requirements, including the number of specialties and services DHA expects. The process follows the same path as a medical centre: initial approval, engineering approval, fit-out, staff licensing and the final inspection. We confirm the right facility category for your service mix first."],
        ],
    ],
    'd2_licensing_audit_description_with_timing' => "A structured review of your facility against DHA, DOH or MOHAP licensing requirements before inspection or renewal. Typically 2 to 6 weeks. $C",
];

/* Existing figures on these live pages that nobody has confirmed yet (listed, not changed). */
$verify = [
    'faq 142 (expansion): "3 to 12 months from feasibility through licensing approval"',
    'faq 145 (expansion): cardiology, oncology, rehabilitation, mental health and imaging named as fastest growing',
    'faq 292 (change of type): "4 to 12 weeks", "3 to 6 months"',
    'faq 294 (change of type): DHA "Amend Facility License" service on Sheryan',
    'faq 220 (drug store): "8 to 16 weeks"',
    'faq 69 (ADHICS): "3-6 months", "2-3 weeks", "8-16 weeks", "2-4 weeks"',
    'faq 70 (ADHICS): enforcement "Q2 2024 for new facilities and Q4 2024 for existing"',
    'faq 71 (ADHICS): "60-90 day remediation", "AED 50,000 to AED 500,000" penalties',
    'faq 190 (licensing audit): "2 to 6 weeks"',
    'faq 326 (medical centre): "6-9 months"',
    'faq 327 (medical centre): DHA fees "AED 50,000-150,000", fit-out "AED 2,000-4,000 per sqm"',
    'faq 328 (medical centre): medical centre "3+ specialities", polyclinic "5+ with day surgery"',
    'faq 128 (clinic): "4-6 months", "6-9 months"; faq 133: "2-6 weeks"',
    'faq 10 (DOH): "4 to 8 months"; faq 14: "2 to 4 months"',
    'service 93 removed line: "ISO 9001:2015 certified. DOH approved." (put back if confirmed)',
];

return compact('meta', 'fixes', 'faqFixes', 'held', 'verify');
