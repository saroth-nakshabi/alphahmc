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
        Schema::table('main_categories', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('name');
        });

        // Seed existing rows with their current id order
        \DB::statement('UPDATE main_categories SET sort_order = id');
    }

    public function down(): void
    {
        Schema::table('main_categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
