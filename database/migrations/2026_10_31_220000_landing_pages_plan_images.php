<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per facility type floor plans for "The same floor, planned twice." (Saroth, 30 Oct 2026): an unplanned and a
 * planned layout image per landing page, uploaded in the landing editor. Without both, the built-in drawings show.
 */
return new class extends Migration
{
    private const COLUMNS = ['plan_before_image', 'plan_before_alt', 'plan_after_image', 'plan_after_alt'];

    public function up(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            foreach (self::COLUMNS as $column) {
                if (!Schema::hasColumn('landing_pages', $column)) {
                    $table->string($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(array_values(array_filter(self::COLUMNS, fn ($c) => Schema::hasColumn('landing_pages', $c))));
        });
    }
};
