<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catch-up migration (2026-06-22): the About-page counters/stats table
 * (`about_counters`) was applied by hand via SQL and never migrated.
 * Content is fully dashboard-managed (AboutCounterController), so no seed
 * is included — the table is created empty. Idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('about_counters')) {
            return;
        }

        Schema::create('about_counters', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->default(0);          // numeric counter value
            $table->string('suffix', 12)->nullable();      // e.g. "+", "%", "K"
            $table->string('label');                       // caption under the number
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_counters');
    }
};
