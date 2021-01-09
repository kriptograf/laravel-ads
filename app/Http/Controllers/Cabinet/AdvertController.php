<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Cabinet\Advert\CreateRequest;
use App\Models\Advert;
use App\Models\Category;
use App\Models\Region;
use App\Services\Advert\AdvertService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Контроллер объявлений в личном кабинете
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class AdvertController extends Controller
{
    private $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
        // -- Проверяем, что все поля профиля заполнены
        $this->middleware(FilledProfile::class);
    }

    /**
     * Список всех объявлений пользователя
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index()
    {
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.adverts.index', ['adverts' => $adverts]);
    }

    /**
     * Создание объявления - Выбор категории
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.adverts.create.category', ['categories' => $categories]);
    }

    /**
     * Создание объявления - Выбор региона
     *
     * @param Category    $category Категория
     * @param Region|null $region   Регион
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.adverts.create.region', [
            'category' => $category,
            'regions' => $regions,
            'region' => $region,
        ]);
    }

    /**
     * Создание объявления - заполнение основной информации
     *
     * @param Category $category Категория
     * @param Region   $region   Регион
     *
     * @return array
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create(Category $category, Region $region)
    {
        return view('cabinet.adverts.create.advert', [
            'category' => $category,
            'region' => $region,
        ]);
    }

    /**
     * Сохраняем объявление
     *
     * @param \App\Http\Requests\Cabinet\Advert\CreateRequest $request
     * @param \App\Models\Category                            $category
     * @param \App\Models\Region                              $region
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function store(CreateRequest $request, Category $category, Region $region)
    {
        try{
            $advert = $this->service->create(
                Auth::id(),
                $category->id,
                $region->id,
                $request
            );
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.advert.show', $advert);
    }

    /**
     * Просмотр объявления в кабинете
     *
     * @param \App\Models\Advert $advert
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Advert $advert)
    {
        return view('cabinet.adverts.show', ['advert' => $advert]);
    }

    /**
     * Публикация объявления
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function publish(Advert $advert)
    {
        try{
            $advert->published_at = Carbon::now();
            $advert->status = Advert::STATUS_ACTIVE;
            $advert->saveOrFail();
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.advert.show', $advert)->with('success', __('Ads success published!'));
    }

    public function close(Advert $advert)
    {
        try{
            $advert->status = Advert::STATUS_CLOSED;
            $advert->saveOrFail();
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.advert.show', $advert)->with('success', __('Ads success unpublished!'));
    }

    public function edit()
    {
        return view('cabinet.adverts.edit');
    }

    public function update()
    {
        return redirect()->route('cabinet.advert');
    }
}
