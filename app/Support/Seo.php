<?php

namespace App\Support;

/**
 * Builds the text for a page's <title> from CMS fields.
 *
 * Search results show about 60 characters, so long meta titles are cut at the
 * last whole word that fits (never mid-word). A brand suffix such as
 * " | Alpha Health Group" is added only when the full result still fits, so it
 * never pushes the page's own words out of view.
 */
class Seo
{
    public const TITLE_MAX = 60;

    public static function title(?string $metaTitle, ?string $fallback, ?string $brand = null, int $max = self::TITLE_MAX): string
    {
        $title = self::clean($metaTitle);
        if ($title === '') {
            $title = self::clean($fallback);
        }

        $title = self::cut($title, $max);

        if ($brand !== null && $brand !== '' && stripos($title, $brand) === false) {
            $branded = $title . ' | ' . $brand;
            if (mb_strlen($branded) <= $max) {
                return $branded;
            }
        }

        return $title;
    }

    private static function clean(?string $text): string
    {
        $text = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    /** Cut to at most $max characters at a word boundary, dropping trailing separators. */
    private static function cut(string $text, int $max): string
    {
        if (mb_strlen($text) <= $max) {
            return $text;
        }

        $head = mb_substr($text, 0, $max);
        // Keep the whole head when the next character already ends a word.
        if (mb_substr($text, $max, 1) !== ' ') {
            $space = mb_strrpos($head, ' ');
            if ($space !== false) {
                $head = mb_substr($head, 0, $space);
            }
        }

        return rtrim($head, " \t,;:|-");
    }
}
