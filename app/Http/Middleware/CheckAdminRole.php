<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Default redirect paths for different roles
     */
    protected $roleRedirectPaths = [
        'system_admin' => '/admin/dashboard',
        'admin' => '/admin/users',
        'support' => '/admin/support',
        'finance' => '/admin/card-transactions',
        'user_manager' => '/admin/onboarding',
        'manager' => '/admin/dashboard'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth('admin')->check()) {
            return redirect('admin/login');
        }

        $admin = auth('admin')->user();
        
        // If user has any of the required roles, allow access
        if ($admin->hasAnyRole($roles)) {
            return $next($request);
        }

        // If unauthorized, redirect to their appropriate dashboard based on their role
        foreach ($this->roleRedirectPaths as $role => $path) {
            if ($admin->hasRole($role)) {
                return redirect($path)->with('error', 'You do not have permission to access that page.');
            }
        }

        // Fallback redirect if no specific role path is found
        return redirect()->route('admin.dashboard')
            ->with('error', 'You do not have permission to access that page.');
    }
}
