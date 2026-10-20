<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Repositories\PermissionRepository;



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

    public function boot(PermissionRepository $permissions): void
    {
        Password::defaults(function () {
            return Password::min(8)->letters()->numbers()->mixedCase();
        });

        if (Schema::hasTable('permissions')) {
            $permissions->allNames()->each(function (string $name) {
                Gate::define($name, fn (User $user) => $user->hasPermission($name));
            });
        }
    }
}
