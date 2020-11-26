<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Account\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class AccountController
 *
 * @package App\Http\Controllers\Cabinet
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class AccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index()
    {
        $user = Auth::user();

        return view('cabinet.account.index', [
            'user' => $user,
        ]);
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(User $user)
    {
        return view('cabinet.account.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $oldPhone = $user->phone;

        $user->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
        ]);

        // -- Если телефон изменился, сбросить флаг верификации
        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }

        return redirect()->route('cabinet.account');
    }
}
