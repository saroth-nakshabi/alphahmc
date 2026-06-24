<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('planner_outcome_cache')) return;

        Schema::create('planner_outcome_cache', function (Blueprint $table) {
            $table->id();
            $table->string('intent_key', 120)->index();
            $table->string('region_key', 80)->nullable()->index();
            $table->string('category_fingerprint', 200)->nullable();
            $table->longText('process_output');
            $table->text('timeline_output')->nullable();
            $table->unsignedBigInteger('consultant_id')->nullable();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('planner_outcome_cache');
    }
};
