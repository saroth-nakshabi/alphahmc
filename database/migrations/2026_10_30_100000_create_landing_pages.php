<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Landing page builder (brief "alphahmc-facility-landing-build", 30 Oct 2026).
 * landing_pages: one record per /facility-setup/{slug} URL; page copy lives in `content` (JSON, see
 * App\Support\LandingPageSchema). landing_page_revisions: full snapshots for restore.
 * inquiries: the landing form's facility type, emirate, source page and ad click data.
 * Idempotent: prod schema is applied by hand from the raw SQL, so every step checks first.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('landing_pages')) {
            Schema::create('landing_pages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('status', 20)->default('draft');
                $table->string('lead_type', 20)->default('facility');
                $table->string('facility_type')->nullable();
                $table->string('emirate', 50)->nullable();
                $table->string('seo_title')->nullable();
                $table->string('meta_description', 500)->nullable();
                $table->string('canonical')->nullable();
                $table->boolean('indexable')->default(true);
                $table->string('hero_image')->nullable();
                $table->string('hero_image_alt')->nullable();
                $table->json('related_ids')->nullable();
                $table->longText('content')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('landing_page_revisions')) {
            Schema::create('landing_page_revisions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('landing_page_id')->index();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->string('note')->nullable();
                $table->longText('snapshot');
                $table->timestamp('created_at')->nullable();
            });
        }

        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'landing_page_id')) {
                $table->unsignedBigInteger('landing_page_id')->nullable()->after('service_id');
            }
            if (!Schema::hasColumn('inquiries', 'facility_type')) {
                $table->string('facility_type')->nullable()->after('landing_page_id');
            }
            if (!Schema::hasColumn('inquiries', 'emirate')) {
                $table->string('emirate', 50)->nullable()->after('facility_type');
            }
            if (!Schema::hasColumn('inquiries', 'source')) {
                $table->json('source')->nullable()->after('emirate');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_page_revisions');
        Schema::dropIfExists('landing_pages');
        Schema::table('inquiries', function (Blueprint $table) {
            foreach (['source', 'emirate', 'facility_type', 'landing_page_id'] as $col) {
                if (Schema::hasColumn('inquiries', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
