<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User;
use App\Models\Admin\Role;
use Illuminate\Http\Request;

/**
 * Контроллер управления пользователями
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class UsersController extends Controller
{
    /**
     * Вывести список всех пользователей
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index(Request $request)
    {
        $roles = Role::all();

        // -- Поиск пользователей
        $users = User::search($request);

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Вывести форму создания нового пользователя
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.form');
    }

    /**
     * Сохранить нового пользователя в бд
     *
     * @param  CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $user = User::create($request->only(['name', 'email']) + [
            'password' => bcrypt($request['password']),
        ]);

        // -- Присвоим роль новому пользователю
        $user->assignRole(Role::ROLE_USER);

        return redirect()->route('admin.users.show', $user);
    }

    /**
     * Вывести карточка пользователя
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function show(User $user)
    {
        $roles = Role::all();

        return view('admin.users.show', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Вывести форму редактирования пользователя
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Сохранить изменения после редактирования пользователя
     *
     * @param UpdateRequest $request
     * @param User    $user
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email']));

        return redirect()->route('admin.users.show', $user)->with('success', 'User success updated!');
    }

    /**
     * Удалить пользователя
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User success deleted!');
    }

    /**
     * Подтверждение/верификация пользователя вручную
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function verify(User $user)
    {
        if ($user->setVerified()) {
            return redirect()->route('admin.users.index')->with('success', 'Verification success!');
        }

        return redirect()->route('admin.users.index')->with('error', 'Verification fail!');
    }
}
