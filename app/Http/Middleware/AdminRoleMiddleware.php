<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        // Get the authenticated admin
        $admin = Auth::guard('admin')->user();

        // Check if admin exists
        if (!$admin) {
            abort(403, 'Unauthorized access.');
        }

        // Check if admin is a super admin
        if ($admin->hasRole('super_admin')) {
            return $next($request);
        }

        // Check if admin has the required role
        if (!$admin->hasRole($role)) {
            abort(403, 'Unauthorized access.');
        }


        return $next($request);
    }
}
