<?php
/**
 * Part E: create or update the MOHAP service page
 * /services/mohap-license-healthcare-professionals through the Service model (no raw SQL).
 * DRAFT (22 Oct 2026): MOHAP-specific facts marked [confirm] are not confirmed by the licensing
 * team. The script refuses to write while any [confirm] remains (also in --dry-run it lists them).
 * Run licensing-group.php afterwards so the group page lists and links this service.
 *
 * Usage, from the app root:  php scripts/part-e/licensing-mohap.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/part-e/licensing-mohap.php
 *
 * Facts that apply to all UAE authorities come from the Unified PQR, April 2025 (same clauses as the
 * DHA and DOH pages: exam attempts and formats 1.5.2 to 1.5.5, good standing 1.3.3, PSV 1.4, gap of
 * practice 1.7, CME/CPD 2.1 and 2.2, recognition between authorities chapter 3, no exemption after a
 * two-year gap 21.4, minimum experience pp.18, 19, 55, 71, 105).
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;

$dryRun = in_array('--dry-run', $argv, true);
$slug = 'mohap-license-healthcare-professionals';
$categoryId = 44; // Healthcare Professional Licensing
$sourceServiceSlug = 'healthcare-professional-licensing-uae'; // agent, hero image
$relatedSlugs = [
    'healthcare-professional-license-registration',
    'healthcare-professional-license-transfer',
    'activate-your-uae-healthcare-license',
];
$groupUrl = '/service-packages/healthcare-professional-licensing-uae';
$C = '[confirm]';

$fields = [
    'name' => 'MOHAP License for Healthcare Professionals in the UAE',
    'meta_title' => 'MOHAP License Consultants in the UAE | Alpha Health Group',
    'meta_description' => 'MOHAP licensing for doctors, nurses and allied staff in the Northern Emirates: eligibility, DataFlow, the exam and activation. Book a free consultation.',
    'meta_keywords' => 'MOHAP license, MOHAP license UAE, MOHAP exam, Ministry of Health and Prevention license, Northern Emirates healthcare license, DataFlow',
    'areaServed' => "Ajman, Umm Al Quwain, Ras Al Khaimah, Fujairah $C",
    'serviceType' => 'Healthcare Professional Licensing',
    'content' => '<p>We guide doctors, nurses, pharmacists, dentists and allied health staff through Ministry of Health and Prevention licensing, from the eligibility review to an active MOHAP license.</p>',
    'overview' => "<p>Practising in a facility licensed by the Ministry of Health and Prevention (MOHAP) takes a MOHAP license. $C which emirates and facility types this covers. We check your title against the PQR, prepare your documents for DataFlow, guide you through the exam and file your application with MOHAP.</p>",
    'info_one' => "<h2>Who needs a MOHAP license</h2>
<p>Physicians, dentists, nurses, midwives, pharmacists and allied health professionals who will practise in a facility under the <a href=\"https://mohap.gov.ae\" target=\"_blank\" rel=\"noopener\">Ministry of Health and Prevention</a>. $C the emirates and facility types MOHAP licenses for (Ajman, Umm Al Quwain, Ras Al Khaimah, Fujairah, federal facilities; Sharjah and SHA). A DHA or DOH license does not cover MOHAP facilities.</p>
<p>Not sure which authority applies to you? <a href=\"' . \$groupUrl . '\">Compare DHA, DOH and MOHAP</a>.</p>",
    'info_two' => "<h2>How MOHAP licensing works</h2>
<p>You apply for a MOHAP license through MOHAP's online services. $C the channel and service name (website, app, \"Evaluation of Health Professional\").</p>
<ol>
<li><strong>Apply online.</strong> You submit your application for your professional title. $C</li>
<li><strong>Assessment against the PQR.</strong> MOHAP checks your qualifications and experience against the Unified Healthcare Professional Qualification Requirements.</li>
<li><strong>Primary source verification.</strong> DataFlow confirms your degrees, experience, licenses and good standing with the institutions that issued them.</li>
<li><strong>Licensing exam.</strong> If your title requires it, you sit the MOHAP exam. $C the exam provider and how it is booked.</li>
<li><strong>Eligibility.</strong> MOHAP confirms you are eligible for the title.</li>
<li><strong>Activation.</strong> A MOHAP-licensed facility employs you and <a href=\"/services/activate-your-uae-healthcare-license\">activates your license</a>.</li>
</ol>
<h3>Minimum experience for common titles</h3>
<ul>
<li><strong>General Practitioner:</strong> the internship year, then two years in clinical practice</li>
<li><strong>General Dentist:</strong> the internship, then two years of practice</li>
<li><strong>Registered Nurse:</strong> a bachelor's degree in nursing; no minimum experience</li>
<li><strong>Pharmacist:</strong> two years after qualifying, not counting work for pharmaceutical companies or in sales</li>
</ul>
<p>Specialist and Consultant requirements depend on the PQR tier of the country where you trained. Physicians trained in Tier 3 countries cannot hold the Consultant title.</p>
<h3>About the exam</h3>
<p>The format depends on your title and may be written, oral or an OSCE, in English. The PQR gives you three attempts in total across the UAE authorities, then one last attempt with another authority. $C where the MOHAP exam is held and how it is booked.</p>
<h3>Exam exemptions</h3>
<p>You may not need the exam if you are a UAE national, if you hold a qualification or registration on the PQR's lists, such as US or UK board certification, PLAB, USMLE or NCLEX, or if you already hold a valid DHA, DOH or SHA license for the same title with a good standing certificate and a full DataFlow report. No exemption applies after more than two years out of practice.</p>
<h3>Moving from DHA or DOH</h3>
<p>Apply to MOHAP for a transfer while your current license is still valid. Your DataFlow report is transferred to MOHAP, which may ask for more verification. Your new employer then activates the license. $C the transfer steps and typical time. See <a href=\"/services/healthcare-professional-license-transfer\">license transfers between authorities</a>.</p>
<h3>Good standing and time out of practice</h3>
<p>Your good standing certificate must come from the regulator where you last practised and be no more than six months old when you apply. After more than three years out of practice, the PQR requires CME credits and a training period in an approved setting. Non-UAE nationals cannot apply after more than five years away.</p>
<h3>Timeline and costs</h3>
<p>$C a typical time for MOHAP applications. It depends on DataFlow, exam availability and your documents. MOHAP, DataFlow and exam fees are paid to them separately. Your first consultation with us is free; our licensing service is paid.</p>
<h3>Continuing education</h3>
<p>Licensed professionals need CME credits in their specialty each year: 40 for physicians and dentists, 20 for nurses and pharmacists, and 10 for allied health. Surgical specialists also submit a logbook every two years for renewal.</p>",
    'info_three' => '<h2>How Alpha Health Group helps</h2>
<p>Our licensing team takes care of the work around your MOHAP application:</p>
<ul>
<li>Checks your title against the PQR and, for specialists, your country tier</li>
<li>Prepares and checks your documents before DataFlow and MOHAP</li>
<li>Opens and follows your DataFlow verification</li>
<li>Guides you on the MOHAP exam and booking</li>
<li>Files your application with MOHAP and follows it up</li>
</ul>',
    'info_four' => '<p>Send us your title, qualification, years of experience and the country where you trained. We will confirm your MOHAP route, whether you are exempt from the exam, and your next step.</p>
<p>Licensed in Dubai or Abu Dhabi already? See <a href="/services/healthcare-professional-license-transfer">license transfers</a>. Still choosing an emirate? <a href="' . $groupUrl . '">Compare DHA, DOH and MOHAP licensing</a>.</p>',
    'status' => 'published',
    'featured' => 0,
    'show_testimonials' => 0,
];

$faqs = [
    ['How do I apply for a MOHAP license?', "You apply through MOHAP's online services. $C the channel. MOHAP assesses your credentials against the PQR, DataFlow verifies your documents, and you sit the licensing exam unless you are exempt. Your license is activated when a MOHAP-licensed facility employs you."],
    ['Which exam does MOHAP use?', "$C the exam provider. Depending on your title the exam may be written, oral or an OSCE, in English. The PQR allows three attempts across the UAE authorities, then one final attempt with a different authority."],
    ['Can I transfer my DHA or DOH license to MOHAP?', "Yes. Apply to MOHAP while your current license is valid. Your DataFlow report is transferred, and with a good standing certificate you can be exempt from the exam for the same title. $C the typical transfer time."],
    ['Who activates my MOHAP license?', 'A MOHAP-licensed healthcare facility that employs you. Once MOHAP confirms your eligibility, your employer activates the license so you can start practising.'],
    ['How long does MOHAP licensing take?', "$C a typical range. The time depends mainly on DataFlow verification, exam availability and how complete your documents are. We give you an estimate for your title after the eligibility review."],
    ['Which emirates does a MOHAP license cover?', "$C which emirates and facility types. A MOHAP license covers facilities licensed by the Ministry. Dubai (DHA) and Abu Dhabi (DOH) license their own facilities, so practising there needs a license from them."],
];

/* A draft never goes live: list every [confirm] and stop. */
$open = [];
foreach (array_merge(array_values(array_filter($fields, 'is_string')), array_merge(...$faqs)) as $text) {
    if (str_contains($text, $C)) {
        $open[] = mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags($text))), 0, 120);
    }
}
if ($open) {
    fwrite(STDERR, count($open) . " [confirm] item(s) open; nothing written:\n - " . implode("\n - ", $open) . "\n");
    exit(1);
}

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
    // Listed right after the DOH page (or the DHA page).
    $after = Service::where('slug', 'doh-license-healthcare-professionals-abu-dhabi')->value('sort_order')
        ?? Service::where('slug', 'dha-license-healthcare-professionals-dubai')->value('sort_order');
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
