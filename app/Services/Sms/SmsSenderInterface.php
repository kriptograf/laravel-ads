<?php

namespace App\Services\Sms;

/**
 * Interface SmsSenderInterface
 *
 * @package App\Services\Sms
 * @author Виталий Москвин <foreach@mail.ru>
 */
interface SmsSenderInterface
{
    /**
     * @param $number
     * @param $text
     *
     * @return mixed
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function send($number, $text);
}