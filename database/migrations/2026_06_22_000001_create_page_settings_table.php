<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catch-up migration (2026-06-22): the `page_settings` table was originally
 * applied to local/prod by hand via SQL and never had a migration. This makes
 * the repo able to rebuild its own schema. Idempotent so it is safe to run on
 * an environment where the table already exists (e.g. via the prod SQL block).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('page_settings')) {
            return;
        }

        Schema::create('page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();        // registry key, e.g. "about", "all_services"
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_settings');
    }
};
