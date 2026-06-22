<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds `sort_order` to projects (case studies) so they can be drag-reordered in
 * the dashboard, the same way brands/categories/blogs are. Idempotent; seeds the
 * initial order from id so existing rows keep a stable sequence.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('projects') || Schema::hasColumn('projects', 'sort_order')) {
            return;
        }
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('featured');
        });
        DB::statement('UPDATE projects SET sort_order = id');
    }

    public function down(): void
    {
        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'sort_order')) {
            Schema::table('projects', fn (Blueprint $t) => $t->dropColumn('sort_order'));
        }
    }
};
