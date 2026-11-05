<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Hidden "Test" placeholders (SEO agent, Saroth approved 5 Nov 2026). None is shown on a page:
 *   service_groups 13 core_service_header/description (the package template does not render them),
 *   categories 1 service_header and content (the category template does not render them; content is only the
 *   placeholder line and two empty boxes), strategies 1 (old module, routes only redirect, nothing references it),
 *   project_processes 5 (no category or package uses it).
 * Each change applies only while the placeholder is still there. Rows are saved to storage/app/migration-backups
 * first; down() puts them back.
 */
return new class extends Migration
{
    private const BACKUP = 'migration-backups/2026_11_05_120000_remove_hidden_test_placeholders.json';

    public function up(): void
    {
        $saved = Storage::disk('local')->exists(self::BACKUP) ? json_decode(Storage::disk('local')->get(self::BACKUP), true) : [];

        $group = DB::table('service_groups')->where('id', 13)->first(['id', 'core_service_header', 'core_service_description']);
        if ($group && str_contains((string) $group->core_service_header, 'Test Core Service 1')) {
            $saved['service_groups'] ??= (array) $group;
            DB::table('service_groups')->where('id', 13)->update(['core_service_header' => null, 'core_service_description' => null]);
        }

        $category = DB::table('categories')->where('id', 1)->first(['id', 'service_header', 'content']);
        if ($category && $category->service_header === 'Test Service Header 2' && str_starts_with((string) $category->content, '<p>Test Service details')) {
            $saved['categories'] ??= (array) $category;
            DB::table('categories')->where('id', 1)->update(['service_header' => null, 'content' => null]);
        }

        $strategy = DB::table('strategies')->where('id', 1)->first();
        if ($strategy && str_starts_with((string) $strategy->name, 'Test')) {
            $saved['strategies'] ??= (array) $strategy;
            DB::table('strategies')->where('id', 1)->delete();
        }

        $process = DB::table('project_processes')->where('id', 5)->first();
        $inUse = DB::table('categories')->where('project_process_id', 5)->exists() || DB::table('service_groups')->where('project_process_id', 5)->exists();
        if ($process && !$inUse && str_contains((string) $process->process_header, 'Test 1')) {
            $saved['project_processes'] ??= (array) $process;
            DB::table('project_processes')->where('id', 5)->delete();
        }

        Storage::disk('local')->put(self::BACKUP, json_encode($saved, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function down(): void
    {
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        $saved = json_decode(Storage::disk('local')->get(self::BACKUP), true);
        foreach (['service_groups', 'categories'] as $table) {
            if (!empty($saved[$table])) {
                DB::table($table)->where('id', $saved[$table]['id'])->update(array_diff_key($saved[$table], ['id' => 1]));
            }
        }
        foreach (['strategies', 'project_processes'] as $table) {
            if (!empty($saved[$table]) && !DB::table($table)->where('id', $saved[$table]['id'])->exists()) {
                DB::table($table)->insert($saved[$table]);
            }
        }
    }
};
