<?php

namespace App\Providers;

use App\Models\Admin\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Неявно предоставить все разрешения роли суперадминистратора
        //Это работает в приложении с использованием функций, связанных со шлюзами , таких как auth()->user->can () и @can().
        Gate::before(function ($user, $ability) {
            return $user->hasRole(Role::ROLE_ADMIN) ? true : null;
        });
    }
}
