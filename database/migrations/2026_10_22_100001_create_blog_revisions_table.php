<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Staged rewrites of a live post (brief alphahmc-blog-api.md, 3.2). One pending revision per
 * blog (blog_id unique). No slug column: a revision never renames the post.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blog_revisions')) {
            return;
        }

        Schema::create('blog_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->unique()->constrained('blogs')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_keywords', 1000)->nullable();
            $table->json('sources')->nullable();
            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_credential')->nullable();
            $table->string('reviewer_url', 2048)->nullable();
            $table->date('last_reviewed')->nullable();
            $table->timestamp('admin_edited_at')->nullable();
            $table->timestamp('agent_written_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_revisions');
    }
};
