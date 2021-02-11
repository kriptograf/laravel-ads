<?php

namespace App\Providers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Провайдер поиска Elasticsearch
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class SearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function (Application $app) {
            $config = $app->make('config')->get('elasticsearch');

            return ClientBuilder::create()
                ->setHosts($config['hosts'])
                ->setRetries($config['retries'])
                ->build();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
