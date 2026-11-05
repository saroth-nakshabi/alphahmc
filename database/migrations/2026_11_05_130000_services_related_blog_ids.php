<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Related articles chosen per service page (admin: Related articles, up to 3), shown as a full-width row when the page
 * has no case studies (layout fix, Saroth 5 Nov 2026). Picks for the professional licensing pages from the SEO agent,
 * in display order: 68 UAE licensing guide 2026, 67 activation after the eligibility letter, 71 transfers.
 */
return new class extends Migration
{
    private const PICKS = [
        'dha-license-healthcare-professionals-dubai'     => [68, 67, 71],
        'doh-license-healthcare-professionals-abu-dhabi' => [68, 71, 67],
        'mohap-license-healthcare-professionals'         => [68, 71, 67],
    ];

    public function up(): void
    {
        if (!Schema::hasColumn('services', 'related_blog_ids')) {
            Schema::table('services', function (Blueprint $table) {
                $table->json('related_blog_ids')->nullable()->after('related_services');
            });
        }
        foreach (self::PICKS as $slug => $ids) {
            $ids = array_values(array_filter($ids, fn ($id) => DB::table('blogs')->where('id', $id)->exists()));
            DB::table('services')->where('slug', $slug)->whereNull('related_blog_ids')->update(['related_blog_ids' => json_encode($ids)]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'related_blog_ids')) {
            Schema::table('services', fn (Blueprint $table) => $table->dropColumn('related_blog_ids'));
        }
    }
};
