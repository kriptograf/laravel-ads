@component('mail::message')
    # Подтверждение регистрации

    Для подтверждения регистрации, перейдите по ссылке

@component('mail::button', ['url' => route('register.confirm', ['token' => $user->verify_code])])
    Подтвердить
@endcomponent

    С уважением, {{config('app.name')}}
@endcomponent