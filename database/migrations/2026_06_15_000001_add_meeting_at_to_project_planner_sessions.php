<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('project_planner_sessions', 'meeting_at')) {
                $table->dateTime('meeting_at')->nullable()->after('consent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('project_planner_sessions', 'meeting_at')) {
                $table->dropColumn('meeting_at');
            }
        });
    }
};
