<?php
/**
 * Part E step 4: create or update the DHA service page
 * /services/dha-license-healthcare-professionals-dubai through the Service model (no raw SQL).
 * Safe to re-run: it updates the same service and replaces its FAQs.
 * Run licensing-group.php afterwards so the group page lists and links this service.
 *
 * Usage, from the app root:  php scripts/part-e/licensing-dha.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/part-e/licensing-dha.php
 *
 * Facts: the confirmed Part B set (Saroth 19 Oct; DHA licensing manual v1.3: Sheryan,
 * registration valid one year, accepts DataFlow reports transferred from DOH, MOHAP and SHA)
 * and the Unified PQR, April 2025: exam attempts and formats 1.5.2 to 1.5.5, good standing
 * 1.3.3, PSV 1.4, gap of practice 1.7, CME/CPD 2.1 and 2.2, recognition between authorities
 * chapter 3, no exemption after a two-year gap 21.4, minimum experience pp.18, 55, 71, 105.
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;

$dryRun = in_array('--dry-run', $argv, true);
$slug = 'dha-license-healthcare-professionals-dubai';
$categoryId = 44; // Healthcare Professional Licensing
$sourceServiceSlug = 'healthcare-professional-licensing-uae'; // agent, hero image
$relatedSlugs = [
    'healthcare-professional-license-registration',
    'healthcare-professional-license-transfer',
    'activate-your-uae-healthcare-license',
];
$groupUrl = '/service-packages/healthcare-professional-licensing-uae';

$fields = [
    'name' => 'DHA License for Healthcare Professionals in Dubai',
    'meta_title' => 'DHA License Consultants in Dubai | Alpha Health Group',
    'meta_description' => 'DHA licensing for doctors, nurses and allied staff: Sheryan, DataFlow, the Prometric exam and your eligibility letter. Book a free consultation.',
    'meta_keywords' => 'DHA license, DHA license for doctors, DHA license for nurses, DHA exam, Sheryan, DataFlow, Prometric, Dubai healthcare license',
    'areaServed' => 'Dubai',
    'serviceType' => 'Healthcare Professional Licensing',
    'content' => '<p>We take doctors, nurses, pharmacists, dentists and allied health staff through DHA licensing in Sheryan, from the eligibility check to your eligibility letter.</p>',
    'overview' => '<p>To practise in Dubai you need a license from the Dubai Health Authority (DHA). We check your eligibility against the PQR, prepare your documents, follow your DataFlow verification and exam, and submit your application in Sheryan.</p>',
    'info_one' => '<h2>Who needs a DHA license</h2>
<p>Every physician, dentist, nurse, midwife, pharmacist and allied health professional who will work at a facility licensed by the <a href="https://www.dha.gov.ae" target="_blank" rel="noopener">Dubai Health Authority</a>. A license from DOH or MOHAP does not cover Dubai.</p>
<p>Working in Abu Dhabi or the Northern Emirates instead? See <a href="' . $groupUrl . '">which UAE license you need</a>.</p>',
    'info_two' => '<h2>How DHA licensing works</h2>
<p>DHA licensing runs through Sheryan, the DHA licensing system, in six steps.</p>
<ol>
<li><strong>Registration.</strong> You open your application in Sheryan.</li>
<li><strong>Credential evaluation.</strong> DHA evaluates your qualifications and experience against the Unified Healthcare Professional Qualification Requirements (PQR) for your title.</li>
<li><strong>DataFlow verification.</strong> DataFlow verifies your documents directly with the institutions that issued them.</li>
<li><strong>Exam or exemption.</strong> Most titles sit the DHA licensing exam at a Prometric centre.</li>
<li><strong>Eligibility.</strong> DHA issues your registration, often called the eligibility letter. It is valid for one year.</li>
<li><strong>Activation.</strong> Your license becomes active when a DHA-licensed facility employs you and <a href="/services/activate-your-uae-healthcare-license">activates it</a>.</li>
</ol>
<h3>Experience you need</h3>
<p>The PQR sets the minimum for each title. For common titles:</p>
<ul>
<li><strong>General Practitioner:</strong> a one-year internship and two years of clinical experience</li>
<li><strong>General Dentist:</strong> an internship and two years of experience</li>
<li><strong>Registered Nurse:</strong> a bachelor\'s degree in nursing, with no minimum experience</li>
<li><strong>Pharmacist:</strong> two years after qualifying; experience with pharmaceutical companies or in sales does not count</li>
</ul>
<p>For Specialist and Consultant titles, the experience required depends on the PQR tier of the country where you trained. Most allied health titles need one to four years.</p>
<h3>The DHA exam</h3>
<p>Depending on your title, the assessment can be written, oral or an OSCE, and it is held in English. The PQR allows three attempts across the UAE health authorities. After three failed attempts, you have one final attempt with a different authority.</p>
<h3>Who is exempt from the exam</h3>
<p>The PQR exempts UAE nationals and holders of listed international qualifications or registrations, such as UK and US boards, PLAB, USMLE or NCLEX. If you already hold a valid DOH, MOHAP or SHA license, you can apply to DHA for the same title without the exam, with a good standing certificate and a full DataFlow report. Exemptions do not apply after more than two years out of practice.</p>
<h3>Documents and DataFlow</h3>
<p>DataFlow verifies your educational qualifications, experience certificates, professional licenses and good standing certificate. The good standing certificate comes from the regulator in the country where you last worked and must be no older than six months when you apply. Names and dates must match across all documents.</p>
<h3>Time out of practice</h3>
<p>A break of more than three years means completing CME credits and a period of training in an approved setting before you can be licensed, and you still sit the exam. Non-UAE nationals cannot apply after a break of more than five years.</p>
<h3>Timeline and costs</h3>
<p>Most DHA applications take 6 to 8 weeks. The time depends mainly on DataFlow verification, the next available exam date and how complete your documents are. You pay DHA\'s fees, the DataFlow fee and the exam fee separately. Your first consultation with Alpha Health Group is free; the licensing process itself is a paid service.</p>
<h3>Keeping your license</h3>
<p>Every year you complete continuing education in your specialty: 40 CME credits for physicians and dentists, 20 for nurses and pharmacists, and 10 for allied health professionals. Physicians and dentists in surgical specialties also submit a surgical logbook every two years for renewal.</p>',
    'info_three' => '<h2>How Alpha Health Group helps</h2>
<p>Our licensing team handles the work around your DHA application:</p>
<ul>
<li>Checks your eligibility against the PQR for your title and, for specialists, your country tier</li>
<li>Prepares your documents and checks them before submission</li>
<li>Opens and follows your DataFlow verification</li>
<li>Guides you on the exam and your Prometric booking</li>
<li>Submits your application in Sheryan and follows it up with DHA</li>
</ul>',
    'info_four' => '<p>Send us your title, qualification, years of experience and the country where you trained. We will confirm your DHA route, whether you are exempt from the exam, and what to do next.</p>
<p>Not sure Dubai is the right authority? <a href="' . $groupUrl . '">Compare DHA, DOH and MOHAP licensing</a>. Already licensed in another emirate? See <a href="/services/healthcare-professional-license-transfer">license transfers between authorities</a>.</p>',
    'status' => 'published',
    'featured' => 0,
    'show_testimonials' => 0,
];

$faqs = [
    ['How do I get a DHA license?', 'You apply in Sheryan, DHA\'s licensing system. DHA evaluates your credentials against the PQR, DataFlow verifies your documents, and you sit the exam at a Prometric centre unless you are exempt. DHA then issues your eligibility letter, and your license is activated when a Dubai facility employs you.'],
    ['How many times can I take the DHA exam?', 'The PQR allows three attempts across the UAE health authorities. After three failed attempts, you have one final attempt with a different authority. Depending on your title, the exam can be written, oral or an OSCE, and it is held in English.'],
    ['Am I exempt from the DHA exam?', 'You may be. The PQR exempts UAE nationals, holders of listed international qualifications or registrations such as UK and US boards, PLAB, USMLE or NCLEX, and professionals with a valid DOH, MOHAP or SHA license in the same title. Exemptions do not apply after more than two years out of practice.'],
    ['How long is the DHA eligibility letter valid?', 'DHA registration, often called the eligibility letter, is valid for one year from issue. Your license becomes active when a DHA-licensed facility in Dubai employs you and activates it.'],
    ['How long does DHA licensing take?', 'Most applications take 6 to 8 weeks. The time depends mainly on DataFlow verification, the next available exam date and how complete your documents are. We give you an estimate for your title after the eligibility review.'],
    ['Can I apply for a DHA license from outside the UAE?', 'Yes. Registration in Sheryan, credential evaluation and DataFlow verification are done online, and Prometric runs exam centres in many countries, so you can usually sit the exam close to home. Your license is activated once a Dubai facility employs you.'],
];

/* Copy rules, as in the Part B build: no en or em dashes, no "compliant", no compliance claims;
   FAQ answers of 60 words or fewer. */
$all = array_merge(array_values(array_filter($fields, 'is_string')), array_merge(...$faqs));
foreach ($all as $text) {
    if (preg_match('/[\x{2013}\x{2014}]/u', $text) || preg_match('/\bcompliant\b|(ensure[sd]?|full)\b[^.]{0,30}\bcompliance/i', $text)) {
        fwrite(STDERR, "Copy rule broken: " . mb_substr(strip_tags($text), 0, 80) . "\n");
        exit(1);
    }
}
foreach ($faqs as [$q, $a]) {
    if (str_word_count($a) > 60) {
        fwrite(STDERR, "FAQ answer over 60 words: {$q}\n");
        exit(1);
    }
}
if (mb_strlen($fields['meta_title']) > 60 || mb_strlen($fields['meta_description']) > 155) {
    fwrite(STDERR, "Title or description too long\n");
    exit(1);
}

$source = Service::where('slug', $sourceServiceSlug)->first();
$related = Service::whereIn('slug', $relatedSlugs)->pluck('id', 'slug');
if (count($related) !== count($relatedSlugs)) {
    fwrite(STDERR, "Missing related services: " . implode(', ', array_diff($relatedSlugs, $related->keys()->all())) . "\n");
    exit(1);
}
$fields['related_services'] = array_map('strval', array_values(array_map(fn ($s) => $related[$s], $relatedSlugs)));
if ($source) {
    $fields['agent_id'] = $source->agent_id;
    $fields['hero_image'] = $source->hero_image;
}
$fields['updated_date'] = now()->toDateString();

if ($dryRun) {
    echo "dry run: checks passed; would write service '{$slug}' with " . count($faqs) . " FAQs, related " . implode(',', $fields['related_services']) . ", category {$categoryId}\n";
    exit(0);
}

$service = Service::firstOrNew(['slug' => $slug]);
$created = !$service->exists;
if ($created) {
    $fields['published_date'] = now()->toDateString();
    // Listed right after the New Healthcare Professional License service.
    $fields['sort_order'] = (int) Service::where('slug', $relatedSlugs[0])->value('sort_order') + 1;
}
$service->fill($fields)->save();
$service->categories()->sync([$categoryId]);
$service->faq()->delete();
foreach ($faqs as [$question, $answer]) {
    $service->faq()->create(['faq_question' => $question, 'faq_answer' => '<p>' . $answer . '</p>']);
}

echo ($created ? 'created' : 'updated') . " service #{$service->id} '{$slug}': " . $service->faq()->count() . " FAQs, category {$categoryId}\n";
echo "URL: " . route('front.service', $slug) . "\n";
