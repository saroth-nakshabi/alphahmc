<?php
/**
 * SEO brief 29 Oct 2026 (approved by Saroth): fix the stored MOHAP link and set new SEO titles
 * and descriptions on the FANR and cosmetic pages, through the Service model (no raw SQL).
 *
 * 1. /services/mohap-license-healthcare-professionals stored the literal href ' . $groupUrl . '
 *    (licensing-mohap.php line 49 escaped the variable). Any column holding it gets the group URL.
 * 2. meta_title and meta_description only on the FANR and cosmetic pages. Each write is guarded:
 *    the field must still hold the old text from the brief, otherwise the page is left alone.
 *
 * Writes a JSON backup of every row it touches to storage/app/backups before saving.
 *
 * Usage, from the app root:  php scripts/part-e/mohap-link-and-titles.php [--dry-run]
 * On the server:             sudo -u alphahmc php scripts/part-e/mohap-link-and-titles.php
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;

$dryRun = in_array('--dry-run', $argv, true);

$brokenHref = "' . \$groupUrl . '";
$groupUrl = '/service-packages/healthcare-professional-licensing-uae';

$titles = [
    'fanr-licensing-services-uae' => [
        'meta_title' => [
            'FANR Licensing Services UAE | Medical Radiation Compliance | Alpha Health Group',
            'FANR License, Approval and Renewal for UAE Healthcare',
        ],
        'meta_description' => [
            'Expert FANR licensing services in the UAE for hospitals, imaging and radiology centers. End-to-end radiation safety documentation, compliance, and approval.',
            'FANR license applications and renewals for UAE hospitals, imaging and radiology centres: documents, radiation safety files and submission, handled for you.',
        ],
    ],
    'cosmetic-product-registration-dubai' => [
        'meta_title' => [
            'Cosmetic Product Registration in Dubai | Dubai Municipality Approval',
            'Cosmetic Product Registration in Dubai | Alpha Health Group',
        ],
        'meta_description' => [
            'Register cosmetic products with Dubai Municipality fast. Alpha Health Group manages classification, ingredient review, dossiers, labels, and approvals across the UAE and GCC. Start today.',
            'We register cosmetics with Dubai Municipality: classification, ingredient review, dossier, labels and submission, for local and imported brands in the UAE.',
        ],
    ],
];

$changes = []; // slug => [Service, [field => new value]]

$mohap = Service::where('slug', 'mohap-license-healthcare-professionals')->first();
if (!$mohap) {
    fwrite(STDERR, "MOHAP service not found\n");
    exit(1);
}
foreach ($mohap->getAttributes() as $field => $value) {
    if (is_string($value) && str_contains($value, $brokenHref)) {
        $changes[$mohap->slug][1][$field] = str_replace($brokenHref, $groupUrl, $value);
        echo "MOHAP {$field}: " . substr_count($value, $brokenHref) . " broken href(s) fixed\n";
    }
}
if (empty($changes[$mohap->slug])) {
    echo "MOHAP: no broken href stored, nothing to do\n";
} else {
    $changes[$mohap->slug][0] = $mohap;
}

$failed = false;
foreach ($titles as $slug => $fields) {
    $service = Service::where('slug', $slug)->first();
    if (!$service) {
        fwrite(STDERR, "{$slug}: service not found\n");
        $failed = true;
        continue;
    }
    foreach ($fields as $field => [$old, $new]) {
        $current = trim((string) $service->{$field});
        if ($current === $new) {
            echo "{$slug} {$field}: already new, skipped\n";
        } elseif ($current === $old) {
            $changes[$slug][0] = $service;
            $changes[$slug][1][$field] = $new;
            echo "{$slug} {$field}: will update\n";
        } else {
            fwrite(STDERR, "{$slug} {$field}: current text differs from the brief, left alone:\n  {$current}\n");
            $failed = true;
        }
    }
}

if ($failed) {
    fwrite(STDERR, "Stopped: nothing saved. Check the pages above against the brief.\n");
    exit(1);
}
if (!$changes) {
    echo "Nothing to change.\n";
    exit(0);
}
if ($dryRun) {
    echo "Dry run: nothing saved.\n";
    exit(0);
}

$backup = [];
foreach ($changes as $slug => [$service, $fields]) {
    $backup[$slug] = array_intersect_key($service->getAttributes(), $fields) + ['id' => $service->id];
}
$dir = storage_path('app/backups');
if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
    fwrite(STDERR, "Cannot create {$dir}\n");
    exit(1);
}
$file = $dir . '/mohap-link-and-titles-' . date('Ymd-His') . '.json';
if (file_put_contents($file, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
    fwrite(STDERR, "Cannot write backup {$file}\n");
    exit(1);
}
echo "Backup: {$file}\n";

foreach ($changes as $slug => [$service, $fields]) {
    $service->fill($fields)->save();
    echo "{$slug}: saved " . implode(', ', array_keys($fields)) . "\n";
}
