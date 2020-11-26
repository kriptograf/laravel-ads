<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Генерируется командой
 * docker-compose exec php php artisan make:mail -m -- Auth\\RegisterConfirmationMail
 * Служит для формирования шаблона письма
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class RegisterConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * RegisterConfirmationMail constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Подтверждение регистрации')
            ->markdown('mail.auth.register.confirmation');
    }
}
