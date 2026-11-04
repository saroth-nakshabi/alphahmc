<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Package 13 gets its clean address (Saroth approved 4 Nov 2026): healthcare-it-digital-strategy-consulting2 becomes
 * healthcare-it-digital-strategy-consulting. The old address 301s (ServiceGroupController::RENAMED_SLUGS); menus,
 * cards, canonical, schema and the sitemap are built from the slug. No stored text linked to the old address.
 */
return new class extends Migration
{
    private const OLD = 'healthcare-it-digital-strategy-consulting2';
    private const NEW = 'healthcare-it-digital-strategy-consulting';

    public function up(): void
    {
        if (DB::table('service_groups')->where('slug', self::NEW)->exists()) {
            return;
        }
        DB::table('service_groups')->where('slug', self::OLD)->update(['slug' => self::NEW, 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('service_groups')->where('slug', self::NEW)->update(['slug' => self::OLD]);
    }
};
