<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // System Admin
    Gate::define('access-all', function ($admin) {
        return $admin->hasRole('system_admin');
    });

    // Combined Admin and System Admin access
    Gate::define('access-onboarding', function ($admin) {
        return $admin->hasAnyRole(['system_admin', 'admin', 'user_manager']);
    });

    Gate::define('manage-users', function ($admin) {
        return $admin->hasAnyRole(['system_admin', 'admin']);
    });

    // Other role-specific gates - including system_admin access
    Gate::define('manage-users-only', function ($admin) {
        return $admin->hasAnyRole(['system_admin', 'user_manager']);
    });

    Gate::define('access-support', function ($admin) {
        return $admin->hasAnyRole(['system_admin', 'support']);
    });

    Gate::define('access-finance', function ($admin) {
        return $admin->hasAnyRole(['system_admin', 'finance']);
    });
}
}
