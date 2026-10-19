<?php
/**
 * Part E step 7: create or update the DOH service page
 * /services/doh-license-healthcare-professionals-abu-dhabi through the Service model (no raw SQL).
 * Safe to re-run: it updates the same service and replaces its FAQs.
 * Run licensing-group.php afterwards so the group page lists and links this service.
 * Hold until the DHA ad test (Part E steps 5 and 6) is measured, unless Saroth says otherwise.
 *
 * Usage, from the app root:  php scripts/part-e/licensing-doh.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/part-e/licensing-doh.php
 *
 * Facts: the confirmed Part B set (Saroth 19 Oct: apply through TAMM, DOH exam with Pearson VUE
 * booked from TAMM, employer activation, 6 to 8 weeks, transfers 4 to 6 weeks, residency
 * programme documents for specialist transfers to DOH; TAMM and Pearson VUE DOH pages) and the
 * Unified PQR, April 2025: exam attempts and formats 1.5.2 to 1.5.5, good standing 1.3.3, PSV 1.4,
 * gap of practice 1.7, CME/CPD 2.1 and 2.2, recognition between authorities chapter 3, no
 * exemption after a two-year gap 21.4, minimum experience pp.18, 19, 55, 71, 105.
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;

$dryRun = in_array('--dry-run', $argv, true);
$slug = 'doh-license-healthcare-professionals-abu-dhabi';
$categoryId = 44; // Healthcare Professional Licensing
$sourceServiceSlug = 'healthcare-professional-licensing-uae'; // agent, hero image
$relatedSlugs = [
    'healthcare-professional-license-registration',
    'healthcare-professional-license-transfer',
    'activate-your-uae-healthcare-license',
];
$groupUrl = '/service-packages/healthcare-professional-licensing-uae';

$fields = [
    'name' => 'DOH License for Healthcare Professionals in Abu Dhabi',
    'meta_title' => 'DOH License Consultants in Abu Dhabi | Alpha Health Group',
    'meta_description' => 'DOH licensing for doctors, nurses and allied staff in Abu Dhabi: TAMM, DataFlow, the Pearson VUE exam and activation. Book a free consultation.',
    'meta_keywords' => 'DOH license, DOH license Abu Dhabi, DOH exam, Pearson VUE DOH, TAMM healthcare license, DataFlow, Abu Dhabi healthcare license',
    'areaServed' => 'Abu Dhabi',
    'serviceType' => 'Healthcare Professional Licensing',
    'content' => '<p>We guide doctors, nurses, pharmacists, dentists and allied health staff through Department of Health licensing on TAMM, from the eligibility review to an active Abu Dhabi license.</p>',
    'overview' => '<p>Practising in Abu Dhabi, Al Ain or Al Dhafra takes a license from the Department of Health Abu Dhabi (DOH). We check your title against the PQR, prepare your documents for DataFlow, guide you through the Pearson VUE exam and file your application on TAMM.</p>',
    'info_one' => '<h2>Who needs a DOH license</h2>
<p>Physicians, dentists, nurses, midwives, pharmacists and allied health professionals who will practise anywhere in the Emirate of Abu Dhabi. The license is issued by the <a href="https://www.doh.gov.ae" target="_blank" rel="noopener">Department of Health Abu Dhabi</a>; a DHA or MOHAP license does not cover Abu Dhabi.</p>
<p>Not sure which authority applies to you? <a href="' . $groupUrl . '">Compare DHA, DOH and MOHAP</a>.</p>',
    'info_two' => '<h2>How DOH licensing works</h2>
<p>You apply for a DOH license through TAMM, the Abu Dhabi government services platform.</p>
<ol>
<li><strong>Apply on TAMM.</strong> You submit your application for your professional title.</li>
<li><strong>Assessment against the PQR.</strong> DOH checks your qualifications and experience against the Unified Healthcare Professional Qualification Requirements.</li>
<li><strong>Primary source verification.</strong> DataFlow confirms your degrees, experience, licenses and good standing with the institutions that issued them.</li>
<li><strong>Pearson VUE exam.</strong> If your title requires it, you book the DOH licensing exam with Pearson VUE from your TAMM account.</li>
<li><strong>Eligibility.</strong> DOH confirms you are eligible for the title.</li>
<li><strong>Activation.</strong> A DOH-licensed facility in Abu Dhabi employs you and <a href="/services/activate-your-uae-healthcare-license">activates your license</a>.</li>
</ol>
<h3>Minimum experience for common titles</h3>
<ul>
<li><strong>General Practitioner:</strong> the internship year, then two years in clinical practice</li>
<li><strong>General Dentist:</strong> the internship, then two years of practice</li>
<li><strong>Registered Nurse:</strong> a bachelor\'s degree in nursing; no minimum experience</li>
<li><strong>Pharmacist:</strong> two years after qualifying, not counting work for pharmaceutical companies or in sales</li>
</ul>
<p>Specialist and Consultant requirements depend on the PQR tier of the country where you trained. Physicians trained in Tier 3 countries cannot hold the Consultant title.</p>
<h3>About the exam</h3>
<p>Pearson VUE delivers the DOH exam at test centres in many countries, and you book it from your TAMM account. The format depends on your title and may be written, oral or an OSCE, in English. The PQR gives you three attempts in total across the UAE authorities, then one last attempt with another authority.</p>
<h3>Exam exemptions</h3>
<p>You may not need the exam if you are a UAE national, if you hold a qualification or registration on the PQR\'s lists, such as US or UK board certification, PLAB, USMLE or NCLEX, or if you already hold a valid DHA, MOHAP or SHA license for the same title with a good standing certificate and a full DataFlow report. No exemption applies after more than two years out of practice.</p>
<h3>Moving from DHA or MOHAP</h3>
<p>Apply to DOH for a transfer on TAMM while your current license is still valid. Your DataFlow report is transferred to DOH, which may ask for more verification, and specialist titles also need your residency programme documents. Your Abu Dhabi employer then activates the license. Most transfers take 4 to 6 weeks. See <a href="/services/healthcare-professional-license-transfer">license transfers between authorities</a>.</p>
<h3>Good standing and time out of practice</h3>
<p>Your good standing certificate must come from the regulator where you last practised and be no more than six months old when you apply. After more than three years out of practice, the PQR requires CME credits and a training period in an approved setting. Non-UAE nationals cannot apply after more than five years away.</p>
<h3>Timeline and costs</h3>
<p>Most DOH applications take 6 to 8 weeks, depending on DataFlow, exam availability and your documents. DOH, DataFlow and Pearson VUE fees are paid to them separately. Your first consultation with us is free; our licensing service is paid.</p>
<h3>Continuing education</h3>
<p>Licensed professionals need CME credits in their specialty each year: 40 for physicians and dentists, 20 for nurses and pharmacists, and 10 for allied health. Surgical specialists also submit a logbook every two years for renewal.</p>',
    'info_three' => '<h2>How Alpha Health Group helps</h2>
<p>Our licensing team takes care of the work around your DOH application:</p>
<ul>
<li>Checks your title against the PQR and, for specialists, your country tier</li>
<li>Prepares and checks your documents before DataFlow and TAMM</li>
<li>Opens and follows your DataFlow verification</li>
<li>Guides you on the Pearson VUE exam and booking</li>
<li>Files your application on TAMM and follows it with DOH</li>
</ul>',
    'info_four' => '<p>Send us your title, qualification, years of experience and the country where you trained. We will confirm your DOH route, whether you are exempt from the exam, and your next step.</p>
<p>Licensed in Dubai or the Northern Emirates already? See <a href="/services/healthcare-professional-license-transfer">license transfers</a>. Still choosing an emirate? <a href="' . $groupUrl . '">Compare DHA, DOH and MOHAP licensing</a>.</p>',
    'status' => 'published',
    'featured' => 0,
    'show_testimonials' => 0,
];

$faqs = [
    ['How do I apply for a DOH license?', 'You apply on TAMM, the Abu Dhabi government services platform. DOH assesses your credentials against the PQR, DataFlow verifies your documents, and you sit the DOH exam with Pearson VUE unless you are exempt. Your license is activated when a DOH-licensed facility in Abu Dhabi employs you.'],
    ['Which exam does DOH use?', 'The DOH licensing exam is delivered by Pearson VUE and booked from your TAMM account. Depending on your title it may be written, oral or an OSCE, in English. The PQR allows three attempts across the UAE authorities, then one final attempt with a different authority.'],
    ['Can I transfer my DHA or MOHAP license to DOH?', 'Yes. Apply to DOH on TAMM while your current license is valid. Your DataFlow report is transferred, and with a good standing certificate you can be exempt from the exam for the same title. Specialist titles also need residency programme documents. Most transfers take 4 to 6 weeks.'],
    ['Who activates my DOH license?', 'A DOH-licensed healthcare facility in Abu Dhabi that employs you. Once DOH confirms your eligibility, your employer activates the license so you can start practising.'],
    ['How long does DOH licensing take?', 'Most applications take 6 to 8 weeks. The time depends mainly on DataFlow verification, the next available Pearson VUE exam date and how complete your documents are. We give you an estimate for your title after the eligibility review.'],
    ['Can I sit the DOH exam outside the UAE?', 'Usually, yes. Pearson VUE runs test centres in many countries, and the application on TAMM and DataFlow verification are done online, so you can complete most of the process before you move to Abu Dhabi.'],
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
    // Listed right after the DHA page (or the New license service if DHA is missing).
    $after = Service::where('slug', 'dha-license-healthcare-professionals-dubai')->value('sort_order')
        ?? Service::where('slug', $relatedSlugs[0])->value('sort_order');
    $fields['sort_order'] = (int) $after + 1;
}
$service->fill($fields)->save();
$service->categories()->sync([$categoryId]);
$service->faq()->delete();
foreach ($faqs as [$question, $answer]) {
    $service->faq()->create(['faq_question' => $question, 'faq_answer' => '<p>' . $answer . '</p>']);
}

echo ($created ? 'created' : 'updated') . " service #{$service->id} '{$slug}': " . $service->faq()->count() . " FAQs, category {$categoryId}\n";
echo "URL: " . route('front.service', $slug) . "\n";
