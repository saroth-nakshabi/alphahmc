<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('project_planner_sessions', 'consent')) {
                $table->boolean('consent')->default(false)->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('project_planner_sessions', 'consent')) {
                $table->dropColumn('consent');
            }
        });
    }
};
