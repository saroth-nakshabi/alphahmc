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
}
