<?php
/**
 * One-off: extract layout-2's three inline <style> blocks into front-global.css
 * (link replaces block 1 in place; blocks 2 and 3 removed — no stylesheet links
 * exist between them, verified) and the ai-assistant partial's CSS into
 * ai-assistant.css. Also strips UTF-8 BOMs and tightens the directive header
 * of the four service page blades.
 */

$ROOT = dirname(__DIR__);
$CSS  = "$ROOT/public/front/assets/css";

/* ---------- layout-2 ---------- */

$path = "$ROOT/resources/views/front/layout-2.blade.php";
$html = file_get_contents($path);
preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $html, $m, PREG_OFFSET_CAPTURE);
if (count($m[0]) !== 3) { fwrite(STDERR, "expected 3 style blocks in layout-2, found " . count($m[0]) . "\n"); exit(1); }

$css = "/* ============================================================\n"
     . "   Global front-end styles (extracted from layout-2 inline blocks)\n"
     . "   Order preserved: cookie banner / responsive sidebar / global base\n"
     . "   ============================================================ */\n";
foreach ($m[1] as $b) $css .= "\n" . trim($b[0]) . "\n";
file_put_contents("$CSS/front-global.css", $css);

$link = '<link rel="stylesheet" href="{{ asset(\'public/front/assets/css/front-global.css\') }}?v=1">';
// replace from last to first so offsets stay valid
for ($i = 2; $i >= 0; $i--) {
    $rep = $i === 0 ? $link : '';
    $html = substr_replace($html, $rep, $m[0][$i][1], strlen($m[0][$i][0]));
}
file_put_contents($path, $html);
echo "layout-2: 3 blocks -> front-global.css (" . strlen($css) . "B)\n";

/* ---------- ai-assistant partial ---------- */

$path = "$ROOT/resources/views/front/partials/ai-assistant.blade.php";
$html = file_get_contents($path);
preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $html, $m, PREG_OFFSET_CAPTURE);
if (count($m[0]) !== 1) { fwrite(STDERR, "expected 1 style block in ai-assistant, found " . count($m[0]) . "\n"); exit(1); }

$css = "/* Alpha Virtu chat widget styles (extracted from partials/ai-assistant.blade.php) */\n\n"
     . trim($m[1][0][0]) . "\n";
file_put_contents("$CSS/ai-assistant.css", $css);

$link = '<link rel="stylesheet" href="{{ asset(\'public/front/assets/css/ai-assistant.css\') }}?v=1">';
$html = substr_replace($html, $link, $m[0][0][1], strlen($m[0][0][0]));
file_put_contents($path, $html);
echo "ai-assistant: 1 block -> ai-assistant.css (" . strlen($css) . "B)\n";

/* ---------- BOM strip + header tightening on the four page blades ---------- */

$pages = [
    'resources/views/front/service_category.blade.php',
    'resources/views/front/service_group.blade.php',
    'resources/views/front/service.blade.php',
    'resources/views/front/service_group_all_services.blade.php',
];
foreach ($pages as $rel) {
    $p = "$ROOT/$rel";
    $h = file_get_contents($p);
    $orig = $h;
    // strip UTF-8 BOM
    if (strncmp($h, "\xEF\xBB\xBF", 3) === 0) $h = substr($h, 3);
    // collapse blank lines in the header area (before @section('content'))
    $pos = strpos($h, "@section('content')");
    if ($pos !== false) {
        $head = substr($h, 0, $pos);
        $head = preg_replace("/(\R)(?:[ \t]*\R)+/", '$1', $head);
        $h = $head . substr($h, $pos);
    }
    if ($h !== $orig) { file_put_contents($p, $h); echo "cleaned $rel\n"; }
}
echo "done\n";
