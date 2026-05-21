<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('client_name')->nullable()->after('slug');
            $table->string('project_duration')->nullable()->after('client_name');
            $table->string('project_location')->nullable()->after('project_duration');
            $table->string('regulatory_authority')->nullable()->after('project_location');
            $table->string('client_website')->nullable()->after('regulatory_authority');
            $table->text('project_scope')->nullable()->after('client_website');
            $table->json('service_ids')->nullable()->after('project_scope');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['client_name', 'project_duration', 'project_location', 'regulatory_authority', 'client_website', 'project_scope', 'service_ids']);
        });
    }
};
