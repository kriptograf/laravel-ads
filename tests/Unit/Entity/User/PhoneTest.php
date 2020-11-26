<?php

namespace Tests\Unit\Entity\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Class PhoneTest
 *
 * @package Tests\Unit\Entity\User
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class PhoneTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Тест не  верифицированного телефона
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function testDefault()
    {
        /** @var User $user */
        $user = User::factory()->make([
            'phone_verified' => false,
        ]);

        $this->assertFalse($user->isPhoneVerified());
    }

    /**
     * Тест на пустой телефон
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function testRequestEmptyPhone()
    {
        /** @var User $user */
        $user = User::factory()->make([
            'phone' => null,
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);

        $this->expectExceptionMessage('Phone number is empty.');

        $user->requestPhoneVerification(Carbon::now());
    }

    /**
     * Тест запроса на верификацию
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function testRequest()
    {
        /** @var User $user */
        $user = User::factory()->make([
            'phone' => '79999990000',
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);

        $token = $user->requestPhoneVerification(Carbon::now());

        $this->assertFalse($user->isPhoneVerified());

        $this->assertNotEmpty($token);
    }

    /**
     * Запрос на верификацию уже отправлен
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function testRequestAlreadySent()
    {
        /** @var User $user */
        $user = User::factory()->make([
            'phone' => '79990001111',
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);

        $user->requestPhoneVerification($now = Carbon::now());

        $this->expectExceptionMessage('Token is already requested.');

        $user->requestPhoneVerification($now->copy()->addSeconds(15));
    }

    /**
     * Корректный токен
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function testVerify()
    {
        /** @var User $user */
        $user = User::factory()->make([
            'phone' => '79990001111',
            'phone_verified' => false,
            'phone_verify_token' => $token = 'token',
            'phone_verify_token_expire' => $now = Carbon::now(),
        ]);

        $this->assertFalse($user->isPhoneVerified());

        $user->verifyPhone($token, $now->copy()->subSeconds(15));

        $this->assertTrue($user->isPhoneVerified());
    }

    /**
     * Не корректный токен
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function testVerifyIncorrectToken()
    {
        /** @var User $user */
        $user = User::factory()->make([
            'phone' => '79990001111',
            'phone_verified' => false,
            'phone_verify_token' => 'token',
            'phone_verify_token_expire' => $now = Carbon::now(),
        ]);

        $this->expectExceptionMessage('Incorrect verify token.');

        $user->verifyPhone('some_token', $now->copy()->subSeconds(15));
    }

    /**
     * Время жизни токена истекло
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function testVerifyExpiredToken()
    {
        /** @var User $user */
        $user = User::factory()->make([
            'phone' => '79990001111',
            'phone_verified' => false,
            'phone_verify_token' => $token = 'token',
            'phone_verify_token_expire' => $now = Carbon::now(),
        ]);

        $this->expectExceptionMessage('Token is expired.');

        $user->verifyPhone($token, $now->copy()->addSeconds(500));

        $this->assertTrue($user->isPhoneVerified());
    }
}
