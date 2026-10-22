<?php
/**
 * Part E step 2: create or update the UAE licensing group page
 * /service-packages/healthcare-professional-licensing-uae through the ServiceGroup model
 * (no raw SQL). Safe to re-run: it updates the same group and replaces its FAQs, the way
 * the admin's update does.
 *
 * Usage, from the app root:  php scripts/part-e/licensing-group.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/part-e/licensing-group.php
 *
 * Facts are the confirmed Part B set (Saroth 19 Oct; Unified PQR v3 April 2025; DHA licensing
 * manual v1.3; MOHAP, TAMM, Pearson VUE and DataFlow official pages).
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;
use App\Models\ServiceGroup;

$dryRun = in_array('--dry-run', $argv, true);
$slug = 'healthcare-professional-licensing-uae';

// Authority pages: listed and linked only once their script has run and the page is published,
// so this script can be re-run before the next authority page goes live.
$authorityPages = [
    'Dubai Health Authority (DHA)' => ['dha-license-healthcare-professionals-dubai', 'Working in Dubai? See <a href="/services/%s">DHA licensing for healthcare professionals</a>.'],
    'Department of Health (DOH)' => ['doh-license-healthcare-professionals-abu-dhabi', 'Working in Abu Dhabi? See <a href="/services/%s">DOH licensing for healthcare professionals</a>.'],
    'Ministry of Health and Prevention (MOHAP)' => ['mohap-license-healthcare-professionals', 'Working in Ajman, Umm Al Quwain, Ras Al Khaimah or Fujairah? See <a href="/services/%s">MOHAP licensing for healthcare professionals</a>.'],
];
$serviceSlugs = [
    'healthcare-professional-license-registration',   // New healthcare professional license
    'healthcare-professional-license-transfer',       // Transfer between authorities
    'activate-your-uae-healthcare-license',           // Activation with a new employer
];
$categoryId = 44; // Healthcare Professional Licensing (drives the related article)
$sourceServiceSlug = 'healthcare-professional-licensing-uae'; // agent, hero image

$fields = [
    'name' => 'Healthcare Professional Licensing in the UAE: DHA, DOH and MOHAP',
    'meta_title' => 'DHA, DOH and MOHAP License Consultants | Alpha Health Group',
    'meta_description' => 'Licensing help for doctors, nurses and allied staff: DHA, DOH and MOHAP eligibility, DataFlow, exams and applications. Book a free consultation.',
    'content' => '<p>We guide doctors, nurses, pharmacists and allied health staff through DHA, DOH and MOHAP licensing, from eligibility to your license.</p>',
    'service_details_header' => '<h2>Which UAE license do I need?</h2>',
    'overview' => '<p>Every doctor, nurse, pharmacist, dentist and allied health professional needs a license from the health authority of the emirate where they will work. All three authorities assess applicants against the same Unified Healthcare Professional Qualification Requirements (PQR). The main differences are where you apply, which provider runs the exam, and who activates the license.</p>
<div class="table-scroll"><table>
<thead><tr><th>Authority</th><th>Emirates</th><th>Apply through</th><th>Licensing exam</th><th>License activated by</th></tr></thead>
<tbody>
<tr><td>Dubai Health Authority (DHA)</td><td>Dubai</td><td>Sheryan</td><td>Prometric</td><td>Your DHA-licensed employer</td></tr>
<tr><td>Department of Health (DOH)</td><td>Abu Dhabi</td><td>TAMM</td><td>Pearson VUE</td><td>Your DOH-licensed employer</td></tr>
<tr><td>Ministry of Health and Prevention (MOHAP)</td><td>Northern Emirates</td><td>MOHAP website or app</td><td>Prometric</td><td>Your employer, which applies for the license</td></tr>
</tbody>
</table></div>
<p>Sharjah has its own regulator, the Sharjah Health Authority (SHA), which also licenses professionals under the PQR.</p>
<h3>Timeline</h3>
<p>Most applications with DHA, DOH or MOHAP take 6 to 8 weeks. The time depends mainly on DataFlow verification, the next available exam date and how complete your documents are.</p>
<h3>Applying from abroad</h3>
<p>Registration, credential evaluation and DataFlow verification are done online, and Prometric and Pearson VUE run exam centres in many countries, so you can usually sit the exam close to home. Your good standing certificate must be no older than six months when you apply.</p>
<h3>Costs</h3>
<p>You pay the authority\'s application and licensing fees, the DataFlow verification fee and, where it applies, the exam fee. These are set by the authorities and DataFlow and are paid separately. Your first consultation with Alpha Health Group is free. The licensing process itself is a paid service.</p>',
    'service_header' => 'UAE licensing services',
    'description' => 'Start a new license, move it between emirates, or activate it with a new employer.',
    'process_intro' => '<h2>How UAE licensing works</h2>
<p>DHA, DOH and MOHAP follow the same six steps.</p>',
    'info_four' => '<h2>How Alpha Health Group helps</h2>
<p>Our licensing team handles the work around your application: we check your eligibility against the PQR for your title and authority, prepare and check your documents, open and follow your DataFlow verification, guide you on the exam, and follow the application through to approval.</p>
<p>{authority_links}Applying for the first time? See <a href="/services/healthcare-professional-license-registration">new healthcare professional licenses</a>. Changing emirate? See <a href="/services/healthcare-professional-license-transfer">license transfers between DHA, DOH and MOHAP</a>. Joining a new employer? See <a href="/services/activate-your-uae-healthcare-license">license activation</a>.</p>
<p>Planning to work elsewhere in the GCC? See <a href="/services/saudi-healthcare-professional-licensing">SCFHS licensing in Saudi Arabia</a>, <a href="/services/healthcare-professional-licensing-qatar">DHP licensing in Qatar</a> and <a href="/services/healthcare-professional-licensing-oman">licensing in Oman</a>.</p>',
    'status' => 'published',
    'is_featured' => 0,
    'show_testimonials' => 0,
];

// Six shared steps; a step can point to the service that covers it.
$steps = [
    ['Registration', 'You open your application with the authority: Sheryan for DHA, TAMM for DOH, or the MOHAP portal.', 'healthcare-professional-license-registration'],
    ['Credential evaluation', 'The authority evaluates your qualifications and experience against the PQR for your title.', null],
    ['DataFlow verification', 'DataFlow verifies your education, experience, professional licenses and good standing certificate with the institutions that issued them.', null],
    ['Exam or exemption', 'Most titles sit the licensing exam. The PQR exempts some applicants, for example holders of listed UK or US qualifications, or of a valid license from another UAE authority.', null],
    ['Eligibility', 'The authority confirms your eligibility. With DHA it is a registration, valid for one year.', null],
    ['Activation', 'Your license becomes active when a licensed facility in that emirate employs you.', 'activate-your-uae-healthcare-license'],
];

$faqs = [
    ['Which UAE license do I need?', 'It depends on where you will work: DHA for Dubai, DOH for Abu Dhabi and MOHAP for the Northern Emirates, while Sharjah has its own regulator, SHA. Each authority licenses professionals for its own emirate, so your job offer decides the authority. We confirm it in your first consultation.'],
    ['How long does UAE licensing take?', 'Most applications take 6 to 8 weeks with DHA, DOH or MOHAP. The time depends mainly on DataFlow verification, the next available exam date and how complete your documents are. We give you an estimate for your title after the eligibility review.'],
    ['Do I need to pass an exam, and which one?', 'Most titles must pass the authority\'s licensing exam: Prometric for DHA and MOHAP, Pearson VUE for DOH. The PQR exempts some applicants, including holders of listed international qualifications or registrations, such as UK and US boards, PLAB, USMLE or NCLEX. We check your exemption in the eligibility review.'],
    ['What documents do I need for DataFlow?', 'DataFlow verifies your educational qualifications, experience certificates, professional licenses and good standing certificate with the institutions that issued them. Your passport is uploaded to the authority, not verified. Names and dates must match across documents, and the good standing certificate must be no older than six months.'],
    ['Can I apply from outside the UAE?', 'Yes. Registration, credential evaluation and DataFlow verification are done online, and Prometric and Pearson VUE run exam centres in many countries, so you can usually sit the exam close to home. Your license is activated once a UAE healthcare facility employs you.'],
    ['How much does it cost, and what is included in Alpha\'s fee?', 'The authority\'s licensing fees, the DataFlow fee and any exam fee are set by them and paid separately. Alpha\'s fee covers the eligibility review, document preparation, DataFlow follow-up, exam guidance and the application. Your first consultation is free; the licensing process itself is charged.'],
];

/* Copy rules, as in the Part B build: no en or em dashes, no "compliant", no compliance claims. */
$all = array_merge(array_values(array_filter($fields, 'is_string')), array_merge(...array_map(fn ($s) => [$s[0], $s[1]], $steps)), array_merge(...$faqs));
foreach ($all as $text) {
    if (preg_match('/[\x{2013}\x{2014}]/u', $text) || preg_match('/\bcompliant\b|(ensure[sd]?|full)\b[^.]{0,30}\bcompliance/i', $text)) {
        fwrite(STDERR, "Copy rule broken: " . mb_substr(strip_tags($text), 0, 80) . "\n");
        exit(1);
    }
}
if (mb_strlen($fields['meta_title']) > 60 || mb_strlen($fields['meta_description']) > 155) {
    fwrite(STDERR, "Title or description too long\n");
    exit(1);
}

$source = Service::where('slug', $sourceServiceSlug)->first();
$services = Service::whereIn('slug', $serviceSlugs)->get()->keyBy('slug');
$missing = array_diff($serviceSlugs, $services->keys()->all());
if ($missing) {
    fwrite(STDERR, "Missing services: " . implode(', ', $missing) . "\n");
    exit(1);
}

// Live authority pages go first in the group's list and get linked from the table and help block.
$authorityLive = Service::published()->whereIn('slug', array_column($authorityPages, 0))->get()->keyBy('slug');
$authorityLinks = '';
foreach ($authorityPages as $cell => [$pageSlug, $sentence]) {
    if (!isset($authorityLive[$pageSlug])) {
        continue;
    }
    $fields['overview'] = str_replace("<td>{$cell}</td>", "<td><a href=\"/services/{$pageSlug}\">{$cell}</a></td>", $fields['overview']);
    $authorityLinks .= sprintf($sentence, $pageSlug) . ' ';
}
$fields['info_four'] = str_replace('{authority_links}', $authorityLinks, $fields['info_four']);
$services = $authorityLive->toBase()->merge($services->toBase()); // base merge keeps the slug keys

// Hero image and inquiry officer carry over from the UAE service page.
if ($source) {
    $fields['agent_id'] = $source->agent_id;
    if ($source->hero_image) {
        $fields['hero_image'] = 'uploads/service_images/' . $source->hero_image;
    }
}
$fields['process_header'] = json_encode(array_column($steps, 0), JSON_UNESCAPED_UNICODE);
$fields['process_description'] = json_encode(array_column($steps, 1), JSON_UNESCAPED_UNICODE);
$fields['process_service_ids'] = json_encode(array_map(fn ($s) => $s[2] ? (string) $services[$s[2]]->id : null, $steps));
$fields['updated_date'] = now()->toDateString();

if ($dryRun) {
    echo "dry run: checks passed; would write group '{$slug}' with " . count($steps) . " steps, " . count($faqs) . " FAQs, services " . implode(',', $services->pluck('id')->all()) . "\n";
    exit(0);
}

$group = ServiceGroup::firstOrNew(['slug' => $slug]);
$created = !$group->exists;
if ($created) {
    $fields['published_date'] = now()->toDateString();
}
$group->fill($fields)->save();
$group->services()->sync($services->pluck('id')->all());
$group->categories()->sync([$categoryId]);
$group->faqs()->delete();
foreach ($faqs as [$question, $answer]) {
    $group->faqs()->create(['faq_question' => $question, 'faq_answer' => '<p>' . $answer . '</p>']);
}

echo ($created ? 'created' : 'updated') . " group #{$group->id} '{$slug}': " . $group->services()->count() . " services, " . $group->faqs()->count() . " FAQs, category {$categoryId}\n";
echo "URL: " . route('service-packages', $slug) . "\n";
