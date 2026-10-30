<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Case study fields (brief "alphahmc-case-studies" part 2, 30 Oct 2026): a browser title and meta description
 * of their own, closing paragraphs after the pillars, a reviewer with a review date (as on service pages and
 * blogs), and alt text per project image. All nullable: existing case studies keep their current output.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('projects', 'meta_description')) {
                $table->string('meta_description', 500)->nullable();
            }
            if (!Schema::hasColumn('projects', 'closing')) {
                $table->longText('closing')->nullable();
            }
            if (!Schema::hasColumn('projects', 'reviewer')) {
                $table->string('reviewer', 60)->nullable();
            }
            if (!Schema::hasColumn('projects', 'reviewed_at')) {
                $table->date('reviewed_at')->nullable();
            }
        });

        if (!Schema::hasColumn('project_images', 'alt')) {
            Schema::table('project_images', function (Blueprint $table) {
                $table->string('alt')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['meta_title', 'meta_description', 'closing', 'reviewer', 'reviewed_at'] as $col) {
            if (Schema::hasColumn('projects', $col)) {
                Schema::table('projects', fn (Blueprint $table) => $table->dropColumn($col));
            }
        }
        if (Schema::hasColumn('project_images', 'alt')) {
            Schema::table('project_images', fn (Blueprint $table) => $table->dropColumn('alt'));
        }
    }
};
