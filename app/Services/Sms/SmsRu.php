<?php

namespace App\Services\Sms;

use GuzzleHttp\Client;

/**
 * Отправка смс через сервис sms.ru
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class SmsRu implements SmsSenderInterface
{
    /** @var string ключ для внешних программ */
    private $appId;
    /** @var string адрес по которому будет отправляться запрос */
    private $url;
    /** @var Client Http client */
    private $client;

    public function __construct($appId, $url = 'https://sms.ru/sms/send')
    {
        if (empty($appId)) {
            throw new \InvalidArgumentException('Sms appId must be set.');
        }

        $this->appId = $appId;
        $this->url = $url;
        $this->client = new Client();
    }

    public function send($number, $text)
    {
        $this->client->post($this->url, [
            'form_params' => [
                'api_id' => $this->appId,
                'to' => '+' . trim($number, '+'),
                'text' => $text,
            ],
        ]);
    }
}