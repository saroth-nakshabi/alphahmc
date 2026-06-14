<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('featured');
            }
        });

        // Seed an initial order from existing ids so nothing starts at 0/ties.
        $blogs = \DB::table('blogs')->orderBy('id')->pluck('id');
        foreach ($blogs as $position => $id) {
            \DB::table('blogs')->where('id', $id)->update(['sort_order' => $position + 1]);
        }
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
