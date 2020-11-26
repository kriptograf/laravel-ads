<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Categories\CreateRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Список родительских категорий
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index()
    {
        $categories = Category::defaultOrder()->withDepth()->get();

        return view('admin.categories.index', ['categories' => $categories]);
    }

    /**
     * Показать форму создания новой категории
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create()
    {
        $categories = Category::defaultOrder()->withDepth()->get();

        return view('admin.categories.form', [
            'categories' => $categories,
        ]);
    }

    /**
     * Сохранить категорию
     *
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function store(CreateRequest $request)
    {
        Category::create([
            'name'      => $request['name'],
            'slug'      => $request['slug'],
            'parent_id' => $request['parent_id'],
        ]);

        return redirect()->route('admin.category.index')->with('success', __('Successful created category!'));
    }

    /**
     * Просмотр категории
     *
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Category $category)
    {
        $attributes = Attribute::where('category_id', $category->id)->get();

        return view('admin.categories.show', [
            'category'   => $category,
            'attributes' => $attributes,
        ]);
    }

    /**
     * Показать форму редактирования категории
     *
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(Category $category)
    {
        $categories = Category::defaultOrder()->withDepth()->get();

        return view('admin.categories.edit', [
            'category'   => $category,
            'categories' => $categories,
        ]);
    }

    /**
     * Обновить категорию
     *
     * @param UpdateRequest $request
     * @param Category      $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function update(UpdateRequest $request, Category $category)
    {
        $category->update([
            'name'      => $request['name'],
            'slug'      => $request['slug'],
            'parent_id' => $request['parent_id'],
        ]);

        return redirect()->route('admin.category.show', $category);
    }

    /**
     * Удалить категорию
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Категория успешно удалена!');
    }

    /**
     * Переместить на первую позицию
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function first(Category $category)
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertBeforeNode($first);
        }

        return redirect()->route('admin.category.index')->with('success', 'Категория успешно перемещена на первую позицию!');
    }

    /**
     * Переместить выше
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function up(Category $category)
    {
        $category->up();

        return redirect()->route('admin.category.index')->with('success', 'Категория успешно перемещена вверх!');
    }

    /**
     * Переместить ниже
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function down(Category $category)
    {
        $category->down();

        return redirect()->route('admin.category.index')->with('success', 'Категория успешно перемещена вниз!');
    }

    /**
     * Переместить на последнюю позицию
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function last(Category $category)
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }

        return redirect()->route('admin.category.index')->with('success', 'Категория успешно перемещена на последнюю позицию!');
    }
}
