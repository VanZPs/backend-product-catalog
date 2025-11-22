<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Http\Middleware\HandleCors;

use App\Http\Middleware\ApiAuthenticate;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        // Sanctum: SPA Authentication (stateful)
        $middleware->statefulApi()
            ->prependToGroup('api', EnsureFrontendRequestsAreStateful::class);

        // Wajib untuk session-based login
        $middleware->appendToGroup('api', StartSession::class);
        $middleware->appendToGroup('api', AddQueuedCookiesToResponse::class);

        // Supaya auth API tidak redirect ke "/login"
        // Mendaftarkan dua alias: 'auth.api' dan 'api.auth'
        $middleware->alias([
            'auth.api' => ApiAuthenticate::class,
            'api.auth' => ApiAuthenticate::class,
        ]);

        // Global CORS
        $middleware->use([
            HandleCors::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

->create();
