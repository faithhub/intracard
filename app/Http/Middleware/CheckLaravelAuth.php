<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLaravelAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if Laravel session is still valid
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Session expired. Please log in again.',
            ], 401); // Unauthorized
        }

        return $next($request);
    }
}
