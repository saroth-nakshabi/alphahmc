<?php

namespace App\Support;

/**
 * Inline critical CSS and the merged stylesheets (see scripts/css/build.mjs and critical.mjs).
 *
 * A template opts in with @section('critical', '<template>'); the front layout then inlines
 * resources/critical/<template>/<bundle>.css where each stylesheet sits and loads the full file
 * without blocking. Without a critical file the stylesheet is linked normally.
 */
final class CriticalCss
{
    private static ?array $manifest = null;

    /** @var array<string, string|null> */
    private static array $inline = [];

    public static function bundleUrl(string $bundle): string
    {
        if (self::$manifest === null) {
            $file = public_path('front/assets/css/bundle/manifest.json');
            self::$manifest = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        }
        $name = $bundle . '.min.css';

        return asset('public/front/assets/css/bundle/' . $name) . '?v=' . (self::$manifest[$name] ?? '1');
    }

    public static function inline(?string $template, string $bundle): ?string
    {
        if ($template === null || !preg_match('/^[a-z-]+$/', $template . $bundle)) {
            return null;
        }
        $key = $template . '/' . $bundle;
        if (!array_key_exists($key, self::$inline)) {
            $file = resource_path('critical/' . $key . '.css');
            self::$inline[$key] = is_file($file) ? trim((string) file_get_contents($file)) : null;
        }

        return self::$inline[$key];
    }
}
