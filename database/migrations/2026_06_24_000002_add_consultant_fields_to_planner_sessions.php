<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('project_planner_sessions')) return;

        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('project_planner_sessions', 'process_source')) {
                $table->string('process_source', 20)->default('ai_generated')->after('engine');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'ai_process_output')) {
                $table->longText('ai_process_output')->nullable()->after('process_source');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'consultant_outcome')) {
                $table->longText('consultant_outcome')->nullable()->after('ai_process_output');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'consultant_notes')) {
                $table->text('consultant_notes')->nullable()->after('consultant_outcome');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'consultant_id')) {
                $table->unsignedBigInteger('consultant_id')->nullable()->after('consultant_notes');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'consultant_reviewed_at')) {
                $table->timestamp('consultant_reviewed_at')->nullable()->after('consultant_id');
            }
        });
    }

    public function down(): void {
        if (!Schema::hasTable('project_planner_sessions')) return;
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            foreach (['process_source','ai_process_output','consultant_outcome','consultant_notes','consultant_id','consultant_reviewed_at'] as $col) {
                if (Schema::hasColumn('project_planner_sessions', $col)) $table->dropColumn($col);
            }
        });
    }
};
