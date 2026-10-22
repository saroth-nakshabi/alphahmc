<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * One machine caller (the SEO agent): the Bearer token's SHA-256 must match
 * services.agent_api.token_sha256. Fails closed when no hash is configured. The token and the
 * Authorization header are never logged. Also forces JSON answers (the client sends no Accept).
 */
class AgentToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        $expected = strtolower(trim((string) config('services.agent_api.token_sha256')));
        $token = (string) $request->bearerToken();

        if ($expected === '' || $token === '' || !hash_equals($expected, hash('sha256', $token))) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
