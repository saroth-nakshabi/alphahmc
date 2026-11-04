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
}
