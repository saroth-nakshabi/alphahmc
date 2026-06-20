<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Per-page, dashboard-managed settings for the site's STANDARD pages:
 * SEO tags (title / description / keywords / OG image) for every page, plus
 * an optional hero (image + eyebrow / title / subtitle / description) for the
 * pages that have one.
 *
 * Detail pages (services / categories / service groups) keep their own per-record
 * meta_* columns and are NOT managed here.
 */
class PageSetting extends Model
{
    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'hero_image',
        'hero_eyebrow',
        'hero_title',
        'hero_subtitle',
        'hero_description',
    ];

    /**
     * The standard pages managed here.
     * key => [label, route name, has_hero, default SEO + hero values].
     */
    public const REGISTRY = [
        'home' => [
            'label'      => 'Home',
            'route'      => 'home',
            'has_hero'   => false,
            'meta_title' => 'Healthcare Consultancy in Dubai | Alpha Health Group',
            'meta_description' => 'Alpha Health Group is a trusted healthcare consultancy in the UAE. We deliver DOH compliance, accreditation support, quality assurance, and operational excellence for hospitals and clinics.',
        ],
        'all_services' => [
            'label'      => 'All Services',
            'route'      => 'front.all-services',
            'has_hero'   => true,
            'meta_title' => 'All Healthcare Consultancy Services | Alpha Health Group',
            'meta_description' => 'Browse all healthcare consultancy services by Alpha Health Group — DOH licensing, JCIA accreditation, quality assurance, infection control, patient safety, and more for UAE facilities.',
            'hero_eyebrow'  => 'Our Expertise',
            'hero_title'    => 'Healthcare',
            'hero_subtitle' => 'Consultancy Services',
        ],
        'facility_types' => [
            'label'      => 'Services by Facility Type',
            'route'      => 'front.facility-types',
            'has_hero'   => true,
            'meta_title' => 'Healthcare Services by Facility Type | Alpha Health Group',
            'meta_description' => 'Find Alpha Health Group consultancy services tailored to your facility — hospitals, medical centers, day surgery centers, pharmacies, diagnostic labs, rehabilitation, home healthcare, and telehealth across the UAE.',
            'hero_eyebrow'  => 'Tailored to Your Facility',
            'hero_title'    => 'Services by',
            'hero_subtitle' => 'Facility Type',
        ],
        'about' => [
            'label'      => 'About Us',
            'route'      => 'front.new-about',
            'has_hero'   => true,
            'meta_title' => 'About Alpha Health Group | Healthcare Consultancy UAE',
            'meta_description' => 'Discover Alpha Health Group — a leading healthcare consultancy in the UAE delivering DOH compliance, accreditation, quality assurance, and operational excellence for hospitals and clinics.',
        ],
        'brands' => [
            'label'      => 'Brands',
            'route'      => 'front.brands',
            'has_hero'   => true,
            'meta_title' => 'Alpha Health Group Branches | Our Business Portfolio',
            'meta_description' => 'Explore the Alpha Health Group portfolio of specialised healthcare companies and brands across the UAE.',
        ],
        'clients' => [
            'label'      => 'Our Clients',
            'route'      => 'front.our-clients',
            'has_hero'   => true,
            'meta_title' => 'Our Clients | Trusted Healthcare Partners | Alpha Health Group',
            'meta_description' => 'Discover the healthcare facilities, hospitals, and medical organisations across the UAE that trust Alpha Health Group for DOH compliance, accreditation, and quality consultancy.',
            'hero_eyebrow'     => 'Trusted Partnerships',
            'hero_title'       => 'Healthcare Facilities',
            'hero_subtitle'    => 'That Trust Alpha',
            'hero_description' => 'From DOH-licensed hospitals to homecare providers and specialty clinics, Alpha Health Group partners with leading healthcare organisations across the UAE to deliver compliance, accreditation, and operational excellence.',
        ],
        'testimonials' => [
            'label'      => 'Testimonials / Reviews',
            'route'      => 'front.testimonials',
            'has_hero'   => true,
            'meta_title' => 'Client Reviews & Testimonials | Alpha Health Group',
            'meta_description' => 'Read verified reviews and testimonials from healthcare clients who have worked with Alpha Health Group across the UAE and GCC.',
            'hero_eyebrow'  => 'Client Reviews',
        ],
        'insights' => [
            'label'      => 'Insights / Blog',
            'route'      => 'front.new_blog',
            'has_hero'   => true,
            'meta_title' => 'Healthcare Management Updates & Insights | Alpha Health Group',
            'meta_description' => 'Explore healthcare management updates, leadership guides, DOH compliance insights, and operational excellence strategies from Alpha Health Group experts in the UAE and GCC.',
        ],
        'projects' => [
            'label'      => 'Projects / Case Studies',
            'route'      => 'front.project',
            'has_hero'   => true,
            'meta_title' => 'Projects & Case Studies | Alpha Health Group',
            'meta_description' => 'Explore healthcare facility projects and case studies delivered by Alpha Health Group across the UAE.',
        ],
        'contact' => [
            'label'      => 'Contact',
            'route'      => 'contact',
            'has_hero'   => true,
            'meta_title' => 'Contact Us | Alpha Health Group',
            'meta_description' => 'Get in touch with Alpha Health Group for healthcare consultancy services, DOH compliance support, and accreditation assistance for healthcare facilities across the UAE.',
        ],
    ];

    /** Fetch (or create with seeded defaults) the settings row for a page key. */
    public static function for(string $key): ?self
    {
        if (!isset(self::REGISTRY[$key])) {
            return null;
        }
        $defaults = self::REGISTRY[$key];
        return self::firstOrCreate(['page_key' => $key], [
            'meta_title'       => $defaults['meta_title'] ?? null,
            'meta_description' => $defaults['meta_description'] ?? null,
            'meta_keywords'    => $defaults['meta_keywords'] ?? null,
            'hero_eyebrow'     => $defaults['hero_eyebrow'] ?? null,
            'hero_title'       => $defaults['hero_title'] ?? null,
            'hero_subtitle'    => $defaults['hero_subtitle'] ?? null,
            'hero_description' => $defaults['hero_description'] ?? null,
        ]);
    }

    /** Map the current route name to a managed page key (or null). */
    public static function keyForRoute(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }
        foreach (self::REGISTRY as $key => $cfg) {
            if (($cfg['route'] ?? null) === $routeName) {
                return $key;
            }
        }
        return null;
    }

    public function hasHero(): bool
    {
        return (bool) (self::REGISTRY[$this->page_key]['has_hero'] ?? false);
    }

    /** Public (front-end) URL for this page, or null if the route can't be resolved. */
    public function publicUrl(): ?string
    {
        $routeName = self::REGISTRY[$this->page_key]['route'] ?? null;
        if (!$routeName || !\Illuminate\Support\Facades\Route::has($routeName)) {
            return null;
        }
        try {
            return route($routeName);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function label(): string
    {
        return self::REGISTRY[$this->page_key]['label'] ?? ucfirst($this->page_key);
    }
}
