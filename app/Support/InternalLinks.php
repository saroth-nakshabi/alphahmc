<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceGroup;

/**
 * Hand-picked internal links (brief "alphahmc-reviewers-and-linking", section 3, 30 Oct 2026).
 * Link text is always the target page's own name (its H1), never a keyword anchor.
 */
class InternalLinks
{
    private const PRO_LICENSING = [
        '/services/doh-license-healthcare-professionals-abu-dhabi',
        '/services/mohap-license-healthcare-professionals',
    ];

    /** Extra links per page (3c). */
    private const EXTRA = [
        '/service-category/healthcare-professional-licensing'  => self::PRO_LICENSING,
        '/service-packages/healthcare-professional-licensing-uae' => self::PRO_LICENSING,
        '/service-category/facility-licensing-setup'           => ['/plan-your-project'],
        '/services/clinic-setup-licensing-uae'                 => ['/plan-your-project'],
        '/services/medical-centre-setup-licensing-dubai'       => ['/plan-your-project'],
    ];

    /** Blogs reviewed by these reviewers also link to these pages (3c). */
    private const EXTRA_BY_REVIEWER = [
        'murshid-bafick' => self::PRO_LICENSING,
    ];

    /** Case studies linked from the service category of their facility type (confirmed by the SEO agent, 30 Oct). */
    private const CASE_STUDIES = [
        'day-surgery-centers'            => ['broadway-vascular', 'bone-care', 'royal-spanish-center'],
        'diagnostic-labs-imaging-centers' => ['sanimed-group'],
        'hospitals'                      => ['reem-hospital', 'smch'],
        'rehabilitation-centers'         => ['revital-hospital'],
        'medical-centers-polyclinics'    => ['beijing-therapy', 'nhs-medical-center', 'vfs-global', 'ihlad-abu-dhabi', 'dna-health-corp'],
        'drugstores'                     => ['healthcare-solutions'],
    ];

    /** Pages that are not records: their H1 as shown on the page. */
    private const STATIC_TITLES = [
        '/plan-your-project'         => 'Plan your healthcare project',
        '/services-by-facility-type' => 'Services by facility type',
    ];

    private static array $resolved = [];

    /**
     * Extra links for a page, as [['url' => ..., 'text' => ...], ...]. Unpublished or missing targets are
     * left out, and so is the page itself.
     */
    public static function forPage(string $path, ?string $reviewerKey = null): array
    {
        $path = '/' . trim($path, '/');
        $targets = array_merge(self::EXTRA[$path] ?? [], $reviewerKey ? (self::EXTRA_BY_REVIEWER[$reviewerKey] ?? []) : []);
        $links = [];
        foreach (array_unique($targets) as $target) {
            if ($target !== $path && ($link = self::page($target))) {
                $links[] = $link;
            }
        }

        if (preg_match('#^/service-category/([^/]+)$#', $path, $m) && isset(self::CASE_STUDIES[$m[1]])) {
            $projects = Project::whereIn('slug', self::CASE_STUDIES[$m[1]])->get()->keyBy('slug');
            foreach (self::CASE_STUDIES[$m[1]] as $slug) {
                if ($p = $projects->get($slug)) {
                    $links[] = ['url' => route('front.project_details', $p->slug), 'text' => trim($p->client_name ?: $p->name) . ' case study'];
                }
            }
        }

        return $links;
    }

    /**
     * A site page by path, with its H1 as link text, or null when it is missing or not published.
     *
     * @return array{url: string, text: string}|null
     */
    public static function page(?string $path): ?array
    {
        $path = '/' . trim((string) $path, '/');
        if (array_key_exists($path, self::$resolved)) {
            return self::$resolved[$path];
        }

        $link = null;
        if (isset(self::STATIC_TITLES[$path])) {
            $link = ['url' => url($path), 'text' => self::STATIC_TITLES[$path]];
        } elseif (preg_match('#^/(services|service-category|service-packages|blog_single)/([^/?\#]+)$#', $path, $m)) {
            $record = match ($m[1]) {
                'services'         => Service::published()->where('slug', $m[2])->first(['name', 'slug']),
                'service-category' => Category::where('slug', $m[2])->first(['name', 'slug']),
                'service-packages' => ServiceGroup::where('slug', $m[2])->where('status', 'published')->first(['name', 'slug']),
                'blog_single'      => Blog::where('slug', $m[2])->first(['title', 'slug']),
            };
            if ($record) {
                $link = ['url' => url($path), 'text' => trim(html_entity_decode(strip_tags($record->name ?? $record->title), ENT_QUOTES | ENT_HTML5, 'UTF-8'))];
            }
        }

        return self::$resolved[$path] = $link;
    }
}
