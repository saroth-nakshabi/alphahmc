<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds SEO meta fields + areaServed to brands (2026-06-22) so brand detail
 * pages can carry their own meta_title/description/keywords and schema area.
 * Idempotent per-column guards.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('brands')) {
            return;
        }

        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'meta_title'))       $table->string('meta_title')->nullable()->after('google_location');
            if (!Schema::hasColumn('brands', 'meta_description'))  $table->text('meta_description')->nullable()->after('meta_title');
            if (!Schema::hasColumn('brands', 'meta_keywords'))     $table->text('meta_keywords')->nullable()->after('meta_description');
            if (!Schema::hasColumn('brands', 'areaServed'))        $table->string('areaServed')->nullable()->after('meta_keywords');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('brands')) {
            return;
        }
        Schema::table('brands', function (Blueprint $table) {
            foreach (['meta_title', 'meta_description', 'meta_keywords', 'areaServed'] as $c) {
                if (Schema::hasColumn('brands', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
