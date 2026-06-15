<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('project_planner_sessions', 'answers')) {
                $table->json('answers')->nullable()->after('free_text');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'cost_estimate')) {
                $table->text('cost_estimate')->nullable()->after('brief');
            }
            if (!Schema::hasColumn('project_planner_sessions', 'timeline_estimate')) {
                $table->text('timeline_estimate')->nullable()->after('cost_estimate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            foreach (['answers', 'cost_estimate', 'timeline_estimate'] as $c) {
                if (Schema::hasColumn('project_planner_sessions', $c)) $table->dropColumn($c);
            }
        });
    }
};
