<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inquiries') && !Schema::hasColumn('inquiries', 'meeting_at')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->dateTime('meeting_at')->nullable()->after('message');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('inquiries') && Schema::hasColumn('inquiries', 'meeting_at')) {
            Schema::table('inquiries', fn (Blueprint $t) => $t->dropColumn('meeting_at'));
        }
    }
};
