<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Landing pages: switch off the "We license facilities with" authorities strip (Saroth, 30 Oct 2026). The section and
 * its copy stay in each page's content, so an admin can switch it back on in Landing pages; each change is kept in
 * the page history.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->setAuthorities(false, 'Authorities strip switched off (Saroth, 30 Oct 2026)');
    }

    public function down(): void
    {
        $this->setAuthorities(true, 'Authorities strip switched back on');
    }

    private function setAuthorities(bool $enabled, string $note): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->get() as $page) {
            $content = json_decode((string) $page->content, true);
            if (!is_array($content) || empty($content['sections'])) {
                continue;
            }
            $changed = false;
            foreach ($content['sections'] as &$section) {
                if (($section['key'] ?? null) === 'authorities' && (bool) ($section['enabled'] ?? false) !== $enabled) {
                    $section['enabled'] = $enabled;
                    $changed = true;
                }
            }
            unset($section);
            if (!$changed) {
                continue;
            }
            $json = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            DB::table('landing_pages')->where('id', $page->id)->update(['content' => $json]);
            if (Schema::hasTable('landing_page_revisions')) {
                $snapshot = (array) $page;
                $snapshot['content'] = $content;
                $snapshot['related_ids'] = json_decode((string) $page->related_ids, true);
                DB::table('landing_page_revisions')->insert([
                    'landing_page_id' => $page->id,
                    'user_name'       => 'System',
                    'note'            => $note,
                    'snapshot'        => json_encode(array_intersect_key($snapshot, array_flip(\App\Models\LandingPage::SNAPSHOT_FIELDS)), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'created_at'      => now(),
                ]);
            }
        }
    }
};
