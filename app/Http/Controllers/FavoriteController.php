<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Services\User\FavoriteService;
use Illuminate\Support\Facades\Auth;

/**
 * Фронт контроллер избранного. Добавление/ удаление
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class FavoriteController extends Controller
{
    /** @var FavoriteService  */
    private $service;

    /**
     * FavoriteController constructor.
     *
     * @param FavoriteService $service
     */
    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    /**
     * Добавляем в избранное
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function add(Advert $advert)
    {
        try{
            $this->service->add(Auth::id(), $advert->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert)->with('success', __('Advert is added to you favorites'));
    }

    /**
     * Удаляем из избранного
     *
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function remove(Advert $advert)
    {
        try{
            $this->service->remove(Auth::id(), $advert->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }
}
