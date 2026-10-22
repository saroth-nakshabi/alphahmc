<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * blogs.content was TEXT (64 KB); a long guide from the agent does not fit (brief 3.2).
 * Widening to LONGTEXT keeps every existing value. Laravel 11 changes columns natively.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->longText('content')->change();
        });
    }

    public function down(): void
    {
        // Not narrowed back: a post longer than 64 KB would be truncated.
    }
};
