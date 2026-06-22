<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Catch-up migration (2026-06-22): the original create_faqs_table made
 * `service_id` NOT NULL (constrained). FAQs are now also attached to categories
 * and service groups (Category::faqs / ServiceGroup::faqs), so `service_id` was
 * made nullable and `category_id` + `service_group_id` columns were added — all
 * by hand via SQL, never migrated. Idempotent.
 *
 * The nullable change uses a raw MODIFY (the FK constraint on service_id can stay
 * in place during a nullability change) and only runs if the column is currently
 * NOT NULL, so it is safe to re-run.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('faqs')) {
            return;
        }

        // Add the new link columns (no FK constraint to stay tolerant of legacy data).
        if (!Schema::hasColumn('faqs', 'category_id')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('service_id');
            });
        }
        if (!Schema::hasColumn('faqs', 'service_group_id')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->unsignedBigInteger('service_group_id')->nullable()->after('category_id');
            });
        }

        // Make service_id nullable only if it is still NOT NULL.
        // (SHOW COLUMNS does not accept bound parameters on MariaDB, so query
        //  information_schema against the current database instead.)
        $col = DB::selectOne(
            "SELECT IS_NULLABLE FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'faqs' AND COLUMN_NAME = 'service_id'"
        );
        if ($col && strtoupper($col->IS_NULLABLE) === 'NO') {
            DB::statement('ALTER TABLE faqs MODIFY service_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('faqs')) {
            return;
        }

        if (Schema::hasColumn('faqs', 'service_group_id')) {
            Schema::table('faqs', fn (Blueprint $t) => $t->dropColumn('service_group_id'));
        }
        if (Schema::hasColumn('faqs', 'category_id')) {
            Schema::table('faqs', fn (Blueprint $t) => $t->dropColumn('category_id'));
        }
        // service_id is intentionally left nullable on rollback (reverting to NOT NULL
        // would fail if any FAQ is attached to a category/service group instead).
    }
};
