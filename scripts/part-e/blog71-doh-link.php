<?php
/**
 * Link the authority mentions in blog 71 to their service pages (no raw SQL):
 * "Abu Dhabi under DOH" to the DOH page, "northern emirates under MOHAP" to the MOHAP page.
 * Safe to re-run: a phrase whose link already exists is skipped.
 *
 * Usage, from the app root:  php scripts/part-e/blog71-doh-link.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/part-e/blog71-doh-link.php
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Blog;
use App\Models\Scopes\PublishedScope;

$dryRun = in_array('--dry-run', $argv, true);
$links = [
    'Abu Dhabi under DOH' => '/services/doh-license-healthcare-professionals-abu-dhabi',
    'northern emirates under MOHAP' => '/services/mohap-license-healthcare-professionals',
];

$blog = Blog::withoutGlobalScope(PublishedScope::class)->findOrFail(71);
$original = (string) $blog->content;
$content = $original;

echo "Blog 71: {$blog->title}\n";
foreach ($links as $phrase => $href) {
    if (str_contains($content, $href)) {
        echo "\"{$phrase}\": already linked, skipped.\n";
        continue;
    }

    $count = substr_count($content, $phrase);
    if ($count !== 1) {
        fwrite(STDERR, "\"{$phrase}\": expected exactly one occurrence, found {$count}; stopping without changes.\n");
        exit(1);
    }

    $i = strpos($content, $phrase);
    // Refuse if the phrase already sits inside a link.
    $before = substr($content, 0, $i);
    if (strrpos($before, '<a ') !== false && strrpos($before, '<a ') > (int) strrpos($before, '</a>')) {
        fwrite(STDERR, "\"{$phrase}\": already inside a link; stopping without changes.\n");
        exit(1);
    }

    $content = substr_replace($content, '<a href="' . $href . '">' . $phrase . '</a>', $i, strlen($phrase));
    echo "\"{$phrase}\": linked to {$href}\n";
    echo "  After: ..." . substr($content, max(0, $i - 120), 340) . "...\n";
}

if ($content === $original) {
    echo "Nothing to change.\n";
    exit(0);
}
if ($dryRun) {
    echo "Dry run, nothing saved.\n";
    exit(0);
}

$backup = storage_path('app/blog71-before-links-' . date('Ymd-His') . '.html');
file_put_contents($backup, $original);
$blog->content = $content;
$blog->save();
echo "Saved. Backup of the old content: {$backup}\n";
