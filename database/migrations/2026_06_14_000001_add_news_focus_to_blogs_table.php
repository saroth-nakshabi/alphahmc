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
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'news_focus')) {
                // Comma-separated, up to 3 values (e.g. "Corporate Updates, Strategic Growth, Media Releases")
                $table->string('news_focus')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'news_focus')) {
                $table->dropColumn('news_focus');
            }
        });
    }
};
