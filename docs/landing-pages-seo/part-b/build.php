<?php
/**
 * Builds Part B from content.php:
 *   partB.sql        Part 1 (ready, runnable) + Part 2 (held in a comment until [confirm] is resolved)
 *   partB-review.md  plain-text review copy for the licensing team, every [confirm] listed
 * and fails if any copy rule is broken. Usage: php build.php [--apply-local]
 */

$data = require __DIR__ . '/content.php';
['svc' => $svc, 'blog38' => $blog38, 'pageSettings' => $pageSettings, 'aboutHeroDescription' => $aboutHero,
 'blogAuthor' => $blogAuthor, 'blogCategories' => $blogCategories] = $data;

$pdo = new PDO('mysql:host=127.0.0.1;dbname=alphahmc_webnext;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$q = fn ($v) => $pdo->quote($v);
$text = fn ($html) => trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace(['</li>', '</p>', '</h2>'], ["\n", "\n", "\n"], $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
$words = fn ($s) => str_word_count(str_replace('[confirm]', '', $s));

/* ── copy checks ───────────────────────────────────────────────────── */
$errors = [];
$warn = [];
$check = function (string $where, string $value) use (&$errors) {
    if (preg_match('/[\x{2013}\x{2014}]/u', $value)) $errors[] = "$where: contains an en or em dash";
    if (preg_match('/\bcomplian(t|ce)\b/i', $value)) $errors[] = "$where: contains compliant/compliance";
};
foreach ($svc as $id => $s) {
    foreach (['ready', 'confirm'] as $part) foreach ($s[$part] as $col => $v) {
        $check("svc $id $col", $v);
        $plain = trim(str_replace('[confirm]', '', $v));
        if ($col === 'meta_title' && mb_strlen($plain) > 60) $errors[] = "svc $id meta_title is " . mb_strlen($plain) . ' chars';
        if ($col === 'meta_description' && mb_strlen($plain) > 155) $errors[] = "svc $id meta_description is " . mb_strlen($plain) . ' chars';
    }
    foreach ($s['faqs'] as $fid => [$question, $answer]) {
        $check("faq $fid question", $question);
        $check("faq $fid answer", $answer);
        $n = $words($answer);
        if ($n < 35 || $n > 62) $warn[] = "faq $fid answer has $n words (target 40 to 60)";
    }
}
foreach ($pageSettings as $id => $d) { $check("page_settings $id", $d); if (mb_strlen($d) > 155) $errors[] = "page_settings $id is " . mb_strlen($d) . ' chars'; }
$check('about hero', $aboutHero);
$check('blog38 meta_title', $blog38['meta_title']);

// FAQ ids must belong to the service they are listed under.
foreach ($svc as $id => $s) {
    $ids = $pdo->query('SELECT id FROM faqs WHERE service_id = ' . (int) $id)->fetchAll(PDO::FETCH_COLUMN);
    foreach (array_keys($s['faqs']) as $fid) if (!in_array($fid, $ids)) $errors[] = "faq $fid is not a row of service $id";
}

// Every blog mapped exactly once, to an existing category.
$seen = [];
foreach ($blogCategories as $cat => $ids) foreach ($ids as $b) $seen[$b][] = $cat;
foreach ($seen as $b => $cats) if (count($cats) > 1) $errors[] = "blog $b mapped to categories " . implode(',', $cats);
$allBlogs = $pdo->query('SELECT id FROM blogs')->fetchAll(PDO::FETCH_COLUMN);
foreach (array_diff($allBlogs, array_keys($seen)) as $b) $errors[] = "blog $b has no category";
foreach (array_diff(array_keys($seen), $allBlogs) as $b) $errors[] = "blog $b does not exist";
$catIds = $pdo->query('SELECT id FROM categories')->fetchAll(PDO::FETCH_COLUMN);
foreach (array_keys($blogCategories) as $cat) if (!in_array($cat, $catIds)) $errors[] = "category $cat does not exist";

if ($errors) { fwrite(STDERR, "COPY CHECK FAILED\n  " . implode("\n  ", $errors) . "\n"); exit(1); }

/* ── SQL ───────────────────────────────────────────────────────────── */
$ready = $held = [];
$ready[] = "-- Services 64-67, 112: titles, descriptions, areaServed (no open facts)";
foreach ($svc as $id => $s) {
    $sets = [];
    foreach ($s['ready'] as $col => $v) $sets[] = "`$col` = " . $q($v);
    $ready[] = "UPDATE services SET " . implode(', ', $sets) . ", updated_at = NOW() WHERE id = $id;";
}
$ready[] = "\n-- Blog 38 (B7): title, and section headings H3 -> H2 (only while it has no H2 yet)";
$ready[] = "UPDATE blogs SET meta_title = " . $q($blog38['meta_title']) . ", content = REPLACE(REPLACE(content, '<h3>', '<h2>'), '</h3>', '</h2>') WHERE id = 38 AND content NOT LIKE '%<h2%';";
$ready[] = "\n-- Blog authors (Saroth 19 Oct): only blogs without an author";
$ready[] = "UPDATE blogs SET author_name = " . $q($blogAuthor) . " WHERE author_name IS NULL OR author_name = '';";
$ready[] = "\n-- Blog categories: every blog to the service category whose pages show it as the related article";
foreach ($blogCategories as $cat => $ids) $ready[] = "UPDATE blogs SET category_id = $cat WHERE id IN (" . implode(', ', $ids) . ");";
$ready[] = "\n-- page_settings descriptions: no compliance claims, no dashes, 155 characters or fewer";
foreach ($pageSettings as $id => $d) $ready[] = "UPDATE page_settings SET meta_description = " . $q($d) . ", updated_at = NOW() WHERE id = $id;";
$ready[] = "UPDATE page_settings SET hero_description = " . $q($aboutHero) . ", updated_at = NOW() WHERE id = 2;";

foreach ($svc as $id => $s) {
    $sets = [];
    foreach ($s['confirm'] as $col => $v) $sets[] = "`$col` = " . $q($v);
    $held[] = "UPDATE services SET " . implode(",\n    ", $sets) . ",\n    updated_at = NOW() WHERE id = $id;";
    foreach ($s['faqs'] as $fid => [$question, $answer]) {
        $held[] = "UPDATE faqs SET faq_question = " . $q($question) . ", faq_answer = " . $q("<p>$answer</p>") . ", updated_at = NOW() WHERE id = $fid AND service_id = $id;";
    }
}

$sql = "-- Part B content for alphahmc_webnext. Generated by part-b/build.php; edit content.php, not this file.\n"
    . "-- PART 1 is ready to run. PART 2 stays commented out until every [confirm] in content.php is resolved and this file is rebuilt.\n\n"
    . "-- ===== PART 1: ready =====\n" . implode("\n", $ready) . "\n\n"
    . "-- Check after PART 1\nSELECT id, meta_title, CHAR_LENGTH(meta_title) AS title_len, CHAR_LENGTH(meta_description) AS desc_len, areaServed FROM services WHERE id IN (64, 65, 66, 67, 112);\n"
    . "SELECT id, page_key, CHAR_LENGTH(meta_description) AS desc_len FROM page_settings WHERE meta_description LIKE '%complian%';  -- expect no rows\n\n"
    . "-- ===== PART 2: held until [confirm] items are resolved =====\n/*\n" . str_replace('*/', '* /', implode("\n\n", $held)) . "\n*/\n\n"
    . "-- After PART 2, this must return no rows:\n-- SELECT id FROM services WHERE CONCAT_WS(' ', name, meta_description, content, overview, info_one, info_two, info_three, info_four) LIKE '%[confirm]%' UNION ALL SELECT id FROM faqs WHERE CONCAT(faq_question, faq_answer) LIKE '%[confirm]%';\n";
file_put_contents(__DIR__ . '/partB.sql', $sql);

/* ── review document ───────────────────────────────────────────────── */
$names = $pdo->query('SELECT id, slug FROM services WHERE id IN (64,65,66,67,112)')->fetchAll(PDO::FETCH_KEY_PAIR);
$md = "# Part B draft: licensing pages\n\nEvery [confirm] needs a fact from the licensing team before Part 2 goes live. Text without a marker uses facts already on the site.\n";
$confirms = [];
foreach ([67, 112, 66, 65, 64] as $id) {
    $s = $svc[$id];
    $md .= "\n## /services/{$names[$id]} (id $id)\n\n";
    foreach (array_merge($s['ready'], $s['confirm']) as $col => $v) {
        $md .= "**$col**\n\n" . (str_contains($v, '<') ? str_replace(["\n"], ["\n\n"], trim(preg_replace(['#<h2>(.*?)</h2>#', '#<li>(.*?)</li>#', '#</?(p|ol|ul)>#'], ["### \$1", "- \$1", ''], $v))) : $v) . "\n\n";
        if (str_contains($v, '[confirm]')) foreach (preg_split('/(?<=[.!?])\s+/', $text($v)) as $sentence) if (str_contains($sentence, '[confirm]')) $confirms[] = "id $id $col: " . trim($sentence);
    }
    $md .= "**FAQs**\n\n";
    foreach ($s['faqs'] as $fid => [$question, $answer]) {
        $md .= "- **$question** (faq $fid, " . $words($answer) . " words)  \n  $answer\n";
        if (str_contains($answer, '[confirm]')) $confirms[] = "faq $fid: " . trim(preg_replace('/^.*?(\[confirm\][^.]*\.?).*$/s', '$1', $answer));
    }
}
$md .= "\n## Open [confirm] items\n\n" . implode("\n", array_map(fn ($c) => "- $c", array_unique($confirms))) . "\n";
file_put_contents(__DIR__ . '/partB-review.md', $md);

echo "copy checks passed\n";
foreach ($warn as $w) echo "  note: $w\n";
echo "ready statements: " . (count($ready) - 3) . ", held statements: " . count($held) . ", open [confirm] items: " . count(array_unique($confirms)) . "\n";

/* ── optional: apply both parts to the LOCAL database for a render test ── */
if (in_array('--apply-local', $argv, true)) {
    foreach (array_merge(array_filter($ready, fn ($l) => str_starts_with($l, 'UPDATE')), $held) as $stmt) $pdo->exec($stmt);
    echo "applied to LOCAL database (render test only)\n";
}
