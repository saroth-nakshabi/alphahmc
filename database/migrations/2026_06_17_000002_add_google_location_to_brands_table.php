<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('brands', 'google_location')) {
            return; // column already added directly on the server (host drift)
        }

        Schema::table('brands', function (Blueprint $table) {
            // Google Maps embed link (or full <iframe> embed code) for the Global Offices section
            $table->text('google_location')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('google_location');
        });
    }
};
