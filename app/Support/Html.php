<?php

namespace App\Support;

/**
 * Render-time helpers for CMS-authored rich-text (TinyMCE blog/service content).
 *
 * fixLinks() repairs internal links that were saved as *bare relative* paths
 * (e.g. href="services/foo") by the rich-text editor's old URL-conversion
 * behaviour. A bare relative href resolves against the CURRENT page path, so
 * a link to "services/foo" placed in /blog_single/<slug> wrongly pointed at
 * /blog_single/services/foo. We re-anchor such links to the app base via url()
 * so they work in any environment (local /alphahmc subdir or prod domain root).
 *
 * The editor itself is now configured with convert_urls:false, so NEW links are
 * stored as full absolute URLs and pass through untouched — this helper only
 * heals content that was already saved with the broken relative form.
 */
class Html
{
    /**
     * Re-anchor bare relative internal links in CMS HTML to the app base URL.
     * Absolute URLs (http/https/protocol-relative), root-relative paths (/...),
     * anchors (#...), and mailto:/tel:/javascript: links are left untouched.
     */
    public static function fixLinks(?string $html): string
    {
        if ($html === null || $html === '' || stripos($html, '<a') === false) {
            return (string) $html;
        }

        return preg_replace_callback(
            '/(<a\b[^>]*?\bhref\s*=\s*)(["\'])(.*?)\2/is',
            function ($m) {
                $pre   = $m[1];
                $quote = $m[2];
                $href  = trim($m[3]);

                // Leave anything that is already absolute / rooted / a non-page scheme.
                if ($href === '' || preg_match('~^(?:[a-z][a-z0-9+.-]*:|//|/|#)~i', $href)) {
                    return $m[0];
                }

                return $pre . $quote . e(url($href)) . $quote;
            },
            $html
        );
    }

    /**
     * Renders the opening h3 of a CMS block as an h2 with the given class, so the section heading sits at the right
     * outline level while keeping its h3 look. Only an h3 that opens the block (after optional wrapper divs) changes;
     * later h3s and blocks that open with text or another heading stay as they are.
     */
    public static function leadHeadingToH2(?string $html, string $class): string
    {
        $html = (string) $html;

        return preg_replace_callback(
            '~^(\s*(?:<div\b[^>]*>\s*)*)<h3\b([^>]*)>(.*?)</h3>~is',
            function ($m) use ($class) {
                $attrs = preg_match('~\bclass\s*=~i', $m[2])
                    ? preg_replace('~\bclass\s*=\s*(["\'])~i', 'class=$1' . $class . ' ', $m[2], 1)
                    : ' class="' . $class . '"' . $m[2];

                return $m[1] . '<h2' . $attrs . '>' . $m[3] . '</h2>';
            },
            $html,
            1
        );
    }

    /**
     * Renders the headings of a CMS body below the page h1 without skipped levels: the highest level used becomes h2
     * and each later heading is at most one level below the one before it. A heading that changes level gets the
     * Bootstrap class of its stored level (h3 -> <h2 class="h3">), so it keeps its look. The stored text is unchanged.
     */
    public static function bodyHeadingsFrom2(?string $html): string
    {
        $html = (string) $html;
        if (!preg_match_all('~<h([1-6])\b~i', $html, $found)) {
            return $html;
        }
        $shift = min(array_map('intval', $found[1])) - 2;
        $prev = 1;

        return preg_replace_callback(
            '~<h([1-6])\b([^>]*)>(.*?)</h\1\s*>~is',
            function ($m) use ($shift, &$prev) {
                $stored = (int) $m[1];
                $level = max(2, min(6, $stored - $shift, $prev + 1));
                $prev = $level;
                if ($level === $stored) {
                    return $m[0];
                }
                $attrs = preg_match('~\bclass\s*=~i', $m[2])
                    ? preg_replace('~\bclass\s*=\s*(["\'])~i', 'class=$1h' . $stored . ' ', $m[2], 1)
                    : ' class="h' . $stored . '"' . $m[2];

                return '<h' . $level . $attrs . '>' . $m[3] . '</h' . $level . '>';
            },
            $html
        );
    }
}
