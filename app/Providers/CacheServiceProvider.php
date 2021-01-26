<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

/**
 * Сервис провайдер кеша
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // -- Очищаем кеш по тегу
        $this->registerFlusher(Region::class);
        $this->registerFlusher(Category::class);
    }

    /**
     * Очищаем кеш по тегу
     * 
     * @param $class
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function registerFlusher($class): void
    {
        /** @var Model $class */
        $flush = function () use ($class) {
            Cache::tags($class)->flush();
        };

        $class::created($flush);
        $class::saved($flush);
        $class::updated($flush);
        $class::deleted($flush);
    }
}
