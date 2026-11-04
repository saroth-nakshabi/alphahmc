<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Optional heading above a service page's slider cards (SEO brief "alphahmc-heading-levels", Saroth 4 Nov 2026).
 * Rendered as an h2 in front/service.blade.php, hidden when empty. Text from slot 5 of each page's .slots.md.
 */
return new class extends Migration
{
    private const HEADINGS = [
        'clinic-setup-licensing-uae' => 'Steps to set up a clinic in Dubai',
        'medical-centre-setup-licensing-dubai' => 'How we take your centre from plan to licence',
        'dental-centre-setup-licensing-dubai' => 'From plan to licence in five phases',
        'day-surgery-centre-setup-licensing-uae' => 'How we take it from plan to licence',
        'dha-license-healthcare-professionals-dubai' => 'How Alpha Health Group helps',
        'doh-license-healthcare-professionals-abu-dhabi' => 'How Alpha Health Group helps',
        'mohap-license-healthcare-professionals' => 'How Alpha Health Group helps',
    ];

    public function up(): void
    {
        if (!Schema::hasColumn('services', 'slider_heading')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('slider_heading', 150)->nullable()->after('info_four');
            });
        }

        foreach (self::HEADINGS as $slug => $heading) {
            // A page missing on this copy of the site (the local database has no MOHAP page) is skipped.
            DB::table('services')->where('slug', $slug)->update(['slider_heading' => $heading]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'slider_heading')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('slider_heading');
            });
        }
    }
};
