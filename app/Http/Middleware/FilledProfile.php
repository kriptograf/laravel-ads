<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Посредник проверяющий доступ к странце своих объявлений
 * Проверяется заполнение полей профиля
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class FilledProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (false === $user->hasFilledProfile()) {
            return redirect()->route('cabinet.profile')->with('error', __('Please fill out the profile and verify your phone.'));
        }

        return $next($request);
    }
}
