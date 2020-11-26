<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permissions\CreateRequest;
use App\Http\Requests\Admin\Permissions\UpdateRequest;
use App\Models\Admin\Permission;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Управление разрешениями
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class PermissionController extends Controller
{
    /**
     * Список разрешений
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index()
    {
        $permissions = Permission::orderBy('id', 'desc')->paginate(20);

        return view('admin.permissions.index', ['permissions' => $permissions]);
    }

    /**
     * Показать форму создания разрешения
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create()
    {
        return view('admin.permissions.form');
    }

    /**
     * Сохранить разрешение
     *
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function store(CreateRequest $request)
    {
        Permission::create(['name' => $request['name']]);

        return redirect()->route('admin.permission.index')->with('success', __('Successful created permission!'));
    }

    /**
     * Просмотр разрешения
     *
     * @param Permission $permission
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Permission $permission)
    {
        return view('admin.permissions.show', ['permission' => $permission]);
    }

    /**
     * Показать форму редактирования разрешения
     *
     * @param Permission $permission
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', ['permission' => $permission]);
    }

    /**
     * Обновить разрешение
     *
     * @param UpdateRequest $request
     * @param Permission    $permission
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function update(UpdateRequest $request, Permission $permission)
    {
        $permission->update($request->only(['name']));

        return redirect()->route('admin.permission.show', $permission);
    }

    /**
     * Удалить разрешение
     *
     * @param Permission $permission
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Permission $permission)
    {
        // -- Найти всех пользователей с таким разрешением
        $users = User::permission($permission->name)->get();

        if (count($users)) {
            return redirect()->route('admin.permission.index')->with('error', 'Есть пользователи с разрешением ' . $permission->name . '. Назначьте сначала этим пользователям другое разрешение!');
        }

        $permission->delete();

        return redirect()->route('admin.permission.index')->with('success', 'Разрешение успешно удалено!');
    }

    /**
     * Присвоить разрешения пользователю
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function assign(Request $request)
    {
        $user = User::find($request['user_id']);

        $user->$user->syncPermissions($request['permissions']);

        return redirect()->route('admin.users.show', $user)->with('success', 'Разрешения успешно присвоены !');
    }
}
