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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('email')->nullable()->after('author_name');
            $table->unsignedBigInteger('service_id')->nullable()->after('rating');
            $table->foreign('service_id')->references('id')->on('services')->nullOnDelete();
            $table->boolean('approved')->default(false)->after('service_id');
            $table->string('source')->default('admin')->after('approved');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn(['email', 'service_id', 'approved', 'source']);
        });
    }
};
