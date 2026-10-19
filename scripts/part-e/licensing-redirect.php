<?php
/**
 * Part E step 3: retire the UAE licensing service page now that
 * /services/healthcare-professional-licensing-uae redirects (301) to the package page.
 * Writes through the models (no raw SQL). Safe to re-run.
 *
 * - Service 'healthcare-professional-licensing-uae' is set to draft, so menus, category pages,
 *   all services, search and the sitemap stop listing a page that only redirects.
 *   The record stays: the group script copies its hero image and agent.
 * - Blog links to the old URL are repointed: professional licensing links go to the package page,
 *   facility licensing links go to the facility licensing category.
 *
 * Usage, from the app root:  php scripts/part-e/licensing-redirect.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/part-e/licensing-redirect.php
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Blog;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceGroup;

$dryRun = in_array('--dry-run', $argv, true);
$slug = 'healthcare-professional-licensing-uae';
$oldUrl = 'https://alphahmc.com/services/' . $slug;

$group = ServiceGroup::where('slug', $slug)->where('status', 'published')->first();
$facility = Category::where('slug', 'facility-licensing-setup')->first();
if (!$group || !$facility) {
    fwrite(STDERR, "Missing " . (!$group ? "published group '{$slug}' (run licensing-group.php first)" : "category 'facility-licensing-setup'") . "\n");
    exit(1);
}

// Blog id => new target, chosen from each link's anchor text.
$targets = [
    22 => '/service-packages/' . $slug,           // "UAE" (DataFlow report reused for UAE licensing)
    38 => '/service-packages/' . $slug,           // "healthcare licensing consultant"
    49 => '/service-category/facility-licensing-setup', // "healthcare licensing services" for new facilities
    61 => '/service-category/facility-licensing-setup', // "healthcare facility licensing"
];

$service = Service::where('slug', $slug)->first();
$plan = [];
if ($service && $service->status !== 'draft') {
    $plan[] = "service #{$service->id}: status {$service->status} -> draft";
}
$blogs = Blog::whereIn('id', array_keys($targets))->get()->keyBy('id');
foreach ($targets as $id => $path) {
    $blog = $blogs[$id] ?? null;
    $count = $blog ? substr_count($blog->content, $oldUrl) : 0;
    if ($count) {
        $plan[] = "blog #{$id}: {$count} link(s) -> {$path}";
    }
}
// Any other blog still linking to the old URL needs a decision, not a guess.
$others = Blog::where('content', 'like', '%/services/' . $slug . '%')->whereNotIn('id', array_keys($targets))->pluck('id')->all();
if ($others) {
    fwrite(STDERR, "Other blogs link to the old URL, decide their target first: " . implode(', ', $others) . "\n");
    exit(1);
}

if (!$plan) {
    echo "nothing to do: already applied\n";
    exit(0);
}
echo ($dryRun ? "dry run, would apply:\n  " : "applying:\n  ") . implode("\n  ", $plan) . "\n";
if ($dryRun) {
    exit(0);
}

if ($service && $service->status !== 'draft') {
    $service->status = 'draft';
    $service->save();
}
foreach ($targets as $id => $path) {
    $blog = $blogs[$id] ?? null;
    if ($blog && str_contains($blog->content, $oldUrl)) {
        $blog->content = str_replace($oldUrl, 'https://alphahmc.com' . $path, $blog->content);
        $blog->save();
    }
}
echo "done\n";
