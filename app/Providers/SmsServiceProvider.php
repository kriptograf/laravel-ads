<?php

namespace App\Providers;

use App\Services\Sms\DummySms;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSenderInterface;
use Illuminate\Foundation\Application;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // -- Регистрируем сервис для использования в приложении
        $this->app->singleton(SmsSenderInterface::class, function (Application $app) {
            $config = $app->make('config')->get('sms');

            switch ($config['driver']) {
                case 'sms.ru':
                    $params = $config['drivers']['sms.ru'];
                    if (!empty($params['url'])) {
                        return new SmsRu($params['app_id'], $params['url']);
                    }

                    return new SmsRu($params['app_id']);
                case 'array':
                    return new DummySms();
                default:
                    throw new \InvalidArgumentException('Undefined sms driver ' . $config['driver']);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
