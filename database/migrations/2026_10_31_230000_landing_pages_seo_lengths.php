<?php

use App\Models\LandingPage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Landing page SEO titles within 60 characters and descriptions within 155 (Saroth, 30 Oct 2026). The brand suffix
 * goes, as on service pages when it does not fit (App\Support\Seo::title). Only a value still equal to the seeded
 * one is changed, so an admin edit is never overwritten. Each changed page gets a history entry.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->each(fn ($old) => [
            'seo_title'        => preg_replace('/ \| Alpha Health Group$/', '', $old['seo_title']),
            'meta_description' => str_replace(
                [' with one team for layout planning, ', ' licensing, documentation and the final inspection.'],
                [' with one team for layout, ', ' licensing and the final inspection.'],
                $old['meta_description']
            ),
        ], fn ($old) => str_ends_with($old['seo_title'], ' | Alpha Health Group')
            && str_contains($old['meta_description'], 'licensing, documentation and the final inspection.'),
        'SEO title and description shortened to the length limits');
    }

    public function down(): void
    {
        // The shortened copy is valid either way; nothing to restore.
    }

    private function each(callable $change, callable $applies, string $note): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->get(['id', 'seo_title', 'meta_description']) as $row) {
            $old = (array) $row;
            if (!$applies($old)) {
                continue;
            }
            DB::table('landing_pages')->where('id', $row->id)->update($change($old));
            if (Schema::hasTable('landing_page_revisions')) {
                DB::table('landing_page_revisions')->insert([
                    'landing_page_id' => $row->id,
                    'user_name'       => 'System',
                    'note'            => $note,
                    'snapshot'        => json_encode(LandingPage::find($row->id)->snapshot(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'created_at'      => now(),
                ]);
            }
        }
    }
};
