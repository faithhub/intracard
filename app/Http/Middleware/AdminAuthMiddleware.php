<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated as an admin
        if (!Auth::guard('admin')->check()) {
            // Store the current URL before redirecting
            if (!$request->is('admin/sign-in') && !$request->is('admin/logout')) {
                $currentUrl = $request->fullUrl();
                session()->put('admin_intended_url', $currentUrl);
            }
            
            // Redirect to the admin login page if not authenticated
            return redirect()->route('admin.login')->with('error', 'Please log in to access this page.');
        }

        return $next($request);
    }
}