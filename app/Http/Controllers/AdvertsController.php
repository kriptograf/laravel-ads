<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Category;
use App\Models\Region;
use App\Router\AdvertPath;
use Illuminate\Support\Facades\Gate;

/**
 * Контроллер объявлений для фронтенда
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class AdvertsController extends Controller
{
    /**
     * Список объявлений
     *
     * @param AdvertPath   $path
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index(AdvertPath $path)
    {
        $query = Advert::active()->with(['category', 'region'])->orderByDesc('published_at');

        if ($category = $path->category) {
            $query->forCategory($category);
        }

        if ($region = $path->region) {
            $query->forRegion($region);
        }

        $adverts = $query->paginate(20);

        $regions = $region ? $region->children()->orderBy('name')->getModels() : Region::roots()->orderBy('name')->getModels();

        $categories = $category ? $category->children()->defaultOrder()->getModels() : Category::whereIsRoot()->defaultOrder()->getModels();

        return view('adverts.index', [
            'category'   => $category,
            'region'     => $region,
            'regions'    => $regions,
            'categories' => $categories,
            'adverts'    => $adverts,
        ]);
    }

    /**
     * Просмотр объявления
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Advert $advert)
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }

        // @todo Добавить здесь выборку похожих объявлений, кроме текущего
        // @todo Выбирать случайные
        $query = Advert::with(['category', 'region'])->orderByDesc('id');
        $query->forCategory($advert->category);
        $query->forRegion($advert->region);

        $similarAdverts = $query->limit(10)->get();

        return view('adverts.show', ['advert' => $advert, 'similarAdverts' => $similarAdverts]);
    }

    /**
     * Получаем номер телефона при клике на кнопку "Показать телефон"
     * @todo доработать защиту от парсинга
     * @param Advert $advert
     *
     * @return string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function phone(Advert $advert)
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }

        return $advert->user->phone;
    }
}
