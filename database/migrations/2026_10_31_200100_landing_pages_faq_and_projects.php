<?php

use App\Models\LandingPage;
use App\Support\LandingPageSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Landing pages per facility type (SEO brief "alphahmc-facility-types" part B, Saroth 30 Oct 2026):
 * - the FANR X-ray clause leaves the "how long" answer where the facility type has no radiation equipment;
 * - the case studies match the facility type (none: the section shows a "See our projects" link);
 * - a new "Related services" section above the contact form, with the starting selection of the brief.
 * All three stay editable per page in the admin. Projects and pages resolve by slug (ids differ per database);
 * a slug missing on this database is left out. Each page gets a history entry; down() restores the old state.
 */
return new class extends Migration
{
    private const NO_RADIATION = ['home-healthcare-dubai', 'clinical-laboratory-dubai', 'dental-laboratory-dubai', 'aesthetic-centre-dubai'];

    private const FANR_ANSWER = 'It depends on your facility, the fit-out and authority steps such as FANR approval for X-ray equipment. You get a dated project plan after the free scoping call.';
    private const PLAIN_ANSWER = 'It depends on your facility, the fit-out and the authority steps. You get a dated project plan after the free scoping call.';

    private const PROJECTS = [
        'medical-centre-dubai'       => ['nhs-medical-center', 'beijing-therapy'],
        'aesthetic-centre-dubai'     => ['skin111', 'cryo-health-clinic'],
        'day-surgery-centre-dubai'   => ['broadway-vascular', 'bone-care'],
        'clinical-laboratory-dubai'  => ['sanimed-group'],
        'medical-facility-abu-dhabi' => ['cryo-health-clinic', 'mabc-abilities-development'],
        'dental-centre-dubai'        => [],
        'dental-laboratory-dubai'    => [],
        'home-healthcare-dubai'      => [],
        'medical-facility-sharjah'   => [],
    ];

    /** Card copy already written for these two; the others take the client and the case study headline. */
    private const PROJECT_COPY = [
        'nhs-medical-center' => ['title' => 'NHS Medical Center', 'type_label' => 'Medical complex, Dubai',
            'summary' => 'A visa and occupational screening complex, developed end to end from planning to operation.',
            'image_alt' => 'NHS Medical Center building in Dubai'],
        'broadway-vascular' => ['title' => 'Broadway Vascular Surgery Center', 'type_label' => 'Surgical facility',
            'summary' => 'A specialised vascular surgery facility, delivered turnkey.',
            'image_alt' => 'Broadway Vascular Surgery Center building'],
    ];

    private const SERVICES = [
        'dental-centre-dubai'        => ['service:radiation-equipment-fanr-registration-uae', 'service:medical-engineering-equipment-planning', 'service:medical-facility-fit-out'],
        'medical-centre-dubai'       => ['service:medical-centre-setup-licensing-dubai', 'service:clinic-setup-licensing-uae', 'service:dha-audit-preparation-inspection-consultation'],
        'aesthetic-centre-dubai'     => ['service:clinic-setup-licensing-uae', 'service:medical-facility-fit-out', 'service:healthcare-architectural-interior-design'],
        'home-healthcare-dubai'      => ['category:home-healthcare', 'service:healthcare-staffing-solutions', 'service:dha-audit-preparation-inspection-consultation'],
        'clinical-laboratory-dubai'  => ['category:diagnostic-labs-imaging-centers', 'service:iso-certification-consulting-for-healthcare-facilities', 'service:medical-engineering-equipment-planning'],
        'dental-laboratory-dubai'    => ['service:medical-facility-fit-out', 'service:medical-engineering-equipment-planning', 'service:clinic-setup-licensing-uae'],
        'day-surgery-centre-dubai'   => ['category:day-surgery-centers', 'service:hospital-infrastructure-planning-engineering-design', 'service:radiation-equipment-fanr-registration-uae'],
        'medical-facility-abu-dhabi' => ['service:doh-healthcare-facility-licensing-abu-dhabi', 'service:doh-inspection-preparation-services', 'service:adhics-audit-consultation'],
        'medical-facility-sharjah'   => ['service:mohap-license-healthcare-professionals', 'service:clinic-setup-licensing-uae', 'service:medical-facility-fit-out'],
    ];

    public function up(): void
    {
        $this->each(function (array $content, string $slug) {
            $out = [];
            foreach ($content['sections'] as $section) {
                if ($section['key'] === 'services') {
                    continue;   // placed again below, right above the contact form
                }
                if ($section['key'] === 'faq' && in_array($slug, self::NO_RADIATION, true)) {
                    foreach ($section['data']['items'] as &$item) {
                        if ($item['answer'] === self::FANR_ANSWER) {
                            $item['answer'] = self::PLAIN_ANSWER;
                        }
                    }
                    unset($item);
                }
                if ($section['key'] === 'projects' && array_key_exists($slug, self::PROJECTS)) {
                    $section['data']['items'] = $this->projectItems(self::PROJECTS[$slug]);
                }
                if ($section['key'] === 'contact') {
                    $out[] = ['key' => 'services', 'enabled' => true, 'data' => [
                        'heading' => 'Related services', 'text' => '',
                        'items' => array_map(fn ($t) => ['target' => $t, 'label' => ''], $this->targets(self::SERVICES[$slug] ?? [])),
                    ]];
                }
                $out[] = $section;
            }
            $content['sections'] = $out;

            return $content;
        }, 'FAQ, case studies and related services per facility type (SEO brief, 30 Oct 2026)');
    }

    public function down(): void
    {
        $this->each(function (array $content, string $slug) {
            $content['sections'] = array_values(array_filter($content['sections'], fn ($s) => $s['key'] !== 'services'));
            foreach ($content['sections'] as &$section) {
                if ($section['key'] === 'faq') {
                    foreach ($section['data']['items'] as &$item) {
                        if ($item['answer'] === self::PLAIN_ANSWER) {
                            $item['answer'] = self::FANR_ANSWER;
                        }
                    }
                    unset($item);
                }
                if ($section['key'] === 'projects') {
                    $section['data']['items'] = $this->projectItems(['nhs-medical-center', 'broadway-vascular']);
                }
            }
            unset($section);

            return $content;
        }, 'Per facility type FAQ, case studies and related services undone');
    }

    private function projectItems(array $slugs): array
    {
        $items = [];
        foreach ($slugs as $slug) {
            $p = DB::table('projects')->where('slug', $slug)->first(['id', 'name', 'client_name']);
            if (!$p) {
                continue;
            }
            $items[] = ['project_id' => (int) $p->id] + (self::PROJECT_COPY[$slug] ?? [
                'title' => (string) ($p->client_name ?: $p->name), 'type_label' => '',
                'summary' => (string) $p->name, 'image_alt' => (string) ($p->client_name ?: $p->name),
            ]);
        }

        return $items;
    }

    /** "service:slug" / "category:slug" => "service:ID" / "category:ID", leaving out slugs this database lacks. */
    private function targets(array $refs): array
    {
        $tables = ['service' => 'services', 'category' => 'categories', 'package' => 'service_groups'];
        $out = [];
        foreach ($refs as $ref) {
            [$type, $slug] = explode(':', $ref, 2);
            if ($id = DB::table($tables[$type])->where('slug', $slug)->value('id')) {
                $out[] = "$type:$id";
            }
        }

        return $out;
    }

    private function each(callable $change, string $note): void
    {
        if (!Schema::hasTable('landing_pages')) {
            return;
        }
        foreach (DB::table('landing_pages')->get(['id', 'slug', 'content']) as $row) {
            $content = LandingPageSchema::normalize(json_decode((string) $row->content, true) ?: []);
            $content = LandingPageSchema::normalize($change($content, $row->slug));
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
