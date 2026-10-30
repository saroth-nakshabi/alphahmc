<?php

use App\Models\LandingPage;
use App\Support\LandingPageSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Landing pages: optional services for a turnkey project (Saroth, 30 Oct 2026). A new "turnkey" section after the
 * scope rows replaces the one-line "Also available" note: construction phase engineering, construction and fit-out,
 * staff hiring, soft launch, and three months of operational support after licensing at no extra cost.
 * Each page gets a history entry; down() removes the section and restores the note.
 */
return new class extends Migration
{
    public const TURNKEY = [
        'label'   => 'Optional services',
        'heading' => 'Make it turnkey.',
        'text'    => 'Add any of these and one team takes you from the first drawing to your first months of operation.',
        'note'    => '*Terms and conditions apply.',
        'items'   => [
            ['title' => 'Construction phase engineering', 'text' => 'Design package, BOQ, contractor appointment, NOCs and site supervision.'],
            ['title' => 'Construction and fit-out', 'text' => 'We build and fit out your facility to the approved design.'],
            ['title' => 'Staff hiring', 'text' => 'Recruitment of your clinical and support team, ready for licensing.'],
            ['title' => 'Soft launch', 'text' => 'A controlled first opening to test your workflows, systems and team before full operation.'],
            ['title' => 'Three months of operational support, free*', 'text' => 'After your license is issued we support you for three months at no extra cost, so the immediate post-licensing requirements are handled and your first stages run smoothly.'],
        ],
    ];

    private const OLD_NOTE = 'Also available: construction phase engineering (design package, BOQ, contractor appointment, NOCs and site supervision) and recruitment of your clinical team.';

    public function up(): void
    {
        $this->each(function (array $content) {
            $out = [];
            foreach ($content['sections'] as $section) {
                if ($section['key'] === 'turnkey') {
                    continue;   // placed again below, right after the scope rows
                }
                if ($section['key'] === 'scope') {
                    $section['data']['optional'] = '';
                    $out[] = $section;
                    $out[] = ['key' => 'turnkey', 'enabled' => true, 'data' => self::TURNKEY];
                    continue;
                }
                $out[] = $section;
            }
            $content['sections'] = $out;

            return $content;
        }, 'Optional services for a turnkey project added (Saroth, 30 Oct 2026)');
    }

    public function down(): void
    {
        $this->each(function (array $content) {
            $content['sections'] = array_values(array_filter($content['sections'], fn ($s) => $s['key'] !== 'turnkey'));
            foreach ($content['sections'] as &$section) {
                if ($section['key'] === 'scope' && ($section['data']['optional'] ?? '') === '') {
                    $section['data']['optional'] = self::OLD_NOTE;
                }
            }

            return $content;
        }, 'Turnkey section removed');
    }

    private function each(callable $change, string $note): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->get(['id', 'content']) as $row) {
            $content = LandingPageSchema::normalize(json_decode((string) $row->content, true) ?: []);
            $content = LandingPageSchema::normalize($change($content));
            DB::table('landing_pages')->where('id', $row->id)->update([
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
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
