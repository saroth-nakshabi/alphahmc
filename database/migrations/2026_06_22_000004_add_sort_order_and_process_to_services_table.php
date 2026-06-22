<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catch-up migration (2026-06-22): `services.sort_order` (manual ordering) and
 * `services.project_process_id` (link to a shared ProjectProcess, resolved live
 * via the ResolvesProcess trait) were applied by hand via SQL and never migrated.
 * The original project_processes migration added the FK column to categories and
 * service_groups only — services was missed. Idempotent per-column guards.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        if (!Schema::hasColumn('services', 'sort_order')) {
            Schema::table('services', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('status');
            });
        }

        if (!Schema::hasColumn('services', 'project_process_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->unsignedBigInteger('project_process_id')->nullable()->after('sort_order');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        if (Schema::hasColumn('services', 'project_process_id')) {
            Schema::table('services', fn (Blueprint $t) => $t->dropColumn('project_process_id'));
        }
        if (Schema::hasColumn('services', 'sort_order')) {
            Schema::table('services', fn (Blueprint $t) => $t->dropColumn('sort_order'));
        }
    }
};
