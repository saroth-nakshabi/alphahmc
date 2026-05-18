<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();  // URL/path
            $table->longText('overview');  // Using longText for potentially larger content
            $table->longText('content');
            $table->longText('info_one');
            $table->longText('info_two');
            $table->boolean('featured')->default(false);
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            // meta details
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->string('areaServed')->nullable();
            $table->string('serviceType')->nullable();
            $table->foreignId('announcement_id')->nullable()->constrained('announcements')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
