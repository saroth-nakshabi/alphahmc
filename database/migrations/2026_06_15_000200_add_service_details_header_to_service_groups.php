<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_groups') && !Schema::hasColumn('service_groups', 'service_details_header')) {
            Schema::table('service_groups', function (Blueprint $table) {
                $table->longText('service_details_header')->nullable()->after('overview');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('service_groups') && Schema::hasColumn('service_groups', 'service_details_header')) {
            Schema::table('service_groups', fn (Blueprint $t) => $t->dropColumn('service_details_header'));
        }
    }
};
