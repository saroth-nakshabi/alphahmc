<?php

namespace App\Support;

/**
 * What a landing page's `content` JSON may hold (brief "alphahmc-facility-landing-build", 30 Oct 2026).
 *
 * The admin editor is drawn from this schema and normalize() keeps only what it lists, so admins edit
 * copy while the template, typography and tracking stay in code. Shape:
 *   ['page' => [field => value], 'hero' => [...], 'sections' => [['key' => .., 'enabled' => bool, 'data' => [..]], ..]]
 * Section `data` holds its fields plus its lists (each a list of rows of fields).
 * Field types: text, textarea, int, project (a projects.id).
 */
class LandingPageSchema
{
    /** Page-wide copy: header, WhatsApp, phone and the phone dock. */
    public const PAGE = [
        'label'  => 'Header, WhatsApp and phone',
        'fields' => [
            'phone_display'  => ['Phone shown in the header', 'text'],
            'phone_tel'      => ['Phone to dial (digits with +)', 'text'],
            'wa_number'      => ['WhatsApp number (digits only, with country code)', 'text', true],
            'wa_text'        => ['WhatsApp prefilled message', 'text', true],
            'wa_header'      => ['Header WhatsApp button', 'text'],
            'dock_wa'        => ['Phone dock: WhatsApp button', 'text'],
            'dock_proposal'  => ['Phone dock: proposal button', 'text'],
            'wa_window_title'   => ['WhatsApp window: title', 'text'],
            'wa_window_subtitle'=> ['WhatsApp window: line under the title', 'text'],
            'wa_window_message' => ['WhatsApp window: welcome message', 'textarea'],
            'wa_window_send'    => ['WhatsApp window: send button label (for screen readers)', 'text'],
        ],
    ];

    public const HERO = [
        'label'  => 'Hero',
        'fields' => [
            'crumb_parent'    => ['Breadcrumb: first part', 'text'],
            'crumb_current'   => ['Breadcrumb: this page', 'text'],
            'h1_before'       => ['H1: text before the highlight', 'text', true],
            'h1_highlight'    => ['H1: highlighted words (teal)', 'text', true],
            'h1_after'        => ['H1: text after the highlight', 'text'],
            'intro'           => ['Intro line', 'textarea', true],
            'wa_button'       => ['WhatsApp button label', 'text', true],
            'proposal_button' => ['Proposal button label', 'text', true],
            'chips_label'     => ['Label before the facility links', 'text'],
            'list_label'      => ['Label before the facilities list (pages without facility links)', 'text'],
            'caption_bold'    => ['Photo caption: bold part', 'text'],
            'caption'         => ['Photo caption: rest', 'text'],
        ],
        'lists' => [
            'facilities' => ['Facilities we set up (shown when no facility links are chosen)', ['name' => ['Facility', 'text']]],
        ],
    ];

    /**
     * Section key => label, fields, lists. The key also picks the Blade partial
     * (front/landing/sections/{key}) and, for nav sections, the anchor id.
     */
    public const SECTIONS = [
        'authorities' => [
            'label'  => 'Authorities strip',
            'fields' => ['label' => ['Lead-in text', 'text']],
            'lists'  => ['items' => ['Authorities', ['name' => ['Name', 'text']]]],
        ],
        'thesis' => [
            'label'  => 'Quote: most delays start on the drawing board',
            'fields' => [
                'quote_before'    => ['Quote: before the highlight', 'textarea'],
                'quote_highlight' => ['Quote: highlighted words', 'text'],
                'quote_after'     => ['Quote: after the highlight', 'text'],
            ],
            'lists' => ['items' => ['Points', ['title' => ['Title', 'text'], 'text' => ['Text', 'textarea']]]],
        ],
        'beyond' => [
            'label'  => 'Beyond approval: in-house teams',
            'fields' => [
                'heading'    => ['Heading', 'text', true],
                'text'       => ['Text', 'textarea'],
                'list_label' => ['Teams list label', 'text'],
            ],
            'lists' => ['items' => ['Teams', ['title' => ['Team', 'text'], 'text' => ['What they do', 'textarea']]]],
        ],
        'planning' => [
            'label'  => 'Floor comparison and key facts',
            'nav'    => true,
            'fields' => [
                'nav_label'      => ['Header link label', 'text'],
                'heading'        => ['Heading', 'text', true],
                'text'           => ['Text', 'textarea'],
                'tag_before'     => ['Comparison tag: left', 'text'],
                'tag_after'      => ['Comparison tag: right', 'text'],
                'note'           => ['Note under the drawing', 'text'],
                'legend_rooms'   => ['Legend: clinical rooms', 'text'],
                'legend_lost'    => ['Legend: lost space', 'text'],
                'facts_heading'  => ['Key facts heading', 'text'],
            ],
            'lists' => ['facts' => ['Key facts', ['text' => ['Fact', 'textarea']]]],
        ],
        'scope' => [
            'label'  => 'Scope: everything between the idea and the license',
            'nav'    => true,
            'fields' => [
                'nav_label' => ['Header link label', 'text'],
                'heading'   => ['Heading', 'text', true],
                'text'      => ['Text', 'textarea'],
                'image_alt' => ['Photo alt text', 'text'],
                'optional'  => ['Line under the rows', 'textarea'],
            ],
            'lists' => ['rows' => ['Scope rows (rows with the same group sit together)', [
                'group' => ['Group', 'text'], 'title' => ['Title', 'text'], 'text' => ['Text', 'textarea'],
            ]]],
        ],
        'plan' => [
            'label'  => 'Project plan',
            'nav'    => true,
            'fields' => [
                'nav_label' => ['Header link label', 'text'],
                'heading'   => ['Heading', 'text', true],
                'text'      => ['Text', 'textarea'],
                'foot_note' => ['Note under the plan', 'text'],
                'foot_link' => ['Link label under the plan', 'text'],
            ],
            'lists' => ['phases' => ['Phases (bar start and end on a shared scale, end after start)', [
                'title' => ['Title', 'text'], 'text' => ['Text', 'textarea'],
                'start' => ['Bar start', 'int'], 'end' => ['Bar end', 'int'],
            ]]],
        ],
        'excluded' => [
            'label'  => 'What is not in our fee',
            'fields' => ['heading' => ['Heading', 'text', true], 'text' => ['Text', 'text']],
            'lists'  => ['items' => ['Items', ['text' => ['Item', 'text']]]],
        ],
        'others' => [
            'label'  => 'Other facility types (links to the other published landing pages)',
            'fields' => ['heading' => ['Heading', 'text', true], 'text' => ['Text', 'text']],
        ],
        'projects' => [
            'label'  => 'Facilities we have delivered',
            'nav'    => true,
            'fields' => [
                'nav_label'     => ['Header link label', 'text'],
                'heading'       => ['Heading', 'text', true],
                'see_all_label' => ['See all projects button', 'text'],
                'card_link'     => ['Card link label', 'text'],
            ],
            'lists' => ['items' => ['Projects', [
                'project_id' => ['Project', 'project'], 'title' => ['Card title', 'text'],
                'type_label' => ['Label above the title', 'text'], 'summary' => ['Summary', 'textarea'],
                'image_alt'  => ['Photo alt text', 'text'],
            ]]],
        ],
        'rescue' => [
            'label'  => 'Started with someone else',
            'fields' => ['heading' => ['Heading', 'text', true], 'text' => ['Text', 'textarea']],
        ],
        'faq' => [
            'label'  => 'Questions (also the FAQ schema)',
            'nav'    => true,
            'fields' => ['nav_label' => ['Header link label', 'text'], 'heading' => ['Heading', 'text', true]],
            'lists'  => ['items' => ['Questions', ['question' => ['Question', 'text'], 'answer' => ['Answer', 'textarea']]]],
        ],
        'contact' => [
            'label'  => 'Contact and form',
            'fields' => [
                'heading'       => ['Heading', 'text', true],
                'text'          => ['Text', 'textarea'],
                'call_button'   => ['Call button', 'text'],
                'wa_button'     => ['WhatsApp button', 'text'],
                'form_button'   => ['Send an inquiry button (opens the form)', 'text'],
                'wa_label'      => ['WhatsApp line label', 'text'],
                'wa_display'    => ['WhatsApp number as shown', 'text'],
                'call_label'    => ['Phone line label', 'text'],
                'form_heading'  => ['Form heading', 'text'],
                'submit_label'  => ['Submit button', 'text'],
                'success_text'  => ['Message after sending', 'textarea'],
                'fine_print'    => ['Line under the form', 'text'],
            ],
        ],
    ];

    /** Options of the form's facility and emirate fields. */
    public const FACILITY_TYPES = [
        'Dental centre', 'Medical centre', 'Aesthetic centre', 'Home healthcare', 'Clinical laboratory',
        'Dental laboratory', 'Day surgery centre', 'Other facility',
    ];
    public const EMIRATES = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Northern Emirates', 'Not decided'];

    /** Keep only what the schema lists, as strings or ints, with sections in the given order. */
    public static function normalize(array $content): array
    {
        $out = [
            'page' => self::cleanFields(self::PAGE['fields'], $content['page'] ?? []),
            'hero' => self::cleanFields(self::HERO['fields'], $content['hero'] ?? [])
                + self::cleanLists(self::HERO['lists'], $content['hero'] ?? []),
            'sections' => [],
        ];

        $seen = [];
        foreach ($content['sections'] ?? [] as $section) {
            $key = $section['key'] ?? null;
            if (!is_string($key) || !isset(self::SECTIONS[$key]) || isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $def  = self::SECTIONS[$key];
            $data = $section['data'] ?? [];
            $out['sections'][] = [
                'key'     => $key,
                'enabled' => filter_var($section['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'data'    => self::cleanFields($def['fields'], $data) + self::cleanLists($def['lists'] ?? [], $data),
            ];
        }

        // A section missing from the input is kept, switched off, so the editor always shows all of them.
        foreach (self::SECTIONS as $key => $def) {
            if (!isset($seen[$key])) {
                $out['sections'][] = ['key' => $key, 'enabled' => false,
                    'data' => self::cleanFields($def['fields'], []) + self::cleanLists($def['lists'] ?? [], [])];
            }
        }

        return $out;
    }

    /**
     * Problems that block saving: required fields of the hero and of every switched-on section,
     * and phase bars that end before they start.
     *
     * @return array<string, string> path => message
     */
    public static function errors(array $content): array
    {
        $errors = [];
        foreach (['page' => self::PAGE, 'hero' => self::HERO] as $part => $def) {
            foreach ($def['fields'] as $f => $spec) {
                if (!empty($spec[2]) && trim((string) ($content[$part][$f] ?? '')) === '') {
                    $errors["$part.$f"] = $spec[0] . ' is required.';
                }
            }
        }
        foreach ($content['sections'] as $i => $section) {
            if (!$section['enabled']) {
                continue;
            }
            $def = self::SECTIONS[$section['key']];
            foreach ($def['fields'] as $f => $spec) {
                if (!empty($spec[2]) && trim((string) ($section['data'][$f] ?? '')) === '') {
                    $errors["sections.$i.$f"] = $def['label'] . ': ' . $spec[0] . ' is required.';
                }
            }
            if ($section['key'] === 'plan') {
                foreach ($section['data']['phases'] as $j => $phase) {
                    if ($phase['end'] <= $phase['start']) {
                        $errors["sections.$i.phases.$j.end"] = 'Project plan, phase ' . ($j + 1) . ': the bar must end after it starts.';
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Copy-rule warnings (they do not block saving): em or en dashes, and "compliant" or "compliance".
     *
     * @return string[]
     */
    public static function copyWarnings(array $content, array $pageFields = []): array
    {
        $warnings = [];
        $check = function (string $where, $value) use (&$warnings) {
            if (!is_string($value) || $value === '') {
                return;
            }
            if (preg_match('/[\x{2013}\x{2014}]/u', $value)) {
                $warnings[] = "$where contains an em or en dash.";
            }
            if (preg_match('/\bcomplian(t|ce)\b/i', $value)) {
                $warnings[] = "$where uses \"compliant\" or \"compliance\".";
            }
        };

        foreach ($pageFields as $label => $value) {
            $check($label, $value);
        }
        foreach (self::PAGE['fields'] as $f => $spec) {
            $check($spec[0], $content['page'][$f] ?? '');
        }
        foreach (self::HERO['fields'] as $f => $spec) {
            $check('Hero: ' . $spec[0], $content['hero'][$f] ?? '');
        }
        foreach ($content['hero']['facilities'] ?? [] as $row) {
            $check('Hero: facilities list', $row['name'] ?? '');
        }
        foreach ($content['sections'] as $section) {
            $def = self::SECTIONS[$section['key']];
            foreach ($def['fields'] as $f => $spec) {
                $check($def['label'] . ': ' . $spec[0], $section['data'][$f] ?? '');
            }
            foreach ($def['lists'] ?? [] as $list => [$listLabel, $cols]) {
                foreach ($section['data'][$list] ?? [] as $n => $row) {
                    foreach ($row as $value) {
                        $check($def['label'] . ': ' . $listLabel . ' row ' . ($n + 1), $value);
                    }
                }
            }
        }

        return array_values(array_unique($warnings));
    }

    /** Data of a switched-on section, or null. */
    public static function section(array $content, string $key): ?array
    {
        foreach ($content['sections'] ?? [] as $section) {
            if ($section['key'] === $key) {
                return $section['enabled'] ? $section['data'] : null;
            }
        }

        return null;
    }

    private static function cleanFields(array $fields, $input): array
    {
        $input = is_array($input) ? $input : [];
        $out = [];
        foreach ($fields as $f => $spec) {
            $out[$f] = self::cleanValue($spec[1], $input[$f] ?? null);
        }

        return $out;
    }

    private static function cleanLists(array $lists, $input): array
    {
        $input = is_array($input) ? $input : [];
        $out = [];
        foreach ($lists as $list => [, $cols]) {
            $rows = [];
            foreach (is_array($input[$list] ?? null) ? $input[$list] : [] as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $clean = self::cleanFields($cols, $row);
                // Skip rows the admin added but left empty.
                if (array_filter($clean, fn ($v) => $v !== '' && $v !== 0) === []) {
                    continue;
                }
                $rows[] = $clean;
            }
            $out[$list] = $rows;
        }

        return $out;
    }

    private static function cleanValue(string $type, $value)
    {
        if ($type === 'int' || $type === 'project') {
            return is_numeric($value) ? max(0, (int) $value) : 0;
        }

        return is_scalar($value) ? trim(str_replace("\r\n", "\n", (string) $value)) : '';
    }
}
