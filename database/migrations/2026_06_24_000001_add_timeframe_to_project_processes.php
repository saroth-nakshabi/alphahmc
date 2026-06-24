<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('project_processes') && !Schema::hasColumn('project_processes', 'timeframe')) {
            Schema::table('project_processes', function (Blueprint $table) {
                $table->text('timeframe')->nullable()->after('process_intro');
            });
        }
    }
    public function down(): void {
        if (Schema::hasTable('project_processes') && Schema::hasColumn('project_processes', 'timeframe')) {
            Schema::table('project_processes', fn (Blueprint $t) => $t->dropColumn('timeframe'));
        }
    }
};
