<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('planner_workflow_steps')) {
            Schema::create('planner_workflow_steps', function (Blueprint $table) {
                $table->id();
                $table->string('step_key')->unique();
                $table->string('label');
                $table->string('help_text')->nullable();
                $table->string('icon')->nullable();
                // choice | multichoice | text | admin
                $table->string('kind', 20)->default('choice');
                // none | regions | categories | services | custom
                $table->string('source', 20)->default('custom');
                $table->json('options')->nullable();         // for custom choice/multichoice
                $table->longText('admin_content')->nullable(); // for admin knowledge blocks
                $table->boolean('enabled')->default(true);
                $table->boolean('is_core')->default(false);   // core steps can't be deleted
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        $now = '2026-06-15 00:00:00';
        $seed = [
            [
                'step_key' => 'scope', 'label' => 'What would you like to do?', 'help_text' => 'Pick the goal that best fits your project.',
                'icon' => 'fa-solid fa-bullseye', 'kind' => 'choice', 'source' => 'custom',
                'options' => json_encode([
                    'Start a new healthcare facility', 'Expand or upgrade an existing facility',
                    'Achieve accreditation or licensing', 'Improve quality & operations',
                    'Staffing & outsourcing support', 'Something else',
                ]),
                'admin_content' => null, 'enabled' => 1, 'is_core' => 1, 'sort_order' => 1,
            ],
            [
                'step_key' => 'location', 'label' => 'Where is your project based?', 'help_text' => 'We align to the local health authority.',
                'icon' => 'fa-solid fa-location-dot', 'kind' => 'choice', 'source' => 'regions',
                'options' => json_encode([
                    'Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain',
                    'Saudi Arabia', 'Qatar', 'Oman', 'Kuwait', 'Bahrain', 'Other / Not sure',
                ]),
                'admin_content' => null, 'enabled' => 1, 'is_core' => 1, 'sort_order' => 2,
            ],
            [
                'step_key' => 'category', 'label' => 'Which areas do you need help with?', 'help_text' => 'Choose all that apply.',
                'icon' => 'fa-solid fa-layer-group', 'kind' => 'multichoice', 'source' => 'categories',
                'options' => null, 'admin_content' => null, 'enabled' => 1, 'is_core' => 1, 'sort_order' => 3,
            ],
            [
                'step_key' => 'service', 'label' => 'Any specific services in mind?', 'help_text' => 'Optional — pick any that are relevant.',
                'icon' => 'fa-solid fa-list-check', 'kind' => 'multichoice', 'source' => 'services',
                'options' => null, 'admin_content' => null, 'enabled' => 1, 'is_core' => 1, 'sort_order' => 4,
            ],
            [
                'step_key' => 'details', 'label' => 'Tell us about your goal or challenge', 'help_text' => 'Optional — the more you share, the sharper your plan.',
                'icon' => 'fa-solid fa-comment-dots', 'kind' => 'text', 'source' => 'none',
                'options' => null, 'admin_content' => null, 'enabled' => 1, 'is_core' => 1, 'sort_order' => 5,
            ],
            [
                'step_key' => 'process', 'label' => 'Our process', 'help_text' => 'Internal guidance used to shape the plan (not shown to the visitor).',
                'icon' => 'fa-solid fa-diagram-project', 'kind' => 'admin', 'source' => 'none', 'options' => null,
                'admin_content' => "Alpha Health Group follows a four-stage method: Research (gap analysis against best practice and the relevant authority's standards), Plan (a dedicated account manager builds a standards-aligned roadmap), Execute (specialist teams deliver each milestone with monitoring), and Results (outcomes reviewed against the baseline and sustained). Tailor each stage to the visitor's scope, location and selected areas.",
                'enabled' => 1, 'is_core' => 1, 'sort_order' => 6,
            ],
            [
                'step_key' => 'plan_details', 'label' => 'Cost & timeline guidance', 'help_text' => 'Internal cost/timeline notes the plan should reflect (not shown verbatim).',
                'icon' => 'fa-solid fa-coins', 'kind' => 'admin', 'source' => 'none', 'options' => null,
                'admin_content' => "Timeline: a new facility licensing + fit-out typically runs 4–9 months depending on emirate and facility type; accreditation programmes 6–12 months; quality/operations improvement 3–6 months. Cost: provide indicative ranges only and always frame as 'subject to a detailed scope'. Position Alpha as the partner that compresses timelines and de-risks spend through experience.",
                'enabled' => 1, 'is_core' => 1, 'sort_order' => 7,
            ],
        ];

        foreach ($seed as $row) {
            if (!DB::table('planner_workflow_steps')->where('step_key', $row['step_key'])->exists()) {
                DB::table('planner_workflow_steps')->insert($row + ['created_at' => $now, 'updated_at' => $now]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('planner_workflow_steps');
    }
};
