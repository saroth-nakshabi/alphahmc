<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Ref click log retention: rows not seen for 120 days (also pruned now and then by POST /api/ref-log).
Artisan::command('ref-clicks:prune', function () {
    $this->info(\App\Models\RefClick::prune() . ' expired Ref rows deleted.');
})->purpose('Delete Ref click rows not seen for 120 days');
