<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Advert\AttributesRequest;
use App\Http\Requests\Cabinet\Advert\PhotoRequest;
use App\Models\Advert;
use App\Services\Advert\AdvertService;
use Illuminate\Support\Carbon;

class ManageAdvertsController extends Controller
{
    private $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
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
        try{
            $this->service->addPhotos($advert->id, $request);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
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

        return redirect()->route('advert.show', $advert)->with('success', __('Ads success published!'));
    }

    /**
     * Закрыть объявление
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function close(Advert $advert)
    {
        try{
            $advert->status = Advert::STATUS_CLOSED;
            $advert->saveOrFail();
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('advert.show', $advert)->with('success', __('Ads success unpublished!'));
    }

    public function edit()
    {
        return view('cabinet.adverts.edit');
    }

    public function update()
    {
        return redirect()->route('cabinet.advert');
    }

    public function reject()
    {
        return redirect()->route('cabinet.advert');
    }

    /**
     * Удаление фотографий
     * 
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Advert $advert)
    {
        try{
            $this->service->remove($advert->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return back();
    }
}
