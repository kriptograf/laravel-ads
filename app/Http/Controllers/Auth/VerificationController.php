<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Куда редиректить после подтверждения аккаунта.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Разрешаем неавторизованному пользователю, подтвердить свой аккаунт
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('show', 'resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Переопределим стандартное поведение из трейта
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws AuthorizationException
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function verify(Request $request)
    {
        if (!$this->guard()->onceUsingId($request->route('id'))) {
            throw new AuthorizationException;
        }

        // -- Получить пользователя
        $user = $this->guard()->user();

        // -- Проверить хеш из письма
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        // -- Если пользователь уже подтвержден, вернуть его на главную страницу
        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        // -- Пометить кользователя как верифицированного
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // -- Авторизуем пользователя
        $this->guard()->login($user);

        return redirect('/')->with('success', 'Ваш аккаунт успешно подтвержден.');
    }

    /**
     * Переопределяем стандартное поведение трейта и разлогинивам пользователя после отправки письма
     *
     * @param Request $request
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        Auth::logout();

        return $request->wantsJson()
            ? new JsonResponse([], 202)
            : redirect('/')->with('success', 'Письмо с инструкцией для подтверждения было отправлено повторно.');
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     * @author Виталий Москвин <foreach@mail.ru>
     */
	protected function guard()
	{
		return Auth::guard();
	}
}
