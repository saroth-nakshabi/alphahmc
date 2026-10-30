<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Blog author Nuha Ansaf (brief "alphahmc-blog-author", Saroth 30 Oct 2026). The team table that holds the
 * reviewers gets a role ("reviewer" or "author"), so the author has a bio page at /team/nuha-ansaf without
 * appearing in the reviewer lists; ReviewerSeeder adds her row. Every post by Saroth Nakshabi now shows Nuha
 * Ansaf as author. The query builder leaves updated_at alone: a byline change is not a content update.
 */
return new class extends Migration
{
    private const OLD_AUTHOR = 'Saroth Nakshabi';
    private const NEW_AUTHOR = 'Nuha Ansaf';

    public function up(): void
    {
        if (Schema::hasTable('reviewers') && !Schema::hasColumn('reviewers', 'role')) {
            Schema::table('reviewers', function (Blueprint $table) {
                $table->string('role', 20)->default('reviewer')->after('key');
            });
        }

        DB::table('blogs')->where('author_name', self::OLD_AUTHOR)->update(['author_name' => self::NEW_AUTHOR]);
    }

    public function down(): void
    {
        DB::table('blogs')->where('author_name', self::NEW_AUTHOR)->update(['author_name' => self::OLD_AUTHOR]);

        if (Schema::hasColumn('reviewers', 'role')) {
            DB::table('reviewers')->where('role', 'author')->delete();
            Schema::table('reviewers', fn (Blueprint $table) => $table->dropColumn('role'));
        }
    }
};
