<?php

use App\Models\LandingPage;
use App\Support\LandingPageSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Landing pages (Saroth, 30 Oct 2026): no DHCC ("DHA or DHCC" becomes "DHA", the DHCC authority and the DHA-or-DHCC
 * FAQ go), "What is not in our fee" switched off and replaced in place by "Other facility types" (pills to the other
 * landing pages), and every landing page published. Pages stay noindex. Each page gets a history entry; down() only
 * returns the pages to draft, the copy changes stay in their history.
 */
return new class extends Migration
{
    private const OTHERS = [
        'heading' => 'Open another facility with Alpha.',
        'text'    => 'Other facility types we set up, from planning to the final license.',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->get() as $row) {
            $content = json_decode((string) $row->content, true) ?: [];
            $content = $this->dropDhcc($content);

            $sections = [];
            foreach ($content['sections'] ?? [] as $section) {
                if (($section['key'] ?? null) === 'others') {
                    continue;   // placed again below, where the fee section is
                }
                if (($section['key'] ?? null) === 'excluded') {
                    $section['enabled'] = false;
                    $sections[] = ['key' => 'others', 'enabled' => true, 'data' => self::OTHERS];
                }
                $sections[] = $section;
            }
            $content['sections'] = $sections;
            $content = LandingPageSchema::normalize($content);

            DB::table('landing_pages')->where('id', $row->id)->update([
                'content'      => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'status'       => 'published',
                'published_at' => $row->published_at ?: now(),
            ]);
            $this->revision($row->id, 'Published; DHCC removed; fee section replaced by other facility types (Saroth, 30 Oct 2026)');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        DB::table('landing_pages')->where('slug', '!=', 'dental-centre-dubai')->update(['status' => 'draft']);
    }

    /** "DHA or DHCC" → "DHA"; the DHCC authority item and any FAQ about DHCC go. */
    private function dropDhcc(array $content): array
    {
        array_walk_recursive($content, function (&$value) {
            if (is_string($value)) {
                $value = str_replace(['DHA or DHCC', 'DHA and DHCC'], 'DHA', $value);
            }
        });
        foreach ($content['sections'] ?? [] as $i => $section) {
            if (in_array($section['key'] ?? null, ['authorities', 'faq'], true)) {
                $items = array_values(array_filter($section['data']['items'] ?? [], function ($item) {
                    return stripos(implode(' ', array_map('strval', (array) $item)), 'DHCC') === false;
                }));
                $content['sections'][$i]['data']['items'] = $items;
            }
        }

        return $content;
    }

    private function revision(int $id, string $note): void
    {
        if (!Schema::hasTable('landing_page_revisions')) {
            return;
        }
        $page = LandingPage::find($id);
        DB::table('landing_page_revisions')->insert([
            'landing_page_id' => $id,
            'user_name'       => 'System',
            'note'            => $note,
            'snapshot'        => json_encode($page->snapshot(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'created_at'      => now(),
        ]);
    }
};
