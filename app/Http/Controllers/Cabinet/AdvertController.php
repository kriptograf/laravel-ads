<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Cabinet\Profile\UpdateRequest;
use App\Models\Category;
use App\Models\Profile;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;

/**
 * Контроллер объявлений в личном кабинете
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class AdvertController extends Controller
{
    public function __construct()
    {
        // -- Проверяем, что все поля профиля заполнены
        $this->middleware(FilledProfile::class);
    }

    public function index()
    {
        return view('cabinet.adverts.index');
    }

    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.adverts.create.category', ['categories' => $categories]);
    }

    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.adverts.create.region', [
            'category' => $category,
            'regions' => $regions,
            'region' => $region,
        ]);
    }

    public function create(Category $category, Region $region)
    {
        return [$category, $region];
    }

    public function show()
    {
        return view('cabinet.adverts.show');
    }

    public function edit()
    {
        return view('cabinet.adverts.edit');
    }

    public function update()
    {
        return redirect()->route('cabinet.advert');
    }

    public function destroy()
    {
        return redirect()->route('cabinet.advert');
    }
}
