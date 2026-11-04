<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * SEO brief "alphahmc-medical-centre-text-and-fanr" (Saroth approved 4 Nov 2026, guard PASS on both pages).
 * Text word for word from proposals/alphahmc-medical-centre-setup-dubai.slots.md and
 * proposals/alphahmc-dental-centre-setup-dubai.slots.md.
 *   Medical centre: hero line, quote, 4a, 4b, card 2, new FAQ "Do I need FANR approval..." as question 5 of 7.
 *   Dental centre: hero line, the X-ray bullet in 4b, card 2.
 * FAQs show in id order, so the medical centre's last two rows take questions 5 and 6 and a new row holds question 7.
 * The previous text is saved to storage/app/migration-backups; down() restores it.
 */
return new class extends Migration
{
    private const BACKUP = 'migration-backups/2026_11_04_110000_medical_centre_text_and_dental_fanr.json';

    private const COLUMNS = ['content', 'overview', 'info_one', 'info_two', 'reviewer', 'reviewed_at', 'updated_date'];

    private const MEDICAL = 'medical-centre-setup-licensing-dubai';
    private const DENTAL = 'dental-centre-setup-licensing-dubai';

    private const DENTAL_XRAY_OLD = '<li>X-ray needs a separate approval. Dental X-ray and other radiation equipment need approval from the <a href="https://www.fanr.gov.ae/">Federal Authority for Nuclear Regulation (FANR)</a>. We build the shielded room and the FANR application into the layout and the schedule from the start, so it does not hold up opening.</li>';
    private const DENTAL_XRAY_NEW = '<li>X-ray, if you install it. Dental X-ray and other radiation equipment need approval from the <a href="https://www.fanr.gov.ae/">Federal Authority for Nuclear Regulation (FANR)</a>. If your centre will have X-ray, we plan the shielded room and the FANR application into the layout and the schedule from the start, so it does not hold up opening.</li>';

    private const MEDICAL_FAQS = [
        [
            'Do I need FANR approval for my medical centre?',
            '<p>Only if you install X-ray or other radiation equipment. That equipment needs approval from the <a href="https://www.fanr.gov.ae/">Federal Authority for Nuclear Regulation (FANR)</a>, and the room needs shielding planned into the layout. If imaging is in your scope, we add the FANR application to your plan from the start.</p>',
        ],
        [
            'Can I add specialties to my existing clinic?',
            '<p>Yes. A new specialty changes your facility licence and often needs layout changes and new staff licences. See <a href="https://alphahmc.com/blog_single/how-to-successfully-expand-healthcare-services-and-specialties-in-the-uae">how to expand healthcare services in the UAE</a>.</p>',
        ],
        [
            'Can I buy an existing centre instead?',
            '<p>Yes. Check its licence, inspection history and staff licences before you sign. Our <a href="https://alphahmc.com/services/healthcare-facility-acquisition-due-diligence">acquisition due diligence</a> service covers this.</p>',
        ],
    ];

    public function up(): void
    {
        $medical = DB::table('services')->where('slug', self::MEDICAL)->first();
        $dental = DB::table('services')->where('slug', self::DENTAL)->first();
        $this->backup([$medical, $dental]);
        $now = now();
        $today = $now->toDateString();

        if ($medical) {
            DB::table('services')->where('id', $medical->id)->update([
                'content' => '<p>Trade licence, scope, layout and DHA licensing for your centre, with one team.</p>',
                'overview' => '<p>To open a medical centre or polyclinic in Dubai, you need a trade licence for your company and a facility licence from the <a href="https://www.dha.gov.ae/">Dubai Health Authority (DHA)</a> for the centre. Every specialty you add changes the rooms, staff and building services DHA expects. Alpha Health Group plans your scope, layout and licensing together, through to the final inspection.</p>',
                'info_one' => "<h3>What it takes to open your centre</h3>\n<p>Most medical centre projects lose months to the same few mistakes. Here is what you need to get right, and the parts we handle for you.</p>",
                'info_two' => self::MEDICAL_INFO_TWO,
                'reviewer' => 'siraj-hm-ibrahim',
                'reviewed_at' => '2026-11-04',
                'updated_date' => $today,
                'updated_at' => $now,
            ]);
            $this->setCard($medical->id, 1, '<p>Trade licence steps, the DHA facility application, and layout and engineering drawings for approval. FANR approval is added only if you install X-ray equipment.</p>', $now);

            // Questions 1 to 4 stay; the rows from position 5 on take the FANR question and the two after it.
            $faqs = DB::table('faqs')->where('service_id', $medical->id)->orderBy('id')->get();
            foreach (self::MEDICAL_FAQS as $i => [$question, $answer]) {
                $row = $faqs[$i + 4] ?? null;
                if ($row) {
                    DB::table('faqs')->where('id', $row->id)->update(['faq_question' => $question, 'faq_answer' => $answer, 'updated_at' => $now]);
                } else {
                    DB::table('faqs')->insert(['service_id' => $medical->id, 'faq_question' => $question, 'faq_answer' => $answer,
                        'created_at' => $now, 'updated_at' => $now]);
                }
            }
        }

        if ($dental) {
            DB::table('services')->where('id', $dental->id)->update([
                'content' => '<p>Layout, sterilisation, staff licensing and DHA approval for your dental centre, with one team.</p>',
                'info_two' => str_replace(self::DENTAL_XRAY_OLD, self::DENTAL_XRAY_NEW, (string) $dental->info_two),
                'reviewer' => 'afrin-nakshabi',
                'reviewed_at' => '2026-11-04',
                'updated_date' => $today,
                'updated_at' => $now,
            ]);
            $this->setCard($dental->id, 1, '<p>Trade licence steps, the DHA facility application, and layout and engineering drawings for approval, with the FANR application if you install X-ray.</p>', $now);
        }
    }

    public function down(): void
    {
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        $saved = json_decode(Storage::disk('local')->get(self::BACKUP), true);
        foreach ($saved as $row) {
            $id = $row['service']['id'];
            DB::table('services')->where('id', $id)->update(array_intersect_key($row['service'], array_flip(self::COLUMNS)));
            foreach ($row['magazines'] as $m) {
                DB::table('service_magazines')->where('id', $m['id'])->update(['description' => $m['description']]);
            }
            DB::table('faqs')->where('service_id', $id)->delete();
            foreach ($row['faqs'] as $f) {
                DB::table('faqs')->insert($f);
            }
        }
    }

    /** Sets the description of the card at the given 0-based position (cards show in id order). */
    private function setCard(int $serviceId, int $position, string $description, $now): void
    {
        $card = DB::table('service_magazines')->where('service_id', $serviceId)->orderBy('id')->skip($position)->first();
        if ($card) {
            DB::table('service_magazines')->where('id', $card->id)->update(['description' => $description, 'updated_at' => $now]);
        }
    }

    /** Saves the current state once (a re-run keeps the first, pre-change copy). */
    private function backup(array $services): void
    {
        if (Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        $saved = [];
        foreach (array_filter($services) as $service) {
            $saved[$service->slug] = [
                'service' => array_intersect_key((array) $service, array_flip(array_merge(['id'], self::COLUMNS))),
                'magazines' => DB::table('service_magazines')->where('service_id', $service->id)->get(['id', 'description'])->map(fn ($r) => (array) $r)->all(),
                'faqs' => DB::table('faqs')->where('service_id', $service->id)->get()->map(fn ($r) => (array) $r)->all(),
            ];
        }
        Storage::disk('local')->put(self::BACKUP, json_encode($saved, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private const MEDICAL_INFO_TWO = '<h3>Two licences, planned together</h3><p>Your company needs a trade licence and your centre needs a facility licence. On the mainland, the trade licence comes from the Department of Economy and Tourism (DET); in Dubai Healthcare City (DHCC), it comes from the DHCC Free Zone Authority. The medical licence comes from DHA in both. We handle both applications and run them in the same phase of your plan.</p>'
        . '<h3>Your specialties set the scope</h3><p>The services you plan to offer decide your DHA facility category, and the category decides the rooms, staff and equipment you need. Imaging, procedure rooms, sample collection or a pharmacy each change all three. We agree your specialties and scope of services with you before any drawing, because changing the category later means new drawings.</p>'
        . '<h3>The layout is approved before you build</h3><p>DHA approves your layout and engineering drawings before fit-out starts. Patient and staff routes, clean and dirty flows, room sizes and access have to work for each specialty. Ventilation, power backup and, where your services need them, medical gases are designed with the layout, not added after it. Our medical engineering team prepares the drawings and answers the authority\'s comments until they are approved.</p>'
        . '<h3>Where projects stall</h3><ul><li>Signing a lease before checking the space. If the unit cannot fit the rooms, access and services your specialties need, you face a redesign or a new lease. We check the space before you sign.</li><li>Starting fit-out early. Work built before DHA approves the drawings is often torn out and rebuilt.</li><li>Licensing staff late. Your medical director, doctors, nurses and allied staff each need a DHA licence before you open. We run <a href="https://alphahmc.com/services/dha-license-healthcare-professionals-dubai">DHA professional licensing</a> alongside the facility licence.</li></ul>'
        . '<p>If your project has stalled, or your centre did not pass an authority audit, we review where it stands, list what is missing and take it through to opening.</p>'
        . '<p>Opening a single specialty practice? See <a href="https://alphahmc.com/services/clinic-setup-licensing-uae">clinic setup in Dubai</a>. Building or refitting the premises? See <a href="https://alphahmc.com/services/medical-facility-fit-out">medical facility fit-out</a>, or browse <a href="https://alphahmc.com/service-category/medical-centers-polyclinics">medical centres and polyclinics</a>.</p>';
};
