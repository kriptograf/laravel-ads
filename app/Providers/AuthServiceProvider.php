<?php

namespace App\Providers;

use App\Models\Admin\Role;
use App\Models\Advert;
use App\Models\Banner;
use App\Models\User;
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
        Gate::before(function (User $user, $ability) {
            return $user->hasRole(Role::ROLE_ADMIN) ? true : null;
        });

        Gate::define('moderateAdvert', function (User $user, Advert $advert) {
            return $user->hasRole(Role::ROLE_MODERATOR);
        });

        Gate::define('show-advert', function (User $user, Advert $advert) {
            return $user->hasRole(Role::ROLE_ADMIN) || $advert->user_id === $user->id;
        });

        // -- Проверка, что пользователь может редактировать свое объявление
        Gate::define('manage-own-advert', function (User $user, Advert $advert) {
            return $advert->user_id === $user->id;
        });

        // -- Проверка, что пользователь может редактировать свой баннер
        Gate::define('manage-own-banner', function (User $user, Banner $banner) {
            return $banner->user_id === $user->id;
        });
    }
}
