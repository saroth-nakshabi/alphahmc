<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Service pages follow the template (SEO brief "alphahmc-service-layout-fix", Saroth 31 Oct 2026; layout of
 * /services/medical-centre-setup-licensing-dubai as live on 31 Oct). Slots from proposals/<file>.slots.md, word for
 * word (guard PASS on all 7):
 *   2 hero line = content, 3 quote = overview, 4a = info_one, 4b = info_two (behind Read more), 5 = five slider cards
 *   (service_magazines; images kept, missing cards borrow a sister page's images), 6 CTA = info_three + info_four,
 *   7 FAQ (new questions on clinic and medical centre only), 8 case studies via the projects' service lists.
 * Clinic and medical centre also get the titles, descriptions and reviewer of brief
 * "alphahmc-clinic-and-medical-centre-pages" (Siraj H.M Ibrahim 31 Oct 2026, Updated = deploy date); the other five
 * keep their reviewer and Updated date.
 * The previous text, cards, FAQs and project links are saved to storage/app/migration-backups; down() restores them.
 */
return new class extends Migration
{
    private const BACKUP = 'migration-backups/2026_10_31_280000_service_pages_template_layout.json';

    private const META = [
        'clinic-setup-licensing-uae' => [
            'Clinic Setup in Dubai: DHA Licensing and Approvals',
            'Set up a clinic in Dubai with one team: DET trade licence, DHA layout approval, staff licensing and the final inspection. Abu Dhabi and Sharjah too.',
        ],
        'medical-centre-setup-licensing-dubai' => [
            'Medical Centre and Polyclinic Setup in Dubai',
            'Open a multi-specialty medical centre or polyclinic in Dubai: DHA category, layout and engineering approval, staff licensing and the final inspection.',
        ],
    ];

    /** Pages with fewer than five cards borrow the remaining images from this page's cards. */
    private const IMAGE_FROM = [
        'dental-centre-setup-licensing-dubai' => 'medical-centre-setup-licensing-dubai',
        'day-surgery-centre-setup-licensing-uae' => 'clinic-setup-licensing-uae',
    ];

    /** Slot 8: case studies shown on the page (RelatedContent::projects reads projects.service_ids). */
    private const CASE_STUDIES = [
        'clinic-setup-licensing-uae' => ['nhs-medical-center', 'broadway-vascular'],
        'medical-centre-setup-licensing-dubai' => ['nhs-medical-center', 'vfs-global', 'dna-health-corp'],
        'dental-centre-setup-licensing-dubai' => ['nhs-medical-center', 'broadway-vascular'],
        'day-surgery-centre-setup-licensing-uae' => ['broadway-vascular', 'bone-care', 'royal-spanish-center'],
    ];

    private const OLD_PROCESSES = ['Clinic setup in Dubai: five steps', 'Medical centre setup in Dubai: five phases'];

    private const SERVICE_COLUMNS = ['name', 'meta_title', 'meta_description', 'content', 'overview', 'info_one', 'info_two',
        'info_three', 'info_four', 'reviewer', 'reviewed_at', 'updated_date', 'project_process_id'];

    public function up(): void
    {
        $pages = json_decode(self::TEXT, true);
        $this->backup($pages);
        $now = now();

        // Images of the sister pages, read before any card changes.
        $borrow = [];
        foreach (self::IMAGE_FROM as $slug => $from) {
            $fromId = DB::table('services')->where('slug', $from)->value('id');
            $borrow[$slug] = $fromId
                ? DB::table('service_magazines')->where('service_id', $fromId)->orderBy('id')->pluck('image')->filter()->values()->all()
                : [];
        }

        foreach ($pages as $page) {
            $slug = $page['slug'];
            $id = DB::table('services')->where('slug', $slug)->value('id');
            if (!$id) {
                continue;
            }

            $row = $page['fields'] + ['name' => $page['name'], 'updated_at' => $now];
            if (isset(self::META[$slug])) {
                $row += [
                    'meta_title' => self::META[$slug][0],
                    'meta_description' => self::META[$slug][1],
                    'reviewer' => 'siraj-hm-ibrahim',
                    'reviewed_at' => '2026-10-31',
                    'updated_date' => $now->toDateString(),
                    'project_process_id' => null,
                ];
            }
            DB::table('services')->where('id', $id)->update($row);

            // Five cards in order: reuse the existing rows (and their images), add or remove the rest.
            $existing = DB::table('service_magazines')->where('service_id', $id)->orderBy('id')->get();
            foreach ($page['cards'] as $i => $card) {
                if (isset($existing[$i])) {
                    DB::table('service_magazines')->where('id', $existing[$i]->id)
                        ->update(['title' => $card['title'], 'description' => $card['description'], 'updated_at' => $now]);
                } else {
                    DB::table('service_magazines')->insert([
                        'service_id' => $id, 'title' => $card['title'], 'description' => $card['description'],
                        'image' => $borrow[$slug][$i] ?? null, 'created_at' => $now, 'updated_at' => $now,
                    ]);
                }
            }
            $extra = $existing->slice(count($page['cards']))->pluck('id')->all();
            if ($extra) {
                DB::table('service_magazines')->whereIn('id', $extra)->delete();
            }

            if ($page['faqs'] !== null) {
                DB::table('faqs')->where('service_id', $id)->delete();
                foreach ($page['faqs'] as $faq) {
                    DB::table('faqs')->insert(['service_id' => $id, 'faq_question' => $faq['question'],
                        'faq_answer' => $faq['answer'], 'created_at' => $now, 'updated_at' => $now]);
                }
            }

            foreach (self::CASE_STUDIES[$slug] ?? [] as $projectSlug) {
                $project = DB::table('projects')->where('slug', $projectSlug)->first(['id', 'service_ids']);
                if (!$project) {
                    continue;
                }
                $ids = array_map('strval', (array) json_decode((string) $project->service_ids, true));
                if (!in_array((string) $id, $ids, true)) {
                    $ids[] = (string) $id;
                    DB::table('projects')->where('id', $project->id)->update(['service_ids' => json_encode(array_values($ids))]);
                }
            }
        }

        // The first build of the clinic and medical centre pages (never deployed) used process cards.
        DB::table('project_processes')->whereIn('name', self::OLD_PROCESSES)->delete();
    }

    public function down(): void
    {
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        $saved = json_decode(Storage::disk('local')->get(self::BACKUP), true);
        foreach ($saved['services'] as $row) {
            $id = $row['service']['id'];
            DB::table('services')->where('id', $id)->update(array_intersect_key($row['service'], array_flip(self::SERVICE_COLUMNS)));
            DB::table('service_magazines')->where('service_id', $id)->delete();
            foreach ($row['magazines'] as $m) {
                DB::table('service_magazines')->insert($m);
            }
            DB::table('faqs')->where('service_id', $id)->delete();
            foreach ($row['faqs'] as $f) {
                DB::table('faqs')->insert($f);
            }
        }
        foreach ($saved['projects'] as $id => $serviceIds) {
            DB::table('projects')->where('id', $id)->update(['service_ids' => $serviceIds]);
        }
    }

    /** Saves the current state once (a re-run keeps the first, pre-change copy). */
    private function backup(array $pages): void
    {
        if (Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        $saved = ['services' => [], 'projects' => []];
        foreach ($pages as $page) {
            $service = DB::table('services')->where('slug', $page['slug'])->first();
            if (!$service) {
                continue;
            }
            $saved['services'][$page['slug']] = [
                'service' => array_intersect_key((array) $service, array_flip(array_merge(['id'], self::SERVICE_COLUMNS))),
                'magazines' => DB::table('service_magazines')->where('service_id', $service->id)->get()->map(fn ($r) => (array) $r)->all(),
                'faqs' => DB::table('faqs')->where('service_id', $service->id)->get()->map(fn ($r) => (array) $r)->all(),
            ];
        }
        $projectSlugs = array_unique(array_merge(...array_values(self::CASE_STUDIES)));
        foreach (DB::table('projects')->whereIn('slug', $projectSlugs)->get(['id', 'service_ids']) as $p) {
            $saved['projects'][$p->id] = $p->service_ids;
        }
        Storage::disk('local')->put(self::BACKUP, json_encode($saved, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /** Built from proposals/alphahmc-service-slots.mjs by the website terminal; the word and link check against each
     *  proposal's guard-checked html passed on all 7 pages (the slider heading is not built). */
    private const TEXT = <<<'JSON'
[
 {
  "slug": "clinic-setup-licensing-uae",
  "name": "Clinic Setup and Licensing in Dubai",
  "fields": {
   "content": "<p>Trade licence, DHA licensing and the final inspection, handled by one team.</p>",
   "overview": "<p>To set up a clinic in Dubai you need two licences: a trade licence for the business and a medical licence for the clinic. On the mainland, the trade licence comes from the Department of Economy and Tourism (DET); inside Dubai Healthcare City (DHCC), it comes from the DHCC Free Zone Authority. The medical licence comes from the <a href=\"https://www.dha.gov.ae/\">Dubai Health Authority (DHA)</a> in both.</p>",
   "info_one": "<h3>Mainland or Dubai Healthcare City</h3>\n<p>For a small clinic, the licensing project typically takes about 14 weeks; fit-out and authority steps can extend it.</p>",
   "info_two": "<table><thead><tr><th></th><th>Mainland</th><th>DHCC</th></tr></thead><tbody><tr><th scope=\"row\">Trade licence</th><td>DET</td><td>DHCC Free Zone Authority</td></tr><tr><th scope=\"row\">Medical licence (clinic and staff)</th><td>DHA</td><td>DHA</td></tr></tbody></table><p>The medical licence comes from DHA in both, so the choice is about the trade licence and the location. We help you choose in the first planning meeting, based on your specialties, patients and budget.</p><h3>Where projects lose time</h3><ul><li>Signing a lease before checking the layout. The space has to fit the rooms, access and services your specialties need. A unit that cannot fit them means a redesign or a new lease.</li><li>Starting fit-out early. Work built before DHA approves the drawings is often torn out and rebuilt.</li><li>Licensing staff late. Every doctor and nurse must be licensed individually before opening. We run <a href=\"https://alphahmc.com/services/dha-license-healthcare-professionals-dubai\">DHA professional licensing</a> alongside the facility application.</li></ul><p>Outside Dubai, a different authority licenses your clinic: the <a href=\"https://www.doh.gov.ae/\">Department of Health (DOH)</a> in Abu Dhabi, Sharjah Health Authority in Sharjah, and <a href=\"https://mohap.gov.ae/\">MOHAP</a> in the Northern Emirates. See <a href=\"https://alphahmc.com/services/doh-healthcare-facility-licensing-abu-dhabi\">healthcare facility licensing in Abu Dhabi</a>.</p><p>Opening several specialties under one licence? See <a href=\"https://alphahmc.com/services/medical-centre-setup-licensing-dubai\">medical centre and polyclinic setup</a>. Need the build as well? We deliver <a href=\"https://alphahmc.com/services/medical-facility-fit-out\">medical facility fit-out</a>.</p>",
   "info_three": "<h3>Plan your clinic with us</h3>\n<p>We have delivered or consulted on more than 250 healthcare facilities.</p>",
   "info_four": "<p>After a free scoping call, you get a fixed-fee proposal and a dated plan. Our fee does not include government fees, fit-out, medical equipment, IT systems or malpractice insurance.</p>"
  },
  "cards": [
   {
    "title": "01 Discovery and planning, weeks 1 to 2",
    "description": "<p>Choose mainland or DHCC, confirm the facility category for your specialties, and check the space against DHA requirements before you sign a lease.</p>"
   },
   {
    "title": "02 Trade licence and drawings, weeks 2 to 5",
    "description": "<p>Trade name and trade licence, the facility application to the <a href=\"https://www.dha.gov.ae/\">Dubai Health Authority</a>, and layout and engineering drawings for approval. Fit-out starts only after DHA approves the drawings.</p>"
   },
   {
    "title": "03 Documentation, weeks 5 to 10",
    "description": "<p>Policies and procedures, staff licensing and HR documents, equipment documentation and the mandatory service contracts your clinic needs to open.</p>"
   },
   {
    "title": "04 Inspection readiness, weeks 10 to 12",
    "description": "<p>A mock inspection against DHA standards and a gap list with owners and dates. The timing depends on how ready the site is.</p>"
   },
   {
    "title": "05 Final inspection and licence, weeks 12 to 14",
    "description": "<p>We attend the DHA inspection with your team and stay on the project until the facility licence is issued.</p>"
   }
  ],
  "faqs": [
   {
    "question": "How long does it take to open a clinic in Dubai?",
    "answer": "<p>For a small facility, the licensing project typically takes about 14 weeks from planning to the final DHA inspection. Fit-out and other authority steps can extend it, which is why we plan them from the first week.</p>"
   },
   {
    "question": "How much does it cost to open a clinic in Dubai?",
    "answer": "<p>The budget is made up of government fees, the fit-out, medical equipment, staff licensing and our fee. The size of the space and your specialties drive most of it. After a free scoping call, we give you a fixed-fee proposal and a list of the costs you pay directly.</p>"
   },
   {
    "question": "Can I open a clinic if I am not a doctor?",
    "answer": "<p>Yes. The owner does not need to be a doctor, but the clinic needs a licensed medical director and licensed clinical staff.</p>"
   },
   {
    "question": "Should I open on the mainland or in Dubai Healthcare City?",
    "answer": "<p>Choose by where your patients are and which trade licence suits your company: DET on the mainland or the DHCC Free Zone Authority in DHCC. The medical licence comes from DHA either way. We compare both for your project in the first meeting.</p>"
   },
   {
    "question": "Can I start the fit-out before DHA approval?",
    "answer": "<p>No. Start building only after DHA approves the layout and engineering drawings. Work done earlier often has to be redone.</p>"
   },
   {
    "question": "Can you take over a clinic project that stalled or failed inspection?",
    "answer": "<p>Yes. We review where the project stands, list what is missing and take it through to opening.</p>"
   }
  ]
 },
 {
  "slug": "medical-centre-setup-licensing-dubai",
  "name": "Medical Centre and Polyclinic Setup in Dubai",
  "fields": {
   "content": "<p>Category, layout, staff licensing and the DHA inspection, planned as one project.</p>",
   "overview": "<p>A medical centre or polyclinic brings several specialties under one facility licence, and the category you apply for decides the rooms, staff and building services the premises need. Alpha Health Group plans the category, the layout and the licensing together, with one team from the first drawing to the final inspection by the <a href=\"https://www.dha.gov.ae/\">Dubai Health Authority (DHA)</a>.</p>",
   "info_one": "<h3>What changes with several specialties</h3>\n<p>For investors and groups opening a multi-specialty centre, hospitals adding an outpatient centre, and practice owners growing into a polyclinic.</p>",
   "info_two": "<ul><li>The category comes first. DHA classifies outpatient facilities by the services they offer. We confirm the right category for your specialties before any drawing, because changing it later means new drawings.</li><li>Each specialty adds rooms and flows. Imaging, procedure rooms, sample collection and a pharmacy each change the layout. X-ray and other radiation equipment also need approval from the <a href=\"https://www.fanr.gov.ae/\">Federal Authority for Nuclear Regulation (FANR)</a>.</li><li>Building services carry more weight. Ventilation, power backup and, where your services need them, medical gases are planned with the layout, not after it.</li><li>More people to license at once. A licensed medical director and the doctors, nurses and allied staff for each specialty, all licensed before opening. We run <a href=\"https://alphahmc.com/services/dha-license-healthcare-professionals-dubai\">DHA professional licensing</a> in parallel.</li></ul><p>If your project has stalled, or your facility did not pass an authority audit, we can take it over from where it stands.</p><p>Opening a single specialty practice? See <a href=\"https://alphahmc.com/services/clinic-setup-licensing-uae\">clinic setup in Dubai</a>. Building or refitting the premises? See <a href=\"https://alphahmc.com/services/medical-facility-fit-out\">medical facility fit-out</a>, or browse <a href=\"https://alphahmc.com/service-category/medical-centers-polyclinics\">medical centres and polyclinics</a>.</p>",
   "info_three": "<h3>Start your medical centre plan</h3>\n<p>One team from the first drawing to the final DHA inspection.</p>",
   "info_four": "<p>After a free scoping call, you get a fixed-fee proposal and a dated plan. Our fee does not include government fees, fit-out, equipment, IT systems or malpractice insurance.</p>"
  },
  "cards": [
   {
    "title": "01 Strategy and service planning, weeks 1 to 2",
    "description": "<p>Your specialties, the facility category, the site and a gap analysis against DHA requirements.</p>"
   },
   {
    "title": "02 Applications and engineering approval, weeks 2 to 5",
    "description": "<p>Trade licence steps, the DHA facility application, layout and engineering drawings for approval and any FANR application.</p>"
   },
   {
    "title": "03 Documentation and staffing, weeks 5 to 10",
    "description": "<p>Policies and procedures for each department, staff licensing, equipment and biomedical files, and mandatory service contracts.</p>"
   },
   {
    "title": "04 Mock inspection, weeks 10 to 12",
    "description": "<p>A walk-through against DHA standards and a gap list with owners and dates. The timing depends on how ready the site is.</p>"
   },
   {
    "title": "05 Final inspection, weeks 12 to 14",
    "description": "<p>We attend the DHA inspection with your team through to the facility licence. These week ranges are typical for a small facility; a multi-specialty centre usually takes longer, so you get a dated plan after phase 1.</p>"
   }
  ],
  "faqs": [
   {
    "question": "How long does DHA licensing take for a multi-specialty centre?",
    "answer": "<p>It depends on the number of specialties, the fit-out and how fast staff licences come through. A small facility typically takes about 14 weeks; a multi-specialty centre usually takes longer. We give you a dated plan after the first phase.</p>"
   },
   {
    "question": "What is the difference between a medical centre and a polyclinic?",
    "answer": "<p>Both offer outpatient care in more than one specialty. The DHA category you apply for depends on the services you offer, and it sets the rooms, staff and building services required. We confirm the category with you in the first phase.</p>"
   },
   {
    "question": "What does it cost to set up a medical centre in Dubai?",
    "answer": "<p>The budget covers government fees, the fit-out, equipment for each specialty, staff licensing and our fee. The number of specialties and the size of the premises drive most of it. After a free scoping call, we give you a fixed-fee proposal and a list of the costs you pay directly.</p>"
   },
   {
    "question": "Can an investor who is not a doctor own one?",
    "answer": "<p>Yes. The owner does not need to be a doctor, but it needs a licensed medical director and licensed clinical staff.</p>"
   },
   {
    "question": "Can I add specialties to my existing clinic?",
    "answer": "<p>Yes. A new specialty changes your facility licence and often needs layout changes and new staff licences. See <a href=\"https://alphahmc.com/blog_single/how-to-successfully-expand-healthcare-services-and-specialties-in-the-uae\">how to expand healthcare services in the UAE</a>.</p>"
   },
   {
    "question": "Can I buy an existing centre instead?",
    "answer": "<p>Yes. Check its licence, inspection history and staff licences before you sign. Our <a href=\"https://alphahmc.com/services/healthcare-facility-acquisition-due-diligence\">acquisition due diligence</a> service covers this.</p>"
   }
  ]
 },
 {
  "slug": "dental-centre-setup-licensing-dubai",
  "name": "Dental Centre Setup and Licensing in Dubai",
  "fields": {
   "content": "<p>Layout, sterilisation, FANR approval and DHA licensing for your dental centre, with one team.</p>",
   "overview": "<p>Alpha Health Group plans, licenses and opens dental centres in Dubai with one team, from the first layout to the final inspection by the <a href=\"https://www.dha.gov.ae/\">Dubai Health Authority (DHA)</a>. For a small facility, the licensing project typically takes about 14 weeks; fit-out and authority steps can extend it.</p>",
   "info_one": "<h3>What makes a dental centre different</h3>\n<p>Most delays in a dental project start on the drawing board, so we plan your treatment rooms, sterilisation and X-ray around the specialties you want to offer before any fit-out begins.</p>",
   "info_two": "<h3>Who this is for</h3><p>Dentists opening their own practice, groups adding a dental centre, and investors who are not dentists. A non-dentist can own a dental centre in Dubai; the centre still needs a licensed medical director. If your project has stalled, or your facility did not pass an authority audit, we can take it over from where it stands.</p><ul><li>Rooms follow your specialties. General dentistry, orthodontics, implants and paediatric dentistry each change the number and type of treatment rooms. We map them against DHA requirements in the first phase, before any drawing.</li><li>Sterilisation has its own flow. Instruments move from dirty to clean without crossing paths, and the number of sterilisation areas depends on the size of the practice. Getting this wrong is a common reason drawings are sent back.</li><li>X-ray needs a separate approval. Dental X-ray and other radiation equipment need approval from the <a href=\"https://www.fanr.gov.ae/\">Federal Authority for Nuclear Regulation (FANR)</a>. We build the shielded room and the FANR application into the layout and the schedule from the start, so it does not hold up opening.</li><li>Staff to open. At minimum, a licensed medical director and a licensed nurse, with the dentists for the specialties you offer. We handle their DHA licensing alongside the facility licence.</li></ul><h3>Planning decides cost and time</h3><p>Poor planning causes hidden losses: usable clinical space lost to the wrong layout, higher construction cost, and drawings returned by the authority. Proper planning makes full use of the space and shortens the approval path. Our medical engineering team plans the layout, building services and equipment for your specialties first, and the licence follows.</p><p>Need the build as well? We also deliver <a href=\"https://alphahmc.com/services/healthcare-design-and-build\">design and build</a> and <a href=\"https://alphahmc.com/services/medical-facility-fit-out\">medical facility fit-out</a>. For other facility types, see <a href=\"https://alphahmc.com/service-category/facility-licensing-setup\">facility licensing and setup</a> and <a href=\"https://alphahmc.com/services/clinic-setup-licensing-uae\">clinic setup in Dubai</a>.</p>",
   "info_three": "<h3>Plan your project with us</h3>\n<p>We have delivered or consulted on more than 250 healthcare facilities.</p>",
   "info_four": "<p>After the first planning meeting, you get a fixed scope and fee for our part. Our fee does not include government fees, fit-out, dental equipment, IT systems or malpractice insurance.</p>"
  },
  "cards": [
   {
    "title": "01 Discovery and planning, weeks 1 to 2",
    "description": "<p>Your specialties, the site, a service map and a gap analysis against DHA requirements.</p>"
   },
   {
    "title": "02 Applications and engineering drawings, weeks 2 to 5",
    "description": "<p>Trade licence steps, the DHA facility application, layout and engineering drawings for approval, and the FANR radiation application.</p>"
   },
   {
    "title": "03 Documentation, weeks 5 to 10",
    "description": "<p>Policies and procedures, staff licensing and HR documents, equipment and biomedical documentation, and mandatory service contracts.</p>"
   },
   {
    "title": "04 Inspection readiness, weeks 10 to 12",
    "description": "<p>A mock inspection against DHA standards and a gap list with owners and dates. The timing depends on how ready the site is.</p>"
   },
   {
    "title": "05 Final inspection and licence, weeks 12 to 14",
    "description": "<p>We attend the DHA inspection with your team through to the facility licence.</p>"
   }
  ],
  "faqs": null
 },
 {
  "slug": "day-surgery-centre-setup-licensing-uae",
  "name": "Day Surgery Centre Setup and Licensing in the UAE",
  "fields": {
   "content": "<p>Theatres, building services, licensing and the final inspection, planned as one project.</p>",
   "overview": "<p>A day surgery centre is the hardest outpatient facility to get right on paper. Operating theatres, recovery, sterile and clean flows, medical gases, air handling and backup power all have to work together before the <a href=\"https://www.dha.gov.ae/\">Dubai Health Authority (DHA)</a> or the <a href=\"https://www.doh.gov.ae/\">Department of Health Abu Dhabi (DOH)</a> will license it.</p>",
   "info_one": "<h3>What sets a surgical centre apart</h3>\n<p>Alpha Health Group plans the theatres and building services with the layout from the first drawing, then carries the facility through licensing, documentation and the final inspection.</p>",
   "info_two": "<h3>Who this is for</h3><p>Surgeons and groups opening their own surgical centre, hospital groups adding an ambulatory site, investors entering surgical care, and owners of a centre whose licence has lapsed or whose expansion has stalled. We also take over projects that are part built or did not pass an authority audit.</p><ul><li>The number of operating theatres follows the classification. In Dubai, the DHA standards for standalone day surgery centres set how many operating theatres a centre needs for its category, so the classification is decided before the layout.</li><li>Theatre layout and workflow decide approval. Patient, staff, sterile supply and waste routes must not cross, and recovery has to sit next to the theatres. Operating theatre layout, workflow and the engineering drawings are where most day surgery projects run into trouble.</li><li>Building services are planned with the rooms. Medical gases, HVAC for the theatres and backup power are designed together with the layout, not added after it. Changing them late means reworking the fit-out.</li><li>Imaging needs its own approval. Theatres with X-ray or fluoroscopy need approval from <a href=\"https://www.fanr.gov.ae/\">FANR</a> for the radiation equipment, which we plan into the shielding and the schedule.</li></ul><p>Related: <a href=\"https://alphahmc.com/service-category/day-surgery-centers\">day surgery centre consulting</a> for centres already operating, and <a href=\"https://alphahmc.com/service-category/facility-licensing-setup\">facility licensing and setup</a> for other facility types.</p>",
   "info_three": "<h3>Plan your surgical centre</h3>\n<p>Timelines depend on the classification, the building and the fit-out, so we set yours after the first planning phase.</p>",
   "info_four": "<p>Our fee does not include government fees, construction and fit-out work, surgical and medical equipment, IT systems or malpractice insurance.</p>"
  },
  "cards": [
   {
    "title": "01 Classification and planning",
    "description": "<p>The specialties and procedures you will offer, the facility category, the number of theatres, and a gap analysis against the DHA or DOH standards.</p>"
   },
   {
    "title": "02 Engineering design and approval",
    "description": "<p>Theatre and recovery layout, medical gases, HVAC and power backup, equipment planning, and the drawings submitted for authority approval.</p>"
   },
   {
    "title": "03 Licensing and documentation",
    "description": "<p>The facility application, policies and procedures, staff licensing and credentialing, equipment and biomedical documentation, and mandatory service contracts.</p>"
   },
   {
    "title": "04 Inspection readiness",
    "description": "<p>A mock inspection against the authority standards, with every gap listed with an owner and a date.</p>"
   },
   {
    "title": "05 Final inspection and licence",
    "description": "<p>We attend the inspection with your team through to the facility licence, then stay on for operational management if you want us to.</p>"
   }
  ],
  "faqs": null
 },
 {
  "slug": "dha-license-healthcare-professionals-dubai",
  "name": "DHA License for Healthcare Professionals in Dubai",
  "fields": {
   "content": "<p>DHA licensing in Sheryan, from the eligibility check to your eligibility letter.</p>",
   "overview": "<p>To practise in Dubai you need a license from the Dubai Health Authority (DHA). We check your eligibility against the PQR, prepare your documents, follow your DataFlow verification and exam, and submit your application in Sheryan.</p>",
   "info_one": "<h3>Who needs a DHA license</h3>\n<p>Every physician, dentist, nurse, midwife, pharmacist and allied health professional who will work at a facility licensed by the Dubai Health Authority.</p>",
   "info_two": "<p>A license from DOH or MOHAP does not cover Dubai. Working in Abu Dhabi or the Northern Emirates instead? See <a href=\"/service-packages/healthcare-professional-licensing-uae\">which UAE license you need</a>. Official requirements: <a href=\"https://www.dha.gov.ae\">Dubai Health Authority</a>.</p><h3>How DHA licensing works</h3><p>DHA licensing runs through Sheryan, the DHA licensing system, in six steps.</p><ul><li>Registration. You open your application in Sheryan.</li><li>Credential evaluation. DHA evaluates your qualifications and experience against the Unified Healthcare Professional Qualification Requirements (PQR) for your title.</li><li>DataFlow verification. DataFlow verifies your documents directly with the institutions that issued them.</li><li>Exam or exemption. Most titles sit the DHA licensing exam at a Prometric centre.</li><li>Eligibility. DHA issues your registration, often called the eligibility letter. It is valid for one year.</li><li>Activation. Your license becomes active when a DHA-licensed facility employs you and <a href=\"/services/activate-your-uae-healthcare-license\">activates it</a>.</li></ul><h3>Experience you need</h3><p>The PQR sets the minimum for each title. For common titles:</p><ul><li>General Practitioner: a one-year internship and two years of clinical experience</li><li>General Dentist: an internship and two years of experience</li><li>Registered Nurse: a bachelor's degree in nursing, with no minimum experience</li><li>Pharmacist: two years after qualifying; experience with pharmaceutical companies or in sales does not count</li></ul><p>For Specialist and Consultant titles, the experience required depends on the PQR tier of the country where you trained. Most allied health titles need one to four years.</p><h3>The DHA exam</h3><p>Depending on your title, the assessment can be written, oral or an OSCE, and it is held in English. The PQR allows three attempts across the UAE health authorities. After three failed attempts, you have one final attempt with a different authority.</p><h3>Who is exempt from the exam</h3><p>The PQR exempts UAE nationals and holders of listed international qualifications or registrations, such as UK and US boards, PLAB, USMLE or NCLEX. If you already hold a valid DOH, MOHAP or SHA license, you can apply to DHA for the same title with an exam exemption, with a good standing certificate and a full DataFlow report. Exemptions do not apply after more than two years out of practice.</p><h3>Time out of practice</h3><p>A break of more than three years means completing CME credits and a period of training in an approved setting before you can be licensed, and you still sit the exam. Non-UAE nationals cannot apply after a break of more than five years.</p><h3>Keeping your license</h3><p>Every year you complete continuing education in your specialty: 40 CME credits for physicians and dentists, 20 for nurses and pharmacists, and 10 for allied health professionals. Physicians and dentists in surgical specialties also submit a surgical logbook every two years for renewal.</p><p>Already licensed in another emirate? See <a href=\"/services/healthcare-professional-license-transfer\">license transfers between authorities</a>.</p>",
   "info_three": "<h3>Check your DHA route</h3>\n<p>Send us your title, qualification, years of experience and the country where you trained.</p>",
   "info_four": "<p>We will confirm your route, whether you are exempt from the exam, and what to do next. Your first consultation is free; the licensing process itself is a paid service.</p>"
  },
  "cards": [
   {
    "title": "Eligibility check against the PQR",
    "description": "<p>We check your title against the PQR and, for Specialist and Consultant titles, the tier of the country where you trained. You know your route, and whether you are exempt from the exam, before you apply.</p>"
   },
   {
    "title": "Documents checked before submission",
    "description": "<p>We prepare your documents and check them before submission. The good standing certificate comes from the regulator in the country where you last worked and must be no older than six months, and names and dates must match across all documents.</p>"
   },
   {
    "title": "DataFlow verification",
    "description": "<p>We open and follow your DataFlow verification of your educational qualifications, experience certificates, professional licenses and good standing certificate. Verification is one of the main factors in how long your application takes.</p>"
   },
   {
    "title": "Exam guidance and Prometric booking",
    "description": "<p>We guide you on the exam for your title and on your Prometric booking. Most applications take 6 to 8 weeks, depending mainly on DataFlow, the next available exam date and how complete your documents are.</p>"
   },
   {
    "title": "Sheryan application and follow-up",
    "description": "<p>We submit your application in Sheryan and follow it up until your eligibility letter is issued. You pay the authority fees, the DataFlow fee and the exam fee separately.</p>"
   }
  ],
  "faqs": null
 },
 {
  "slug": "doh-license-healthcare-professionals-abu-dhabi",
  "name": "DOH License for Healthcare Professionals in Abu Dhabi",
  "fields": {
   "content": "<p>Department of Health licensing on TAMM, from the eligibility review to an active license.</p>",
   "overview": "<p>Practising in Abu Dhabi, Al Ain or Al Dhafra takes a license from the Department of Health Abu Dhabi (DOH). We check your title against the PQR, prepare your documents for DataFlow, guide you through the Pearson VUE exam and file your application on TAMM.</p>",
   "info_one": "<h3>Who needs a DOH license</h3>\n<p>Physicians, dentists, nurses, midwives, pharmacists and allied health professionals who will practise anywhere in the Emirate of Abu Dhabi.</p>",
   "info_two": "<p>The license is issued by the <a href=\"https://www.doh.gov.ae\">Department of Health Abu Dhabi</a>; a DHA or MOHAP license does not cover Abu Dhabi. Not sure which authority applies to you? <a href=\"/service-packages/healthcare-professional-licensing-uae\">Compare DHA, DOH and MOHAP</a>.</p><h3>How DOH licensing works</h3><p>You apply for a DOH license through TAMM, the Abu Dhabi government services platform.</p><ul><li>Apply on TAMM. You submit your application for your professional title.</li><li>Assessment against the PQR. DOH checks your qualifications and experience against the Unified Healthcare Professional Qualification Requirements.</li><li>Primary source verification. DataFlow confirms your degrees, experience, licenses and good standing with the institutions that issued them.</li><li>Pearson VUE exam. If your title requires it, you book the DOH licensing exam with Pearson VUE from your TAMM account.</li><li>Eligibility. DOH confirms you are eligible for the title.</li><li>Activation. A DOH-licensed facility in Abu Dhabi employs you and <a href=\"/services/activate-your-uae-healthcare-license\">activates your license</a>.</li></ul><h3>Minimum experience for common titles</h3><ul><li>General Practitioner: the internship year, then two years in clinical practice</li><li>General Dentist: the internship, then two years of practice</li><li>Registered Nurse: a bachelor's degree in nursing; no minimum experience</li><li>Pharmacist: two years after qualifying, not counting work for pharmaceutical companies or in sales</li></ul><p>Specialist and Consultant requirements depend on the PQR tier of the country where you trained. Physicians trained in Tier 3 countries cannot hold the Consultant title.</p><h3>Exam exemptions</h3><p>You may not need the exam if you are a UAE national, if you hold a qualification or registration on the PQR's lists, such as US or UK board certification, PLAB, USMLE or NCLEX, or if you already hold a valid DHA, MOHAP or SHA license for the same title with a good standing certificate and a full DataFlow report. No exemption applies after more than two years out of practice.</p><h3>Moving from DHA or MOHAP</h3><p>Apply to DOH for a transfer on TAMM while your current license is still valid. Your DataFlow report is transferred to DOH, which may ask for more verification, and specialist titles also need your residency programme documents. Your Abu Dhabi employer then activates the license. Most transfers take 4 to 6 weeks. See <a href=\"/services/healthcare-professional-license-transfer\">license transfers between authorities</a>.</p><h3>Good standing and time out of practice</h3><p>Your good standing certificate must come from the regulator where you last practised and be no more than six months old when you apply. After more than three years out of practice, the PQR requires CME credits and a training period in an approved setting. Non-UAE nationals cannot apply after more than five years away.</p><h3>Continuing education</h3><p>Licensed professionals need CME credits in their specialty each year: 40 for physicians and dentists, 20 for nurses and pharmacists, and 10 for allied health. Surgical specialists also submit a logbook every two years for renewal.</p>",
   "info_three": "<h3>Check your DOH route</h3>\n<p>Send us your title, qualification, years of experience and the country where you trained.</p>",
   "info_four": "<p>We will confirm your route, whether you are exempt from the exam, and your next step. Authority, DataFlow and Pearson VUE fees are paid to them separately. Your first consultation with us is free; our licensing service is paid.</p>"
  },
  "cards": [
   {
    "title": "Title check against the PQR",
    "description": "<p>We check your title against the PQR and, for specialists, the tier of the country where you trained. You know your route, and whether you are exempt from the exam, before you apply.</p>"
   },
   {
    "title": "Documents ready for DataFlow and TAMM",
    "description": "<p>We prepare and check your documents before DataFlow and TAMM, including a good standing certificate from the regulator where you last practised, no more than six months old when you apply.</p>"
   },
   {
    "title": "DataFlow verification",
    "description": "<p>We open and follow your DataFlow verification, which confirms your degrees, experience, licenses and good standing with the institutions that issued them.</p>"
   },
   {
    "title": "Pearson VUE exam guidance",
    "description": "<p>Pearson VUE delivers the exam at test centres in many countries, booked from your TAMM account. The format depends on your title and may be written, oral or an OSCE, in English. We guide you on the exam and the booking.</p>"
   },
   {
    "title": "TAMM application and follow-up",
    "description": "<p>We file your application on TAMM and follow it up. Most applications take 6 to 8 weeks, depending on DataFlow, exam availability and your documents.</p>"
   }
  ],
  "faqs": null
 },
 {
  "slug": "mohap-license-healthcare-professionals",
  "name": "MOHAP License for Healthcare Professionals in the UAE",
  "fields": {
   "content": "<p>Licensing for the Northern Emirates, from the eligibility review to an active license.</p>",
   "overview": "<p>Practising in Ajman, Umm Al Quwain, Ras Al Khaimah or Fujairah takes a license from the Ministry of Health and Prevention (MOHAP). We check your title against the PQR, prepare your documents for DataFlow, guide you through the Prometric exam and file your application on the MOHAP portal.</p>",
   "info_one": "<h3>Who needs a MOHAP license</h3>\n<p>Physicians, dentists, nurses, midwives, pharmacists and allied health professionals who will practise in Ajman, Umm Al Quwain, Ras Al Khaimah or Fujairah.</p>",
   "info_two": "<p>The license is issued by the <a href=\"https://mohap.gov.ae\">Ministry of Health and Prevention</a>. Dubai (DHA), Abu Dhabi (DOH) and Sharjah (SHA) license their own healthcare professionals, so a MOHAP license does not cover them. Not sure which authority applies to you? <a href=\"/service-packages/healthcare-professional-licensing-uae\">Compare DHA, DOH and MOHAP</a>.</p><h3>How MOHAP licensing works</h3><p>You apply for a MOHAP license on the MOHAP portal, through the <a href=\"https://mohap.gov.ae/en/w/licensing-or-re-licensing-of-health-professional\">licensing or re-licensing of health professional</a> service.</p><ul><li>Apply on the MOHAP portal. You submit your application for your professional title.</li><li>Assessment against the PQR. MOHAP checks your qualifications and experience against the Unified Healthcare Professional Qualification Requirements.</li><li>Primary source verification. DataFlow confirms your degrees, experience, licenses and good standing with the institutions that issued them.</li><li>Prometric exam. If your title requires it, you sit the MOHAP licensing exam with Prometric.</li><li>Eligibility. MOHAP confirms you are eligible for the title.</li><li>Activation. A MOHAP-licensed facility employs you and <a href=\"/services/activate-your-uae-healthcare-license\">activates your license</a>.</li></ul><h3>Minimum experience for common titles</h3><ul><li>General Practitioner: the internship year, then two years in clinical practice</li><li>General Dentist: the internship, then two years of practice</li><li>Registered Nurse: a bachelor's degree in nursing; no minimum experience</li><li>Pharmacist: two years after qualifying, not counting work for pharmaceutical companies or in sales</li></ul><p>Specialist and Consultant requirements depend on the PQR tier of the country where you trained. Physicians trained in Tier 3 countries cannot hold the Consultant title.</p><h3>Exam exemptions</h3><p>You may not need the exam if you are a UAE national, if you hold a qualification or registration on the PQR's lists, such as US or UK board certification, PLAB, USMLE or NCLEX, or if you already hold a valid DHA, DOH or SHA license for the same title with a good standing certificate and a full DataFlow report. No exemption applies after more than two years out of practice.</p><h3>Moving from DHA or DOH</h3><p>Apply to MOHAP for a transfer while your current license is still valid. Your DataFlow report is transferred to MOHAP, which may ask for more verification. Your new employer then activates the license. Most transfers take 3 to 5 weeks. See <a href=\"/services/healthcare-professional-license-transfer\">license transfers between authorities</a>.</p><h3>Good standing and time out of practice</h3><p>Your good standing certificate must come from the regulator where you last practised and be no more than six months old when you apply. After more than three years out of practice, the PQR requires CME credits and a training period in an approved setting. Non-UAE nationals cannot apply after more than five years away.</p><h3>Continuing education</h3><p>Licensed professionals need CME credits in their specialty each year: 40 for physicians and dentists, 20 for nurses and pharmacists, and 10 for allied health. Surgical specialists also submit a logbook every two years for renewal.</p>",
   "info_three": "<h3>Check your MOHAP route</h3>\n<p>Send us your title, qualification, years of experience and the country where you trained.</p>",
   "info_four": "<p>We will confirm your route, whether you are exempt from the exam, and your next step. Authority, DataFlow and Prometric fees are paid to them separately. Your first consultation with us is free; our licensing service is paid.</p>"
  },
  "cards": [
   {
    "title": "Title check against the PQR",
    "description": "<p>We check your title against the PQR and, for specialists, the tier of the country where you trained. You know your route, and whether you are exempt from the exam, before you apply.</p>"
   },
   {
    "title": "Documents ready for DataFlow",
    "description": "<p>We prepare and check your documents before DataFlow and the portal application, including a good standing certificate from the regulator where you last practised, no more than six months old when you apply.</p>"
   },
   {
    "title": "DataFlow verification",
    "description": "<p>We open and follow your DataFlow verification, which confirms your degrees, experience, licenses and good standing with the institutions that issued them.</p>"
   },
   {
    "title": "Prometric exam guidance",
    "description": "<p>Prometric delivers the licensing exam. The format depends on your title and may be written, oral or an OSCE, in English. The PQR gives you three attempts in total across the UAE authorities. We guide you on the exam.</p>"
   },
   {
    "title": "Portal application and follow-up",
    "description": "<p>We file your application on the portal and follow it up. Most applications take 5 to 8 weeks, depending on DataFlow, exam availability and your documents.</p>"
   }
  ],
  "faqs": null
 }
]
JSON;
};
