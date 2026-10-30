<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Reviewer;
use App\Models\Service;
use App\Models\ServiceGroup;
use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Reviewers and their pages (brief "alphahmc-reviewers-and-linking", 30 Oct 2026).
 * Bios word for word from the SEO agent's alphahmc-reviewer-bios.md (titles as Saroth gave them, names as on
 * LinkedIn). Page assignments, real sign-off dates and the blogs' service links come from
 * database/data/reviewer-assignments.json (186 pages). Re-running updates the same rows.
 *
 * Rule from the SEO agent: a page's reviewed_at changes only when its reviewer re-reads it after a body edit.
 */
class ReviewerSeeder extends Seeder
{
    public const REVIEWERS = [
        'siraj-hm-ibrahim' => [
            'name' => 'Siraj H.M Ibrahim',
            'title' => 'CEO, Healthcare Investment Expert',
            'qualifications' => 'DMS (Hospital Administration and Clinical Research), MBA, ACHE (member of the American College of Healthcare Executives).',
            'experience' => '25+ years in healthcare quality, regulation, facility licensing and corporate governance in the UAE.',
            'bio' => 'Siraj has led hospitals and multi-branch medical centres from feasibility to full regulatory approval and day-to-day operation. He advises investors and owners on feasibility, market entry, growth and governance.',
            'reviews' => 'healthcare investment, feasibility, acquisitions, facility setup for investors.',
            'linkedin' => 'https://www.linkedin.com/in/siraj-hm-ibrahim/',
        ],
        'mohammed-ehsaan' => [
            'name' => 'Dr Mohammed Ehsaan',
            'title' => 'Healthcare Regulatory Consultant & COO',
            'qualifications' => 'BDS (dentist by qualification), MBA in Healthcare Services, CPHQ, Six Sigma Green Belt.',
            'experience' => '15+ years in accreditation, regulatory compliance, insurance operations and healthcare consulting in the UAE, Saudi Arabia and India.',
            'bio' => 'Dr Ehsaan has led facilities through DOH, DHA and MOH inspections, accreditation audits (including JCI and ACHC readiness) and operational restructuring. He oversees quality management, clinical governance and policy development.',
            'reviews' => 'regulation hubs, facility licensing, accreditation, product and equipment registration, telehealth.',
            'linkedin' => 'https://www.linkedin.com/in/dr-mohammed-ehsaan-2558021a/',
        ],
        'areej-m-bathurudeen' => [
            'name' => 'Areej M. Bathurudeen',
            'title' => 'Healthcare Quality Consultant',
            'qualifications' => 'Diploma in Business Management, CPHQ.',
            'experience' => '4+ years in UAE healthcare regulatory compliance and quality improvement.',
            'bio' => 'Areej runs compliance gap analyses, facility licensing audits and specialty activation audits against DOH, DHA and MOHAP standards, and turns new regulator circulars into policies, SOPs and staff training.',
            'reviews' => 'inspections and audits, FANR and radiation, DOH regulation, JAWDA.',
            'linkedin' => 'https://www.linkedin.com/in/areej-m-bathurudeen-56b54a265/',
        ],
        'murshid-bafick' => [
            'name' => 'Murshid Bafick',
            'title' => 'Professional Licensing Manager',
            'qualifications' => 'BBM (Bachelor of Business Management).',
            'experience' => '5+ years in healthcare professional licensing.',
            'bio' => 'Murshid leads Alpha\'s professional licensing team. He handles qualification requirement analysis, licensing for the UAE and other countries, and liaison with health authorities and international medical regulators when an application runs into problems.',
            'reviews' => 'professional licensing for doctors, nurses and allied health professionals.',
            'linkedin' => 'https://www.linkedin.com/in/murshid-bafick-bb9a31b4/',
        ],
        'afrin-nakshabi' => [
            'name' => 'Eng. Afrin Nakshabi',
            'title' => 'Healthcare Planning & Infrastructure Engineer',
            'qualifications' => 'BSc (Civil Engineering), CPP.',
            'experience' => '7+ years and 100+ healthcare projects: hospitals, medical centres, diagnostic facilities, day surgery units and laboratories.',
            'bio' => 'Afrin plans and delivers healthcare facilities to DOH, DHA, MOH, JCI and Accreditation Canada engineering standards, covering clinical workflow, infection control, design, MEP and project management, and resolves engineering issues directly with authorities.',
            'reviews' => 'facility planning, design, design and build, fit-out, construction, infrastructure, medical equipment planning.',
            'linkedin' => 'https://www.linkedin.com/in/eng-afrin-nakshabi-50497ab1/',
        ],
        'saroth-nakshabi' => [
            'name' => 'Saroth Nakshabi',
            'title' => 'Health Technology Consultant & CIO',
            'qualifications' => 'MSc Data Science, BSc Software Engineering, MBA in Project Management.',
            'experience' => '12+ years in healthcare technology, data compliance, digital transformation and information security.',
            'bio' => 'Saroth leads Alpha\'s technology and innovation work: healthcare IT planning, information security and data governance, risk and control mapping, and business process design aligned with regulator requirements.',
            'reviews' => 'business, IT and digital, cybersecurity and ADHICS, insurance and payers, KPIs, staffing.',
            'linkedin' => 'https://www.linkedin.com/in/sarothnakshabi/',
        ],
    ];

    public function run(): void
    {
        foreach (array_keys(self::REVIEWERS) as $i => $key) {
            Reviewer::updateOrCreate(['key' => $key], self::REVIEWERS[$key] + ['sort_order' => $i + 1]);
        }

        $json = json_decode(file_get_contents(database_path('data/reviewer-assignments.json')), true);
        $models = [
            'services'         => Service::class,
            'service-category' => Category::class,
            'service-packages' => ServiceGroup::class,
            'blog_single'      => Blog::class,
        ];
        $missing = [];
        foreach ($json['pages'] as $page) {
            [, $type, $slug] = array_pad(explode('/', $page['path'], 3), 3, null);
            $model = $models[$type] ?? null;
            $query = $model ? $model::query() : null;
            if ($model === Blog::class) {
                $query->withoutGlobalScope(PublishedScope::class);
            }
            $record = $query?->where('slug', $slug)->first();
            if (!$record) {
                $missing[] = $page['path'];
                continue;
            }
            $record->reviewer = $page['reviewer'];
            $record->reviewed_at = $page['reviewed_at'] ?? null;
            if ($model === Blog::class) {
                $record->service_link = $page['service_link'] ?? null;
            }
            $record->timestamps = false;   // a review assignment is not a content change
            $record->save();
        }

        if ($missing) {
            Log::warning('ReviewerSeeder: pages not found', $missing);
            $this->command?->warn('Pages not found: ' . implode(', ', $missing));
        }
    }
}
