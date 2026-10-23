<?php
/**
 * Mobile LCP step 4: edit the /google tag row (googletags #1) through the model (the fields are
 * JSON-cast: never raw SQL).
 * - removes the Tag Manager GTM-ND5F4WS loader and its noscript iframe (its only active tags were the
 *   Meta Pixel, now in resources/views/front/partials/meta-pixel.blade.php; the container itself stays
 *   published, so a rollback is restoring this row);
 * - keeps the dataLayer, gtag() and both config calls inline in the head, so events from early taps
 *   are queued, and loads gtag/js after the first paint instead of with the HTML.
 * Writes a JSON backup of the row first. Stops without changes if the row does not hold the expected text.
 *
 * Usage, from the app root:  php scripts/tracking/gtag-after-paint.php [--dry-run]
 * Rollback:                  php scripts/tracking/gtag-after-paint.php --restore=<backup.json>
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\googletag;

$dryRun  = in_array('--dry-run', $argv, true);
$restore = null;
foreach ($argv as $a) {
    if (str_starts_with($a, '--restore=')) {
        $restore = substr($a, 10);
    }
}

$row = googletag::findOrFail(1);

if ($restore !== null) {
    $saved = json_decode((string) file_get_contents($restore), true);
    if (!is_array($saved) || !array_key_exists('tags', $saved)) {
        fwrite(STDERR, "Backup file not readable.\n");
        exit(1);
    }
    $row->tags = $saved['tags'];
    $row->noscript_tags = $saved['noscript_tags'];
    $row->save();
    echo "Restored googletags #1 from {$restore}\n";
    exit(0);
}

$tags = (string) $row->tags;
$nl   = str_contains($tags, "\r\n") ? "\r\n" : "\n";

// 1. The Tag Manager block, from its opening comment to its closing comment.
$start = strpos($tags, '<!-- Google Tag Manager -->');
$end   = strpos($tags, '<!-- End Google Tag Manager -->');
if ($start === false || $end === false || substr_count($tags, 'GTM-ND5F4WS') !== 1) {
    fwrite(STDERR, "Tag Manager block not found exactly once; stopping without changes.\n");
    exit(1);
}
$end += strlen('<!-- End Google Tag Manager -->');
$new = substr($tags, 0, $start) . substr($tags, $end);

// 2. The gtag/js library tag becomes a loader that runs after the first paint.
$lib = '<script async src="https://www.googletagmanager.com/gtag/js?id=G-KBQ7ZEQ196"></script>';
if (substr_count($new, $lib) !== 1 || substr_count($new, "gtag('config', 'AW-695565753')") !== 1 || substr_count($new, "gtag('config', 'G-KBQ7ZEQ196')") !== 1) {
    fwrite(STDERR, "gtag library tag or config calls not found exactly once; stopping without changes.\n");
    exit(1);
}
$new = str_replace($lib, '', $new);
$loader = implode($nl, [
    '<!-- gtag.js loads after the first paint (page speed, 2026-10-23): the dataLayer, gtag() and the',
    '     config calls above run with the HTML, so events from early taps are queued and sent once it loads. -->',
    '<script>',
    '(function () {',
    '  function loadGtag() {',
    '    if (window.ahgGtagRequested) return;',
    '    window.ahgGtagRequested = true;',
    "    var s = document.createElement('script');",
    "    s.async = true;",
    "    s.src = 'https://www.googletagmanager.com/gtag/js?id=G-KBQ7ZEQ196';",
    "    document.head.appendChild(s);",
    '  }',
    '  // The next frame is painted before the timeout runs; then wait for idle time (at most 2 s).',
    '  function afterPaint() {',
    "    if ('requestIdleCallback' in window) { requestIdleCallback(loadGtag, { timeout: 2000 }); } else { setTimeout(loadGtag, 0); }",
    '  }',
    "  requestAnimationFrame(function () { setTimeout(afterPaint, 0); });",
    "  window.addEventListener('load', loadGtag);",
    '})();',
    '</script>',
]);
$new = rtrim($new) . $nl . $loader;
$new = ltrim($new);

echo "---- new tags ----\n{$new}\n---- noscript_tags: removed (was the GTM iframe) ----\n";
if ($dryRun) {
    echo "Dry run, nothing saved.\n";
    exit(0);
}

$backup = storage_path('app/googletags-1-before-gtag-after-paint-' . date('Ymd-His') . '.json');
file_put_contents($backup, json_encode(['tags' => $row->tags, 'noscript_tags' => $row->noscript_tags], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
$row->tags = $new;
$row->noscript_tags = null;
$row->save();
echo "Saved. Backup: {$backup}\n";
