<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Dental centre meta description without FANR (SEO agent, Saroth approved 4 Nov 2026: "remove fanr approval entirely").
 * Only the meta description changes; the Updated date stays as it is.
 */
return new class extends Migration
{
    private const SLUG = 'dental-centre-setup-licensing-dubai';

    private const OLD = 'Open a dental centre in Dubai with one team: layout and sterilisation planning, FANR approval for X-ray, DHA licensing and the final inspection.';

    private const NEW = 'Open a dental centre in Dubai with one team: layout and sterilisation planning, DHA licensing and the final inspection.';

    public function up(): void
    {
        DB::table('services')->where('slug', self::SLUG)->where('meta_description', self::OLD)->update(['meta_description' => self::NEW]);
    }

    public function down(): void
    {
        DB::table('services')->where('slug', self::SLUG)->where('meta_description', self::NEW)->update(['meta_description' => self::OLD]);
    }
};
