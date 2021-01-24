<?php

namespace App\Router;

use App\Models\Category;
use App\Models\Region;
use Illuminate\Contracts\Routing\UrlRoutable;

class AdvertPath implements UrlRoutable
{
    /** @var Region */
    public $region;

    /** @var Category */
    public $category;

    /**
     * Клонируем объект, иначе он у нас перезапишется
     *
     * @param Region|null $region
     *
     * @return AdvertPath
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function withRegion(?Region $region)
    {
        $clone = clone $this;
        $clone->region = $region;

        return $clone;
    }

    /**
     * Клонируем объект, иначе он у нас перезапишется
     *
     * @param Category|null $category
     *
     * @return AdvertPath
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function withCategory(?Category $category)
    {
        $clone = clone $this;
        $clone->category = $category;

        return $clone;
    }

    /**
     * @return mixed|string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getRouteKey()
    {
        $segments = [];

        if ($this->region) {
            $segments[] = $this->region->getPath();
        }

        if ($this->category) {
            $segments[] = $this->category->getPath();
        }

        return implode('/', $segments);
    }

    /**
     * @return string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getRouteKeyName()
    {
        return 'adverts_path';
    }

    /**
     * @param mixed $value
     * @param null  $field
     *
     * @return AdvertPath|null
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $chunks = explode('/', $value);

        /** @var Region|null $region */
        $region = null;

        do {
            $slug = reset($chunks);
            if ($slug && $next = Region::where('slug', $slug)->where('parent_id', $region ? $region->id : null)->first()) {
                $region = $next;
                array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        /** @var Category|null $category */
        $category = null;

        do {
            $slug = reset($chunks);
            if ($slug && $next = Category::where('slug', $slug)->where('parent_id', $category ? $category->id : null)->first()) {
                $category = $next;
                array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        if (!empty($chunks)) {
            abort(404);
        }

        return $this->withRegion($region)->withCategory($category);
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}