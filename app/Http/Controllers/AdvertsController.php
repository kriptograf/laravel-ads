<?php

namespace App\Http\Controllers;

use App\Http\Requests\Adverts\SearchRequest;
use App\Models\Advert;
use App\Models\Category;
use App\Models\Region;
use App\Router\AdvertPath;
use App\Services\Advert\SearchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Контроллер объявлений для фронтенда
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class AdvertsController extends Controller
{
    private $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    /**
     * Список объявлений
     *
     * @param SearchRequest $request
     * @param AdvertPath    $path
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index(SearchRequest $request, AdvertPath $path)
    {
        $region = $path->region;
        $category = $path->category;

        $regions = $region ? $region->children()->orderBy('name')->getModels() : Region::roots()->orderBy('name')->getModels();

        $categories = $category ? $category->children()->defaultOrder()->getModels() : Category::whereIsRoot()->defaultOrder()->getModels();

        // -- Получаем объявления
        $result = $this->search->search($category, $region, $request, 20, $request->get('page', 1));

        $regionsCounts = $result->regionsCounts;
        $categoriesCounts = $result->categoriesCounts;

        // -- Не выводим регионы и категории у которых нет объявлений
        $regions = array_filter($regions, function (Region $region) use ($regionsCounts) {
            return isset($regionsCounts[$region->id]) && $regionsCounts[$region->id] > 0;
        });

        $categories = array_filter($categories, function (Category $category) use ($categoriesCounts) {
            return isset($categoriesCounts[$category->id]) && $categoriesCounts[$category->id] > 0;
        });
        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

        return view('adverts.index', [
            'category'         => $category,
            'region'           => $region,
            'regions'          => $regions,
            'categories'       => $categories,
            'adverts'          => $result->adverts,
            'regionsCounts'    => $regionsCounts,
            'categoriesCounts' => $categoriesCounts,
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

        $user = Auth::user();

        return view('adverts.show', [
            'advert' => $advert,
            'similarAdverts' => $similarAdverts,
            'user' => $user,
        ]);
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
