<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Allow-list HTML sanitiser for blog HTML written through the agent API (brief alphahmc-blog-api.md
 * 6.6). Covers what the admin editor's toolbar produces; everything else is removed or unwrapped.
 * No composer dependency: DOMDocument plus explicit tag, attribute, style and URL scheme rules.
 */
class HtmlSanitizer
{
    /** Kept tags (img is limited separately). */
    private const ALLOWED = ['p', 'br', 'h2', 'h3', 'h4', 'h5', 'h6', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li',
        'a', 'blockquote', 'pre', 'code', 'span', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'img'];

    /** Removed together with everything inside them. */
    private const DROP = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'link',
        'meta', 'svg', 'math', 'noscript', 'template', 'textarea', 'select', 'frame', 'frameset', 'base'];

    /** Elements that may keep style="text-align: left|center|right". */
    private const ALIGNABLE = ['p', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'td', 'th'];

    public static function clean(string $html, ?string $title = null): string
    {
        if (trim($html) === '') {
            return '';
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        // The XML declaration makes libxml read the input as UTF-8 (Arabic and accents survive).
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="__sanitize_root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('__sanitize_root');
        if (!$root) {
            return '';
        }

        self::dropLeadingTitle($root, $title);
        self::walk($root);

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }

    /** Plain text for titles, descriptions and meta fields. */
    public static function text(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(preg_replace('/\s+/u', ' ', strip_tags($value)));
    }

    /** The template prints the title as the page h1, so a leading h1 equal to it is removed. */
    private static function dropLeadingTitle(DOMElement $root, ?string $title): void
    {
        if ($title === null) {
            return;
        }
        foreach ($root->childNodes as $node) {
            if ($node->nodeType === XML_TEXT_NODE && trim($node->textContent) === '') {
                continue;
            }
            if ($node instanceof DOMElement && strtolower($node->nodeName) === 'h1'
                && self::norm($node->textContent) === self::norm($title)) {
                $root->removeChild($node);
            }
            return;
        }
    }

    private static function walk(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            switch ($node->nodeType) {
                case XML_TEXT_NODE:
                    break;
                case XML_ELEMENT_NODE:
                    self::element($node);
                    break;
                default:
                    // comments, CDATA, processing instructions, doctype, entity references
                    $parent->removeChild($node);
            }
        }
    }

    private static function element(DOMElement $el): void
    {
        $tag = strtolower($el->nodeName);

        if (in_array($tag, self::DROP, true)) {
            $el->parentNode->removeChild($el);
            return;
        }

        if ($tag === 'h1') {
            $el = self::rename($el, 'h2');
            $tag = 'h2';
        }

        if (!in_array($tag, self::ALLOWED, true)) {
            // Unknown element: keep its children in its place.
            $parent = $el->parentNode;
            $children = iterator_to_array($el->childNodes);
            foreach ($children as $child) {
                $parent->insertBefore($child, $el);
            }
            $parent->removeChild($el);
            foreach ($children as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    self::element($child);
                } elseif ($child->nodeType !== XML_TEXT_NODE) {
                    $parent->removeChild($child);
                }
            }
            return;
        }

        self::attributes($el, $tag);

        if ($tag === 'img' && !$el->hasAttribute('src')) {
            $el->parentNode->removeChild($el);
            return;
        }

        self::walk($el);
    }

    private static function attributes(DOMElement $el, string $tag): void
    {
        $keep = [];
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->nodeName);
            $value = $attr->nodeValue;

            $ok = match (true) {
                $tag === 'a' && $name === 'href'               => self::schemeOk($value, false),
                $tag === 'a' && in_array($name, ['title', 'target', 'rel'], true) => true,
                $tag === 'img' && $name === 'src'              => self::schemeOk($value, true),
                $tag === 'img' && $name === 'alt'              => true,
                $tag === 'img' && in_array($name, ['width', 'height'], true) => (bool) preg_match('/^\d{1,4}$/', trim($value)),
                in_array($tag, ['td', 'th'], true) && in_array($name, ['colspan', 'rowspan'], true) => (bool) preg_match('/^\d{1,3}$/', trim($value)),
                $name === 'style'                              => ($value = self::style($tag, $value)) !== '',
                default                                        => false,
            };

            if ($ok) {
                $keep[$name] = $value;
            }
        }

        foreach (iterator_to_array($el->attributes) as $attr) {
            $el->removeAttribute($attr->nodeName);
        }
        foreach ($keep as $name => $value) {
            $el->setAttribute($name, $value);
        }

        if ($tag === 'a' && strtolower($el->getAttribute('target')) === '_blank') {
            $rel = preg_split('/\s+/', strtolower(trim($el->getAttribute('rel'))), -1, PREG_SPLIT_NO_EMPTY);
            if (!in_array('noopener', $rel, true)) {
                $rel[] = 'noopener';
            }
            $el->setAttribute('rel', implode(' ', $rel));
        }
    }

    /**
     * URL scheme check on a normalised copy: entities decoded (with or without the semicolon),
     * control and whitespace characters removed anywhere, lower-cased.
     */
    public static function schemeOk(string $value, bool $image): bool
    {
        $v = preg_replace_callback('/&#(x[0-9a-f]+|\d+);?/i', function ($m) {
            $code = $m[1][0] === 'x' || $m[1][0] === 'X' ? hexdec(substr($m[1], 1)) : (int) $m[1];
            return $code > 0 && $code < 0x110000 ? mb_chr($code, 'UTF-8') : '';
        }, $value);
        $v = html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $v = preg_replace('/[\x00-\x20\x7F]|\p{Z}|\x{FEFF}|\x{200B}/u', '', $v) ?? '';
        $v = strtolower($v);

        $siteRelative = str_starts_with($v, '/') && !str_starts_with($v, '//');
        if ($image) {
            return str_starts_with($v, 'https:') || $siteRelative;
        }

        return str_starts_with($v, 'http:') || str_starts_with($v, 'https:') || str_starts_with($v, 'mailto:')
            || str_starts_with($v, 'tel:') || str_starts_with($v, '#') || $siteRelative;
    }

    /** Only text-align on block elements, and only colour on spans; anything else is dropped. */
    private static function style(string $tag, string $value): string
    {
        $kept = [];
        foreach (explode(';', $value) as $decl) {
            if (!str_contains($decl, ':')) {
                continue;
            }
            [$prop, $val] = array_map(fn ($s) => strtolower(trim($s)), explode(':', $decl, 2));

            if ($prop === 'text-align' && in_array($tag, self::ALIGNABLE, true) && in_array($val, ['left', 'center', 'right'], true)) {
                $kept[] = "text-align: {$val}";
            } elseif ($tag === 'span' && in_array($prop, ['color', 'background-color'], true)
                && preg_match('/^(#[0-9a-f]{3}|#[0-9a-f]{6}|rgb\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*\))$/', $val)) {
                $kept[] = "{$prop}: {$val}";
            }
        }

        return implode('; ', $kept);
    }

    private static function rename(DOMElement $el, string $tag): DOMElement
    {
        $new = $el->ownerDocument->createElement($tag);
        foreach (iterator_to_array($el->attributes) as $attr) {
            $new->setAttribute($attr->nodeName, $attr->nodeValue);
        }
        while ($el->firstChild) {
            $new->appendChild($el->firstChild);
        }
        $el->parentNode->replaceChild($new, $el);

        return $new;
    }

    private static function norm(string $text): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'))));
    }
}
