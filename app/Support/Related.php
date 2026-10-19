<?php

namespace App\Support;

/**
 * Picks the related item (e.g. a blog post) whose title best matches a page.
 *
 * Words shared by most candidates ("healthcare", "guide") say little, so each
 * shared word is weighted by how rare it is among the candidate titles: a
 * "transfer" or "Saudi" match outranks a "healthcare" match. Simple stemming
 * lets "licensing" match "license". Ties keep the candidates' given order, so
 * callers pass candidates already sorted by their preferred fallback order.
 */
class Related
{
    private const STOP = ['the', 'and', 'for', 'with', 'your', 'how', 'what', 'why', 'get', 'from',
        'into', 'that', 'this', 'are', 'you', 'our', 'complete', 'guide', 'know', 'need'];

    /**
     * @param  array<int|string, string> $candidates  key => title, in fallback order
     * @return int|string|null  key of the best match, or null when nothing overlaps
     */
    public static function bestTitleMatch(string $pageText, array $candidates)
    {
        if (!$candidates) {
            return null;
        }

        $pageWords = array_unique(self::words($pageText));
        $titleWords = array_map(fn ($t) => array_unique(self::words((string) $t)), $candidates);

        $df = [];
        foreach ($titleWords as $words) {
            foreach ($words as $w) {
                $df[$w] = ($df[$w] ?? 0) + 1;
            }
        }
        $n = count($candidates);

        $bestKey = null;
        $bestScore = 0.0;
        foreach ($titleWords as $key => $words) {
            $score = 0.0;
            foreach (array_intersect($words, $pageWords) as $w) {
                $score += log(1 + $n / $df[$w]);
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestKey = $key;
            }
        }

        return $bestKey;
    }

    /** @return string[] lower-case stems of the significant words */
    private static function words(string $text): array
    {
        $text = mb_strtolower(html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        preg_match_all('/[\p{L}\p{N}]{3,}/u', $text, $m);

        $out = [];
        foreach ($m[0] as $w) {
            if (in_array($w, self::STOP, true)) {
                continue;
            }
            $out[] = preg_replace('/(ing|es|s|e)$/u', '', $w);
        }

        return $out;
    }
}
