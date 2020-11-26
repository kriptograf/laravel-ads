<?php

namespace App\Services\Sms;

/**
 * Заглушка отправки смс для использования в тестах
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class DummySms implements SmsSenderInterface
{
    /** @var array Массив сообщений */
    private $messages;

    /**
     * @param $number
     * @param $text
     *
     * @return mixed|void
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function send($number, $text)
    {
        $this->messages[] = [
            'to' => '+' . trim($number, '+'),
            'text' => $text,
        ];
    }

    /**
     * Возвращает массив сообщений
     *
     * @return mixed
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getMessages() {
        return $this->messages;
    }
}