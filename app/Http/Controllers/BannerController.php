<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\Banner\BannerService;
use Illuminate\Http\Request;

/**
 * Class BannerController
 *
 *
 * @package App\Http\Controllers
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class BannerController extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\View|string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function get(Request $request)
    {
        $format = $request['format'];
        $categoryId = $request['category'];
        $regionId = $request['region'];

        if (!$banner = $this->service->getRandomForView($categoryId, $regionId, $format)) {
            return '';
        }

        return view('layouts.partials.banner', ['banner' => $banner]);
    }

    /**
     * Обрабатываем клик на баннере
     *
     * @param Banner $banner
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function click(Banner $banner)
    {
        $this->service->click($banner);

        return redirect($banner->url);
    }
}
