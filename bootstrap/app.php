<?php

use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\AdminRoleMiddleware;
use App\Http\Middleware\CheckLaravelAuth;
use App\Http\Middleware\CustomGuestMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedAdmin;
use App\Http\Middleware\VerifyOtp;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register global middleware here (applied to all routes)
        \App\Http\Middleware\VerifyCsrfToken::class;
        \Illuminate\Http\Middleware\HandleCors::class; // Handles CORS requests
        // $middleware->add(\App\Http\Middleware\AdminAuthMiddleware::class);

        $middleware->prependToGroup('admin.auth', [
            AdminAuthMiddleware::class,
        ]);

        $middleware->prependToGroup('admin.guest', [
            RedirectIfAuthenticatedAdmin::class,
        ]);
        // Optionally, register middleware groups or aliases (if applicable)
        $middleware->alias([
            'check.otp' => VerifyOtp::class,
            'custom-guest' => CustomGuestMiddleware::class,
            'checkLaravelAuth' => CheckLaravelAuth::class,
            'admin.role' => AdminRoleMiddleware::class,
        ]);
        // $middleware->alias([
        //     'admin.auth' => AdminAuthMiddleware::class
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
