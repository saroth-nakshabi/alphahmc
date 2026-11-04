<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * SEO brief "alphahmc-ahrefs-audit-fixes" (Saroth approved 4 Nov 2026), the data items:
 *   4. Links in stored text to 17 old URLs point straight at their final page (the old URLs redirect).
 *   7. The test title "Healthcare Professional Resourcing Services test1" on service 1.
 * Item 1 (menu promo links) was fixed directly on 4 Nov 2026. Changed rows are saved to
 * storage/app/migration-backups; down() restores them.
 */
return new class extends Migration
{
    private const BACKUP = 'migration-backups/2026_11_04_140000_ahrefs_audit_data.json';

    /** Columns holding the links (found by scanning every text column of the live database on 4 Nov 2026). */
    private const COLUMNS = [
        ['projects', 'closing'],
        ['service_magazines', 'description'],
        ['services', 'info_two'],
    ];

    /** Old path => final URL (each old path redirects there, or will after this release). */
    private const LINKS = [
        'services/healthcare-facility-licensing-abu-dhabi' => 'https://alphahmc.com/services/doh-healthcare-facility-licensing-abu-dhabi',
        'services/health-information-systems' => 'https://alphahmc.com/service-category/hie-integration',
        'services/health-information-system-integration' => 'https://alphahmc.com/service-category/hie-integration',
        'services/hospital-infrastructure-planning' => 'https://alphahmc.com/services/hospital-planning-requirement-mapping',
        'services/healthcare-business-setup-uae' => 'https://alphahmc.com/service-category/facility-licensing-setup',
        'services/healthcare-consulting' => 'https://alphahmc.com/service-packages/healthcare-management-consultancy-in-uae',
        'services/healthcare-regulatory-licensing' => 'https://alphahmc.com/service-category/regulatory-quality',
        'services/dha-healthcare-licensing' => 'https://alphahmc.com/services/dha-license-healthcare-professionals-dubai',
        'services/doh-healthcare-licensing' => 'https://alphahmc.com/service-category/healthcare-professional-licensing',
        'services/malaffi-nabidh-integration' => 'https://alphahmc.com/service-category/hie-integration',
        'services/medical-device-registration-uae' => 'https://alphahmc.com/services/medical-equipment-moh-licensing-consulting',
        'services/doh-licensing-compliance' => 'https://alphahmc.com/services/hospital-compliance-doh-consultation',
        'services/operational-audit' => 'https://alphahmc.com/service-category/audit-consultation',
        'services/healthcare-digitalization' => 'https://alphahmc.com/services/hospital-digital-transformation-consulting',
        'services/revenue-cycle-management' => 'https://alphahmc.com/service-category/insurance-empanelment',
        'services/healthcare-licensing-consultancy' => 'https://alphahmc.com/service-category/healthcare-professional-licensing',
        'about' => 'https://alphahmc.com/about-alpha-health-group',
    ];

    private const TEST_TITLE = 'Healthcare Professional Resourcing Services test1';
    private const NEW_TITLE = 'Healthcare Management Consultancy in the UAE | Alpha Health Group';

    public function up(): void
    {
        $saved = Storage::disk('local')->exists(self::BACKUP)
            ? json_decode(Storage::disk('local')->get(self::BACKUP), true) : [];
        $paths = implode('|', array_map(fn ($p) => preg_quote($p, '~'), array_keys(self::LINKS)));
        $re = '~(href\s*=\s*["\'])(?:https?://(?:www\.)?alphahmc\.com)?/(' . $paths . ')/?(?=["\'#?])~i';

        foreach (self::COLUMNS as [$table, $column]) {
            foreach (DB::table($table)->where($column, 'like', '%href%')->get(['id', $column]) as $row) {
                $new = preg_replace_callback($re, fn ($m) => $m[1] . self::LINKS[strtolower($m[2])], (string) $row->$column);
                if ($new !== null && $new !== $row->$column) {
                    $saved["$table.$column.$row->id"] ??= $row->$column;
                    DB::table($table)->where('id', $row->id)->update([$column => $new]);
                }
            }
        }

        $service = DB::table('services')->where('id', 1)->where('meta_title', self::TEST_TITLE)->first(['id']);
        if ($service) {
            $saved['services.meta_title.1'] ??= self::TEST_TITLE;
            DB::table('services')->where('id', 1)->update(['meta_title' => self::NEW_TITLE]);
        }

        Storage::disk('local')->put(self::BACKUP, json_encode($saved, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function down(): void
    {
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        foreach (json_decode(Storage::disk('local')->get(self::BACKUP), true) as $key => $value) {
            [$table, $column, $id] = explode('.', $key);
            DB::table($table)->where('id', $id)->update([$column => $value]);
        }
    }
};
