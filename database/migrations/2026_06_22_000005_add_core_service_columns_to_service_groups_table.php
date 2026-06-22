<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catch-up migration (2026-06-22): `service_groups.core_service_header` and
 * `service_groups.core_service_description` (both store JSON arrays via the
 * ServiceGroup get/set accessors) were applied by hand via SQL and never
 * migrated. Distinct from `service_details_header`, which already has its own
 * migration. Idempotent per-column guards.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('service_groups')) {
            return;
        }

        if (!Schema::hasColumn('service_groups', 'core_service_header')) {
            Schema::table('service_groups', function (Blueprint $table) {
                $table->longText('core_service_header')->nullable()->after('service_details_header');
            });
        }

        if (!Schema::hasColumn('service_groups', 'core_service_description')) {
            Schema::table('service_groups', function (Blueprint $table) {
                $table->longText('core_service_description')->nullable()->after('core_service_header');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('service_groups')) {
            return;
        }

        if (Schema::hasColumn('service_groups', 'core_service_description')) {
            Schema::table('service_groups', fn (Blueprint $t) => $t->dropColumn('core_service_description'));
        }
        if (Schema::hasColumn('service_groups', 'core_service_header')) {
            Schema::table('service_groups', fn (Blueprint $t) => $t->dropColumn('core_service_header'));
        }
    }
};
