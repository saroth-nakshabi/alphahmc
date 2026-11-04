<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Test text left on live pages (SEO agent, 4 Nov 2026; Saroth's decisions in the website terminal the same day).
 *   Service 1 (/services/healthcare-management-consultancy-in-uae): staffing description ending "test2" replaced
 *   with the SEO agent's text; staffing keywords ("test3") removed, so the page prints no keywords tag;
 *   "Middle East test4" -> "Middle East"; " test5" dropped from the admin-only serviceType.
 *   Package 13: the placeholder process ("Test 1" / "Process Description") cleared, so the section is hidden.
 * The previous values are saved to storage/app/migration-backups; down() restores them.
 */
return new class extends Migration
{
    private const BACKUP = 'migration-backups/2026_11_04_160000_remove_test_text.json';

    private const SERVICE_COLUMNS = ['meta_description', 'meta_keywords', 'areaServed', 'serviceType'];

    private const GROUP_COLUMNS = ['project_process_id', 'process_intro', 'process_header', 'process_description', 'process_service_ids'];

    private const DESCRIPTION = 'Healthcare management consultancy in the UAE: regulatory compliance, business strategy and operational audits for medical centres and hospitals.';

    public function up(): void
    {
        $service = DB::table('services')->where('id', 1)->where('slug', 'healthcare-management-consultancy-in-uae')
            ->first(array_merge(['id'], self::SERVICE_COLUMNS));
        $group = DB::table('service_groups')->where('id', 13)->first(array_merge(['id'], self::GROUP_COLUMNS));

        if (!Storage::disk('local')->exists(self::BACKUP)) {
            Storage::disk('local')->put(self::BACKUP, json_encode(['service' => $service, 'group' => $group],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        if ($service) {
            $row = [];
            if (str_contains((string) $service->meta_description, 'test2')) {
                $row['meta_description'] = self::DESCRIPTION;
            }
            if (str_contains((string) $service->meta_keywords, 'test3')) {
                $row['meta_keywords'] = null;
            }
            $row['areaServed'] = preg_replace('/\s*test\s*4\b/i', '', (string) $service->areaServed);
            $row['serviceType'] = preg_replace('/\s*test\s*5\b/i', '', (string) $service->serviceType);
            DB::table('services')->where('id', 1)->update($row + ['updated_at' => now()]);
        }

        if ($group && str_contains((string) $group->process_header, 'Test 1')) {
            DB::table('service_groups')->where('id', 13)->update([
                'project_process_id' => null, 'process_intro' => null, 'process_header' => null,
                'process_description' => null, 'process_service_ids' => null, 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        $saved = json_decode(Storage::disk('local')->get(self::BACKUP), true);
        if (!empty($saved['service'])) {
            DB::table('services')->where('id', 1)->update(array_intersect_key($saved['service'], array_flip(self::SERVICE_COLUMNS)));
        }
        if (!empty($saved['group'])) {
            DB::table('service_groups')->where('id', 13)->update(array_intersect_key($saved['group'], array_flip(self::GROUP_COLUMNS)));
        }
    }
};
