<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Two indexed service pages (SEO briefs "alphahmc-dental-centre-page" and "alphahmc-day-surgery-page", approved by
 * Saroth 30 Oct 2026; reviewed by Eng. Afrin Nakshabi 30 Oct 2026). Text is the SEO agent's drafts word for word,
 * mapped onto the standard service template; the FAQ uses the template's own heading.
 * Links in: a "Dental centres" card after "Clinics" and the "Day surgery centres" card repointed (facility types),
 * one sentence each on the clinic and medical centre pages, one on the day surgery category.
 * Everything resolves by slug; down() removes the pages and puts the links back.
 */
return new class extends Migration
{
    private const PAGES = [
        'dental' => [
            'slug' => 'dental-centre-setup-licensing-dubai',
            'meta_title' => 'Dental Centre Setup and DHA Licensing in Dubai',
            'meta_description' => 'Open a dental centre in Dubai with one team: layout and sterilisation planning, FANR approval for X-ray, DHA licensing and the final inspection.',
            'meta_keywords' => 'dental centre setup Dubai, dental clinic licensing Dubai, DHA dental centre licence, FANR dental X-ray approval',
            'areaServed' => 'Dubai, UAE',
            'categories' => ['facility-licensing-setup'],
            'related' => ['radiation-equipment-fanr-registration-uae', 'medical-facility-fit-out', 'medical-engineering-equipment-planning'],
        ],
        'daysurgery' => [
            'slug' => 'day-surgery-centre-setup-licensing-uae',
            'meta_title' => 'Day Surgery Centre Setup and Licensing in the UAE',
            'meta_description' => 'Plan, license and open a day surgery centre in Dubai or Abu Dhabi: theatre classification, layout, medical gases and HVAC, DHA or DOH licensing.',
            'meta_keywords' => 'day surgery centre setup UAE, day surgery licensing Dubai, DOH day surgery licence, operating theatre planning UAE',
            'areaServed' => 'Dubai and Abu Dhabi, UAE',
            'categories' => ['facility-licensing-setup', 'day-surgery-centers'],
            'related' => ['hospital-infrastructure-planning-engineering-design', 'doh-healthcare-facility-licensing-abu-dhabi', 'radiation-equipment-fanr-registration-uae'],
        ],
    ];

    private const DENTAL_LINK = '<p>Opening a dental centre? See <a href="https://alphahmc.com/services/dental-centre-setup-licensing-dubai">dental centre setup and licensing in Dubai</a>.</p>';
    private const DAY_SURGERY_LINK = '<p>Opening a new day surgery centre? See <a href="https://alphahmc.com/services/day-surgery-centre-setup-licensing-uae">day surgery centre setup and licensing</a>.</p>';

    private const DENTAL_CARD = ['Dental centres', 'Treatment rooms, sterilisation and X-ray planned for DHA approval.'];
    private const DAY_SURGERY_LINE = 'Theatres, recovery and building services planned for DHA or DOH approval.';
    private const DAY_SURGERY_OLD_LINE = 'Operating theatres, recovery and sterile flows planned for approval.';

    public function up(): void
    {
        $text = json_decode(self::TEXT, true);
        $ids = [];
        foreach (self::PAGES as $key => $page) {
            $ids[$key] = $this->upsertService($page, $text[$key]);
        }

        // Sentences linking in, added once.
        foreach (['clinic-setup-licensing-uae', 'medical-centre-setup-licensing-dubai'] as $slug) {
            $this->appendOnce('services', $slug, 'info_four', self::DENTAL_LINK);
        }
        $this->appendOnce('categories', 'day-surgery-centers', 'overview', self::DAY_SURGERY_LINK);

        if (Schema::hasTable('facility_type_cards')) {
            $this->facilityCards($ids);
        }
    }

    public function down(): void
    {
        foreach (self::PAGES as $page) {
            $id = DB::table('services')->where('slug', $page['slug'])->value('id');
            if (!$id) {
                continue;
            }
            DB::table('faqs')->where('service_id', $id)->delete();
            DB::table('service_categories')->where('service_id', $id)->delete();
            DB::table('services')->where('id', $id)->delete();
        }
        foreach (['clinic-setup-licensing-uae', 'medical-centre-setup-licensing-dubai'] as $slug) {
            $this->removeOnce('services', $slug, 'info_four', self::DENTAL_LINK);
        }
        $this->removeOnce('categories', 'day-surgery-centers', 'overview', self::DAY_SURGERY_LINK);

        if (Schema::hasTable('facility_type_cards')) {
            DB::table('facility_type_cards')->where('name', self::DENTAL_CARD[0])->delete();
            $catId = DB::table('categories')->where('slug', 'day-surgery-centers')->value('id');
            if ($catId) {
                DB::table('facility_type_cards')->where('name', 'Day surgery centres')
                    ->update(['target' => "category:$catId", 'line' => self::DAY_SURGERY_OLD_LINE, 'updated_at' => now()]);
            }
        }
    }

    private function upsertService(array $page, array $text): int
    {
        $now = now();
        $row = $text['fields'] + [
            'name' => $text['name'],
            'slug' => $page['slug'],
            'meta_title' => $page['meta_title'],
            'meta_description' => $page['meta_description'],
            'meta_keywords' => $page['meta_keywords'],
            'areaServed' => $page['areaServed'],
            'serviceType' => 'Healthcare facility licensing',
            'status' => 'published',
            'featured' => 0,
            'show_testimonials' => 0,
            // B2B line, as on the medical centre page
            'agent_id' => DB::table('agents')->where('whatsapp', '+971507807163')->value('id')
                ?? DB::table('services')->where('slug', 'medical-centre-setup-licensing-dubai')->value('agent_id'),
            'related_services' => json_encode(array_map('strval', DB::table('services')->whereIn('slug', $page['related'])->pluck('id')->all())),
            'reviewer' => 'afrin-nakshabi',
            'reviewed_at' => '2026-10-30',
            'published_date' => '2026-10-30',
            'updated_date' => '2026-10-30',
            'updated_at' => $now,
        ];

        $id = DB::table('services')->where('slug', $page['slug'])->value('id');
        if ($id) {
            DB::table('services')->where('id', $id)->update($row);
        } else {
            $row['sort_order'] = (int) DB::table('services')->max('sort_order') + 1;
            $id = DB::table('services')->insertGetId($row + ['created_at' => $now]);
        }

        DB::table('faqs')->where('service_id', $id)->delete();
        foreach ($text['faqs'] as $faq) {
            DB::table('faqs')->insert(['service_id' => $id, 'faq_question' => $faq['question'],
                'faq_answer' => $faq['answer'], 'created_at' => $now, 'updated_at' => $now]);
        }

        DB::table('service_categories')->where('service_id', $id)->delete();
        foreach (DB::table('categories')->whereIn('slug', $page['categories'])->pluck('id') as $catId) {
            DB::table('service_categories')->insert(['service_id' => $id, 'category_id' => $catId, 'created_at' => $now, 'updated_at' => $now]);
        }

        return $id;
    }

    private function facilityCards(array $ids): void
    {
        if (!DB::table('facility_type_cards')->where('name', self::DENTAL_CARD[0])->exists()) {
            $clinics = DB::table('facility_type_cards')->where('name', 'Clinics')->value('sort_order');
            $at = $clinics !== null ? $clinics + 1 : (int) DB::table('facility_type_cards')->max('sort_order') + 1;
            DB::table('facility_type_cards')->where('sort_order', '>=', $at)->increment('sort_order');
            DB::table('facility_type_cards')->insert([
                'name' => self::DENTAL_CARD[0], 'line' => self::DENTAL_CARD[1], 'target' => 'service:' . $ids['dental'],
                'sort_order' => $at, 'active' => true, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        DB::table('facility_type_cards')->where('name', 'Day surgery centres')
            ->update(['target' => 'service:' . $ids['daysurgery'], 'line' => self::DAY_SURGERY_LINE, 'updated_at' => now()]);
    }

    private function appendOnce(string $table, string $slug, string $column, string $html): void
    {
        $row = DB::table($table)->where('slug', $slug)->first(['id', $column]);
        if ($row && !str_contains((string) $row->$column, $html)) {
            DB::table($table)->where('id', $row->id)->update([$column => rtrim((string) $row->$column) . "\n" . $html]);
        }
    }

    private function removeOnce(string $table, string $slug, string $column, string $html): void
    {
        $row = DB::table($table)->where('slug', $slug)->first(['id', $column]);
        if ($row) {
            DB::table($table)->where('id', $row->id)->update([$column => str_replace("\n" . $html, '', (string) $row->$column)]);
        }
    }

    /** Built from the drafts by the website terminal's converter; the word check passed (FAQ heading excepted). */
    private const TEXT = <<<'JSON'
{
 "dental": {
  "name": "Dental Centre Setup and Licensing in Dubai",
  "fields": {
   "content": "<p>Alpha Health Group plans, licenses and opens dental centres in Dubai with one team, from the first layout to the final inspection by the <a href=\"https://www.dha.gov.ae/\">Dubai Health Authority (DHA)</a>. Most delays in a dental project start on the drawing board, so we plan your treatment rooms, sterilisation and X-ray around the specialties you want to offer before any fit-out begins. For a small facility, the licensing project typically takes about 14 weeks; fit-out and authority steps can extend it.</p>",
   "overview": "<h2>Who this is for</h2>\n<p>Dentists opening their own practice, groups adding a dental centre, and investors who are not dentists. A non-dentist can own a dental centre in Dubai; the centre still needs a licensed medical director. If your project has stalled, or your facility did not pass an authority audit, we can take it over from where it stands.</p>",
   "info_one": "<h2>What makes a dental centre different</h2>\n<ul><li>Rooms follow your specialties. General dentistry, orthodontics, implants and paediatric dentistry each change the number and type of treatment rooms. We map them against DHA requirements in the first phase, before any drawing.</li><li>Sterilisation has its own flow. Instruments move from dirty to clean without crossing paths, and the number of sterilisation areas depends on the size of the practice. Getting this wrong is a common reason drawings are sent back.</li><li>X-ray needs a separate approval. Dental X-ray and other radiation equipment need approval from the <a href=\"https://www.fanr.gov.ae/\">Federal Authority for Nuclear Regulation (FANR)</a>. We build the shielded room and the FANR application into the layout and the schedule from the start, so it does not hold up opening.</li><li>Staff to open. At minimum, a licensed medical director and a licensed nurse, with the dentists for the specialties you offer. We handle their DHA licensing alongside the facility licence.</li></ul>",
   "info_two": "<h2>Planning decides cost and time</h2>\n<p>Poor planning causes hidden losses: usable clinical space lost to the wrong layout, higher construction cost, and drawings returned by the authority. Proper planning makes full use of the space and shortens the approval path. Our medical engineering team plans the layout, building services and equipment for your specialties first, and the licence follows.</p>",
   "info_three": "<h2>How we take your dental centre from plan to licence</h2>\n<ul><li>Discovery and planning (weeks 1 to 2): your specialties, the site, a service map and a gap analysis against DHA requirements.</li><li>Applications and engineering drawings (weeks 2 to 5): trade licence steps, the DHA facility application, layout and engineering drawings for approval, and the FANR radiation application.</li><li>Compliance documentation (weeks 5 to 10): policies and procedures, staff licensing and HR documents, equipment and biomedical documentation, mandatory service contracts.</li><li>Inspection readiness (weeks 10 to 12, depending on site readiness): a mock inspection against DHA standards and a gap list with owners and dates.</li><li>Final inspection and licence (weeks 12 to 14): we attend the DHA inspection with your team through to the facility licence.</li></ul>\n<p>Need the build as well? We also deliver <a href=\"https://alphahmc.com/services/healthcare-design-and-build\">design and build</a> and <a href=\"https://alphahmc.com/services/medical-facility-fit-out\">medical facility fit-out</a>, and plan equipment through <a href=\"https://alphahmc.com/services/medical-engineering-equipment-planning\">medical equipment planning</a>.</p>",
   "info_four": "<h2>What is not included in our fee</h2>\n<p>Government fees (authority, trade licence, DataFlow, Civil Defence, Municipality), construction and fit-out work, dental equipment and consumables, IT and patient record systems, and malpractice insurance.</p>\n<h2>Facilities we have delivered</h2>\n<p>We have delivered or consulted on more than 250 healthcare facilities. See recent projects such as the <a href=\"https://alphahmc.com/project_details/nhs-medical-center\">NHS Medical Center</a> in Dubai and <a href=\"https://alphahmc.com/project_details/broadway-vascular\">Broadway Vascular</a>, or <a href=\"https://alphahmc.com/project\">all our projects</a>. For other facility types, see <a href=\"https://alphahmc.com/service-category/facility-licensing-setup\">facility licensing and setup</a> and <a href=\"https://alphahmc.com/services/clinic-setup-licensing-uae\">clinic setup and licensing in the UAE</a>.</p>"
  },
  "faqs": [
   {
    "question": "How long does it take to open a dental centre in Dubai?",
    "answer": "<p>For a small dental centre, the licensing project typically takes about 14 weeks from planning to the final DHA inspection. Fit-out, FANR approval for X-ray and other authority steps can extend it, which is why we plan them from the first week.</p>"
   },
   {
    "question": "Can I open a dental centre if I am not a dentist?",
    "answer": "<p>Yes. The owner does not need to be a dentist, but the centre needs a licensed medical director and licensed clinical staff. We can help you find and license them.</p>"
   },
   {
    "question": "Do I need FANR approval for dental X-ray?",
    "answer": "<p>Yes. Dental X-ray and other radiation equipment need approval from FANR before use. See our <a href=\"https://alphahmc.com/services/radiation-equipment-fanr-registration-uae\">radiation equipment FANR registration</a> service.</p>"
   },
   {
    "question": "What does it cost to set up a dental centre?",
    "answer": "<p>The cost depends mainly on the size of the space, the number of treatment rooms, the fit-out and the equipment for your specialties. We give you a fixed scope and fee for our part after the first planning meeting, and a clear list of government fees and other costs you will pay directly.</p>"
   },
   {
    "question": "Can I buy an existing dental centre instead?",
    "answer": "<p>Yes. Check the licence, the facility's inspection history and the staff licences before you sign. Our <a href=\"https://alphahmc.com/services/healthcare-facility-acquisition-due-diligence\">acquisition due diligence</a> service covers this.</p>"
   }
  ]
 },
 "daysurgery": {
  "name": "Day Surgery Centre Setup and Licensing in the UAE",
  "fields": {
   "content": "<p>A day surgery centre is the hardest outpatient facility to get right on paper. Operating theatres, recovery, sterile and clean flows, medical gases, air handling and backup power all have to work together before the <a href=\"https://www.dha.gov.ae/\">Dubai Health Authority (DHA)</a> or the <a href=\"https://www.doh.gov.ae/\">Department of Health Abu Dhabi (DOH)</a> will license it. Alpha Health Group plans the theatres and building services with the layout from the first drawing, then carries the facility through licensing, documentation and the final inspection.</p>",
   "overview": "<h2>Who this is for</h2>\n<p>Surgeons and groups opening their own surgical centre, hospital groups adding an ambulatory site, investors entering surgical care, and owners of a centre whose licence has lapsed or whose expansion has stalled. We also take over projects that are part built or did not pass an authority audit.</p>",
   "info_one": "<h2>What sets a surgical centre apart</h2>\n<ul><li>The number of operating theatres follows the classification. In Dubai, the DHA standards for standalone day surgery centres set how many operating theatres a centre needs for its category, so the classification is decided before the layout.</li><li>Theatre layout and workflow decide approval. Patient, staff, sterile supply and waste routes must not cross, and recovery has to sit next to the theatres. Operating theatre layout, workflow and the engineering drawings are where most day surgery projects run into trouble.</li><li>Building services are planned with the rooms. Medical gases, HVAC for the theatres and backup power are designed together with the layout, not added after it. Changing them late means reworking the fit-out.</li><li>Imaging needs its own approval. Theatres with X-ray or fluoroscopy need approval from <a href=\"https://www.fanr.gov.ae/\">FANR</a> for the radiation equipment, which we plan into the shielding and the schedule.</li></ul>",
   "info_two": "<h2>How we take it from plan to licence</h2>\n<ul><li>Classification and planning: the specialties and procedures you will offer, the facility category, the number of theatres, and a gap analysis against the DHA or DOH standards.</li><li>Engineering design and approval: theatre and recovery layout, medical gases, HVAC and power backup, equipment planning, and the drawings submitted for authority approval.</li><li>Licensing and documentation: the facility application, policies and procedures, staff licensing and credentialing, equipment and biomedical documentation, and mandatory service contracts.</li><li>Inspection readiness: a mock inspection against the authority standards, with every gap listed with an owner and a date.</li><li>Final inspection and licence: we attend the inspection with your team through to the facility licence, then stay on for operational management if you want us to.</li></ul>\n<p>Timelines depend on the classification, the building and the fit-out, so we set yours after the first planning phase rather than quote a number up front.</p>",
   "info_three": "<h2>Surgical centres we have delivered</h2>\n<ul><li><a href=\"https://alphahmc.com/project_details/broadway-vascular\">Broadway Vascular Surgery Center</a>, Dubai: a turnkey specialised surgical facility with two operating theatres for vascular procedures, from concept to DHA licensing within six months.</li><li><a href=\"https://alphahmc.com/project_details/bone-care\">Bonecare Day Surgery Centre</a>, Abu Dhabi: an expired licence reactivated under current DOH standards within six months, with orthopaedics and plastic surgery launched, and Alpha retained for operational management.</li><li><a href=\"https://alphahmc.com/project_details/royal-spanish-center\">Royal Spanish Center</a>: engineering design, authority approvals and management outsourcing for an ophthalmic network of day surgery and primary care branches in Abu Dhabi and Dubai, over four years and ongoing.</li></ul>",
   "info_four": "<h2>What is not included in our fee</h2>\n<p>Government fees, construction and fit-out work, surgical and medical equipment and consumables, IT and patient record systems, and malpractice insurance.</p>\n<p>Related: <a href=\"https://alphahmc.com/service-category/day-surgery-centers\">day surgery centre consulting</a> for centres already operating, and <a href=\"https://alphahmc.com/service-category/facility-licensing-setup\">facility licensing and setup</a> for other facility types.</p>"
  },
  "faqs": [
   {
    "question": "How many operating theatres does a day surgery centre need?",
    "answer": "<p>It depends on the facility category. In Dubai, the DHA standards for standalone day surgery centres link the number of operating theatres to the category, so we settle the classification with you before any layout is drawn.</p>"
   },
   {
    "question": "How long does it take to open a day surgery centre?",
    "answer": "<p>It depends on the classification, the building and the fit-out. Two of our recent projects, Broadway Vascular in Dubai and the Bonecare reactivation in Abu Dhabi, each reached licensing within six months. We give you a schedule after the first planning phase.</p>"
   },
   {
    "question": "Can you reactivate an expired day surgery licence?",
    "answer": "<p>Yes. We assess the facility against current standards, fix the gaps in infrastructure, equipment and documentation, and take it through to reactivation, as we did for <a href=\"https://alphahmc.com/project_details/bone-care\">Bonecare</a>.</p>"
   },
   {
    "question": "Do you plan medical gases and HVAC?",
    "answer": "<p>Yes. Our engineering team plans medical gases, theatre air handling and backup power together with the layout, then follows them through approval and construction. See <a href=\"https://alphahmc.com/services/hospital-infrastructure-planning-engineering-design\">infrastructure planning and engineering design</a>.</p>"
   },
   {
    "question": "Do you work in Dubai and Abu Dhabi?",
    "answer": "<p>Yes, under both DHA in Dubai and DOH in Abu Dhabi. For Abu Dhabi, see also <a href=\"https://alphahmc.com/services/doh-healthcare-facility-licensing-abu-dhabi\">healthcare facility licensing in Abu Dhabi</a>.</p>"
   }
  ]
 }
}
JSON;
};
