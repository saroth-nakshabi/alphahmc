<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ref code click log (brief "alphahmc-ref-click-log" from the Google Ads session, Saroth 30 Oct 2026).
 * ref_clicks: one row per WhatsApp Ref code with the Google click that produced it, so a CRM win can be
 * uploaded to Google Ads. No name, phone, email or message. Rows are deleted 120 days after last_seen_at.
 * ref_inquiries: the same click data for website inquiries (inquiries.source), without any personal data;
 * the read-only user of the Google Ads session can SELECT only on these two.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ref_clicks')) {
            Schema::create('ref_clicks', function (Blueprint $table) {
                $table->id();
                $table->string('ref', 16)->unique();
                $table->string('channel', 20);
                $table->string('gclid')->nullable();
                $table->string('gbraid')->nullable();
                $table->string('wbraid')->nullable();
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->string('utm_term')->nullable();
                $table->string('utm_content')->nullable();
                $table->string('landing_path')->nullable();
                $table->timestamp('click_at')->nullable();
                $table->string('ad_consent', 12);
                $table->timestamp('first_seen_at')->nullable();
                $table->timestamp('last_seen_at')->nullable()->index();
            });
        }

        $j = fn (string $key) => "JSON_UNQUOTE(JSON_EXTRACT(`source`, '$.{$key}'))";
        DB::statement('CREATE OR REPLACE VIEW `ref_inquiries` AS SELECT `id`, `created_at`, '
            . "{$j('ref_id')} AS `ref`, {$j('channel')} AS `channel`, "
            . "{$j('gclid')} AS `gclid`, {$j('gbraid')} AS `gbraid`, {$j('wbraid')} AS `wbraid`, "
            . "{$j('utm_source')} AS `utm_source`, {$j('utm_medium')} AS `utm_medium`, {$j('campaign')} AS `utm_campaign`, "
            . "{$j('term')} AS `utm_term`, {$j('adgroup')} AS `utm_content`, {$j('ad_consent')} AS `ad_consent` "
            . 'FROM `inquiries` WHERE `source` IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS `ref_inquiries`');
        Schema::dropIfExists('ref_clicks');
    }
};
