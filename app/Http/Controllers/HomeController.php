<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Region;
use Illuminate\Http\Request;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $regions = Region::roots()->orderBy('name')->getModels();
        $categories = Category::whereIsRoot()->defaultOrder()->getModels();

        return view('home', [
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }
}
