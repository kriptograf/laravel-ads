<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Attributes\CreateRequest;
use App\Http\Requests\Admin\Attributes\UpdateRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    /**
     * Детальный просмотр атрибута
     *
     * @param Category  $category
     * @param Attribute $attribute
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Category $category, Attribute $attribute)
    {
        return view('admin.attributes.show', [
            'category'  => $category,
            'attribute' => $attribute,
        ]);
    }

    /**
     * Показать форму создания нового атрибута
     *
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create(Category $category)
    {
        $types = Attribute::listTypes();

        return view('admin.attributes.form', [
            'category' => $category,
            'types'    => $types,
        ]);
    }

    /**
     * Сохранить атрибут
     *
     * @param CreateRequest $request
     * @param Category      $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function store(CreateRequest $request, Category $category)
    {
        $category->attributes()->create([
            'name'      => $request['name'],
            'type'      => $request['type'],
            'required'  => (bool)$request['required'],
            'variants'  => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort'      => $request['sort'],
        ]);

        return redirect()->route('admin.category.show', $category)->with('success', __('Successful created attribute!'));
    }

    /**
     * Показать форму редактирования атрибута
     *
     * @param Category  $category
     * @param Attribute $attribute
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(Category $category, Attribute $attribute)
    {
        $types = Attribute::listTypes();

        return view('admin.attributes.edit', [
            'category'  => $category,
            'attribute' => $attribute,
            'types'     => $types,
        ]);
    }

    /**
     * Обновить атрибут
     *
     * @param UpdateRequest $request
     * @param Category      $category
     * @param Attribute     $attribute
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function update(UpdateRequest $request, Category $category, Attribute $attribute)
    {
        $category->attributes()->findOrFail($attribute->id)->update([
            'name'     => $request['name'],
            'type'     => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort'     => $request['sort'],
        ]);

        return redirect()->route('admin.category.show', $category);
    }

    /**
     * Удалить атрибут
     *
     * @param Category  $category
     * @param Attribute $attribute
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Category $category, Attribute $attribute)
    {
        $attribute->delete();

        return redirect()->route('admin.category.show', $category)->with('success', 'Атрибут успешно удален!');
    }

}
