<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One WhatsApp Ref code with the Google click that produced it (brief "alphahmc-ref-click-log").
 * Written by POST /api/ref-log (App\Http\Controllers\RefLogController); read by the Google Ads session.
 */
class RefClick extends Model
{
    public $timestamps = false;

    /** Ref prefix => channel, as set by front/partials/whatsapp-ref.blade.php. */
    public const CHANNELS = [
        'AHA' => 'google_ads', 'AHO' => 'organic', 'AHM' => 'meta_ads', 'AHS' => 'meta_organic',
        'AHC' => 'other_campaign', 'AHR' => 'referral', 'AHD' => 'direct',
    ];
    public const REF_PATTERN = '/^AH[AOMSCRD]-[A-Z0-9]{6}$/';
    public const CLICK_FIELDS = ['gclid', 'gbraid', 'wbraid'];
    public const RETENTION_DAYS = 120;

    protected $fillable = [
        'ref', 'channel', 'gclid', 'gbraid', 'wbraid', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term',
        'utm_content', 'landing_path', 'click_at', 'ad_consent', 'first_seen_at', 'last_seen_at',
    ];

    protected $casts = ['click_at' => 'datetime', 'first_seen_at' => 'datetime', 'last_seen_at' => 'datetime'];

    /** Delete rows not seen for 120 days (the Google Ads conversion window is 90). */
    public static function prune(): int
    {
        return static::where('last_seen_at', '<', now()->subDays(self::RETENTION_DAYS))->delete();
    }
}
