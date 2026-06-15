<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('project_planner_sessions')) {
            Schema::create('project_planner_sessions', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->index();
                $table->string('intent')->nullable();
                $table->string('region')->nullable();
                $table->string('facility_type')->nullable();
                $table->json('selected_services')->nullable();
                $table->text('free_text')->nullable();
                $table->text('ai_solution')->nullable();
                $table->longText('brief')->nullable();
                $table->json('recommended_service_ids')->nullable();
                $table->string('engine', 20)->default('rules'); // 'ai' | 'rules'
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('status', 20)->default('new'); // new | contacted | closed
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_planner_sessions');
    }
};
