<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Dynamically register gates for all permissions
        try {
            Permission::all()->each(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermission($permission->name);
                });
            });
        } catch (\Exception $e) {
            // Handle case where database is not yet migrated
        }

        // Register special gates
        Gate::define('manage-roles', function ($user) {
            return $user->canManageRoles();
        });

        Gate::define('is-admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
