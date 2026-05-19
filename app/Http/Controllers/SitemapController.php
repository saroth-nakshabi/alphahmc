<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceGroup;
use App\Models\MainCategory;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = $this->staticPages();
        $services    = $this->servicePages();
        $categories  = $this->categoryPages();
        $groups      = $this->serviceGroupPages();

        $xml = $this->buildXml(array_merge($staticPages, $groups, $categories, $services));

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
            ['loc' => route('how_alpha_work'),            'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('front.blog'),                'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('contact'),                   'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('front.project'),             'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('front.brands'),              'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('service_calendar'),          'priority' => '0.6', 'changefreq' => 'weekly'],
        ];
    }

    private function servicePages(): array
    {
        $pages = [];
        try {
            Service::select('slug', 'updated_at')->get()->each(function ($s) use (&$pages) {
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
            MainCategory::select('slug', 'updated_at')->get()->each(function ($c) use (&$pages) {
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
            ServiceGroup::select('slug', 'updated_at')->get()->each(function ($g) use (&$pages) {
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
