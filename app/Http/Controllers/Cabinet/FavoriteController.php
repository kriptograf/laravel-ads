<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Services\User\FavoriteService;
use Illuminate\Support\Facades\Auth;

/**
 * Избранное в личном кабинете пользователя
 *
 * @author Виталий Москвин <foreach@mail.ru>
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

    public function index()
    {
        $adverts = $this->service->getUserFavorites(Auth::id());

        return view('cabinet.favorites.index', ['adverts' => $adverts]);
    }

    /**
     * Удалить из избранного
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

        return redirect()->route('cabinet.favorites');
    }
}
