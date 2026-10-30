<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceGroup;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = $this->staticPages();
        $services    = $this->servicePages();
        $categories  = $this->categoryPages();
        $groups      = $this->serviceGroupPages();
        $blogs       = $this->blogPages();
        $projects    = $this->projectPages();
        $brands      = $this->brandPages();
        $reviewers   = $this->reviewerPages();

        $xml = $this->buildXml(array_merge(
            $staticPages, $groups, $categories, $services, $blogs, $projects, $brands, $reviewers
        ));

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    private function staticPages(): array
    {
        return [
            ['loc' => route('home'),                      'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('front.new-about'),           'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('front.all-services'),        'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('healthcare_quality_assurance'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('front.new_blog'),            'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('contact'),                   'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('front.project'),             'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('front.brands'),              'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('front.ahg-updates'),         'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => route('front.privacy-policy'),      'priority' => '0.3', 'changefreq' => 'yearly'],
        ];
    }

    /** Reviewer bio pages at /team/{key}. */
    private function reviewerPages(): array
    {
        $pages = [];
        try {
            \App\Models\Reviewer::orderBy('sort_order')->get(['key', 'updated_at'])->each(function ($r) use (&$pages) {
                $pages[] = [
                    'loc'        => route('front.reviewer', $r->key),
                    'lastmod'    => $r->updated_at?->toAtomString(),
                    'priority'   => '0.5',
                    'changefreq' => 'monthly',
                ];
            });
        } catch (\Throwable) {}

        return $pages;
    }

    private function servicePages(): array
    {
        $pages = [];
        try {
            Service::published()->select('slug', 'updated_at')->get()->each(function ($s) use (&$pages) {
                $pages[] = [
                    'loc'        => route('front.service', $s->slug),
                    'lastmod'    => $s->updated_at?->toAtomString(),
                    'priority'   => '0.8',
                    'changefreq' => 'monthly',
                ];
            });
        } catch (\Throwable) {}

        return $pages;
    }

    private function categoryPages(): array
    {
        $pages = [];
        try {
            // /service-category/{slug} serves Category; main_categories has no slug column.
            Category::select('slug', 'updated_at')->get()->each(function ($c) use (&$pages) {
                $pages[] = [
                    'loc'        => route('front.service-category', $c->slug),
                    'lastmod'    => $c->updated_at?->toAtomString(),
                    'priority'   => '0.7',
                    'changefreq' => 'monthly',
                ];
            });
        } catch (\Throwable) {}

        return $pages;
    }

    private function serviceGroupPages(): array
    {
        $pages = [];
        try {
            ServiceGroup::where('status', 'published')->select('slug', 'updated_at')->get()->each(function ($g) use (&$pages) {
                $pages[] = [
                    'loc'        => route('service-packages', $g->slug),
                    'lastmod'    => $g->updated_at?->toAtomString(),
                    'priority'   => '0.7',
                    'changefreq' => 'monthly',
                ];
            });
        } catch (\Throwable) {}

        return $pages;
    }

    private function blogPages(): array
    {
        $pages = [];
        try {
            // Drafts are excluded by Blog's PublishedScope. lastmod follows the dates the post shows.
            Blog::select('slug', 'updated_at', 'updated_date', 'published_date')->whereNotNull('slug')->where('slug', '!=', '')->get()->each(function ($b) use (&$pages) {
                $pages[] = [
                    'loc'        => route('front.singleBlog', $b->slug),
                    'lastmod'    => ($b->updated_date ?? $b->published_date ?? $b->updated_at)?->toAtomString(),
                    'priority'   => '0.6',
                    'changefreq' => 'monthly',
                ];
            });
        } catch (\Throwable) {}

        return $pages;
    }

    private function projectPages(): array
    {
        $pages = [];
        try {
            Project::select('slug', 'updated_at')->whereNotNull('slug')->where('slug', '!=', '')->get()->each(function ($p) use (&$pages) {
                $pages[] = [
                    'loc'        => route('front.project_details', $p->slug),
                    'lastmod'    => $p->updated_at?->toAtomString(),
                    'priority'   => '0.6',
                    'changefreq' => 'monthly',
                ];
            });
        } catch (\Throwable) {}

        return $pages;
    }

    private function brandPages(): array
    {
        $pages = [];
        try {
            Brand::select('slug', 'updated_at')->whereNotNull('slug')->where('slug', '!=', '')->get()->each(function ($b) use (&$pages) {
                $pages[] = [
                    'loc'        => route('front.singleBrand', $b->slug),
                    'lastmod'    => $b->updated_at?->toAtomString(),
                    'priority'   => '0.5',
                    'changefreq' => 'monthly',
                ];
            });
        } catch (\Throwable) {}

        return $pages;
    }

    private function buildXml(array $urls): string
    {
        $today = now()->toAtomString();
        $items = '';

        foreach ($urls as $url) {
            $items .= "\n  <url>";
            $items .= "\n    <loc>" . htmlspecialchars($url['loc']) . "</loc>";
            $items .= "\n    <lastmod>" . ($url['lastmod'] ?? $today) . "</lastmod>";
            $items .= "\n    <changefreq>" . ($url['changefreq'] ?? 'monthly') . "</changefreq>";
            $items .= "\n    <priority>" . ($url['priority'] ?? '0.5') . "</priority>";
            $items .= "\n  </url>";
        }

        return '<?xml version="1.0" encoding="UTF-8"?>'
            . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            . $items
            . "\n" . '</urlset>';
    }
}
