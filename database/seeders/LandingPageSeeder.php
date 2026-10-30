<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use App\Models\LandingPageRevision;
use App\Models\Project;
use App\Support\LandingPageSchema;
use Illuminate\Database\Seeder;

/**
 * Seeds the facility setup landing pages from the approved prototype (version 5, Saroth 30 Oct 2026):
 * D:/Gads-agent/docs/facility-landing-prototype.html. Copy is word for word; the per-type parts come
 * from the prototype's T object and setType(). Dental centre is published, every other page is a draft.
 * Sharjah and Abu Dhabi swap the Dubai authorities (brief section "Sharjah and Abu Dhabi"); their
 * authority facts and FAQ answers wait for the facility team's confirmation before publishing.
 *
 * Idempotent per slug: an existing page is left alone, so admin edits are never overwritten.
 */
class LandingPageSeeder extends Seeder
{
    private const DUBAI = [
        // slug => [facility_type, name in copy, intro, facts]
        'dental-centre-dubai' => ['Dental centre', 'dental centre',
            'One team plans your layout around your specialties, secures your DHA license, prepares your documentation and handles the final inspection for you.',
            ['Treatment rooms and sterilisation areas are planned around the specialties you offer.', 'X-ray equipment needs FANR approval. We build it into the layout and the schedule from the start.']],
        'medical-centre-dubai' => ['Medical centre', 'medical centre',
            'One team plans your layout around your specialties, secures your DHA license, prepares your documentation and handles the final inspection for you.',
            ['Rooms, areas and staffing follow the specialties you choose. We map them before any drawing starts.', 'Adding a specialty later is easier when the first layout leaves room for it.']],
        'aesthetic-centre-dubai' => ['Aesthetic centre', 'aesthetic centre',
            'One team plans your clinical aesthetic centre, secures the license, prepares your documentation and handles the final inspection for you.',
            ['A medical aesthetic centre offers clinical aesthetic services, licensed as a healthcare facility.', 'Injectables are performed by a licensed medical practitioner. Rooms are planned by specialty.']],
        'home-healthcare-dubai' => ['Home healthcare', 'home healthcare company',
            'One team sets up your office, secures your DHA license, prepares your documentation and handles the final inspection for you.',
            ['A home healthcare company needs an office in Dubai.', 'Planning covers your vehicles, equipment and how medication is handled.']],
        'clinical-laboratory-dubai' => ['Clinical laboratory', 'clinical laboratory',
            'One team plans your laboratory around the tests you will offer, secures your DHA license, prepares your documentation and handles the final inspection for you.',
            ['Areas and workflow are planned around the scope of tests you will offer.', 'One team carries the project from planning to the final inspection.']],
        'dental-laboratory-dubai' => ['Dental laboratory', 'dental laboratory',
            'One team plans your dental laboratory, secures your DHA license, prepares your documentation and handles the final inspection for you.',
            ['Dental laboratories in Dubai are licensed by DHA.', 'Fit-out and Civil Defence approval usually take the longest. We plan for both from the start.']],
        'day-surgery-centre-dubai' => ['Day surgery centre', 'day surgery centre',
            'One team plans your theatres and patient flow, takes your engineering drawings through approval, prepares your documentation and handles the final inspection for you.',
            ['The DHA classification of your centre sets how many operating theatres you need.', 'Theatre layout, patient flow and engineering drawings are the main challenge. Medical gases, HVAC and power backup are planned with the layout.']],
    ];

    /** slug => [emirate, health authority, economic department] */
    private const EMIRATE_PAGES = [
        'medical-facility-sharjah'   => ['Sharjah', 'SHA', 'SEDD'],
        'medical-facility-abu-dhabi' => ['Abu Dhabi', 'DOH', 'ADDED'],
    ];

    public function run(): void
    {
        $projects = Project::whereIn('slug', ['nhs-medical-center', 'broadway-vascular'])->pluck('id', 'slug');

        foreach (self::DUBAI as $slug => [$type, $name, $intro, $facts]) {
            $this->seedPage($slug, [
                'name'             => $type . ', Dubai',
                'status'           => 'published',   // all published since 30 Oct (Saroth)
                'facility_type'    => $type,
                'emirate'          => 'Dubai',
                'seo_title'        => ucwords($type) . ' Setup and Licensing in Dubai',
                'meta_description' => 'Open your ' . $name . ' in Dubai with one team for layout, licensing and the final inspection. Fixed fee proposal after a free scoping call.',
                'content'          => $this->content($name, 'Dubai', $intro, $facts, ['DHA', 'DED'], 'DHA', 'DED', true, $projects),
            ]);
        }

        // Every Dubai page links to the seven Dubai pages, in the prototype's chip order.
        $dubaiIds = LandingPage::whereIn('slug', array_keys(self::DUBAI))->get()->sortBy(
            fn ($p) => array_search($p->slug, array_keys(self::DUBAI))
        )->pluck('id')->values()->all();
        LandingPage::whereIn('slug', array_keys(self::DUBAI))->whereNull('related_ids')->update(['related_ids' => json_encode($dubaiIds)]);

        foreach (self::EMIRATE_PAGES as $slug => [$emirate, $authority, $economic]) {
            $intro = 'One team plans your layout around your specialties, secures your ' . $authority
                . ' license, prepares your documentation and handles the final inspection for you.';
            // Not authority specific; the facility team confirms the facts and FAQ before publishing.
            $facts = ['Rooms, areas and staffing follow the specialties you choose. We map them before any drawing starts.',
                'Adding a specialty later is easier when the first layout leaves room for it.'];
            $this->seedPage($slug, [
                'name'             => 'Medical facility, ' . $emirate,
                'status'           => 'published',
                'facility_type'    => null,
                'emirate'          => $emirate,
                'seo_title'        => 'Medical Facility Setup and Licensing in ' . $emirate,
                'meta_description' => 'Open your medical facility in ' . $emirate . ' with one team for layout, ' . $authority . ' licensing and the final inspection. Fixed fee proposal after a free scoping call.',
                'content'          => $this->content('medical facility', $emirate, $intro, $facts, [$authority, $economic], $authority, $economic, false, $projects),
            ]);
        }
    }

    private function seedPage(string $slug, array $attributes): void
    {
        if (LandingPage::where('slug', $slug)->exists()) {
            return;
        }

        $page = LandingPage::create($attributes + [
            'slug'           => $slug,
            'lead_type'      => 'facility',
            'indexable'      => false,   // ads landing pages stay out of search (SEO brief 30 Oct)
            'hero_image'     => 'uploads/landing_pages/nhs-medical-center-hero.jpg',
            'hero_image_alt' => 'NHS Medical Center in Dubai, a facility Alpha delivered from planning to license',
            'published_at'   => ($attributes['status'] ?? '') === 'published' ? now() : null,
        ]);

        LandingPageRevision::create([
            'landing_page_id' => $page->id,
            'user_name'       => 'Seeded from prototype v5',
            'note'            => 'Seeded from the approved prototype',
            'snapshot'        => $page->fresh()->snapshot(),
        ]);
    }

    /**
     * @param string $name      facility name as used in copy ("dental centre")
     * @param bool   $dubai     Dubai pages get the facility links; the others a facilities list
     */
    private function content(string $name, string $emirate, string $intro, array $facts, array $authorities,
                             string $authority, string $economic, bool $dubai, $projects): array
    {
        $type = ucfirst($name);
        $notDoctor = in_array($name, ['dental centre', 'dental laboratory'], true) ? 'dentist' : 'doctor';
        $aName = (preg_match('/^[aeio]/i', $name) ? 'an ' : 'a ') . $name;   // "an aesthetic centre"

        $faq = [
            ['question' => "How long does it take to open $aName?",
             'answer'   => 'It depends on your facility, the fit-out and authority steps such as FANR approval for X-ray equipment. You get a dated project plan after the free scoping call.'],
            ['question' => "What does it cost to set up $aName?",
             'answer'   => 'Your budget covers government fees, fit-out, equipment, staff licensing and our fee. Every facility is different, so we give you a fixed fee proposal after a free scoping call.'],
            ['question' => "Can I open $aName if I am not a $notDoctor?",
             'answer'   => 'Yes. An investor can own the facility; it needs a licensed medical director, and we help you appoint one.'],
        ];
        $faq[] = ['question' => 'Can you take over a project that is half finished or failed its audit?',
            'answer' => 'Yes, both. We review the current state, close the gaps and carry the project to the final license.'];
        $faq[] = ['question' => "Can I buy an existing $name instead?",
            'answer' => 'Yes. Our acquisition due diligence checks the license, the building and the inspection history before you sign.'];

        $phase = fn ($t, $p, $s, $e) => ['title' => $t, 'text' => $p, 'start' => $s, 'end' => $e];
        $row   = fn ($g, $t, $x) => ['group' => $g, 'title' => $t, 'text' => $x];
        $two   = fn ($t, $x) => ['title' => $t, 'text' => $x];

        $sections = [
            ['key' => 'authorities', 'enabled' => false, 'data' => [   // off since 30 Oct (Saroth); copy kept
                'label' => 'We license facilities with',
                'items' => array_map(fn ($a) => ['name' => $a], $authorities),
            ]],
            ['key' => 'thesis', 'enabled' => true, 'data' => [
                'quote_before' => 'Most delays start on the drawing board.', 'quote_highlight' => 'Plan the facility right once',
                'quote_after' => ', and the license follows.',
                'items' => [
                    $two('Poor planning', 'Clinical space lost to the wrong layout, higher construction cost, and drawings sent back by the authority.'),
                    $two('Proper planning', 'Every square metre used, rooms placed for your specialties, and a shorter path to approval.'),
                    $two('One team', 'Planning, licensing, documentation and the final inspection with the same people, from the first meeting to the license.'),
                ],
            ]],
            ['key' => 'beyond', 'enabled' => true, 'data' => [
                'heading' => "Beyond $authority approval. Built for long-term success.",
                'text' => 'Passing ' . ($authority === 'SHA' ? 'an' : 'a') . " $authority inspection is only part of preparing your facility. We understand your operational needs, business model and long-term plans, then help you plan and set up a compliant facility that is ready to operate.",
                'list_label' => 'Our in-house teams work on your project',
                'items' => [
                    $two('Engineering', "Layouts and engineering drawings prepared to the authority's guidelines."),
                    $two('Biomedical engineering', 'Equipment planning, specifications and installation checks for your specialties.'),
                    $two('Quality and regulations', 'Policies, procedures and forms, and your team ready for the inspection.'),
                    $two('Project management', 'One plan, one point of contact, and progress reported at every phase.'),
                ],
            ]],
            ['key' => 'planning', 'enabled' => true, 'data' => [
                'nav_label' => 'Planning',
                'heading' => 'The same floor, planned twice.',
                'text' => "Rooms, areas and staffing depend on the specialties you offer. Alpha Medical Engineering maps them against the authority's requirements at the start, before any drawing or fit-out. Drag to compare.",
                'tag_before' => 'Without planning', 'tag_after' => 'Planned by Alpha',
                'note' => 'Illustration of one floor area laid out two ways. Your layout depends on your specialties and site.',
                'legend_rooms' => 'Clinical rooms', 'legend_lost' => 'Lost space',
                'facts_heading' => "What shapes $aName",
                'facts' => array_map(fn ($f) => ['text' => $f], $facts),
            ]],
            ['key' => 'scope', 'enabled' => true, 'data' => [
                'nav_label' => 'What we do',
                'heading' => 'Everything between the idea and the license.',
                'text' => 'You deal with one team instead of a license agent, a designer and a documentation consultant.',
                'image_alt' => 'Alpha engineers reviewing a facility layout drawing',
                'optional' => '',   // replaced by the turnkey section (Saroth, 30 Oct)
                'rows' => [
                    $row('Plan and design', 'Strategic planning', 'Project master plan, service mapping by specialty and a gap analysis before you commit.'),
                    $row('Plan and design', 'Design and engineering approval', 'Space programming, layout drawings according to authority guidelines, submission and approval.'),
                    $row('Plan and design', 'Medical equipment planning', 'Equipment lists by specialty, consumables, crash cart and biomedical documentation.'),
                    $row('License and staff', 'Commercial trade license', "$economic or free zone entity, memorandum of association and security clearance."),
                    $row('License and staff', 'Facility license', 'Registration with the health authority through to the final facility license.'),
                    $row('License and staff', 'Professional licensing and HR', 'Staff licensing, activation and transfer, medical director, HR policies and files.'),
                    $row('License and staff', 'Vendor contracts', 'The third party services the authority requires, sourced and contracted.'),
                    $row('Prepare and open', 'Quality documentation', 'Policies, procedures, forms, clinical guidelines and staff orientation.'),
                    $row('Prepare and open', 'Mock audit', 'An inspection run the way the authority runs it, with a corrective action plan.'),
                    $row('Prepare and open', 'Final inspection', 'We request the audit, prepare your facility for it, and attend and handle the full inspection as your consultant.'),
                ],
            ]],
            ['key' => 'turnkey', 'enabled' => true, 'data' => [
                'label' => 'Optional services',
                'heading' => 'Make it turnkey.',
                'text' => 'Add any of these and one team takes you from the first drawing to your first months of operation.',
                'note' => '*Terms and conditions apply.',
                'items' => [
                    $two('Construction phase engineering', 'Design package, BOQ, contractor appointment, NOCs and site supervision.'),
                    $two('Construction and fit-out', 'We build and fit out your facility to the approved design.'),
                    $two('Staff hiring', 'Recruitment of your clinical and support team, ready for licensing.'),
                    $two('Soft launch', 'A controlled first opening to test your workflows, systems and team before full operation.'),
                    $two('Three months of operational support, free*', 'After your license is issued we support you for three months at no extra cost, so the immediate post-licensing requirements are handled and your first stages run smoothly.'),
                ],
            ]],
            ['key' => 'plan', 'enabled' => true, 'data' => [
                'nav_label' => 'Project plan',
                'heading' => 'Your project plan with Alpha.',
                'text' => 'Five phases from the first meeting to your license. Fit-out and authority steps set the timing, and we tell you where your project stands at every phase. Select a phase.',
                'foot_note' => 'Typical plan for a small facility. Larger facilities and day surgery centres get their own plan.',
                'foot_link' => 'Get your project plan',
                'phases' => [
                    $phase('Discovery and strategic planning', 'Site assessment, service mapping by specialty, layout planning, the project master plan and a gap analysis with its risk list.', 0, 2),
                    $phase('Applications and engineering drawings', 'Trade license and initial approval, the facility application to the authority, engineering drawings to guidelines, and professional licensing begins.', 1, 5),
                    $phase('Quality documentation', 'Policies, procedures and forms for your specialties, equipment planning, HR documentation and checks that the build matches the approved design.', 4, 10),
                    $phase('Inspection readiness and mock audit', 'Staff orientation, site visits and a mock audit run the way the authority runs it. Depends on your site being ready.', 9, 12),
                    $phase('Final inspection and license', 'We request the inspection, attend and handle the full audit as your consultant, close any findings and see the license issued.', 11, 14),
                ],
            ]],
            ['key' => 'others', 'enabled' => true, 'data' => [
                'heading' => 'Open another facility with Alpha.',
                'text' => 'Other facility types we set up, from planning to the final license.',
            ]],
            ['key' => 'excluded', 'enabled' => false, 'data' => [   // replaced by "others" (Saroth, 30 Oct)
                'heading' => 'What is not in our fee.',
                'text' => 'So you can budget with no surprises.',
                'items' => array_map(fn ($t) => ['text' => $t], [
                    "Government fees: health authority, $economic, Dataflow, Civil Defence and Municipality",
                    'Construction work and fit-out',
                    'Medical equipment, consumables and medication',
                    'IT systems and the electronic medical record',
                    'Malpractice insurance',
                    'Salaries of the staff you hire',
                ]),
            ]],
            ['key' => 'projects', 'enabled' => true, 'data' => [
                'nav_label' => 'Projects',
                'heading' => 'Facilities we have delivered.',
                'see_all_label' => 'See all projects',
                'card_link' => 'Read the case study',
                'items' => [
                    ['project_id' => (int) ($projects['nhs-medical-center'] ?? 0), 'title' => 'NHS Medical Center',
                     'type_label' => 'Medical complex, Dubai', 'summary' => 'A visa and occupational screening complex, developed end to end from planning to operation.',
                     'image_alt' => 'NHS Medical Center building in Dubai'],
                    ['project_id' => (int) ($projects['broadway-vascular'] ?? 0), 'title' => 'Broadway Vascular Surgery Center',
                     'type_label' => 'Surgical facility', 'summary' => 'A specialised vascular surgery facility, delivered turnkey.',
                     'image_alt' => 'Broadway Vascular Surgery Center building'],
                ],
            ]],
            ['key' => 'rescue', 'enabled' => true, 'data' => [
                'heading' => 'Started with someone else, or failed an audit?',
                'text' => 'We take over half finished projects and facilities that failed their authority audit. We review where it stands, fix the gaps and carry it to the license.',
            ]],
            ['key' => 'faq', 'enabled' => true, 'data' => [
                'nav_label' => 'Questions', 'heading' => 'Questions before you start.', 'items' => $faq,
            ]],
            // Per facility type FAQ, case studies and the related services list: migration 2026_10_31_200100.
            ['key' => 'services', 'enabled' => true, 'data' => ['heading' => 'Related services', 'text' => '', 'items' => []]],
            ['key' => 'contact', 'enabled' => true, 'data' => [
                'heading' => 'Talk to the facility team.',
                'text' => 'Tell us what you plan to open. After a free scoping call you get a fixed fee proposal and a schedule.',
                'wa_label' => 'WhatsApp, facility team', 'wa_display' => '+971 50 780 7163',
                'call_label' => 'Call, Dubai office',
                'form_heading' => 'Request a proposal',
                'submit_label' => 'Request my proposal',
                'success_text' => 'Thank you. The facility team has your request and will contact you within one working day.',
                'fine_print' => 'We reply within one working day.',
            ]],
        ];

        return LandingPageSchema::normalize([
            'page' => [
                'phone_display' => '+971 4 272 4064', 'phone_tel' => '+97142724064',
                'wa_number' => '971507807163', 'wa_text' => "Hi, I would like to discuss $name setup.",
                'wa_header' => 'WhatsApp us', 'dock_wa' => 'WhatsApp', 'dock_proposal' => 'Request a proposal',
            ],
            'hero' => [
                'crumb_parent' => 'Facility setup and licensing', 'crumb_current' => "$type, $emirate",
                'h1_before' => 'Open your', 'h1_highlight' => $name, 'h1_after' => "in $emirate, planned right from the first drawing.",
                'intro' => $intro,
                'wa_button' => 'Plan my facility on WhatsApp', 'proposal_button' => 'Request a proposal',
                'chips_label' => 'Planning a different facility?',
                'list_label' => 'Facilities we set up',
                'caption_bold' => 'NHS Medical Center, Dubai.', 'caption' => 'Delivered end to end by Alpha.',
                'facilities' => $dubai ? [] : array_map(fn ($f) => ['name' => $f], [
                    'Medical centre', 'Dental centre', 'Aesthetic centre', 'Home healthcare',
                    'Clinical laboratory', 'Dental laboratory', 'Day surgery centre',
                ]),
            ],
            'sections' => $sections,
        ]);
    }
}
