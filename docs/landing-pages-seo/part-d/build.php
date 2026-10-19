<?php
/**
 * Builds Part D from content.php: partD.sql (PART 1 ready, PART 2 held in a comment until
 * every [confirm] is resolved) and partD-review.md. Fails on any copy-rule break, on a fix
 * whose original text is not found exactly once, or on lengths over the limits.
 * Usage: php build.php [--apply-local]
 */

['meta' => $meta, 'fixes' => $fixes, 'faqFixes' => $faqFixes, 'held' => $held, 'verify' => $verify] = require __DIR__ . '/content.php';

$pdo = new PDO('mysql:host=127.0.0.1;dbname=alphahmc_webnext;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$q = fn ($v) => $pdo->quote($v);
$errors = [];

$rules = function (string $where, string $v) use (&$errors) {
    if (preg_match('/[\x{2013}\x{2014}]/u', $v)) $errors[] = "$where: en or em dash";
    if (preg_match('/\bcompliant\b/i', $v)) $errors[] = "$where: \"compliant\"";
    if (preg_match('/\b(ensure[sd]?|achiev\w*|full)\b[^.]{0,30}\bcompliance\b/i', $v)) $errors[] = "$where: compliance claim";
    if (preg_match('/200\s*\+|2[05]\+? years|\bzero\b|eliminat|first[- ](time|attempt)/i', $v)) $errors[] = "$where: unbacked claim";
};

// Find the stored form of a plain-text fragment (entities the editor may have used).
$locate = function (string $html, string $plain): ?string {
    $variants = array_unique([
        $plain,
        htmlspecialchars($plain, ENT_NOQUOTES, 'UTF-8'),
        str_replace("'", '&#39;', htmlspecialchars($plain, ENT_NOQUOTES, 'UTF-8')),
        str_replace("'", '&rsquo;', htmlspecialchars($plain, ENT_NOQUOTES, 'UTF-8')),
        str_replace("'", "\u{2019}", $plain),
    ]);
    foreach ($variants as $v) if (substr_count($html, $v) === 1) return $v;
    return null;
};

/* meta */
$ready = ["-- D2: titles and descriptions"];
foreach ($meta as $id => [$title, $desc]) {
    $rules("meta $id title", $title); $rules("meta $id description", $desc);
    if (mb_strlen($title) > 60) $errors[] = "meta $id title is " . mb_strlen($title);
    if (mb_strlen($desc) > 155) $errors[] = "meta $id description is " . mb_strlen($desc);
    $ready[] = "UPDATE services SET meta_title = " . $q($title) . ", meta_description = " . $q($desc) . ", updated_at = NOW() WHERE id = $id;";
}

/* service field fixes */
$ready[] = "\n-- D3: sentence fixes (each applies only while the original text is still there)";
foreach ($fixes as [$id, $col, $old, $new]) {
    $rules("fix $id $col", $new);
    $html = (string) $pdo->query("SELECT `$col` FROM services WHERE id = " . (int) $id)->fetchColumn();
    $stored = $locate($html, $old);
    if ($stored === null) { $errors[] = "fix $id $col: original not found exactly once: " . mb_substr($old, 0, 60); continue; }
    $newStored = $stored === $old ? $new : htmlspecialchars($new, ENT_NOQUOTES, 'UTF-8');
    $ready[] = "UPDATE services SET `$col` = REPLACE(`$col`, " . $q($stored) . ", " . $q($newStored) . "), updated_at = NOW() WHERE id = $id AND INSTR(`$col`, " . $q($stored) . ") > 0;";
}
$ready[] = "\n-- D3: FAQ sentence fixes";
foreach ($faqFixes as [$fid, $old, $new]) {
    $rules("faq $fid", $new);
    $html = (string) $pdo->query('SELECT faq_answer FROM faqs WHERE id = ' . (int) $fid)->fetchColumn();
    $stored = $locate($html, $old);
    if ($stored === null) { $errors[] = "faq $fid: original not found exactly once"; continue; }
    $ready[] = "UPDATE faqs SET faq_answer = REPLACE(faq_answer, " . $q($stored) . ", " . $q($stored === $old ? $new : htmlspecialchars($new, ENT_NOQUOTES, 'UTF-8')) . "), updated_at = NOW() WHERE id = $fid AND INSTR(faq_answer, " . $q($stored) . ") > 0;";
}

/* held */
$rules('d1 text', str_replace('[confirm]', '', $held['d1_info_two_append']));
$rules('d1 title', $held['d1_title']);
if (mb_strlen($held['d1_title']) > 60) $errors[] = 'd1 title too long';
$heldSql = [];
$heldSql[] = "UPDATE services SET info_two = CONCAT(info_two, " . $q("\n" . $held['d1_info_two_append']) . "), meta_title = " . $q($held['d1_title']) . ", updated_at = NOW() WHERE id = 68 AND info_two NOT LIKE '%Change of plan approval%';";
$faqRows = array_map(fn ($f) => [68, $f[0], $f[1]], $held['d1_faqs']);
foreach ($held['d3_faqs'] as $sid => $list) foreach ($list as $f) $faqRows[] = [$sid, $f[0], $f[1]];
$words = fn ($s) => str_word_count(str_replace('[confirm]', '', $s));
$notes = [];
foreach ($faqRows as [$sid, $question, $answer]) {
    $rules("new faq $sid", $question); $rules("new faq $sid", $answer);
    $n = $words($answer); if ($n < 35 || $n > 62) $notes[] = "new faq on $sid has $n words";
    $heldSql[] = "INSERT INTO faqs (service_id, faq_question, faq_answer, created_at, updated_at) SELECT $sid, " . $q($question) . ", " . $q("<p>$answer</p>") . ", NOW(), NOW() WHERE NOT EXISTS (SELECT 1 FROM faqs WHERE service_id = $sid AND faq_question = " . $q($question) . ");";
}
$heldSql[] = "UPDATE services SET meta_description = " . $q(str_replace(' [confirm]', '', $held['d2_licensing_audit_description_with_timing'])) . " WHERE id = 76;  -- only once the 2 to 6 weeks is confirmed";

if ($errors) { fwrite(STDERR, "PART D CHECK FAILED\n  " . implode("\n  ", $errors) . "\n"); exit(1); }

$sql = "-- Part D for alphahmc_webnext. Generated by part-d/build.php; edit content.php, not this file.\n"
    . "-- ===== PART 1: ready =====\n" . implode("\n", $ready) . "\n\n"
    . "-- Check: titles <= 60, descriptions <= 155\nSELECT id, CHAR_LENGTH(meta_title) AS title_len, CHAR_LENGTH(meta_description) AS desc_len FROM services WHERE id IN (" . implode(', ', array_keys($meta)) . ");\n\n"
    . "-- ===== PART 2: held until [confirm] items are resolved =====\n/*\n" . str_replace('*/', '* /', implode("\n\n", $heldSql)) . "\n*/\n";
file_put_contents(__DIR__ . '/partD.sql', $sql);

$md = "# Part D draft: B2B landing pages\n\n## Ready now (PART 1)\n\n" . count($meta) . " titles and descriptions, " . count($fixes) . " sentence fixes and " . count($faqFixes) . " FAQ fixes. Each fix:\n\n";
foreach ($fixes as [$id, $col, $old, $new]) $md .= "- **$id $col**  \n  was: $old  \n  now: $new\n";
$md .= "\n## Held (PART 2)\n\n### D1 change of plan and relocation (service 68)\n\n" . trim(preg_replace(['#<h2>(.*?)</h2>#', '#</?p>#'], ["#### \$1", ''], $held['d1_info_two_append'])) . "\n\nNew title: {$held['d1_title']}\n\n";
foreach ($faqRows as [$sid, $question, $answer]) $md .= "- **$question** (service $sid)  \n  $answer\n";
$md .= "\n## Existing figures on live pages to verify\n\n" . implode("\n", array_map(fn ($v) => "- $v", $verify)) . "\n";
file_put_contents(__DIR__ . '/partD-review.md', $md);

echo "Part D checks passed: " . count($meta) . " meta, " . count($fixes) . " fixes, " . count($faqFixes) . " faq fixes ready; " . count($heldSql) . " held statements\n";
foreach ($notes as $n) echo "  note: $n\n";

if (in_array('--apply-local', $argv, true)) {
    foreach ($ready as $stmt) if (str_starts_with($stmt, 'UPDATE')) $pdo->exec($stmt);
    echo "PART 1 applied to LOCAL database\n";
}
