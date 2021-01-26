<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Regions\CreateRequest;
use App\Http\Requests\Admin\Regions\UpdateRequest;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Список родительских регионов
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index()
    {
        $regions = Region::where('parent_id', null)->paginate(20);

        return view('admin.regions.index', ['regions' => $regions]);
    }

    /**
     * Показать форму создания региона
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create(Request $request)
    {
        $parent = null;

        if ($request->get('parent')) {
            $parent = Region::findOrFail($request->get('parent'));
        }

        //$parents = Region::where('parent_id', null)->get();

        return view('admin.regions.form', [
            'parent' => $parent,
        ]);
    }

    /**
     * Сохранить регион
     *
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function store(CreateRequest $request)
    {
        $region = Region::create([
            'name'      => $request['name'],
            'slug'      => $request['slug'],
            'parent_id' => $request['parent_id'],
        ]);

        //return redirect()->route('admin.region.index')->with('success', __('Successful created region!'));

        return redirect()->route('admin.region.show', $region)->with('success', __('Successful created region!'));
    }

    /**
     * Просмотр региона
     *
     * @param Region $region
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Region $region)
    {
        $childrens = $region->children()->orderBy('name')->paginate(20);

        return view('admin.regions.show', [
            'region' => $region,
            'childrens' => $childrens,
        ]);
    }

    /**
     * Показать форму редактирования региона
     *
     * @param Region $region
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(Region $region)
    {
        $parent = $region->parent;

        return view('admin.regions.edit', [
            'region' => $region,
            'parent' => $parent,
        ]);
    }

    /**
     * Обновить регион
     *
     * @param UpdateRequest $request
     * @param Region        $region
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function update(UpdateRequest $request, Region $region)
    {
        $region->update([
            'name' => $request['name'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent_id'],
        ]);

        return redirect()->route('admin.region.show', $region);
    }

    /**
     * Удалить регион
     *
     * @param Region $region
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()->route('admin.region.index')->with('success', 'Регион успешно удален!');
    }
}
