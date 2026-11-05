<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Arabic facility landing pages link the official authority (SEO agent, 5 Nov 2026; indexed YMYL pages need the
 * source): in the scope row "ترخيص المنشأة", the authority name already in the text links to its Arabic site.
 * No words change. down() removes the two links.
 */
return new class extends Migration
{
    private const LINKS = [
        'dubai'     => ['هيئة الصحة بدبي', 'https://www.dha.gov.ae/ar'],
        'abu-dhabi' => ['دائرة الصحة في أبوظبي', 'https://www.doh.gov.ae/ar'],
    ];

    public function up(): void
    {
        $this->apply(true);
    }

    public function down(): void
    {
        $this->apply(false);
    }

    private function apply(bool $on): void
    {
        foreach (self::LINKS as $slug => [$text, $url]) {
            $page = DB::table('landing_pages')->where('locale', 'ar')->where('slug', $slug)->first(['id', 'content']);
            if (!$page) {
                continue;
            }
            $content = json_decode($page->content, true);
            foreach ($content['sections'] as &$section) {
                if ($section['key'] !== 'scope') {
                    continue;
                }
                foreach ($section['data']['rows'] as &$row) {
                    if ($row['title'] === 'ترخيص المنشأة' && str_contains($row['text'], $text)) {
                        $row['link_text'] = $on ? $text : '';
                        $row['link_url'] = $on ? $url : '';
                    }
                }
                unset($row);
            }
            unset($section);
            DB::table('landing_pages')->where('id', $page->id)
                ->update(['content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
        }
    }
};
