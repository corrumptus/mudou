<?php

use App\Http\Middleware\Web\UserFirstAccessRedirect;
use App\Http\Middleware\Web\UserHomePageSelectionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use App\Http\Middleware\Api\ApiAuthenticationService;
use App\Http\Middleware\Web\WebAuthenticationService;

$dir = dirname(__DIR__);

return Application::configure(basePath: $dir)
    ->withRouting(
        web: [
            "$dir/routes/web/admin.php",
            "$dir/routes/web/general.php",
            "$dir/routes/web/teacher.php",
            "$dir/routes/web/student.php"
        ],
        api: [
            "$dir/routes/api/admin.php",
            "$dir/routes/api/general.php",
            "$dir/routes/api/teacher.php",
            "$dir/routes/api/student.php"
        ],
        health: '/up',
    )
    ->withMiddleware(fn (Middleware $middleware) => $middleware
        ->web(append: [
            AddLinkHeadersForPreloadedAssets::class,
            WebAuthenticationService::class,
            UserFirstAccessRedirect::class,
            UserHomePageSelectionMiddleware::class
        ])

        ->api([
            ApiAuthenticationService::class
        ])

        ->encryptCookies(except: [
            WebAuthenticationService::$COOKIE_NAME,
        ])
    )
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
