<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(
            at: '*',  // Trust all proxies (Heroku’s router IPs are dynamic)
            headers: Request::HEADER_X_FORWARDED_AWS_ELB // Recognize Heroku's forwarded headers
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
