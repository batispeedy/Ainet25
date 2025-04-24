<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    // …

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-settings', function (User $user) {
            return $user->type === 'board';
        });

        Gate::define('manage-inventory', function (User $u) {
            return in_array($u->type, ['board','employee']);
        });
        Gate::define('manage-orders', function (User $u) {
            return in_array($u->type, ['board','employee']);
        });
        Gate::define('manage-users', function (User $u) {
            return $u->type === 'board';
        });
    }
}
