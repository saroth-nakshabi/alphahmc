<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Three new case studies (brief "alphahmc-case-studies" part 2, Saroth 30 Oct 2026): CRYO Health Clinic,
 * MABC Abilities Development and SKIN111. Text word for word from the brief; the "Outcome" lines are the
 * pillars' resolutions, shown under the divider as on the existing case studies. Photos are WebP copies in
 * public/uploads/project_images/<slug>-<n>.webp, the first one the hero. Reviewers come from
 * ReviewerSeeder (run it after this one). Creates a case study only when its slug is free; never overwrites.
 */
class CaseStudySeeder extends Seeder
{
    public const CASE_STUDIES = [
        'cryo-health-clinic' => [
            'name'                 => 'New Cryotherapy & Wellness Centre Setup',
            'meta_title'           => 'Cryotherapy & Wellness Centre Setup in Abu Dhabi',
            'meta_description'     => 'How Alpha Health Group set up CRYO Health Clinic in Abu Dhabi: two branches on Reem Island and Saadiyat Island, from planning to DOH licensing.',
            'category'             => 'Healthcare Infrastructure Projects',
            'client_name'          => 'CRYO Health Clinic L.L.C',
            'client_website'       => 'cryo.com/ae',
            'project_location'     => 'Abu Dhabi, UAE (Reem Island and Saadiyat Island)',
            'project_duration'     => '6 Months (completed July 2026)',
            'regulatory_authority' => 'Department of Health Abu Dhabi (DOH)',
            'project_scope'        => 'Facility Planning, Engineering Design & Authority Approval, DOH Facility Licensing, Medical Equipment Planning, Construction Coordination, Policies & Procedures, Compliance Documentation, Inspection Readiness, Operational Readiness, Multi-Branch Setup',
            'description'          => '<p>Alpha Health Group delivered the complete new facility setup for CRYO Health Clinic, a wellness-focused medical centre with two branches in Abu Dhabi, on Reem Island and Saadiyat Island. The centres offer whole body and localised cryotherapy, CRYO facials and CRYO Slim, alongside complementary therapies. We handled the project from facility planning and engineering through regulatory approval and licensing to operational readiness, so both branches opened as licensed medical facilities under the Department of Health. The project took six months and was completed in July 2026.</p>',
            'challenges'           => [
                ['challenge_title' => 'Facility Planning Around Cryotherapy', 'challenge' => '<p>Planned each branch around its treatment model: cryotherapy rooms, treatment areas, client flow and support spaces, coordinated with the building services the cryotherapy equipment needs and with DOH facility requirements.</p>', 'resolution' => '<p>Two branch layouts planned and approved for cryotherapy and wellness treatments.</p>'],
                ['challenge_title' => 'Regulatory Approval & DOH Licensing', 'challenge' => '<p>Prepared and managed the licensing pathway with the Department of Health, from the facility application and engineering approvals to the inspection and the final facility licence for each branch.</p>', 'resolution' => '<p>Both branches licensed by DOH as medical facilities.</p>'],
                ['challenge_title' => 'Operational Readiness', 'challenge' => '<p>Prepared each branch to operate from day one: policies and procedures, compliance documentation and the operational setup the regulator inspects before opening.</p>', 'resolution' => '<p>Both branches ready for inspection and opening with their compliance documentation in place.</p>'],
            ],
            'closing'              => '<p>Each branch went through the same steps under the <a href="https://www.doh.gov.ae/" target="_blank" rel="noopener">Department of Health Abu Dhabi</a>: facility planning, engineering approval, licensing, inspection and opening. Running the two branches as one programme kept the layouts, documentation and approvals consistent, so the second branch reused what the first had already settled with the regulator.</p><p>Related services: <a href="/services/healthcare-facility-licensing-abu-dhabi">healthcare facility licensing in Abu Dhabi</a>, <a href="/services/hospital-planning-requirement-mapping">facility planning and requirement mapping</a>, <a href="/services/medical-engineering-equipment-planning">medical equipment planning</a> and <a href="/service-category/medical-centers-polyclinics">medical centre setup</a>.</p>',
            'images'               => [
                ['uploads/project_images/cryo-health-clinic-1.webp', 'Corridor to the cryotherapy rooms at CRYO Health Clinic, Abu Dhabi'],
                ['uploads/project_images/cryo-health-clinic-2.webp', 'CRYO Health Clinic, Abu Dhabi'],
                ['uploads/project_images/cryo-health-clinic-3.webp', 'CRYO Health Clinic, Abu Dhabi'],
            ],
        ],
        'mabc-abilities-development' => [
            'name'                 => 'Paediatric Therapy Centre Setup',
            'meta_title'           => 'Paediatric & Speech Therapy Centre Setup in Abu Dhabi',
            'meta_description'     => 'How Alpha Health Group took MABC Abilities Development, a paediatric and speech therapy centre in Abu Dhabi, from planning to its final DOH licence.',
            'category'             => 'Rehabilitation Centers',
            'client_name'          => 'MABC Abilities Development L.L.C S.P.C',
            'client_website'       => null,
            'project_location'     => 'Abu Dhabi, UAE',
            'project_duration'     => '7 Months (completed October 2026)',
            'regulatory_authority' => 'Department of Health Abu Dhabi (DOH)',
            'project_scope'        => 'Facility Planning, Layout Design & Authority Approval, DOH Facility Licensing, Licensing Application Management, Policies & Procedures, Compliance Documentation, Inspection Readiness, Final Licence',
            'description'          => '<p>Alpha Health Group delivered the full facility project for MABC Abilities Development, a specialised paediatric and speech therapy centre in Abu Dhabi. The centre provides speech therapy, occupational therapy, ABA therapy and early childhood behavioural intervention. We managed the project from the first planning stage to the final licence, covering the facility layout, regulatory approvals, documentation and inspection readiness under the Department of Health. The project took seven months and was completed in October 2026.</p>',
            'challenges'           => [
                ['challenge_title' => 'Planning a Child-Centred Therapy Facility', 'challenge' => '<p>Planned the centre around paediatric therapy: individual therapy rooms, group and sensory areas, safe circulation for children and space for parents, aligned with DOH requirements for the services the centre offers.</p>', 'resolution' => '<p>Facility layout planned and approved for paediatric speech, occupational and behavioural therapy.</p>'],
                ['challenge_title' => 'Licensing From Application to Final Licence', 'challenge' => '<p>Managed the licensing pathway with the Department of Health from the initial application through the facility approvals and inspection to the final licence.</p>', 'resolution' => '<p>Final DOH facility licence issued for the centre.</p>'],
                ['challenge_title' => 'Compliance Ready From Opening', 'challenge' => '<p>Prepared the policies, procedures and compliance documentation the centre needs to operate and to pass inspection, so the clinical team could focus on the children in their care.</p>', 'resolution' => '<p>Centre opened with its compliance documentation in place.</p>'],
            ],
            'closing'              => '<p>Paediatric therapy centres are licensed by the <a href="https://www.doh.gov.ae/" target="_blank" rel="noopener">Department of Health Abu Dhabi</a> for the specific services they provide, so the services, the staffing and the rooms had to match from the start. Planning them together avoided changes late in the approval process.</p><p>Related services: <a href="/services/healthcare-facility-licensing-abu-dhabi">healthcare facility licensing in Abu Dhabi</a>, <a href="/service-category/rehabilitation-centers">rehabilitation centre setup</a>, <a href="/services/hospital-planning-requirement-mapping">facility planning and requirement mapping</a> and <a href="/services/regulatory-readiness-compliance-assurance">regulatory readiness</a>.</p>',
            'images'               => [
                ['uploads/project_images/mabc-abilities-development-1.webp', 'Reception at MABC Abilities Development paediatric therapy centre, Abu Dhabi'],
                ['uploads/project_images/mabc-abilities-development-2.webp', 'MABC Abilities Development paediatric therapy centre, Abu Dhabi'],
            ],
        ],
        'skin111' => [
            'name'                 => 'Turnkey Aesthetic & Wellness Clinic',
            'meta_title'           => 'Turnkey Aesthetic & Wellness Clinic in Abu Dhabi',
            'meta_description'     => 'How Alpha Health Group delivered SKIN111 in Abu Dhabi as a turnkey project: planning, engineering, construction, DOH licensing and ongoing regulatory management.',
            'category'             => 'Healthcare Infrastructure Projects',
            'client_name'          => 'SKIN111',
            'client_website'       => 'skin111.com',
            'project_location'     => 'Abu Dhabi, UAE',
            'project_duration'     => '8 Months (completed August 2026) & Ongoing',
            'regulatory_authority' => 'Department of Health Abu Dhabi (DOH)',
            'project_scope'        => 'Facility Planning, Engineering Design & Authority Approval, Construction Management, Facility Licensing, Operational Readiness, Policies & Procedures, Compliance Documentation, Healthcare Regulatory Management Outsourcing',
            'description'          => '<p>Alpha Health Group delivered SKIN111 as a full turnkey project. SKIN111 is an aesthetic, medical and wellness clinic offering cosmetic dermatology, advanced laser treatments, weight loss programmes, regenerative medicine, IV therapy and plastic surgery. Our work covered facility planning and engineering, construction management and operational readiness, and continues today as outsourced healthcare regulatory management. The setup took eight months and was completed in August 2026.</p>',
            'challenges'           => [
                ['challenge_title' => 'Facility Planning & Engineering', 'challenge' => '<p>Planned and engineered the clinic for a wide medical aesthetic service mix: consultation and treatment rooms for lasers and devices, procedure areas, and the clinical support spaces the regulator requires.</p>', 'resolution' => '<p>Clinic layout and engineering designed and approved for aesthetic, medical and wellness services.</p>'],
                ['challenge_title' => 'Construction Management & Operational Readiness', 'challenge' => '<p>Managed construction from start to handover and prepared the clinic to open: compliance documentation, policies and procedures, and the setup the authority inspects before licensing.</p>', 'resolution' => '<p>Clinic built, licensed and ready to open under one turnkey contract.</p>'],
                ['challenge_title' => 'Ongoing Regulatory Management', 'challenge' => '<p>After opening, Alpha Health Group continues to manage the clinic\'s regulatory obligations as an outsourced partner, so the medical team can focus on patients.</p>', 'resolution' => '<p>Regulatory management outsourced to Alpha Health Group and ongoing.</p>'],
            ],
            'closing'              => '<p>A clinic that combines lasers, injectables, IV therapy and surgical procedures sits under several sets of regulator requirements at once. Holding planning, engineering, construction and licensing under one contract kept those requirements aligned from design to opening, under the <a href="https://www.doh.gov.ae/" target="_blank" rel="noopener">Department of Health Abu Dhabi</a>.</p><p>Related services: <a href="/services/healthcare-design-and-build">healthcare design and build</a>, <a href="/services/construction-project-management-supervision">construction project management</a>, <a href="/service-category/healthcare-management-outsourcing">healthcare management outsourcing</a> and <a href="/services/medical-facility-fit-out">medical facility fit-out</a>.</p>',
            'images'               => [
                ['uploads/project_images/skin111-1.webp', 'SKIN111 aesthetic, medical and wellness clinic entrance'],
                ['uploads/project_images/skin111-2.webp', 'SKIN111 aesthetic, medical and wellness clinic'],
                ['uploads/project_images/skin111-3.webp', 'SKIN111 aesthetic, medical and wellness clinic'],
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::CASE_STUDIES as $slug => $c) {
            if (Project::where('slug', $slug)->exists()) {
                $this->command?->info("Case study {$slug} exists, left as it is.");
                continue;
            }
            $categoryId = ProjectCategory::where('name', $c['category'])->value('id');
            if (!$categoryId) {
                throw new \RuntimeException("Project category {$c['category']} not found for {$slug}.");
            }

            DB::transaction(function () use ($slug, $c, $categoryId) {
                $project = Project::create([
                    'project_category_id'  => $categoryId,
                    'slug'                 => $slug,
                    'sort_order'           => (int) Project::max('sort_order') + 1,
                    'challenge_title'      => $c['challenges'][0]['challenge_title'],
                    'challenge'            => $c['challenges'][0]['challenge'],
                    'resolution'           => $c['challenges'][0]['resolution'],
                ] + array_intersect_key($c, array_flip([
                    'name', 'meta_title', 'meta_description', 'client_name', 'client_website', 'project_location',
                    'project_duration', 'regulatory_authority', 'project_scope', 'description', 'challenges', 'closing',
                ])));
                foreach ($c['images'] as [$path, $alt]) {
                    ProjectImage::create(['project_id' => $project->id, 'image' => $path, 'alt' => $alt]);
                }
            });
        }
    }
}
