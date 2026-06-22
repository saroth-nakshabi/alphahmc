<?php
/**
 * Conservative CSS minifier for AlphaHMC front-end stylesheets.
 *
 * Strips /* *​/ comments, per-line indentation, trailing whitespace and blank
 * lines ONLY. It deliberately does NOT collapse spaces inside declaration
 * values, so significant whitespace (e.g. `calc(100% - 10px)`, multi-value
 * shorthands) is preserved byte-for-byte — guaranteeing identical rendering.
 *
 * The readable .css files stay the source of truth. After editing any source
 * file, re-run this script to regenerate its .min.css sibling:
 *
 *     php scripts/minify-css.php
 *
 * The <link> tags reference the .min.css output with a ?v=N cache-buster —
 * bump that version on deploy so browsers pick up the new file.
 */

$root = dirname(__DIR__);

$files = [
    'public/front-new/assets/css/style.css',
    'public/front-new/assets/css/slide-menu.css',
    'public/front/assets/css/front-global.css',
    'public/front/assets/css/service-pages-shared.css',
    'public/front/assets/css/service-detail.css',
    'public/front/assets/css/service-category.css',
];

$totalBefore = 0;
$totalAfter  = 0;

foreach ($files as $rel) {
    $path = $root . '/' . $rel;
    if (!is_file($path)) {
        fwrite(STDERR, "skip (missing): {$rel}\n");
        continue;
    }

    $css    = file_get_contents($path);
    $before = strlen($css);

    // 1. Strip /* ... */ comments (handles multi-line, non-greedy).
    $css = preg_replace('#/\*[^*]*\*+(?:[^/*][^*]*\*+)*/#', '', $css);

    // 2. Trim each line; drop empties. Never touches intra-value spacing.
    $lines = preg_split('/\r\n|\r|\n/', $css);
    $out   = [];
    foreach ($lines as $line) {
        $t = trim($line);
        if ($t !== '') {
            $out[] = $t;
        }
    }
    $min = implode("\n", $out) . "\n";

    $minPath = preg_replace('/\.css$/', '.min.css', $path);
    file_put_contents($minPath, $min);

    $after        = strlen($min);
    $totalBefore += $before;
    $totalAfter  += $after;

    printf("%-55s %6d -> %6d B  (-%d%%)\n",
        basename($minPath), $before, $after,
        $before ? round(($before - $after) / $before * 100) : 0);
}

printf("\nTOTAL  %d -> %d B  (saved %d B, %d%%)\n",
    $totalBefore, $totalAfter, $totalBefore - $totalAfter,
    $totalBefore ? round(($totalBefore - $totalAfter) / $totalBefore * 100) : 0);
