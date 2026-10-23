<?php
/**
 * Creates the WebP twin (uploads/cache/<hash>.webp) for every cached jpg/png derivative that has none.
 * New derivatives get their twin when Img::thumb() makes them; this covers the files made before that.
 * nginx serves a twin at the jpg/png URL to browsers that accept WebP. A twin that would not be smaller
 * is not kept. Safe to re-run.
 *
 * Usage, from the app root:  php scripts/images/webp-twins.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/images/webp-twins.php
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Support\Img;

$dryRun = in_array('--dry-run', $argv, true);
$dir = public_path('uploads/cache');
$made = $skipped = $failed = $existing = 0;
$before = $after = 0;

foreach (glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE) as $file) {
    $twin = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);
    if (is_file($twin)) {
        $existing++;
        continue;
    }
    if ($dryRun) {
        $made++;
        continue;
    }
    $img = @imagecreatefromstring((string) file_get_contents($file));
    if (!$img) {
        $failed++;
        continue;
    }
    Img::writeWebpTwin($img, $file);
    imagedestroy($img);
    if (is_file($twin)) {
        $made++;
        $before += filesize($file);
        $after += filesize($twin);
    } else {
        $skipped++;
    }
}

printf("%s: %d twins made, %d not smaller (skipped), %d already had one, %d unreadable\n", $dryRun ? 'Dry run' : 'Done', $made, $skipped, $existing, $failed);
if ($made && !$dryRun) {
    printf("Covered files: %.1f MB as jpg/png -> %.1f MB as WebP (%.0f%% smaller)\n", $before / 1048576, $after / 1048576, 100 - $after / max(1, $before) * 100);
}
