<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Banner\CreateRequest;
use App\Http\Requests\Cabinet\Banner\EditRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Region;
use App\Services\Banner\BannerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Контроллер управления баннерами в кабинете пользователя
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class BannerController extends Controller
{
    /** @var BannerService  */
    private $service;

    /**
     * BannerController constructor.
     *
     * @param BannerService $service
     */
    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /**
     * Список баннеров в кабинете пользователя
     * @return \Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index()
    {
        $banners = Banner::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.banners.index', ['banners' => $banners]);
    }

    /**
     * Просмотр баннера в кабинете пользователя
     *
     * @param Banner $banner
     *
     * @return \Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Banner $banner)
    {
        $this->checkAccess($banner);

        return view('cabinet.banners.show', ['banner' => $banner]);
    }

    /**
     * Форма редактирования баннера
     *
     * @param Banner $banner
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function update(Banner $banner)
    {
        $formats = Banner::formatsList();

        $this->checkAccess($banner);

        if (!$banner->canBeChanged()) {
            return redirect()
                ->route('cabinet.banners.show', $banner)
                ->with('error', __('Unable to edit banner'));
        }

        return view('cabinet.banners.edit', [
            'banner' => $banner,
            'formats' => $formats
        ]);
    }

    /**
     * Сохранение отредактированной информации о баннере
     *
     * @param EditRequest $request
     * @param Banner      $banner
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(EditRequest $request, Banner $banner)
    {
        $this->checkAccess($banner);

        try{
            $this->service->editByOwner($banner->id, $request);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * Отправить баннер на модерацию
     *
     * @param Banner $banner
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function send(Banner $banner)
    {
        $this->checkAccess($banner);

        try{
            $this->service->sendToModeration($banner->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * Отменить модерацию баннера
     *
     * @param Banner $banner
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function cancel(Banner $banner)
    {
        $this->checkAccess($banner);

        try{
            $this->service->cancelModeration($banner->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * Удалить баннер в кабинете пользователя
     *
     * @param Banner $banner
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Banner $banner)
    {
        $this->checkAccess($banner);

        try{
            $banner->delete();
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners');
    }

    /**
     * Счет на оплату баннера
     *
     * @param Banner $banner
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function order(Banner $banner)
    {
        $this->checkAccess($banner);

        try{
            $banner = $this->service->order($banner->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * Выбор категории при создании баннера
     *
     * @return \Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.banners.create.category', ['categories' => $categories]);
    }

    /**
     * Выбор региона при создании баннера
     *
     * @param Category    $category
     * @param Region|null $region
     *
     * @return \Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.banners.create.region', [
            'category' => $category,
            'region' => $region,
            'regions' => $regions,
        ]);
    }

    /**
     * Форма создания баннера
     *
     * @param Category    $category
     * @param Region|null $region
     *
     * @return \Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function banner(Category $category, Region $region = null)
    {
        $formats = Banner::formatsList();

        return view('cabinet.banners.create.banner', [
            'category' => $category,
            'region' => $region,
            'formats' => $formats,
        ]);
    }

    /**
     * Сохранение информации баннера при создании
     *
     * @param CreateRequest $request
     * @param Category      $category
     * @param Region|null   $region
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function store(CreateRequest $request, Category $category, Region $region = null)
    {
        try{
            $banner = $this->service->create(
                Auth::user(),
                $category,
                $region,
                $request
            );
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * Проверка доступа для управления баннерами
     *
     * @param Banner $banner
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function checkAccess(Banner $banner)
    {
        if (!Gate::allows('manage-own-banner', $banner)) {
            abort(403);
        }
    }
}
