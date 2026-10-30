<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceGroup;

/**
 * Tells the conversion tracker which Google Ads labels a page reports (window._ahgPage.lead_type).
 *
 * Facility and regulatory pages report "Facility WhatsApp" and "Facility form" instead of the
 * general "WhatsApp Click" and "Inquiry Sent" (Ads agent, 29 Oct 2026). A page is 'facility' when
 * it is one of the five categories below, or a service or package in one of them, or one of the
 * listed slugs that sits in other categories. Every other page is 'general'.
 */
class LeadType
{
    public const FACILITY = 'facility';
    public const GENERAL = 'general';

    public const FACILITY_CATEGORIES = [
        'facility-licensing-setup',
        'regulatory-quality',
        'audit-consultation',
        'healthcare-accreditations-certifications',
        'healthcare-management-outsourcing',
    ];

    // Facility work filed under other categories (named by the Ads agent).
    public const FACILITY_SLUGS = [
        'healthcare-management-consultancy-in-uae',
        'healthcare-service-specialty-expansion-consulting',
        'healthcare-facility-acquisition-due-diligence',
        'medical-facility-fit-out',
    ];

    public static function for(Service|Category|ServiceGroup|null $page): string
    {
        if (!$page) {
            return self::GENERAL;
        }

        if ($page instanceof Category) {
            return in_array($page->slug, self::FACILITY_CATEGORIES, true) ? self::FACILITY : self::GENERAL;
        }

        if (in_array($page->slug, self::FACILITY_SLUGS, true)) {
            return self::FACILITY;
        }

        $inFacilityCategory = $page->categories()->whereIn('categories.slug', self::FACILITY_CATEGORIES)->exists();

        return $inFacilityCategory ? self::FACILITY : self::GENERAL;
    }
}
