<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add any routes you want to exclude from CSRF protection
        // 'api/*'
        '/veriff/start',
        '/veriff/callback',
        '/veriff/status/*'

    ];
    
}