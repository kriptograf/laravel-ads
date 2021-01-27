<?php

use App\Models\Category;
use App\Models\Region;
use App\Router\AdvertPath;

if (!function_exists('adverts_path')) {
    /**
     * @param Region|null   $region
     * @param Category|null $category
     *
     * @return AdvertPath
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @author Виталий Москвин <foreach@mail.ru>
     */
    function adverts_path(?Region $region, ?Category $category)
    {
        return app()->make(AdvertPath::class)
            ->withRegion($region)
            ->withCategory($category)
            ;
    }
}