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
        if (Schema::hasColumn('brands', 'sort_order')) {
            return; // column already added directly on the server (host drift)
        }

        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('name');
        });

        // Seed existing rows with their current id order
        \DB::statement('UPDATE brands SET sort_order = id');
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
