<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'info_three')) {
                $table->longText('info_three')->nullable();
            }
            if (!Schema::hasColumn('services', 'info_four')) {
                $table->longText('info_four')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'info_three')) {
                $table->dropColumn('info_three');
            }
            if (Schema::hasColumn('services', 'info_four')) {
                $table->dropColumn('info_four');
            }
        });
    }
};
