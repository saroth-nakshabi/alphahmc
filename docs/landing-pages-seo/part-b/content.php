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
// Facts: Saroth 19 Oct (steps, Pearson VUE for DOH, 6 to 8 weeks, exams abroad, eligibility
// about 1 year); Unified PQR v3 April 2025 (exemptions, PSV, good standing 6 months);
// DHA licensing manual v1.3 (Sheryan, registration valid 1 year); MOHAP service pages;
// TAMM and Pearson VUE DOH pages; Sharjah Executive Council decision July 2025 (SHA).
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
        'overview' => '<p>Alpha Health Group handles healthcare professional licensing with DHA, DOH and MOHAP: credential evaluation, DataFlow primary source verification, exam guidance, the application and license transfers, for doctors, nurses, pharmacists, dentists and allied health professionals.</p>',
        'info_one' => '<h2>Who needs a UAE healthcare license</h2>
<p>Every doctor, nurse, pharmacist, dentist and allied health professional needs a license from the health authority of the emirate where they will work: the <a href="https://www.dha.gov.ae" target="_blank" rel="noopener">Dubai Health Authority (DHA)</a> for Dubai, the <a href="https://www.doh.gov.ae" target="_blank" rel="noopener">Department of Health (DOH)</a> for Abu Dhabi, and the <a href="https://mohap.gov.ae/en" target="_blank" rel="noopener">Ministry of Health and Prevention (MOHAP)</a> for the Northern Emirates. Sharjah has its own regulator, the Sharjah Health Authority (SHA).</p>',
        'info_two' => '<h2>How DHA licensing works</h2>
<p>DHA licensing runs through Sheryan, the DHA licensing system, in six steps.</p>
<ol>
<li><strong>Registration.</strong> You open your application in Sheryan.</li>
<li><strong>Credential evaluation.</strong> DHA evaluates your qualifications and experience against the Unified Healthcare Professional Qualification Requirements (PQR) for your title.</li>
<li><strong>DataFlow verification.</strong> DataFlow verifies your education, experience, professional licenses and good standing certificate directly with the institutions that issued them. This is primary source verification.</li>
<li><strong>Exam or exemption.</strong> Most titles sit the DHA licensing exam at a Prometric centre. The PQR exempts some applicants, for example holders of listed qualifications or registrations from countries such as the UK and the USA.</li>
<li><strong>Eligibility.</strong> DHA issues your registration, often called the eligibility letter. It is valid for one year.</li>
<li><strong>Activation by your employer.</strong> Your license becomes active when a DHA-licensed facility employs you and <a href="/services/activate-your-uae-healthcare-license">activates it</a>.</li>
</ol>
<h2>How DOH and MOHAP licensing differ</h2>
<p><strong>MOHAP</strong> follows the same steps as DHA. You apply on the MOHAP website or app for the Evaluation of Health Professional service, sit the exam at a Prometric centre, and once you are hired, your employer applies for your license.</p>
<p><strong>DOH (Abu Dhabi)</strong> follows the same steps as well, but you apply through TAMM and sit the DOH licensing exam with Pearson VUE, booked from your TAMM account.</p>
<h2>Applying from India and other countries</h2>
<p>You can complete most of the process before you move. Registration, credential evaluation and DataFlow verification are done online, and Prometric and Pearson VUE run exam centres in many countries, so you can usually sit the exam close to home. Your good standing certificate must be no older than six months when you apply.</p>
<p>We work with you on WhatsApp and online calls, tell you which documents to collect, and check them before anything is submitted. The license is activated once a UAE facility employs you.</p>
<h2>Timeline</h2>
<p>Most applications with DHA, DOH or MOHAP take 6 to 8 weeks. The time depends mainly on DataFlow verification, the next available exam date and how complete your documents are.</p>
<h2>Costs</h2>
<p>You pay the health authority\'s application and licensing fees, the DataFlow verification fee and, where it applies, the exam fee. These are set by the authorities and DataFlow and are paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>',
        'info_three' => '<h2>How Alpha Health Group helps</h2>
<p>Our licensing team handles the work around your application:</p>
<ul>
<li>Checks your eligibility against the PQR for your title and authority</li>
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
            "Most applications take 6 to 8 weeks with DHA, DOH or MOHAP. The time depends mainly on DataFlow verification, the next available exam date and how complete your documents are. We give you an estimate for your title after the eligibility review."],
        135 => ["How much does it cost, and what is included in Alpha's fee?",
            "The authority's licensing fees, the DataFlow fee and any exam fee are set by them and paid separately. Alpha's fee covers the eligibility review, document preparation, DataFlow follow-up, exam guidance and the application. Your first consultation is free; the licensing process itself is charged."],
        136 => ["Do I need to pass an exam, and which one?",
            "Most titles must pass the authority's licensing exam: Prometric for DHA and MOHAP, Pearson VUE for DOH. The PQR exempts some applicants, including holders of listed international qualifications or registrations, such as UK and US boards, PLAB, USMLE or NCLEX. We check your exemption in the eligibility review."],
        137 => ["What documents do I need for DataFlow?",
            "DataFlow verifies your educational qualifications, experience certificates, professional licenses and good standing certificate with the institutions that issued them. Your passport is uploaded to the authority, not verified. Names and dates must match across documents, and the good standing certificate must be no older than six months."],
        138 => ["Can I apply from outside the UAE?",
            "Yes. Registration, credential evaluation and DataFlow verification are done online, and Prometric and Pearson VUE run exam centres in many countries, so you can usually sit the exam close to home. Your license is activated once a UAE healthcare facility employs you."],
        139 => ["What happens after I pass the exam?",
            "The authority issues your eligibility. With DHA it is a registration, often called the eligibility letter, valid for one year, and validity at the other authorities is usually similar. Your license is then activated when a licensed facility in that emirate employs you."],
    ],
];

/* ── B2 License transfer (services id 112) ─────────────────────────── */
// Facts: Saroth 19 Oct (apply, DataFlow additional verification, approval; 4 to 6 weeks;
// residency documents for DOH specialist titles); Unified PQR v3 chapter 3 (exam exemption
// with valid license, good standing and full PSV report; same title); DataFlow Report
// Transfer page (5 to 7 days); DHA manual 6.3 (accepts PSV reports from DOH, SHA, MOHAP).
$svc[112] = [
    'ready' => [
        'meta_title' => 'Move Your License Between DHA, DOH and MOHAP | Alpha Health',
        'areaServed' => 'Abu Dhabi, Dubai, Sharjah',
        'meta_description' => "Moving between Dubai, Abu Dhabi and the other emirates? We handle the new authority's license, DataFlow reuse and good standing. Book a consultation.",
    ],
    'confirm' => [
        'content' => '<p>There is no automatic transfer between DHA, DOH and MOHAP. You apply to the new authority for a transfer while your current license is valid, DataFlow carries out an additional verification on your existing report, and the authority approves your license. Most transfers take 4 to 6 weeks.</p>',
        'overview' => '<p>Offered a role in another emirate? We move your DHA, DOH, MOHAP or SHA professional license to the right authority, transfer your DataFlow report and request exam exemption, so you do not repeat the full licensing process.</p>',
        'info_one' => '<h2>One license, one authority</h2>
<p>A UAE healthcare license belongs to the authority that issued it. A DHA license does not let you practise in Abu Dhabi, and a DOH license does not cover Sharjah or Ajman. When you change emirate, you apply to the new authority. Getting your first UAE license instead? See <a href="/services/healthcare-professional-license-registration">new healthcare professional licenses</a>.</p>',
        'info_two' => '<h2>DHA to DOH</h2>
<p>Apply to DOH for a transfer through TAMM while your DHA license is valid. Your DataFlow report is transferred to DOH, which may ask for additional verification. With a valid license, a valid good standing certificate and a full DataFlow report, the PQR makes you eligible for exam exemption for the same title. DOH specialist titles also need your residency programme documents. Your Abu Dhabi employer then activates the license.</p>
<h2>DOH to DHA</h2>
<p>The same steps in the other direction: apply to DHA through Sheryan, transfer your DataFlow report to DHA and request exam exemption for the same title. DHA accepts DataFlow reports transferred from DOH, MOHAP and SHA, and may ask for further verification. Your Dubai facility then activates the license.</p>
<h2>MOHAP and Sharjah</h2>
<p>For the Northern Emirates, you apply on the MOHAP website or app for the Evaluation of Health Professional service, and your new employer then applies for your license. Sharjah has its own regulator, the Sharjah Health Authority (SHA), with its own license transfer service. The pattern is the same: a new application, a transferred DataFlow report and exam exemption where your current license qualifies.</p>
<h2>Documents you need</h2>
<ul>
<li>Your current, valid UAE license</li>
<li>A good standing certificate from your current authority, no older than six months</li>
<li>Your existing DataFlow report</li>
<li>An offer or employment from a facility in the new emirate</li>
<li>For DOH specialist titles, your residency programme documents</li>
</ul>
<h2>Timeline</h2>
<p>Most transfers take 4 to 6 weeks. The DataFlow report transfer itself usually takes 5 to 7 days when no new documents are added. Your new employer then activates the license.</p>',
        'info_three' => '<h2>How Alpha helps</h2>
<p>Send us your current license and the emirate you are moving to. We confirm your exam exemption and DataFlow route in one assessment, then handle the move:</p>
<ul>
<li>Good standing certificate from your current authority</li>
<li>Application on the right channel: Sheryan, TAMM, the MOHAP website or app, or SHA</li>
<li>DataFlow report transferred rather than verified from the start</li>
<li>Exam exemption requested on the basis of your current license</li>
<li>Coordination with your new facility for <a href="/services/activate-your-uae-healthcare-license">license activation</a></li>
</ul>',
        'info_four' => '<p>Send us your current license and the emirate you are moving to, and we will confirm the route before you sign the new offer.</p>',
    ],
    'faqs' => [
        409 => ["Is there an automatic transfer between DHA and DOH?",
            "No. You apply to the new authority for a transfer while your current license is valid. DataFlow transfers your existing report and may carry out an additional verification, and the new authority approves the license. With a valid license, good standing certificate and full DataFlow report, you are eligible for exam exemption."],
        410 => ["Do I need to sit the licensing exam again?",
            "Usually not. Under the Unified PQR, a professional with a valid license from any UAE authority, a valid good standing certificate and a full DataFlow report is eligible for exam exemption for the same title, provided the exam attempts have not been used up. A higher title needs the full requirements."],
        411 => ["Can I reuse my DataFlow report?",
            "Yes. DataFlow calls this a Report Transfer. You sign a new letter of authorisation, link your existing report to the new authority and pay the transfer fee. It usually takes 5 to 7 days when no new documents are added, though the authority may ask for additional verification."],
        412 => ["How long does a license transfer take?",
            "Most transfers take 4 to 6 weeks. The DataFlow report transfer usually takes 5 to 7 days, then the new authority reviews and approves the transfer, and your new employer activates the license. Applying while your current license is still valid avoids a gap between the two."],
        413 => ["How much does a transfer cost, and what is included in Alpha's fee?",
            "The receiving authority's fees and the DataFlow transfer fee are set by them and paid separately. Alpha's fee covers the assessment, good standing certificate, application, DataFlow transfer, exemption request and activation follow-up. Your first consultation is free; the transfer itself is charged."],
        414 => ["Can I hold DHA, DOH and MOHAP licenses at the same time?",
            "Yes. You can hold licenses from more than one UAE authority, and each must be activated by a licensed facility under that authority. A suspension or revocation by one authority also applies with the other authorities where you hold a license."],
    ],
];

/* ── B3 Saudi (services id 66) ─────────────────────────────────────── */
// Facts: scfhs.org.sa Mumaris FAQ, professional classification and re-registration
// requirement pages, SMLE applicant guide 2024; DataFlow SCFHS FAQ; Pearson VUE SCFHS page.
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
<p>Every doctor, nurse, pharmacist, dentist and allied health professional must hold a valid professional registration from the <a href="https://www.scfhs.org.sa" target="_blank" rel="noopener">Saudi Commission for Health Specialties (SCFHS)</a> before practising in any healthcare facility in Saudi Arabia.</p>',
        'info_two' => '<h2>Steps: Mumaris Plus, DataFlow, classification, exam, registration</h2>
<ol>
<li><strong>Mumaris Plus.</strong> Classification, registration and renewal all run through Mumaris Plus, the SCFHS platform. You can apply for classification from outside Saudi Arabia, without a work permit.</li>
<li><strong>DataFlow verification.</strong> DataFlow verifies foreign qualifications, licenses and experience letters with the institutions that issued them. Qualifications issued in Saudi Arabia do not need it.</li>
<li><strong>Professional classification.</strong> SCFHS classifies you by title and level based on your qualification and country of training. Some classifications are decided directly; others need a written exam or a practical evaluation.</li>
<li><strong>Exam.</strong> Where an exam applies, you sit it at a Prometric or Pearson VUE centre, in Saudi Arabia or abroad.</li>
<li><strong>Registration.</strong> With lawful residence and a letter from your Saudi employer, SCFHS registers you and issues your professional license.</li>
</ol>
<h2>Timeline</h2>
<p>SCFHS publishes its own service times: about 7 working days for a classification decided directly, 30 working days for one that needs a written exam, and 5 working days for registration. DataFlow verification usually takes 15 to 25 working days, plus the time to book and sit the exam.</p>
<h2>Costs</h2>
<p>SCFHS fees, the DataFlow fee and any exam fee are set by them and paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>
<h2>Renewal</h2>
<p>Professional registration lasts two years and is renewed through Mumaris Plus, from three months before it expires. Each renewal needs continuing professional development hours: 60 for physicians, 40 for pharmacists and specialists, and 20 for technicians.</p>',
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
<p>Also considering Qatar? See <a href="/services/healthcare-professional-licensing-qatar">DHP licensing in Qatar</a>.</p>',
    ],
    'faqs' => [
        122 => ["Who needs SCFHS registration to work in Saudi Arabia?",
            "Every doctor, nurse, pharmacist, dentist and allied health professional must hold a valid SCFHS professional registration before practising in any healthcare facility in Saudi Arabia, public or private. Classification, registration and renewal all run through Mumaris Plus, the SCFHS platform."],
        123 => ["How long does SCFHS licensing take?",
            "SCFHS publishes its service times: about 7 working days for a classification decided directly, 30 working days for one that needs a written exam, and 5 working days for registration. DataFlow verification usually takes 15 to 25 working days. We give you an estimate after reviewing your profile."],
        124 => ["How much does it cost, and what is included in Alpha's fee?",
            "SCFHS fees, the DataFlow fee and any exam fee are set by them and paid separately. Alpha's fee covers the eligibility and classification review, documents, DataFlow follow-up, the Mumaris Plus application and exam guidance. Your first consultation is free; the licensing process itself is charged."],
        125 => ["Do I need to pass an exam, and which one?",
            "It depends on how SCFHS classifies your qualification and country of training. Some classifications are decided directly; others need a written exam, delivered by Prometric or Pearson VUE, or a practical evaluation. Anyone with a break in practice of two years or more must sit the exam."],
        126 => ["What documents do I need for DataFlow and SCFHS?",
            "Typically your degree and transcripts, professional licenses, experience letters, passport, a good standing certificate and, at registration, a letter from your Saudi employer. DataFlow verifies foreign qualifications, licenses and experience letters with the issuing institutions; qualifications issued in Saudi Arabia do not need it."],
        127 => ["Can I apply from outside Saudi Arabia?",
            "Yes. Classification through Mumaris Plus can start from outside Saudi Arabia without a work permit, and SCFHS-approved exam centres operate internationally. Registration needs lawful residence in Saudi Arabia and a letter from your Saudi employer issued within the last three months."],
    ],
];

/* ── B4 Qatar (services id 65) ─────────────────────────────────────── */
// Facts: dhp.moph.gov.qa About Us (QCHP replaced by DHP, Emiri Decree 14 of 2019) and
// Primary Source Verification page (mandatory, DataFlow / Quadrabay / AVS); Circular
// DHP/2024/16 (service targets); Prometric DHP pages; DataFlow Report Transfer page.
$svc[65] = [
    'ready' => [
        'meta_title' => 'Qatar DHP Medical License Support | Alpha Health Group',
        'meta_description' => 'Qatar MOPH DHP licensing for doctors, nurses and allied staff: eligibility, DataFlow, exams and the application, managed with you. Book a consultation.',
        'areaServed' => 'Qatar',
    ],
    'confirm' => [
        'content' => '<p>We guide doctors, nurses, pharmacists and allied health staff through DHP licensing in Qatar, from eligibility to your license.</p>',
        'overview' => '<p>Alpha Health Group handles Qatar licensing for doctors, nurses, pharmacists, dentists and allied health professionals with the Department of Healthcare Professions (DHP) at the Ministry of Public Health (MOPH): eligibility, primary source verification, Prometric exam guidance and the application.</p>',
        'info_one' => '<h2>Who needs a DHP license</h2>
<p>Every doctor, nurse, pharmacist, dentist and allied health practitioner must hold a license from the Department of Healthcare Professions (DHP), part of the <a href="https://www.moph.gov.qa" target="_blank" rel="noopener">Ministry of Public Health (MOPH)</a>, before practising in Qatar. DHP replaced the Qatar Council for Healthcare Practitioners (QCHP) in 2019, so you may still see the old name.</p>',
        'info_two' => '<h2>Steps: eligibility, verification, exam, evaluation, license</h2>
<ol>
<li><strong>Eligibility.</strong> Your qualification and experience are checked against the DHP requirements for your title.</li>
<li><strong>Primary source verification.</strong> Verification is mandatory for all practitioners and is done through a DHP-approved provider: DataFlow, Quadrabay or AVS.</li>
<li><strong>Exam.</strong> Most titles sit the DHP qualifying exam, a computer-based test delivered at Prometric centres in Qatar and abroad.</li>
<li><strong>Evaluation.</strong> DHP evaluates your application and credentials.</li>
<li><strong>License.</strong> DHP issues your license, and activation is completed with your Qatar employer.</li>
</ol>
<h2>Timeline</h2>
<p>DHP sets its own service targets, counted from a complete application with verified documents: 25 working days for evaluation and 15 for licensing for physicians and dentists, and 15 and 10 working days for nursing, allied health and pharmacy. Verification and the exam come on top.</p>
<h2>Costs</h2>
<p>DHP fees, the verification fee and the exam fee are set by them and paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>
<h2>Why applications are delayed</h2>
<p>The most common causes are name mismatches across documents, an expired good standing certificate, a degree from an institution DHP does not recognise, and too little post-qualification clinical experience. We check for each of these before you apply.</p>',
        'info_three' => '<h2>How Alpha helps</h2>
<p>Our licensing team works through each step with you:</p>
<ul>
<li>Eligibility review against current DHP requirements</li>
<li>Document preparation to DHP standards</li>
<li>Primary source verification, opened and followed up</li>
<li>Prometric exam guidance</li>
<li>Application and evaluation follow-up with DHP</li>
</ul>',
        'info_four' => '<p>Send us your qualification, years of experience and your title. We will tell you whether you are eligible, what you need and what to do next.</p>
<p>Your Qatar DataFlow report can be reused elsewhere in the GCC. See <a href="/services/saudi-healthcare-professional-licensing">SCFHS licensing in Saudi Arabia</a>.</p>',
    ],
    'faqs' => [
        116 => ["Who needs a DHP license to work in Qatar?",
            "Every healthcare professional, including doctors, nurses, pharmacists, dentists and allied health practitioners, must hold a license from the Department of Healthcare Professions (DHP) at the Ministry of Public Health (MOPH) before practising in Qatar. DHP replaced the former QCHP in 2019."],
        117 => ["Is primary source verification required?",
            "Yes. Primary source verification is mandatory for all practitioners and is done through a DHP-approved provider: DataFlow, Quadrabay or AVS. The provider checks your degree, license and experience directly with the institutions that issued them before DHP evaluates your application."],
        118 => ["How long does Qatar DHP licensing take?",
            "DHP's service targets, counted from a complete application with verified documents, are 25 working days for evaluation and 15 for licensing for physicians and dentists, and 15 and 10 for nursing, allied health and pharmacy. Verification and the exam come on top."],
        119 => ["How much does it cost, and what is included in Alpha's fee?",
            "DHP fees, the verification fee and the exam fee are set by them and paid separately. Alpha's fee covers the eligibility review, documents, verification follow-up, exam guidance and the application. Your first consultation is free; the licensing process itself is charged."],
        120 => ["Can I apply from outside Qatar?",
            "Yes. Primary source verification is done online, and Prometric delivers DHP exams at test centres in many countries, so you can complete both before you move. License issue and activation are then completed with your Qatar employer."],
        121 => ["Can I reuse my Qatar DataFlow report in another country?",
            "Yes, within the GCC. DataFlow's Report Transfer service can move a report made for DHP to DHA, DOH, SCFHS, OMSB and Bahrain's NHRA, with a new letter of authorisation and a fee. It usually takes 5 to 7 days, and the receiving regulator may ask for extra checks."],
    ],
];

/* ── B5 Oman (services id 64) ──────────────────────────────────────── */
// Facts: MOH private-sector licensing SOPs (doctors 3rd edition Nov 2025, nursing Dec 2021);
// MOH Health Practitioners Registration e-Service; OMSB Hakeem tutorial; Pearson VUE OMSB
// page; decree.om Royal Decree 43/2025.
$svc[64] = [
    'ready' => [
        'meta_title' => 'Oman OMSB and MOH License Support | Alpha Health Group',
        'meta_description' => 'Oman licensing for doctors, nurses and allied staff: OMSB and MOH requirements, DataFlow, exams and the application, managed with you.',
        'areaServed' => 'Oman',
    ],
    'confirm' => [
        'content' => '<p>We guide doctors, nurses, pharmacists and allied health staff through Oman licensing with OMSB and the Ministry of Health, from eligibility to your license.</p>',
        'overview' => '<p>Alpha Health Group handles Oman licensing for doctors, nurses, pharmacists, dentists and allied health professionals: OMSB registration and eligibility, DataFlow verification, Pearson VUE exam guidance and the Ministry of Health license.</p>',
        'info_one' => '<h2>Who needs an Oman healthcare license</h2>
<p>Every healthcare professional, including doctors, nurses, pharmacists, dentists and allied health technicians, needs a license approved by the <a href="https://www.omsb.gov.om" target="_blank" rel="noopener">Oman Medical Specialty Board (OMSB)</a> and the Ministry of Health (MOH) to practise in any public or private facility in Oman.</p>',
        'info_two' => '<h2>Steps: eligibility, verification, exam, MOH license</h2>
<ol>
<li><strong>OMSB registration and eligibility.</strong> You register on Hakeem, the OMSB portal, and your qualification and experience are assessed for your title. In the private sector, a letter from your sponsor is part of this first application.</li>
<li><strong>DataFlow verification.</strong> DataFlow verifies your degree, license and experience with the institutions that issued them.</li>
<li><strong>Exam.</strong> OMSB exams are delivered by Pearson VUE, at its centres worldwide or at the OMSB National Test Center in Muscat. Doctors also sit an oral exam.</li>
<li><strong>Employer approvals.</strong> After you pass, your employer obtains the Ministry of Labour approval.</li>
<li><strong>MOH license.</strong> The license is issued through the Ministry of Health Health Practitioners Registration e-Service.</li>
</ol>
<h2>Experience requirements</h2>
<p>For the private sector, MOH asks general practitioners for four years of work experience and specialists (level 3) for three years after the specialty. Nurses with a bachelor degree need at least three years, and diploma-holding nurses at least four. Government-sector rules may differ.</p>
<h2>Timeline</h2>
<p>MOH processes a complete registration application in about 5 business days, and DataFlow verification for OMSB takes up to 25 working days. The exam, the oral exam for doctors and your employer\'s labour approval come on top.</p>
<h2>Costs</h2>
<p>MOH and OMSB fees, the DataFlow fee and the exam fee are set by them and paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>
<h2>The 2025 Public Health Law</h2>
<p>Royal Decree No. 43/2025, issued in April 2025, enacts Oman\'s new Public Health Law. It covers facility licensing and accreditation, data confidentiality, pharmaceuticals and penalties. Individual professional licensing still follows MOH and OMSB rules while the implementing regulations are prepared.</p>',
        'info_three' => '<h2>How Alpha helps</h2>
<p>Our licensing team works through each step with you:</p>
<ul>
<li>Hakeem registration and eligibility review for your title</li>
<li>Document preparation and checks</li>
<li>DataFlow verification, opened and followed up</li>
<li>Pearson VUE exam guidance</li>
<li>Application tracking through to the MOH license</li>
</ul>',
        'info_four' => '<p>Send us your qualification, years of experience and your title. We will assess your eligibility for Oman and tell you what you need and what to do next.</p>
<p>Also considering Saudi Arabia? See <a href="/services/saudi-healthcare-professional-licensing">SCFHS licensing in Saudi Arabia</a>.</p>',
    ],
    'faqs' => [
        110 => ["How long does Oman healthcare licensing take?",
            "MOH processes a complete registration application in about 5 business days, and DataFlow verification for OMSB takes up to 25 working days. The Pearson VUE exam, the oral exam for doctors and your employer's labour approval come on top. We give you an estimate after reviewing your profile."],
        111 => ["Who needs an OMSB-approved license in Oman?",
            "Every healthcare professional, including doctors, nurses, pharmacists, dentists and allied health technicians, needs an OMSB-approved license to practise in any public or private facility in Oman. You register with OMSB on Hakeem, and the license is issued through the Ministry of Health registration e-service."],
        112 => ["How much does it cost, and what is included in Alpha's fee?",
            "MOH and OMSB fees, the DataFlow fee and the exam fee are set by them and paid separately. Alpha's fee covers the eligibility review, documents, DataFlow follow-up, exam guidance and the application. Your first consultation is free; the licensing process itself is charged."],
        113 => ["Do I need to pass an exam?",
            "For most categories, yes. OMSB exams are delivered by Pearson VUE, at its centres worldwide or at the OMSB National Test Center in Muscat, and doctors also sit an oral exam. We confirm what applies to your title, including any exemption, before you book."],
        114 => ["What experience do I need for an Oman license?",
            "For the private sector, MOH asks general practitioners for four years of work experience and specialists for three years after the specialty. Nurses with a bachelor degree need at least three years and diploma-holding nurses at least four. Government-sector rules may differ, so we check your certificates first."],
        115 => ["Can I apply from outside Oman?",
            "Yes, for the early steps. You register on Hakeem, complete DataFlow verification and can sit the Pearson VUE exam at a centre outside Oman. In the private sector, a letter from your sponsor is part of the first application, so your employer needs to be in place from the start."],
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

/* Insight slides (service_magazines.description, uncast) on the licensing pages: guarded
   sentence fixes applied with PART 2. [slide id, exact current text, new text] */
$magazineFixes = [
    [118, "identifying the fastest compliant pathway to practice.", "identifying the right authority and the quickest route to practice."],
    [357, "redirecting your DataFlow PSV so you begin sooner, not months from now.", "transferring your DataFlow report so you begin sooner, not months from now."],
    [359, "handling each portal and assessment so the switch stays compliant and on time.", "handling each portal and assessment so the switch stays on time."],
    [360, "map the fastest compliant route, so opportunity is never out of reach.", "map the quickest route, so opportunity is never out of reach."],
    [361, "We redirect your existing DataFlow PSV report and apply for exam exemption on your valid UAE license", "We transfer your existing DataFlow report and apply for exam exemption on your valid UAE license"],
    [101, "From Prometric exam scheduling to MOH Hakeem portal registration, we coordinate", "From Hakeem registration and Pearson VUE exam scheduling to the MOH license application, we coordinate"],
    [106, "The QCHP Prometric qualifying examination is a critical licensing milestone. We provide exam eligibility assessment, exemption evaluation for holders of recognised international board certifications (USMLE, PLAB, NCLEX-RN), scheduling support", "The DHP qualifying exam, delivered by Prometric, is a critical licensing milestone. We provide exam eligibility assessment, exemption evaluation where you hold a recognised international qualification, scheduling support"],
    [110, "DataFlow primary source verification is mandatory for all healthcare professionals in Saudi Arabia.", "DataFlow primary source verification is required for foreign qualifications, licenses and experience letters."],
];
return compact('svc', 'blog38', 'pageSettings', 'aboutHeroDescription', 'blogAuthor', 'blogCategories', 'magazineFixes');
