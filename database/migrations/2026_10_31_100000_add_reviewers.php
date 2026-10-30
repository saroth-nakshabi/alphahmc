<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reviewers (brief "alphahmc-reviewers-and-linking", 30 Oct 2026): bio pages at /team/{key}, and a
 * reviewer + review date on service pages, categories, packages and blog posts ("Reviewed by" line,
 * shown only once reviewed_at is set). Blogs also get service_link: the service page each guide links to.
 * Idempotent: every step checks first.
 */
return new class extends Migration
{
    private const PAGE_TABLES = ['services', 'categories', 'service_groups', 'blogs'];

    public function up(): void
    {
        if (!Schema::hasTable('reviewers')) {
            Schema::create('reviewers', function (Blueprint $table) {
                $table->id();
                $table->string('key', 60)->unique();
                $table->string('name');
                $table->string('title');
                $table->text('qualifications')->nullable();
                $table->text('experience')->nullable();
                $table->text('bio')->nullable();
                $table->text('reviews')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('photo')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        foreach (self::PAGE_TABLES as $name) {
            Schema::table($name, function (Blueprint $table) use ($name) {
                if (!Schema::hasColumn($name, 'reviewer')) {
                    $table->string('reviewer', 60)->nullable();
                }
                if (!Schema::hasColumn($name, 'reviewed_at')) {
                    $table->date('reviewed_at')->nullable();
                }
            });
        }

        if (!Schema::hasColumn('blogs', 'service_link')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('service_link')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blogs', 'service_link')) {
            Schema::table('blogs', fn (Blueprint $table) => $table->dropColumn('service_link'));
        }
        foreach (self::PAGE_TABLES as $name) {
            foreach (['reviewed_at', 'reviewer'] as $col) {
                if (Schema::hasColumn($name, $col)) {
                    Schema::table($name, fn (Blueprint $table) => $table->dropColumn($col));
                }
            }
        }
        Schema::dropIfExists('reviewers');
    }
};
