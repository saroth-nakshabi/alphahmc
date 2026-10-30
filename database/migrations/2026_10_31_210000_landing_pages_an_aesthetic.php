<?php

use App\Models\LandingPage;
use App\Support\LandingPageSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * "a aesthetic centre" -> "an aesthetic centre" in the stored landing copy (the facts heading and three FAQ
 * questions came from the seeder's "a $name"; reported by the Google Ads agent, 30 Oct 2026). Only pages whose
 * copy changes get a history entry. down() puts the old article back.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->each('/\ba aesthetic\b/', 'an aesthetic', 'Grammar: "an aesthetic centre"');
    }

    public function down(): void
    {
        $this->each('/\ban aesthetic\b/', 'a aesthetic', 'Grammar fix undone');
    }

    private function each(string $pattern, string $replacement, string $note): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->get(['id', 'content']) as $row) {
            $content = LandingPageSchema::normalize(json_decode((string) $row->content, true) ?: []);
            $changed = 0;
            array_walk_recursive($content, function (&$value) use ($pattern, $replacement, &$changed) {
                if (is_string($value)) {
                    $value = preg_replace($pattern, $replacement, $value, -1, $n);
                    $changed += $n;
                }
            });
            if (!$changed) {
                continue;
            }
            DB::table('landing_pages')->where('id', $row->id)->update([
                'content' => json_encode(LandingPageSchema::normalize($content), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
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
