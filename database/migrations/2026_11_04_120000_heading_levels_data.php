<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SEO brief "alphahmc-heading-levels-2" (Saroth 4 Nov 2026), the two data items:
 *   1. Slider heading "How Alpha Health Group helps" on two older service pages whose h3 cards follow the h1.
 *   2. The empty h3 (&nbsp; only) in the description of /service-category/healthcare-accreditations-certifications.
 * down() clears the two slider headings; the empty h3 is not put back.
 */
return new class extends Migration
{
    private const SLIDER = ['healthcare-management-consultancy-in-uae', 'construction-project-management-supervision'];

    public function up(): void
    {
        DB::table('services')->whereIn('slug', self::SLIDER)
            ->where(fn ($q) => $q->whereNull('slider_heading')->orWhere('slider_heading', ''))
            ->update(['slider_heading' => 'How Alpha Health Group helps']);

        $category = DB::table('categories')->where('slug', 'healthcare-accreditations-certifications')->first(['id', 'description']);
        if ($category && $category->description) {
            $clean = preg_replace('~\s*<h([1-6])\b[^>]*>(?:\s|&nbsp;|\x{00A0}|<br\s*/?>)*</h\1>~iu', '', $category->description);
            if ($clean !== null && $clean !== $category->description) {
                DB::table('categories')->where('id', $category->id)->update(['description' => $clean]);
            }
        }
    }

    public function down(): void
    {
        DB::table('services')->whereIn('slug', self::SLIDER)->where('slider_heading', 'How Alpha Health Group helps')
            ->update(['slider_heading' => null]);
    }
};
