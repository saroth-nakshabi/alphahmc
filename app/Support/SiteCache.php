<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * 10-minute cache for public page data (menu data shared by every page, home page data).
 *
 * Uses the file store, not the default database store: a database cache read is itself a
 * query plus a large blob, which would cancel most of the saving.
 *
 * Saving or deleting one of the models in AppServiceProvider::SITE_CACHE_MODELS clears it at
 * once. Changes that fire no model event (raw SQL, pivot sync() such as mapping services to
 * categories) show within 10 minutes, or straight away after `php artisan cache:clear file`.
 */
class SiteCache
{
    public const TTL = 600;

    /** Bump when the cached shape changes, so a deploy never reads an older payload. */
    private const VERSION = 'v1';

    private const KEYS = ['nav', 'home'];

    public static function remember(string $key, Closure $build): mixed
    {
        try {
            return Cache::store('file')->remember(self::key($key), self::TTL, $build);
        } catch (\Throwable $e) {
            // Unreadable cache (permissions, corrupt file): serve fresh data, never an error page.
            Log::warning("SiteCache '{$key}' unavailable, building fresh: " . $e->getMessage());
            return $build();
        }
    }

    public static function flush(): void
    {
        foreach (self::KEYS as $key) {
            try {
                Cache::store('file')->forget(self::key($key));
            } catch (\Throwable $e) {
                Log::warning("SiteCache '{$key}' could not be cleared: " . $e->getMessage());
            }
        }
    }

    private static function key(string $key): string
    {
        return 'site.' . self::VERSION . '.' . $key;
    }
}
