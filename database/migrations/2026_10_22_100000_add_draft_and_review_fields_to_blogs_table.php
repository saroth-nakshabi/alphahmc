<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Drafts and review data for the SEO agent's blog API (brief alphahmc-blog-api.md, 3.1).
 * status defaults to 'published', so every existing post stays live. Each column is guarded.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'status')) {
                $table->string('status', 16)->default('published')->index();
            }
            if (!Schema::hasColumn('blogs', 'reviewer_name')) {
                $table->string('reviewer_name')->nullable();
            }
            if (!Schema::hasColumn('blogs', 'reviewer_credential')) {
                $table->string('reviewer_credential')->nullable();
            }
            if (!Schema::hasColumn('blogs', 'reviewer_url')) {
                $table->string('reviewer_url', 2048)->nullable();
            }
            if (!Schema::hasColumn('blogs', 'last_reviewed')) {
                $table->date('last_reviewed')->nullable();
            }
            if (!Schema::hasColumn('blogs', 'sources')) {
                $table->json('sources')->nullable();
            }
            if (!Schema::hasColumn('blogs', 'admin_edited_at')) {
                $table->timestamp('admin_edited_at')->nullable();
            }
            if (!Schema::hasColumn('blogs', 'agent_written_at')) {
                $table->timestamp('agent_written_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            foreach (['status', 'reviewer_name', 'reviewer_credential', 'reviewer_url', 'last_reviewed', 'sources', 'admin_edited_at', 'agent_written_at'] as $column) {
                if (Schema::hasColumn('blogs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
