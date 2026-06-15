<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('project_planner_sessions', 'meeting_link')) {
                $table->string('meeting_link')->nullable()->after('meeting_at');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'calendar_link')) {
                $table->string('calendar_link')->nullable()->after('meeting_link');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'meeting_staff_id')) {
                $table->unsignedBigInteger('meeting_staff_id')->nullable()->after('calendar_link');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'meeting_confirmed')) {
                $table->boolean('meeting_confirmed')->default(false)->after('meeting_staff_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            foreach (['meeting_link', 'calendar_link', 'meeting_staff_id', 'meeting_confirmed'] as $c) {
                if (Schema::hasColumn('project_planner_sessions', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
