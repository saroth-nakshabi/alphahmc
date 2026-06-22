<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // CSP — permissive policy that covers all CDNs used on this site
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com https://www.googletagmanager.com https://www.google-analytics.com https://connect.facebook.net https://maps.googleapis.com https://static.cloudflareinsights.com https://pagead2.googlesyndication.com https://googleads.g.doubleclick.net https://www.googleadservices.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://maxcdn.bootstrapcdn.com",
            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https: http:",
            "media-src 'self' blob: https:",
            "connect-src 'self' https://www.google-analytics.com https://region1.google-analytics.com https://www.googletagmanager.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://pagead2.googlesyndication.com https://googleads.g.doubleclick.net https://www.googleadservices.com https://stats.g.doubleclick.net https://www.google.com wss:",
            "frame-src 'self' https://www.google.com https://www.youtube.com https://player.vimeo.com https://td.doubleclick.net https://googleads.g.doubleclick.net",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
