<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ads landing pages out of search (SEO brief "alphahmc-dental-landing-noindex", Saroth 30 Oct 2026): every
 * /facility-setup/ page becomes noindex, follow and leaves the sitemap; new pages start that way too.
 * The pages stay live for Google Ads. An admin can switch one back on later.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        DB::statement('ALTER TABLE landing_pages ALTER COLUMN indexable SET DEFAULT 0');
        DB::table('landing_pages')->update(['indexable' => false]);
    }

    public function down(): void
    {
        if (Schema::hasTable('landing_pages')) {
            DB::statement('ALTER TABLE landing_pages ALTER COLUMN indexable SET DEFAULT 1');
        }
    }
};
