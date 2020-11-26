<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\CreateRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use App\Models\Admin\Permission;
use App\Models\User;
use App\Models\Admin\Role;
use Illuminate\Http\Request;

/**
 * Управление ролями
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class RoleController extends Controller
{
    /**
     * Список ролей
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->paginate(20);

        return view('admin.roles.index', ['roles' => $roles]);
    }

    /**
     * Показать форму создания роли
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create()
    {
        return view('admin.roles.form');
    }

    /**
     * Сохранить роль
     *
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function store(CreateRequest $request)
    {
        Role::create(['name' => $request['name']]);

        return redirect()->route('admin.role.index')->with('success', __('Successful created role!'));
    }

    /**
     * Просмотр роли
     *
     * @param Role $role
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(Role $role)
    {
        $permissions = Permission::all();

        return view('admin.roles.show', [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    /**
     * Показать форму редактирования роли
     *
     * @param Role $role
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', ['role' => $role]);
    }

    /**
     * Обновить роль
     *
     * @param UpdateRequest $request
     * @param Role          $role
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $role->update($request->only(['name']));

        return redirect()->route('admin.role.show', $role);
    }

    /**
     * Удалить роль
     *
     * @param Role $role
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(Role $role)
    {
        // -- Найти всех пользователей с такой ролью
        $users = User::role($role->name)->get();

        if (in_array($role->name, Role::guardRoles())) {
            return redirect()->route('admin.role.index')->with('error', 'Нельзя удалять роли по умолчанию!');
        }

        if (count($users)) {
            return redirect()->route('admin.role.index')->with('error', 'Есть пользователи с ролью ' . $role->name . '. Назначьте сначала этим пользователям другую роль!');
        }

        $role->delete();

        return redirect()->route('admin.role.index')->with('success', 'Роль успешно удалена!');
    }

    /**
     * Присвоить роли пользователю
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function assign(Request $request)
    {
        $user = User::find($request['user_id']);

        $user->syncRoles($request['role']);

        return redirect()->route('admin.users.show', $user)->with('success', 'Роли успешно присвоены!');
    }

    public function permission(Request $request, Role $role)
    {
        $role->syncPermissions($request['permission']);

        return redirect()->route('admin.role.show', $role)->with('success', 'Разрешения успешно присвоены!');
    }
}
