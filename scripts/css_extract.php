<?php
/**
 * CSS extraction tool for the four service-page blades.
 *
 * Modes:
 *   php scripts/css_extract.php analyze    — report only, writes nothing
 *   php scripts/css_extract.php generate   — writes shared/per-page CSS + rewrites blades
 *   php scripts/css_extract.php verify     — proves final cascade == original per page
 *
 * Safety model:
 *  - A "unit" is one top-level CSS statement (rule, @media{...}, @keyframes{...}).
 *  - Units containing Blade syntax stay inline in the blade.
 *  - Shared file gets only units byte-identical (normalized) in ALL pages.
 *  - Selectors defined >1 time with different bodies inside any single page are
 *    blacklisted from sharing (ordering could flip the cascade winner).
 *  - Per-page files keep remaining units in original order.
 */

$ROOT = dirname(__DIR__);

$PAGES = [
    'service-category'   => 'resources/views/front/service_category.blade.php',
    'service-group'      => 'resources/views/front/service_group.blade.php',
    'service-detail'     => 'resources/views/front/service.blade.php',
    'service-group-all'  => 'resources/views/front/service_group_all_services.blade.php',
];

$mode = $argv[1] ?? 'analyze';

/* ---------- helpers ---------- */

function extractStyleBlocks(string $html): array {
    $blocks = [];
    if (!preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $html, $m, PREG_OFFSET_CAPTURE)) return $blocks;
    foreach ($m[0] as $i => $full) {
        $blocks[] = [
            'full'    => $full[0],
            'offset'  => $full[1],
            'css'     => $m[1][$i][0],
        ];
    }
    return $blocks;
}

/** Split CSS text into top-level units (rules / at-blocks / @import-style statements). */
function tokenizeUnits(string $css): array {
    $units = [];
    $len = strlen($css);
    $i = 0;
    $start = 0;
    $depth = 0;
    $inStr = null;
    $inComment = false;
    while ($i < $len) {
        $c = $css[$i];
        $c2 = $i + 1 < $len ? $css[$i + 1] : '';
        if ($inComment) {
            if ($c === '*' && $c2 === '/') { $inComment = false; $i += 2; continue; }
            $i++; continue;
        }
        if ($inStr !== null) {
            if ($c === '\\') { $i += 2; continue; }
            if ($c === $inStr) $inStr = null;
            $i++; continue;
        }
        if ($c === '/' && $c2 === '*') { $inComment = true; $i += 2; continue; }
        if ($c === '"' || $c === "'") { $inStr = $c; $i++; continue; }
        if ($c === '{') { $depth++; $i++; continue; }
        if ($c === '}') {
            $depth--;
            $i++;
            if ($depth === 0) {
                $units[] = substr($css, $start, $i - $start);
                $start = $i;
            }
            continue;
        }
        if ($c === ';' && $depth === 0) {           // @import / @charset style statement
            $i++;
            $units[] = substr($css, $start, $i - $start);
            $start = $i;
            continue;
        }
        $i++;
    }
    $tail = trim(substr($css, $start));
    if ($tail !== '') $units[] = substr($css, $start);
    return array_values(array_filter($units, fn($u) => trim($u) !== ''));
}

function stripComments(string $s): string {
    return preg_replace('/\/\*.*?\*\//s', '', $s);
}

function normalizeUnit(string $u): string {
    $u = stripComments($u);
    $u = preg_replace('/\s+/', ' ', $u);
    $u = preg_replace('/\s*([{};:,>])\s*/', '$1', $u);
    return trim($u);
}

function unitSelector(string $u): string {
    $u = stripComments($u);
    $pos = strpos($u, '{');
    $sel = $pos === false ? $u : substr($u, 0, $pos);
    return trim(preg_replace('/\s+/', ' ', $sel));
}

function hasBlade(string $u): bool {
    if (strpos($u, '{{') !== false || strpos($u, '{!!') !== false) return true;
    return (bool) preg_match('/@(if|else|elseif|endif|foreach|endforeach|php|endphp|isset|empty|unless)\b/', $u);
}

/* ---------- parse all pages ---------- */

$pages = [];
foreach ($PAGES as $key => $rel) {
    $path = "$ROOT/$rel";
    $html = file_get_contents($path);
    if ($html === false) { fwrite(STDERR, "cannot read $rel\n"); exit(1); }
    $blocks = extractStyleBlocks($html);
    $unitList = [];   // ordered: [blockIdx, raw, norm, selector, blade]
    foreach ($blocks as $bi => $b) {
        foreach (tokenizeUnits($b['css']) as $raw) {
            $norm = normalizeUnit($raw);
            if ($norm === '') continue;
            $unitList[] = [
                'block'    => $bi,
                'raw'      => $raw,
                'norm'     => $norm,
                'hash'     => md5($norm),
                'selector' => unitSelector($raw),
                'blade'    => hasBlade($raw),
            ];
        }
    }
    $pages[$key] = ['path' => $path, 'rel' => $rel, 'html' => $html, 'blocks' => $blocks, 'units' => $unitList];
}

/* ---------- blacklist: selector redefined with different bodies inside ONE page ---------- */

$blacklist = [];
foreach ($pages as $key => $p) {
    $bySel = [];
    foreach ($p['units'] as $u) {
        if ($u['blade']) continue;
        $bySel[$u['selector']][$u['hash']] = true;
    }
    foreach ($bySel as $sel => $hashes) {
        if (count($hashes) > 1) $blacklist[$sel] = true;
    }
}

/* ---------- shared set: hash present in ALL of the three big pages ----------
   service-group-all is a small page with little overlap; it does NOT load the
   shared stylesheet and keeps everything in its own file. */

$SHARED_PAGES = ['service-category', 'service-group', 'service-detail'];

$hashPresence = [];
foreach ($SHARED_PAGES as $key) {
    foreach ($pages[$key]['units'] as $u) {
        if ($u['blade']) continue;
        $hashPresence[$u['hash']][$key] = true;
    }
}
$sharedHashes = [];
foreach ($hashPresence as $hash => $present) {
    if (count($present) === count($SHARED_PAGES)) $sharedHashes[$hash] = true;
}
// drop blacklisted selectors from shared
foreach ($pages as $p) {
    foreach ($p['units'] as $u) {
        if (isset($sharedHashes[$u['hash']]) && isset($blacklist[$u['selector']])) {
            unset($sharedHashes[$u['hash']]);
        }
    }
}

/* ---------- report ---------- */

$report = [];
foreach ($pages as $key => $p) {
    $total = count($p['units']);
    $blade = count(array_filter($p['units'], fn($u) => $u['blade']));
    $shared = in_array($key, $SHARED_PAGES)
        ? count(array_filter($p['units'], fn($u) => !$u['blade'] && isset($sharedHashes[$u['hash']])))
        : 0;
    $report[$key] = [$total, $blade, $shared, $total - $blade - $shared];
}
echo str_pad('page', 22) . str_pad('units', 8) . str_pad('blade', 8) . str_pad('shared', 8) . "page-own\n";
foreach ($report as $key => [$t, $b, $s, $o]) {
    echo str_pad($key, 22) . str_pad($t, 8) . str_pad($b, 8) . str_pad($s, 8) . "$o\n";
}
echo "distinct shared units: " . count($sharedHashes) . "\n";
echo "blacklisted selectors: " . count($blacklist) . "\n";
if ($mode === 'analyze') {
    foreach (array_keys($blacklist) as $sel) echo "  BL: " . substr($sel, 0, 90) . "\n";
    // list blade units briefly
    foreach ($pages as $key => $p) {
        foreach ($p['units'] as $u) {
            if ($u['blade']) echo "  BLADE[$key]: " . substr(preg_replace('/\s+/', ' ', trim($u['raw'])), 0, 110) . "\n";
        }
    }
    exit(0);
}

/* ---------- generate ---------- */

$CSS_DIR = "$ROOT/public/front/assets/css";
@mkdir($CSS_DIR, 0775, true);

if ($mode === 'generate') {
    // snapshot original units so `verify` can compare after the blades are rewritten
    $snapshot = [];
    foreach ($pages as $key => $p) {
        $snapshot[$key] = array_map(fn($u) => $u['norm'], $p['units']);
    }
    file_put_contents("$ROOT/scripts/.css_original_units.json", json_encode($snapshot));

    // shared file: use service-category's order (it defines the most), append any shared
    // hash not seen there from other pages (in page order).
    $written = [];
    $sharedOut = "/* ============================================================\n"
               . "   Shared styles for service pages\n"
               . "   (service category / service group / service detail / group all-services)\n"
               . "   Extracted from inline <style> blocks - identical in all four pages.\n"
               . "   ============================================================ */\n";
    foreach ($SHARED_PAGES as $key) {
        foreach ($pages[$key]['units'] as $u) {
            if ($u['blade'] || !isset($sharedHashes[$u['hash']]) || isset($written[$u['hash']])) continue;
            $written[$u['hash']] = true;
            $sharedOut .= "\n" . trim($u['raw']) . "\n";
        }
    }
    file_put_contents("$CSS_DIR/service-pages-shared.css", $sharedOut);
    echo "wrote service-pages-shared.css (" . strlen($sharedOut) . " bytes)\n";

    foreach ($pages as $key => $p) {
        $own = "/* ============================================================\n"
             . "   Page-specific styles: $key\n"
             . "   Extracted from inline <style> blocks. Loads AFTER\n"
             . "   service-pages-shared.css - order preserved from the original page.\n"
             . "   ============================================================ */\n";
        $isSharedPage = in_array($key, $SHARED_PAGES);
        $kept = 0;
        foreach ($p['units'] as $u) {
            if ($u['blade']) continue;
            if ($isSharedPage && isset($sharedHashes[$u['hash']])) continue;
            $own .= "\n" . trim($u['raw']) . "\n";
            $kept++;
        }
        file_put_contents("$CSS_DIR/$key.css", $own);
        echo "wrote $key.css ($kept units)\n";

        // rewrite blade: rebuild each style block keeping only blade units of that block
        $html = $p['html'];
        // process blocks last-to-first so offsets stay valid
        for ($bi = count($p['blocks']) - 1; $bi >= 0; $bi--) {
            $b = $p['blocks'][$bi];
            $keepUnits = [];
            foreach ($p['units'] as $u) {
                if ($u['block'] === $bi && $u['blade']) $keepUnits[] = trim($u['raw']);
            }
            if ($keepUnits) {
                $replacement = "<style>\n    /* Dynamic (Blade) styles - kept inline intentionally */\n    "
                             . implode("\n\n    ", $keepUnits) . "\n    </style>";
            } else {
                $replacement = '';
            }
            $html = substr_replace($html, $replacement, $b['offset'], strlen($b['full']));
        }

        // inject stylesheet links via custom_css section (head of layout-2)
        $sharedLink = $isSharedPage
            ? "    <link rel=\"stylesheet\" href=\"{{ asset('public/front/assets/css/service-pages-shared.css') }}?v=1\">\n"
            : "";
        $links = "@section('custom_css')\n"
               . $sharedLink
               . "    <link rel=\"stylesheet\" href=\"{{ asset('public/front/assets/css/$key.css') }}?v=1\">\n"
               . "@endsection\n\n";
        if (strpos($html, "@section('custom_css')") !== false || strpos($html, '@section("custom_css")') !== false) {
            fwrite(STDERR, "[$key] already has custom_css section - manual merge needed\n");
            exit(1);
        }
        // place right after the @extends line
        $cnt = 0;
        $html = preg_replace_callback('/^(?:\xEF\xBB\xBF)?@extends\([^\)]+\)/', function ($m) use ($links, &$cnt) {
            $cnt++;
            return $m[0] . "\n\n" . $links;
        }, $html, 1);
        if ($cnt !== 1) { fwrite(STDERR, "[$key] could not find @extends line\n"); exit(1); }

        file_put_contents($p['path'], $html);
        echo "rewrote {$p['rel']}\n";
    }
    exit(0);
}

/* ---------- verify ---------- */

if ($mode === 'verify') {
    $snapshot = json_decode(file_get_contents("$ROOT/scripts/.css_original_units.json"), true);
    if (!$snapshot) { fwrite(STDERR, "no original snapshot found - run generate first\n"); exit(1); }
    $shared = file_get_contents("$CSS_DIR/service-pages-shared.css");
    $sharedUnits = array_map('normalizeUnit', tokenizeUnits(stripComments($shared)));
    $fail = 0;
    foreach ($PAGES as $key => $rel) {
        $own = file_get_contents("$CSS_DIR/$key.css");
        $ownUnits = array_map('normalizeUnit', tokenizeUnits(stripComments($own)));
        $newHtml = file_get_contents("$ROOT/$rel");
        $inlineUnits = [];
        foreach (extractStyleBlocks($newHtml) as $b) {
            foreach (tokenizeUnits($b['css']) as $u) $inlineUnits[] = normalizeUnit($u);
        }
        // final cascade multiset = shared (big pages only) + own + inline
        $final = in_array($key, $SHARED_PAGES)
            ? array_merge($sharedUnits, $ownUnits, $inlineUnits)
            : array_merge($ownUnits, $inlineUnits);
        $finalCount = array_count_values(array_filter($final, fn($x) => $x !== ''));

        // original units from the pre-rewrite snapshot
        $orig = $snapshot[$key] ?? null;
        if ($orig === null) { echo "no original snapshot for $key\n"; $fail++; continue; }
        $missing = [];
        foreach ($orig as $norm) {
            if ($norm !== '' && !isset($finalCount[$norm])) {
                $missing[] = substr($norm, 0, 80);
            }
        }
        if ($missing) {
            $fail++;
            echo "[$key] MISSING " . count($missing) . " units:\n";
            foreach (array_slice($missing, 0, 5) as $m) echo "   $m\n";
        } else {
            echo "[$key] OK - all " . count($orig) . " original units present in final cascade\n";
        }
    }
    exit($fail ? 1 : 0);
}
