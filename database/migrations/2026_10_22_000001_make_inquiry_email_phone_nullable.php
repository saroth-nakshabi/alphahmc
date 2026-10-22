<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The inquiry form accepts an email OR a phone, but both columns were NOT NULL, so phone-only
 * (and email-only) inquiries failed to save with a 500. Applied on prod by hand on 2026-10-22.
 * Idempotent: MODIFY to the same definition is a no-op.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inquiries')) {
            return;
        }

        DB::statement('ALTER TABLE inquiries
            MODIFY email VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY phone VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
    }

    public function down(): void
    {
        // Not reversible safely: rows with a NULL email or phone would block NOT NULL.
    }
};
