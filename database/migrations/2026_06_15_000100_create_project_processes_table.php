<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('project_processes')) {
            Schema::create('project_processes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->longText('process_intro')->nullable();
                $table->longText('process_header')->nullable();        // JSON array of step headers
                $table->longText('process_description')->nullable();   // JSON array of step descriptions
                $table->longText('process_service_ids')->nullable();   // JSON array of linked service ids
                $table->timestamps();
            });
        }

        // Link columns: which shared process a category / service group is using.
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'project_process_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedBigInteger('project_process_id')->nullable()->after('process_intro');
            });
        }
        if (Schema::hasTable('service_groups') && !Schema::hasColumn('service_groups', 'project_process_id')) {
            Schema::table('service_groups', function (Blueprint $table) {
                $table->unsignedBigInteger('project_process_id')->nullable()->after('process_intro');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'project_process_id')) {
            Schema::table('categories', fn (Blueprint $t) => $t->dropColumn('project_process_id'));
        }
        if (Schema::hasTable('service_groups') && Schema::hasColumn('service_groups', 'project_process_id')) {
            Schema::table('service_groups', fn (Blueprint $t) => $t->dropColumn('project_process_id'));
        }
        Schema::dropIfExists('project_processes');
    }
};
