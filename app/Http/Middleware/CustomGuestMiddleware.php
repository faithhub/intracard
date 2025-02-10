<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
       // Check if the user is authenticated
       if (Auth::check()) {
        // Return JSON response with authenticated user details
        if ($request->expectsJson()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user(), // Return authenticated user details
            ], 200);
        }

        // Let Vue handle the redirection for web requests
        return $next($request);
    }

        // Allow access if the user is a guest
        return $next($request);
    }
}
