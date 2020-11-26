<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Auth\RegisterConfirmationMail;
use App\Models\Profile;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Регистрация пользователя
 * Этот контроллер обрабатывает регистрацию новых пользователей,
 * а также их валидацию и создание. По умолчанию этот контроллер использует трейт
 * для обеспечения этой функциональности, не требуя дополнительного кода.
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /** @var string Куда перенаправлять пользователей после регистрации. */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Получить валидатор для входящего запроса на регистрацию.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Внутренний метод
     * Создать новый экземпляр пользователя
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
        ]);

        // -- Присвоим роль новому пользователю
        $user->assignRole('user');

        return $user;
    }

    /**
     * Переопределенный метод из трейта. Убрали авторизацию пользователя после регистрации
     *
     * @param Request $request
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Переопределяем метод трейта, для редиректа на страницу c
     * сообщением о необходимости подтверждения
     *
     * @param Request $request
     * @param User    $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    protected function registered(Request $request, User $user)
    {
        // -- Присвоим роль новому пользователю
        $user->assignRole('user');

        // -- Создадим профиль новому пользователю
        Profile::create([
            'user_id' => $user->id,
            'first_name' => '',
            'last_name' => '',
            'location' => '',
            'photo' => '',
        ]);

        return redirect()->route('home')
            ->with('success', 'На ваш ваш email отправлено письмо с инструкциями по завершению регистрации. Для завершения процесса, следуйте инструкциям в письме.');
    }
}
