<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'published_date')) {
                $table->date('published_date')->nullable()->after('meta_keywords');
            }
            if (!Schema::hasColumn('blogs', 'updated_date')) {
                $table->date('updated_date')->nullable()->after('published_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $cols = array_values(array_filter(
                ['published_date', 'updated_date'],
                fn ($c) => Schema::hasColumn('blogs', $c)
            ));
            if ($cols) {
                $table->dropColumn($cols);
            }
        });
    }
};
