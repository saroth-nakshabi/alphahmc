<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Position & Company Name are optional on testimonials (validation is nullable),
 * but `testimonials.position` was still NOT NULL — saving an empty value threw a
 * DB error ("something went wrong"). Make both columns nullable. Idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('testimonials')) {
            return;
        }

        foreach (['position', 'company_name'] as $col) {
            $info = DB::selectOne(
                "SELECT IS_NULLABLE FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'testimonials' AND COLUMN_NAME = ?",
                [$col]
            );
            if ($info && strtoupper($info->IS_NULLABLE) === 'NO') {
                DB::statement("ALTER TABLE `testimonials` MODIFY `{$col}` VARCHAR(255) NULL");
            }
        }
    }

    public function down(): void
    {
        // Intentionally left nullable on rollback (reverting to NOT NULL would fail
        // for existing rows that legitimately have no position/company).
    }
};
