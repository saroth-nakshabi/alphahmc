<?php

use App\Http\Middleware\AgentToken;
use App\Http\Middleware\CheckStudentProfile;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\TrimHtmlWhitespace;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SecurityHeaders::class);
        $middleware->web(append: [TrimHtmlWhitespace::class]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check_student_profile' => CheckStudentProfile::class,
            'agent.token' => AgentToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // The SEO agent sends no Accept header: answer everything under /api with JSON
        // (422 instead of a redirect, 404 without an HTML page).
        $exceptions->shouldRenderJsonWhen(fn ($request) => $request->is('api/*') || $request->expectsJson());
    })->create();
