<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Cabinet\Advert\AttributesRequest;
use App\Http\Requests\Cabinet\Advert\PhotoRequest;
use App\Models\Advert;
use App\Services\Advert\AdvertService;
use Illuminate\Support\Facades\Gate;

class ManageAdvertsController extends Controller
{
    private $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
        // -- Проверяем, что все поля профиля заполнены
        $this->middleware(FilledProfile::class);
    }

    /**
     * Форма редактирования атрибутов
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function attributes(Advert $advert)
    {
        $this->checkAccess($advert);
        return view('cabinet.adverts.edit.attributes', ['advert' => $advert]);
    }

    /**
     * Сохранение отредактированных атрибутов
     *
     * @param AttributesRequest $request
     * @param Advert            $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function updateAttributes(AttributesRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try{
            $this->service->editAttributes($advert->id, $request);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    /**
     * Форма фотографий
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function photos(Advert $advert)
    {
        $this->checkAccess($advert);

        return view('cabinet.adverts.edit.photos', ['advert' => $advert]);
    }

    /**
     * Загрузка фотографий
     *
     * @param PhotoRequest $request
     * @param Advert       $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function updatePhotos(PhotoRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try{
            $this->service->addPhotos($advert->id, $request);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.advert.show', $advert);
    }

    /**
     * Удаление объявления
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Advert $advert)
    {
        $this->checkAccess($advert);
        try{
            $this->service->remove($advert->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.index');
    }

    /**
     * Проверка, что пользователь может редактировать свое объявление
     *
     * @param Advert $advert
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function checkAccess(Advert $advert)
    {
        if (!Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }
}
