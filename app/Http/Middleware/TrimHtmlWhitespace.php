<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Removes the indentation Blade leaves at the start of every HTML line (about 40% of the
 * home page: 264 KB of 647 KB) and collapses runs of blank lines.
 *
 * Each newline is kept, so the browser sees the same whitespace between elements and the
 * page renders the same. <pre>, <textarea>, <script> and <style> blocks are left untouched
 * (their whitespace can matter: preformatted text, form values, JS template literals).
 */
class TrimHtmlWhitespace
{
    private const PROTECTED = '#(<(pre|textarea|script|style)\b[^>]*>.*?</\2\s*>)#is';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->isMethod('GET')
            || $response instanceof StreamedResponse
            || $response instanceof BinaryFileResponse
            || !str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $html = $response->getContent();
        if (!is_string($html) || $html === '') {
            return $response;
        }

        $parts = preg_split(self::PROTECTED, $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false) {
            return $response;                       // regex failure (e.g. backtrack limit): send as is
        }

        $out = '';
        // preg_split with two capture groups yields: text, whole block, tag name, text, ...
        for ($i = 0, $n = count($parts); $i < $n; $i += 3) {
            $out .= preg_replace(['/\n[ \t]+/', '/\n{2,}/'], ["\n", "\n"], $parts[$i]) ?? $parts[$i];
            if (isset($parts[$i + 1])) {
                $out .= $parts[$i + 1];
            }
        }

        $response->setContent($out);

        return $response;
    }
}
