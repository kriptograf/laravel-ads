<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Account\PhoneRequest;
use App\Services\Sms\SmsSenderInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Верификация телефона
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class PhoneController extends Controller
{
    /** @var SmsSenderInterface Смс сервис */
    private $sms;

    /**
     * PhoneController constructor.
     *
     * @param SmsSenderInterface $sms
     */
    public function __construct(SmsSenderInterface $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Отправить запрос с кодом подтверждения на телефон
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function request(Request $request)
    {
        $user = Auth::user();

        try{
            $token = $user->requestPhoneVerification(Carbon::now());

            $this->sms->send($user->phone, 'Verification token: ' . $token);

            $request->session()->flash('success', __('Send sms code'));
        }catch(\DomainException $e){
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('cabinet.account.phone');
    }

    /**
     * Покзать форму для ввода кода верификации
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function form()
    {
        $user = Auth::user();

        return view('cabinet.account.phone', ['user' => $user]);
    }

    /**
     * Подтверждение телефона полученным токеном
     *
     * @param PhoneRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function verify(PhoneRequest $request)
    {
        $user = Auth::user();

        try{
            $user->verifyPhone($request['token'], Carbon::now());
        }catch(\DomainException $e){
            return redirect()->route('cabinet.account.phone')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.account');
    }
}
