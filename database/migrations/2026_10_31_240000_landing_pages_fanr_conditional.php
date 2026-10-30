<?php

use App\Models\LandingPage;
use App\Support\LandingPageSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * FANR only where it applies (Saroth, 30 Oct 2026): a medical centre needs FANR approval only with general X-ray
 * equipment, so the "how long" answer on the medical centre, Sharjah and Abu Dhabi pages says so. Dental centre and
 * day surgery keep the plain FANR line; the other four pages have none.
 */
return new class extends Migration
{
    private const SLUGS = ['medical-centre-dubai', 'medical-facility-sharjah', 'medical-facility-abu-dhabi'];

    private const OLD = 'It depends on your facility, the fit-out and authority steps such as FANR approval for X-ray equipment. You get a dated project plan after the free scoping call.';
    private const NEW = 'It depends on your facility, the fit-out and authority steps, including FANR approval if you install general X-ray equipment. You get a dated project plan after the free scoping call.';

    public function up(): void
    {
        $this->swap(self::OLD, self::NEW, 'FANR answer: only with general X-ray equipment (Saroth, 30 Oct 2026)');
    }

    public function down(): void
    {
        $this->swap(self::NEW, self::OLD, 'FANR answer change undone');
    }

    private function swap(string $from, string $to, string $note): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->whereIn('slug', self::SLUGS)->get(['id', 'content']) as $row) {
            $content = LandingPageSchema::normalize(json_decode((string) $row->content, true) ?: []);
            $changed = false;
            foreach ($content['sections'] as &$section) {
                if ($section['key'] !== 'faq') {
                    continue;
                }
                foreach ($section['data']['items'] as &$item) {
                    if ($item['answer'] === $from) {
                        $item['answer'] = $to;
                        $changed = true;
                    }
                }
                unset($item);
            }
            unset($section);
            if (!$changed) {
                continue;   // an admin already rewrote the answer: leave it
            }
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
