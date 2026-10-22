<?php
/**
 * Link "Abu Dhabi under DOH" in blog 71 to the DOH service page (no raw SQL).
 * Safe to re-run: it does nothing once the link exists.
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
$phrase = 'Abu Dhabi under DOH';
$href = '/services/doh-license-healthcare-professionals-abu-dhabi';

$blog = Blog::withoutGlobalScope(PublishedScope::class)->findOrFail(71);
$content = (string) $blog->content;

echo "Blog 71: {$blog->title}\n";
if (str_contains($content, $href)) {
    echo "Already linked, nothing to do.\n";
    exit(0);
}

$count = substr_count($content, $phrase);
echo "Occurrences of \"{$phrase}\": {$count}\n";
if ($count !== 1) {
    fwrite(STDERR, "Expected exactly one occurrence; stopping without changes.\n");
    exit(1);
}

$i = strpos($content, $phrase);
// Refuse if the phrase already sits inside a link.
$before = substr($content, 0, $i);
if (strrpos($before, '<a ') !== false && strrpos($before, '<a ') > (int) strrpos($before, '</a>')) {
    fwrite(STDERR, "Phrase is already inside a link; stopping without changes.\n");
    exit(1);
}

$new = substr_replace($content, '<a href="' . $href . '">' . $phrase . '</a>', $i, strlen($phrase));
echo "Before: ..." . substr($content, max(0, $i - 120), 260) . "...\n";
echo "After:  ..." . substr($new, max(0, $i - 120), 340) . "...\n";

if ($dryRun) {
    echo "Dry run, nothing saved.\n";
    exit(0);
}

$backup = storage_path('app/blog71-before-doh-link-' . date('Ymd-His') . '.html');
file_put_contents($backup, $content);
$blog->content = $new;
$blog->save();
echo "Saved. Backup of the old content: {$backup}\n";
