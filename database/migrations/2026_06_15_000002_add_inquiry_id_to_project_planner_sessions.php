<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('project_planner_sessions', 'inquiry_id')) {
                $table->unsignedBigInteger('inquiry_id')->nullable()->index()->after('meeting_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_planner_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('project_planner_sessions', 'inquiry_id')) {
                $table->dropColumn('inquiry_id');
            }
        });
    }
};
