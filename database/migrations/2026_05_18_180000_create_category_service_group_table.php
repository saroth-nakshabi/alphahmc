<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_service_group', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('service_group_id');
            $table->primary(['category_id', 'service_group_id']);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('service_group_id')->references('id')->on('service_groups')->onDelete('cascade');
        });

        // Seed pivot from existing single category_id values
        DB::table('service_groups')
            ->whereNotNull('category_id')
            ->get(['id', 'category_id'])
            ->each(function ($sg) {
                DB::table('category_service_group')->insertOrIgnore([
                    'category_id'      => $sg->category_id,
                    'service_group_id' => $sg->id,
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_service_group');
    }
};
