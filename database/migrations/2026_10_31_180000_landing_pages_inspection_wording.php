<?php

use App\Models\LandingPage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Landing pages, final inspection wording (Saroth, 30 Oct 2026): Alpha requests the audit, attends it as the
 * consultant and handles the full inspection, instead of attending "with you". Each page gets a history entry.
 */
return new class extends Migration
{
    private const REPLACE = [
        'We request it, prepare for it and attend it with you.'
            => 'We request the audit, prepare your facility for it, and attend and handle the full inspection as your consultant.',
        'We request the inspection, attend it with you, close any findings and see the license issued.'
            => 'We request the inspection, attend and handle the full audit as your consultant, close any findings and see the license issued.',
        'and stands with you at the final inspection.'
            => 'and handles the final inspection for you.',
        // Saroth, 30 Oct 2026: "according to" the authority guidelines.
        'layout drawings to authority guidelines'
            => 'layout drawings according to authority guidelines',
    ];

    public function up(): void
    {
        $this->swap(self::REPLACE, 'Final inspection wording: Alpha attends and handles the audit (Saroth, 30 Oct 2026)');
    }

    public function down(): void
    {
        $this->swap(array_flip(self::REPLACE), 'Final inspection wording reverted');
    }

    private function swap(array $map, string $note): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->get(['id', 'content']) as $row) {
            $new = str_replace(array_keys($map), array_values($map), (string) $row->content);
            if ($new === $row->content) {
                continue;
            }
            DB::table('landing_pages')->where('id', $row->id)->update(['content' => $new]);
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
