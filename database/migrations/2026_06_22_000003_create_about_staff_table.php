<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catch-up migration (2026-06-22): the About-page leadership team table
 * (`about_staff`) was applied by hand via SQL and never migrated.
 * NOTE the table name is singular (`about_staff`) — the AboutStaff model
 * sets $table = 'about_staff'. Content is dashboard-managed. Idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('about_staff')) {
            return;
        }

        Schema::create('about_staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();           // role / position
            $table->string('image')->nullable();           // uploads/about_staff/...
            $table->text('short_description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_staff');
    }
};
