<?php
/**
 * Part B content for alphahmc.com (landing-pages-seo-brief.md, B1 to B7 plus
 * page_settings descriptions). Data only: build.php turns it into SQL and a
 * review document. "[confirm]" marks a fact the licensing team must supply.
 *
 * ready   = no unconfirmed facts; may go live now.
 * confirm = contains [confirm]; held back until the team replaces every marker.
 */

$C = '[confirm]';

$svc = [];

/* ── B1 UAE (services id 67) ───────────────────────────────────────── */
$svc[67] = [
    'ready' => [
        'meta_title' => 'DHA, DOH and MOHAP License Consultants | Alpha Health Group',
        // Saroth 19 Oct: H1 rename approved; initial consultation is free, the process is charged.
        'name' => 'Healthcare Professional Licensing in the UAE: DHA, DOH and MOHAP',
        'meta_description' => 'Licensing help for doctors, nurses and allied staff: DHA, DOH and MOHAP eligibility, DataFlow, exams and applications. Book a free consultation.',
        'areaServed' => 'United Arab Emirates',
    ],
    'confirm' => [
        'content' => '<p>We guide doctors, nurses, pharmacists and allied health staff through DHA, DOH and MOHAP licensing, from eligibility to your license.</p>',
        'overview' => '<p>Alpha Health Group handles healthcare professional licensing with DHA, DOH and MOHAP: eligibility assessment, DataFlow primary source verification, exam guidance, the application and license transfers, for doctors, nurses, pharmacists, dentists and allied health professionals.</p>',
        'info_one' => '<h2>Who needs a UAE healthcare license</h2>
<p>Every doctor, nurse, pharmacist, dentist and allied health professional needs a license from the health authority of the emirate where they will work: the <a href="https://www.dha.gov.ae" target="_blank" rel="noopener">Dubai Health Authority (DHA)</a> for Dubai, the <a href="https://www.doh.gov.ae" target="_blank" rel="noopener">Department of Health (DOH)</a> for Abu Dhabi, and the <a href="https://mohap.gov.ae/en" target="_blank" rel="noopener">Ministry of Health and Prevention (MOHAP)</a> for the Northern Emirates. Sharjah also has its own regulator, the Sharjah Health Authority (SHA).</p>',
        'info_two' => "<h2>How DHA licensing works</h2>
<p>The DHA license follows five steps. $C the steps and their wording.</p>
<ol>
<li><strong>Eligibility check.</strong> Your degree, license and experience are checked against the DHA Professional Qualification Requirements (PQR) for your title.</li>
<li><strong>DataFlow verification.</strong> DataFlow checks your degree, license and experience directly with the institutions that issued them. This is primary source verification.</li>
<li><strong>Licensing exam or exemption.</strong> Most titles sit the DHA licensing exam at a Prometric centre. Some applicants qualify for an exemption.</li>
<li><strong>Registration.</strong> Once you pass or are exempted, DHA confirms your eligibility to be licensed.</li>
<li><strong>Activation by your employer.</strong> Your license becomes active when a DHA-licensed facility employs you and <a href=\"/services/activate-your-uae-healthcare-license\">activates it</a>.</li>
</ol>
<h2>How DOH and MOHAP licensing differ</h2>
<p><strong>DOH (Abu Dhabi)</strong> follows the same pattern of eligibility, DataFlow verification, an exam or exemption, and activation by an employer. You apply through TAMM, and DOH applies its own qualification requirements and exam rules. $C the main differences to state.</p>
<p><strong>MOHAP</strong> licenses professionals for the Northern Emirates through MOHAP eServices, with its own qualification requirements and exam. $C the main differences to state.</p>
<h2>Applying from India and other countries</h2>
<p>You can start before you move. The eligibility check, DataFlow verification and exam booking are all done online, so most of the work happens while you are still in India or your home country. $C whether the exam can be taken outside the UAE, and which documents must be attested in the issuing country.</p>
<p>We work with you on WhatsApp and online calls, tell you which documents to collect, and check them before anything is submitted. The license is activated once a UAE facility employs you.</p>
<h2>Timeline</h2>
<p>$C a typical range per authority, from real cases. The time depends mainly on how quickly DataFlow completes your verification, the next available exam date and how complete your documents are.</p>
<h2>Costs</h2>
<p>You pay the health authority's application and licensing fees, the DataFlow verification fee and, where it applies, the exam fee. These are set by the authorities and DataFlow and are paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>",
        'info_three' => '<h2>How Alpha Health Group helps</h2>
<p>Our licensing team handles the work around your application:</p>
<ul>
<li>Checks your eligibility against the current requirements for your title and authority</li>
<li>Prepares your documents and checks them before submission</li>
<li>Opens and follows your DataFlow verification</li>
<li>Guides you on the exam and booking</li>
<li>Submits the application and follows it up with the authority</li>
<li><a href="/services/healthcare-professional-license-transfer">Moves your license between DHA, DOH and MOHAP</a> when you change emirate</li>
</ul>',
        'info_four' => '<p>Send us your qualification, years of experience and the emirate you want to work in. We will tell you which authority applies, what you need and what to do next.</p>
<p>Planning to work elsewhere in the GCC? See <a href="/services/saudi-healthcare-professional-licensing">SCFHS licensing in Saudi Arabia</a>, <a href="/services/healthcare-professional-licensing-qatar">DHP licensing in Qatar</a> and <a href="/services/healthcare-professional-licensing-oman">licensing in Oman</a>.</p>',
    ],
    'faqs' => [
        134 => ["How long does DHA or DOH licensing take?",
            "$C a typical range per authority, from real cases. The time depends mainly on DataFlow verification, the next available exam date and how complete your documents are. We give you an estimate for your title after the eligibility review."],
        135 => ["How much does it cost, and what is included in Alpha's fee?",
            "The authority's licensing fees, the DataFlow fee and any exam fee are set by them and paid separately. Alpha's fee covers the eligibility review, document preparation, DataFlow follow-up, exam guidance and the application. Your first consultation is free; the licensing process itself is charged."],
        136 => ["Do I need to pass an exam, and which one?",
            "Most titles must pass the authority's licensing exam, delivered at Prometric centres. Some applicants qualify for an exemption. $C the exemption criteria to state. We confirm whether you need the exam in your eligibility review, before you book anything."],
        137 => ["What documents do I need for DataFlow?",
            "Usually your degree, your professional license or registration, experience certificates and your passport. $C the standard list. DataFlow verifies each one with the institution that issued it, so names and dates must match across documents. We check them before you submit."],
        138 => ["Can I apply from outside the UAE?",
            "Yes. The eligibility check, DataFlow verification and exam booking are done online, so you can start from India or anywhere else. Your license is activated once a UAE healthcare facility employs you. $C whether the exam can be taken outside the UAE."],
        139 => ["What happens after I pass the exam?",
            "The authority confirms your eligibility to be licensed. Your license is then issued and activated when a licensed facility in that emirate employs you, so you can sign an offer with eligibility already in hand. You can practise only once the license is active. $C how long eligibility stays valid before activation."],
    ],
];

/* ── B2 License transfer (services id 112) ─────────────────────────── */
$svc[112] = [
    'ready' => [
        'meta_title' => 'Move Your License Between DHA, DOH and MOHAP | Alpha Health',
        'areaServed' => 'Abu Dhabi, Dubai, Sharjah',
        'meta_description' => "Moving between Dubai, Abu Dhabi and the other emirates? We handle the new authority's license, DataFlow reuse and good standing. Book a consultation.",
    ],
    'confirm' => [
        'content' => "<p>There is no automatic transfer between DHA, DOH and MOHAP. You apply to the new authority while your current license is still valid, reuse your DataFlow report where it is accepted, provide a good standing certificate and an employer in the new emirate, and sit an exam only if your title requires one. $C the exact rule.</p>",
        'overview' => '<p>Offered a role in another emirate? We move your DHA, DOH, MOHAP or SHA professional license to the right authority, redirect your DataFlow report and request exam exemption, so you do not repeat the full licensing process.</p>',
        'info_one' => '<h2>One license, one authority</h2>
<p>A UAE healthcare license belongs to the authority that issued it. A DHA license does not let you practise in Abu Dhabi, and a DOH license does not cover Sharjah or Ajman. When you change emirate, you apply to the new authority. Getting your first UAE license instead? See <a href="/services/healthcare-professional-licensing-uae">healthcare professional licensing in the UAE</a>.</p>',
        'info_two' => "<h2>DHA to DOH</h2>
<p>Apply to DOH through TAMM, request exam exemption on the strength of your valid DHA license, and redirect your existing DataFlow report to DOH instead of verifying again. Your Abu Dhabi employer activates the DOH license once eligibility is confirmed. $C</p>
<h2>DOH to DHA</h2>
<p>The same steps in the other direction: apply to DHA through Sheryan with your valid DOH license, request exam exemption, redirect your DataFlow report to DHA, and your Dubai facility activates the license. $C</p>
<h2>MOHAP and the other emirates</h2>
<p>MOHAP licenses professionals for the Northern Emirates through MOHAP eServices. Sharjah has its own regulator, the Sharjah Health Authority (SHA), which issues licenses through its own portal. The pattern is the same: a new application, exam exemption where your current license qualifies, and a redirected DataFlow report.</p>
<h2>Documents you need</h2>
<ul>
<li>Your current, valid UAE license</li>
<li>A good standing certificate from your current authority</li>
<li>Your existing DataFlow report</li>
<li>An offer or employment from a facility in the new emirate</li>
<li>$C any other documents the receiving authority asks for</li>
</ul>
<h2>Timeline</h2>
<p>$C a typical range, from real cases. The time depends on how quickly the DataFlow redirect and the exam exemption are approved. Your new employer then activates the license.</p>",
        'info_three' => '<h2>How Alpha helps</h2>
<p>Send us your current license and the emirate you are moving to. We confirm your exam exemption and DataFlow route in one assessment, then handle the move:</p>
<ul>
<li>Good standing certificate from your current authority</li>
<li>Account and application on the right portal: Sheryan, TAMM, MOHAP eServices or the SHA portal</li>
<li>DataFlow report redirected rather than repeated</li>
<li>Exam exemption requested on the basis of your current license</li>
<li>Coordination with your new facility for <a href="/services/activate-your-uae-healthcare-license">license activation</a></li>
</ul>',
        'info_four' => '<p>Send us your current license and the emirate you are moving to, and we will confirm the route before you sign the new offer.</p>',
    ],
    'faqs' => [
        409 => ["Is there an automatic transfer between DHA and DOH?",
            "No. You apply to the new authority while your current license is still valid. A valid DHA or DOH license usually qualifies you for exam exemption, and you can redirect your existing DataFlow report instead of verifying again. $C the exact rule."],
        410 => ["Do I need to sit the licensing exam again?",
            "Usually not. Professionals with a valid, active UAE license can request exam exemption from the receiving authority, subject to its eligibility criteria. We confirm whether your title qualifies in the first assessment, before you sign the new offer or give notice in your current role."],
        411 => ["Can I reuse my DataFlow report?",
            "Yes. In the DataFlow portal you open a Report Transfer or Reissuance case, link your existing primary source verification report to the new authority and pay the transfer fee. Your documents are not verified again from the start."],
        412 => ["How long does a license transfer take?",
            "$C a typical range, from real cases. Most of the time goes on the DataFlow redirect and the exam exemption decision. Once eligibility is confirmed, your new employer activates the license. Applying while your current license is still valid avoids a gap between the two."],
        413 => ["How much does a transfer cost, and what is included in Alpha's fee?",
            "The receiving authority's fees and the DataFlow transfer fee are set by them and paid separately. Alpha's fee covers the assessment, good standing certificate, portal application, DataFlow redirect, exemption request and activation follow-up. Your first consultation is free; the transfer itself is charged."],
        414 => ["Can I hold DHA, DOH and MOHAP licenses at the same time?",
            "Yes. You may hold licenses from more than one authority at once, but each license must be activated and kept active by a separate licensed facility. This suits professionals who work across emirates, for example a part-time role in Dubai alongside a main role in Abu Dhabi."],
    ],
];

/* ── B3 Saudi (services id 66) ─────────────────────────────────────── */
$svc[66] = [
    'ready' => [
        'meta_title' => 'SCFHS License Support for Saudi Arabia | Alpha Health Group',
        'meta_description' => 'Saudi SCFHS licensing for doctors, nurses and allied staff: Mumaris Plus registration, DataFlow, classification and exams, managed with you.',
        'areaServed' => 'Saudi Arabia',
    ],
    'confirm' => [
        'content' => '<p>We guide doctors, nurses, pharmacists and allied health staff through SCFHS registration in Saudi Arabia, from DataFlow to your license.</p>',
        'overview' => '<p>Alpha Health Group handles SCFHS licensing for doctors, nurses, pharmacists, dentists and allied health professionals: eligibility, DataFlow verification, Mumaris Plus registration, professional classification and exam guidance.</p>',
        'info_one' => '<h2>Who needs SCFHS registration</h2>
<p>Every doctor, nurse, pharmacist, dentist and allied health professional must hold a valid professional license from the <a href="https://www.scfhs.org.sa" target="_blank" rel="noopener">Saudi Commission for Health Specialties (SCFHS)</a> before practising in any healthcare facility in Saudi Arabia.</p>',
        'info_two' => "<h2>Steps: Mumaris Plus, DataFlow, classification, exam, registration</h2>
<ol>
<li><strong>Mumaris Plus.</strong> Applications, registration, classification and renewals all go through Mumaris Plus, the SCFHS licensing platform.</li>
<li><strong>DataFlow verification.</strong> DataFlow verifies your academic and professional credentials with the institutions that issued them. Every applicant needs it.</li>
<li><strong>Professional classification.</strong> SCFHS classifies you by title and level based on your qualifications and experience.</li>
<li><strong>Exam.</strong> You sit the SCFHS exam for your classification where one applies. $C which categories are exempt.</li>
<li><strong>Registration.</strong> SCFHS registers you and issues your professional license.</li>
</ol>
<h2>Timeline</h2>
<p>$C a typical range, from real cases. The time depends on your classification category, how quickly DataFlow completes and whether you need an exam.</p>
<h2>Costs</h2>
<p>SCFHS fees, the DataFlow fee and any exam fee are set by them and paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>
<h2>Renewal</h2>
<p>Licenses are renewed through Mumaris Plus. $C the renewal period and requirements, such as continuing education hours.</p>",
        'info_three' => '<h2>How Alpha helps</h2>
<p>Our licensing team works through each step with you:</p>
<ul>
<li>Eligibility and classification review against current SCFHS requirements</li>
<li>Document preparation and checks</li>
<li>DataFlow verification, opened and followed up</li>
<li>Mumaris Plus application and tracking</li>
<li>Exam guidance for your category</li>
</ul>',
        'info_four' => '<p>Send us your qualification, years of experience and the role you are applying for in Saudi Arabia. We will tell you your likely classification, what you need and what to do next.</p>
<p>Also considering the UAE or Qatar? See <a href="/services/healthcare-professional-licensing-uae">UAE licensing with DHA, DOH and MOHAP</a> and <a href="/services/healthcare-professional-licensing-qatar">DHP licensing in Qatar</a>.</p>',
    ],
    'faqs' => [
        122 => ["Who needs SCFHS registration to work in Saudi Arabia?",
            "Every doctor, nurse, pharmacist, dentist and allied health professional must hold a valid SCFHS professional license before practising in any healthcare facility in Saudi Arabia, public or private. Registration, classification and renewal all run through Mumaris Plus."],
        123 => ["How long does SCFHS licensing take?",
            "$C a typical range, from real cases. The time depends on your classification category, how quickly DataFlow completes your verification and whether you need an exam. We give you an estimate after reviewing your profile and tell you which documents to prepare first."],
        124 => ["How much does it cost, and what is included in Alpha's fee?",
            "SCFHS fees, the DataFlow fee and any exam fee are set by them and paid separately. Alpha's fee covers the eligibility and classification review, documents, DataFlow follow-up, the Mumaris Plus application and exam guidance. Your first consultation is free; the licensing process itself is charged."],
        125 => ["Do I need to pass an exam, and which one?",
            "Most categories sit the SCFHS exam for their classification. $C which categories are exempt. We confirm what applies to your title in the eligibility review, before you book, and guide you on the exam and its booking as part of the SCFHS process."],
        126 => ["What documents do I need for DataFlow and SCFHS?",
            "Typically your academic transcripts and degree, professional certificates, passport, a good standing certificate and employer details. Requirements vary by professional category. DataFlow verifies your qualifications and experience directly with the issuing institutions, so names and dates must match across every document. We check them before you submit."],
        127 => ["Can I apply from outside Saudi Arabia?",
            "Yes. Mumaris Plus registration and DataFlow verification are online, so you can start from your home country while you look for a role. $C whether the exam can be taken outside Saudi Arabia, and when employer sponsorship is needed. We tell you which steps to finish before you travel."],
    ],
];

/* ── B4 Qatar (services id 65) ─────────────────────────────────────── */
$svc[65] = [
    'ready' => [
        'meta_title' => 'Qatar DHP Medical License Support | Alpha Health Group',
        'meta_description' => 'Qatar MOPH DHP licensing for doctors, nurses and allied staff: eligibility, DataFlow, exams and the application, managed with you. Book a consultation.',
        'areaServed' => 'Qatar',
    ],
    'confirm' => [
        'content' => '<p>We guide doctors, nurses, pharmacists and allied health staff through DHP licensing in Qatar, from eligibility to your license.</p>',
        'overview' => '<p>Alpha Health Group handles Qatar licensing for doctors, nurses, pharmacists, dentists and allied health professionals with the Department of Healthcare Professions (DHP) at the Ministry of Public Health (MOPH): eligibility, DataFlow verification, Prometric exam guidance and the application.</p>',
        'info_one' => '<h2>Who needs a DHP license</h2>
<p>Every doctor, nurse, pharmacist, dentist and allied health practitioner must hold a license from the Department of Healthcare Professions (DHP), part of the <a href="https://www.moph.gov.qa" target="_blank" rel="noopener">Ministry of Public Health (MOPH)</a>, before practising in Qatar.</p>',
        'info_two' => "<h2>Steps: eligibility, DataFlow, exam, evaluation, license</h2>
<ol>
<li><strong>Eligibility.</strong> Your profile is checked against the current DHP classification criteria for your title.</li>
<li><strong>Primary source verification.</strong> DataFlow verifies your degree, license and experience with the issuing institutions. Under DHP Circular 2025/24, DataFlow verification is required for all applicants from January 2026.</li>
<li><strong>Prometric exam.</strong> Most titles sit the DHP qualifying exam at a Prometric centre. DHP was previously known as QCHP, and older references still use that name. $C</li>
<li><strong>Evaluation.</strong> DHP evaluates your application and credentials.</li>
<li><strong>License and activation.</strong> The license is issued, and activation needs employer sponsorship and your presence in Qatar.</li>
</ol>
<h2>Timeline</h2>
<p>$C a typical range, from real cases. DataFlow verification and exam availability take most of the time.</p>
<h2>Costs</h2>
<p>DHP fees, the DataFlow fee and the exam fee are set by them and paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>
<h2>Why applications are delayed</h2>
<p>The most common causes are name mismatches across documents, an expired good standing certificate, a degree from an institution DHP does not recognise, and too little post-qualification clinical experience. We check for each of these before you apply.</p>",
        'info_three' => '<h2>How Alpha helps</h2>
<p>Our licensing team works through each step with you:</p>
<ul>
<li>Eligibility review against current DHP classification criteria</li>
<li>Document preparation to DHP standards</li>
<li>DataFlow verification, opened and followed up</li>
<li>Prometric exam guidance</li>
<li>Application and evaluation follow-up with DHP</li>
</ul>',
        'info_four' => '<p>Send us your qualification, years of experience and your title. We will tell you whether you are eligible, what you need and what to do next.</p>
<p>Your Qatar DataFlow report can be reused elsewhere. See <a href="/services/healthcare-professional-licensing-uae">UAE licensing</a> and <a href="/services/saudi-healthcare-professional-licensing">SCFHS licensing in Saudi Arabia</a>.</p>',
    ],
    'faqs' => [
        116 => ["Who needs a DHP license to work in Qatar?",
            "Every healthcare professional, including doctors, nurses, pharmacists, dentists and allied health practitioners, must hold a license from the Department of Healthcare Professions (DHP) at the Ministry of Public Health (MOPH) before practising in Qatar. The license is activated once a Qatar employer sponsors you."],
        117 => ["Is DataFlow verification required in 2026?",
            "Yes. DHP Circular 2025/24 made DataFlow primary source verification mandatory for all applicants from January 2026, with no exceptions. DataFlow checks your degree, license and experience directly with the institutions that issued them, and the report can later be transferred to other GCC regulators."],
        118 => ["How long does Qatar DHP licensing take?",
            "$C a typical range, from real cases. DataFlow verification and the next available exam date take most of the time, followed by the DHP evaluation. We give you an estimate after reviewing your profile, and you can complete DataFlow and the exam before you move."],
        119 => ["How much does it cost, and what is included in Alpha's fee?",
            "DHP fees, the DataFlow fee and the exam fee are set by them and paid separately. Alpha's fee covers the eligibility review, documents, DataFlow follow-up, exam guidance and the application. Your first consultation is free; the licensing process itself is charged."],
        120 => ["Can I apply from outside Qatar?",
            "Yes. You can complete DataFlow verification and the Prometric exam before you move. License activation needs employer sponsorship and your presence in Qatar. $C whether the exam can be taken outside Qatar. We tell you which steps to finish from home and what to bring when you arrive."],
        121 => ["Can I reuse my Qatar DataFlow report in another country?",
            "Yes. A DataFlow report obtained for DHP Qatar can be transferred to regulators in the UAE, Saudi Arabia, Bahrain, Oman and the UK. Your documents are not verified again from the start, and you avoid paying for a second full verification."],
    ],
];

/* ── B5 Oman (services id 64) ──────────────────────────────────────── */
$svc[64] = [
    'ready' => [
        'meta_title' => 'Oman OMSB and MOH License Support | Alpha Health Group',
        'meta_description' => 'Oman licensing for doctors, nurses and allied staff: OMSB and MOH requirements, DataFlow, exams and the application, managed with you.',
        'areaServed' => 'Oman',
    ],
    'confirm' => [
        'content' => '<p>We guide doctors, nurses, pharmacists and allied health staff through Oman licensing with OMSB and the Ministry of Health, from eligibility to your license.</p>',
        'overview' => '<p>Alpha Health Group handles Oman licensing for doctors, nurses, pharmacists, dentists and allied health professionals: eligibility against OMSB requirements, document attestation, DataFlow verification, Prometric exam guidance and MOH registration.</p>',
        'info_one' => '<h2>Who needs an Oman healthcare license</h2>
<p>Every healthcare professional, including doctors, nurses, pharmacists, dentists and allied health technicians, needs a license approved by the <a href="https://www.omsb.gov.om" target="_blank" rel="noopener">Oman Medical Specialty Board (OMSB)</a> and the Ministry of Health (MOH) to practise in any public or private facility in Oman.</p>',
        'info_two' => "<h2>Steps: eligibility, DataFlow, exam, MOH registration</h2>
<ol>
<li><strong>Eligibility.</strong> Your qualification and experience are assessed against the OMSB Professional Qualification Requirements (PQR) for your title.</li>
<li><strong>Document attestation.</strong> Certificates are attested before submission.</li>
<li><strong>DataFlow verification.</strong> DataFlow verifies your degree, license and experience with the issuing institutions.</li>
<li><strong>Prometric exam.</strong> OMSB requires the Prometric exam for most categories, with limited exemptions for experienced specialists who meet OMSB criteria.</li>
<li><strong>MOH registration.</strong> Your license is registered through the Hakeem portal.</li>
</ol>
<h2>Experience requirements</h2>
<p>General practitioners need five years of post-qualification clinical experience and specialists three years. Nurses with a bachelor degree need two years, and diploma-holding nurses three years. $C these figures are current.</p>
<h2>Timeline</h2>
<p>$C a typical range, from real cases. DataFlow verification, exam dates and how complete your documents are decide most of the time.</p>
<h2>Costs</h2>
<p>MOH and OMSB fees, the DataFlow fee and the exam fee are set by them and paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>
<h2>The 2025 Public Health Law</h2>
<p>Royal Decree No. 43/2025, the new Public Health Law, updates licensing and accreditation standards in Oman. $C what changes for individual professional licensing.</p>",
        'info_three' => '<h2>How Alpha helps</h2>
<p>Our licensing team works through each step with you:</p>
<ul>
<li>Eligibility review against the OMSB PQR for your title</li>
<li>Document preparation and attestation</li>
<li>DataFlow verification, opened and followed up</li>
<li>Prometric exam guidance</li>
<li>Application tracking through to MOH registration</li>
</ul>',
        'info_four' => '<p>Send us your qualification, years of experience and your title. We will assess your eligibility for Oman and tell you what you need and what to do next.</p>
<p>Also considering the UAE or Saudi Arabia? See <a href="/services/healthcare-professional-licensing-uae">UAE licensing</a> and <a href="/services/saudi-healthcare-professional-licensing">SCFHS licensing in Saudi Arabia</a>.</p>',
    ],
    'faqs' => [
        110 => ["How long does Oman healthcare licensing take?",
            "$C a typical range, from real cases. The time from DataFlow submission to MOH registration depends on your profession, DataFlow verification and exam dates. We give you an estimate after reviewing your profile and tell you which documents to prepare and attest first."],
        111 => ["Who needs an OMSB-approved license in Oman?",
            "Every healthcare professional, including doctors, nurses, pharmacists, dentists and allied health technicians, must hold an OMSB-approved license to practise in any public or private facility in Oman. Registration with the Ministry of Health follows through the Hakeem portal."],
        112 => ["How much does it cost, and what is included in Alpha's fee?",
            "MOH and OMSB fees, the DataFlow fee and the exam fee are set by them and paid separately. Alpha's fee covers the eligibility review, attestation and documents, DataFlow follow-up, exam guidance and the application. Your first consultation is free; the licensing process itself is charged."],
        113 => ["Do I need to pass the Prometric exam?",
            "For most categories, yes. OMSB requires the Prometric exam, with limited exemptions for highly experienced specialists who meet specific OMSB criteria. We confirm what applies to your title before you book, and guide you on preparation and booking."],
        114 => ["What experience do I need for an Oman medical license?",
            "General practitioners need five years of post-qualification clinical experience and specialists three years. Nurses with a bachelor degree need two years, and diploma-holding nurses three years. $C these figures are current. We check your experience certificates against these requirements in the eligibility review."],
        115 => ["Can I apply from outside Oman?",
            "Yes. Eligibility assessment, attestation planning and DataFlow verification can start from your home country, and the Prometric exam is booked online. $C whether the exam can be taken outside Oman, and when the employer must be in place."],
    ],
];

/* ── B7 blog 38 + page_settings descriptions (ready) ───────────────── */
$blog38 = [
    'meta_title' => 'How to Get a Healthcare License in the UAE (2026 Guide)',
    // author_name stays empty until Saroth names a real author or reviewer.
];

$pageSettings = [
    1  => 'UAE healthcare consultancy: facility setup and licensing, professional licensing, accreditation, quality and management support for hospitals and clinics.',
    4  => 'Healthcare facilities, hospitals and medical organisations across the UAE that work with Alpha Health Group on licensing, accreditation and quality.',
    2  => 'A UAE healthcare consultancy helping hospitals, clinics and healthcare professionals with licensing, accreditation, quality and operations.',
    5  => 'Healthcare management updates and guides from Alpha Health Group on licensing, regulation, quality and operations for providers in the UAE and GCC.',
    7  => 'Contact Alpha Health Group about healthcare licensing, accreditation and consultancy for facilities and professionals in the UAE and GCC.',
    8  => 'Browse Alpha Health Group healthcare consultancy services: DOH licensing, accreditation, quality assurance, infection control, patient safety and more.',
    10 => 'Services by facility type: hospitals, medical centers, day surgery, pharmacies, diagnostic labs, rehabilitation, home healthcare and telehealth in the UAE.',
];
$aboutHeroDescription = 'Alpha Health Group provides consulting, regulatory, operational and engineering services that help healthcare organisations build, operate and grow with confidence.';

/* Saroth 19 Oct: author for every blog without one; every blog mapped to the service
   category whose pages should show it as the related article (one category per blog). */
$blogAuthor = 'Saroth Nakshabi';
$blogCategories = [
    1  => [8, 9, 17, 18, 19, 48],                         // Facility Licensing & Setup
    2  => [69, 70, 72],                                   // Healthcare Professional Resourcing
    3  => [10, 60, 62, 63, 76],                           // Healthcare Design & Build
    5  => [2, 13, 24, 34, 55, 56],                        // Healthcare Management Outsourcing
    6  => [14, 16, 25, 49],                               // Healthcare Feasibility Evaluation
    7  => [57],                                           // Training & Education
    8  => [27, 40, 41, 42, 43, 46, 78, 79, 80, 81],       // Pharmaceutical & Product Services
    9  => [12, 26],                                       // Engineering & Design Approvals
    17 => [37],                                           // Medical Centers & Polyclinics (specialty expansion)
    20 => [44],                                           // Drugstores
    24 => [39, 47, 52],                                   // Telehealth
    25 => [11, 35, 50],                                   // Regulatory & Quality
    40 => [32],                                           // Hospital Design & Master Planning
    41 => [6, 7, 15, 20, 29, 30, 54],                     // Audit Consultation
    42 => [36, 51, 53],                                   // Healthcare Information Security Consultation
    44 => [21, 22, 23, 38, 67, 68, 71],                   // Healthcare Professional Licensing
    45 => [28, 45, 61, 73, 74],                           // Bio Medical Engineering
    46 => [3, 4, 58],                                     // HIE Integration
    47 => [31, 33, 64, 65],                               // Insurance Empanelment
    48 => [1, 5, 59, 66, 75, 77],                         // Healthcare Accreditations & Certifications
];

return compact('svc', 'blog38', 'pageSettings', 'aboutHeroDescription', 'blogAuthor', 'blogCategories');
