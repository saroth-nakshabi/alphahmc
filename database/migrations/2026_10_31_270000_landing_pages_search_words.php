<?php

use App\Models\LandingPage;
use App\Models\LandingPageRevision;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ads landing pages use the searchers' words (SEO brief "alphahmc-landing-page-keywords", approved by Saroth
 * 31 Oct 2026; reviewed by Siraj H.M Ibrahim 31 Oct 2026). Text as in the guard-checked proposals
 * D:/SEO-agent/proposals/alphahmc-lp-<slug>.json.
 *  - Adds reviewer + reviewed_at to landing pages (the "Reviewed by" line and WebPage reviewedBy/lastReviewed).
 *  - Changes only the listed phrases inside each page's content, so other admin edits stay. Every phrase must be
 *    found exactly once (or the field must hold the expected value) before anything is written; otherwise the
 *    migration stops and names the miss.
 *  - Each page gets a revision before and after the change, so the admin can restore the old wording.
 * /facility-setup/medical-centre-dubai is left alone on purpose (on hold until about 27 Nov).
 */
return new class extends Migration
{
    private const COMPLIANT = ['set up a compliant facility that is ready to operate', 'set up a facility that is ready to operate'];

    /** slug => [seo_title, meta_description, [[field path or null, old, new], ...]] */
    private const PAGES = [
        'dental-centre-dubai' => [
            'Dental Clinic Setup and Licensing in Dubai',
            'Open your dental clinic or dental centre in Dubai with one team for layout, DHA licensing and the final inspection. Fixed fee proposal after a free call.',
            [
                ['hero.h1_highlight', 'dental centre', 'dental clinic'],
                [null, 'One team plans your layout around your specialties', 'One team plans your dental clinic around your specialties'],
                [null, 'How long does it take to open a dental centre?', 'How long does it take to open a dental clinic in Dubai?'],
                [null, 'What does it cost to set up a dental centre?', 'What does it cost to set up a dental clinic?'],
                [null, 'Can I open a dental centre if I am not a dentist?', 'Can I open a dental clinic if I am not a dentist?'],
            ],
        ],
        'dental-laboratory-dubai' => [
            'Dental Lab Setup and Licensing in Dubai',
            'Open your dental lab in Dubai with one team for layout, DHA licensing and the final inspection. Fixed fee proposal after a free scoping call.',
            [
                ['hero.h1_highlight', 'dental laboratory', 'dental lab'],
                [null, 'How long does it take to open a dental laboratory?', 'How long does it take to open a dental lab?'],
                [null, 'What does it cost to set up a dental laboratory?', 'What does it cost to set up a dental lab?'],
            ],
        ],
        'home-healthcare-dubai' => [
            'Home Healthcare and Home Care License in Dubai',
            'Open a home healthcare or home care company in Dubai: office setup, DHA licensing and the final inspection, with a fixed fee proposal after a free call.',
            [
                ['hero.h1_highlight', 'home healthcare company', 'home care company'],
                [null, 'One team sets up your office,', 'One team sets up your home healthcare office,'],
                [null, 'How long does it take to open a home healthcare company?', 'How long does it take to open a home care company?'],
            ],
        ],
        'medical-facility-sharjah' => [
            'Clinic Setup and SHA Licensing in Sharjah',
            'Open a clinic or medical centre in Sharjah with one team for layout, Sharjah Health Authority (SHA) licensing and the final inspection.',
            [
                ['hero.h1_highlight', 'medical facility', 'clinic or medical centre'],
                [null, 'secures your SHA license,', 'secures your Sharjah Health Authority (SHA) license,'],
                [null, 'How long does it take to open a medical facility?', 'How long does it take to open a clinic in Sharjah?'],
                [null, 'What does it cost to set up a medical facility?', 'What does it cost to set up a clinic in Sharjah?'],
                [null, 'Can I open a medical facility if I am not a doctor?', 'Can I open a clinic in Sharjah if I am not a doctor?'],
            ],
        ],
        'aesthetic-centre-dubai' => [
            'Aesthetic Clinic Setup and Licensing in Dubai',
            'Open your aesthetic or cosmetic clinic in Dubai with one team for layout, licensing and the final inspection. Fixed fee proposal after a free scoping call.',
            [
                ['hero.h1_highlight', 'aesthetic centre', 'aesthetic clinic'],
                [null, 'One team plans your clinical aesthetic centre,', 'One team plans your aesthetic or cosmetic clinic for clinical aesthetic services,'],
                [null, 'How long does it take to open an aesthetic centre?', 'How long does it take to open an aesthetic clinic?'],
                [null, 'What does it cost to set up an aesthetic centre?', 'What does it cost to set up an aesthetic clinic?'],
            ],
        ],
        'day-surgery-centre-dubai' => [
            null,
            null,
            [
                [null, 'One team plans your theatres and patient flow,', 'One team plans your theatres and patient flow for day surgery (ambulatory surgery),'],
            ],
        ],
        'clinical-laboratory-dubai' => [null, null, []],
        'medical-facility-abu-dhabi' => [null, null, []],
    ];

    public function up(): void
    {
        if (!Schema::hasColumn('landing_pages', 'reviewer')) {
            Schema::table('landing_pages', function (Blueprint $table) {
                $table->string('reviewer', 100)->nullable()->after('plan_after_alt');
                $table->date('reviewed_at')->nullable()->after('reviewer');
            });
        }

        // Work out every change first; write nothing if any phrase is missing.
        $planned = [];
        $misses = [];
        foreach (self::PAGES as $slug => [$title, $description, $changes]) {
            $page = LandingPage::where('slug', $slug)->first();
            if (!$page) {
                $misses[] = "$slug: page not found";
                continue;
            }
            $content = $page->content;
            foreach (array_merge($changes, [[null, ...self::COMPLIANT]]) as [$path, $old, $new]) {
                if ($path !== null) {
                    $current = data_get($content, $path);
                    if ($current === $new) {
                        continue;
                    }
                    if ($current !== $old) {
                        $misses[] = "$slug: $path is \"$current\", expected \"$old\"";
                        continue;
                    }
                    data_set($content, $path, $new);
                    continue;
                }
                $count = 0;
                $content = $this->replaceIn($content, $old, $new, $count);
                if ($count !== 1 && !$this->contains($content, $new)) {
                    $misses[] = "$slug: \"$old\" found $count times";
                }
            }
            $planned[$slug] = [$page, $content, $title, $description];
        }
        if ($misses) {
            throw new RuntimeException("Landing page wording not applied, nothing written:\n" . implode("\n", $misses));
        }

        DB::transaction(function () use ($planned) {
            foreach ($planned as [$page, $content, $title, $description]) {
                $this->revision($page, 'Before the search words change (SEO brief, 31 Oct 2026)');
                $page->content = $content;
                if ($title !== null) {
                    $page->seo_title = $title;
                }
                if ($description !== null) {
                    $page->meta_description = $description;
                }
                $page->save();
                DB::table('landing_pages')->where('id', $page->id)
                    ->update(['reviewer' => 'siraj-hm-ibrahim', 'reviewed_at' => '2026-10-31']);
                $this->revision($page, 'Search words from the SEO brief, reviewed by Siraj H.M Ibrahim 31 Oct 2026');
            }
        });
    }

    public function down(): void
    {
        // The old wording is in each page's revisions ("Before the search words change"); restore it from the admin.
        if (Schema::hasColumn('landing_pages', 'reviewer')) {
            Schema::table('landing_pages', function (Blueprint $table) {
                $table->dropColumn(['reviewer', 'reviewed_at']);
            });
        }
    }

    /** Replaces $old in every string of $value; counts the strings changed. */
    private function replaceIn($value, string $old, string $new, int &$count)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->replaceIn($v, $old, $new, $count);
            }
            return $value;
        }
        if (is_string($value) && str_contains($value, $old)) {
            $count += substr_count($value, $old);
            return str_replace($old, $new, $value);
        }
        return $value;
    }

    private function contains($value, string $needle): bool
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                if ($this->contains($v, $needle)) {
                    return true;
                }
            }
            return false;
        }
        return is_string($value) && str_contains($value, $needle);
    }

    private function revision(LandingPage $page, string $note): void
    {
        LandingPageRevision::create([
            'landing_page_id' => $page->id,
            'user_id' => null,
            'user_name' => 'Website terminal',
            'note' => $note,
            'snapshot' => $page->fresh()->snapshot(),
        ]);
    }
};
