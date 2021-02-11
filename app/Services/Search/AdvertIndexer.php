<?php

declare(strict_types=1);

namespace App\Services\Search;

use App\Models\Advert;
use App\Models\Value;
use Elasticsearch\Client;

class AdvertIndexer
{
    /** @var Client */
    private $client;

    /**
     * AdvertIndexer constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Очистить индекс
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function clear()
    {
        $this->client->deleteByQuery([
            'index' => 'adverts',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass()
                ],
            ],
        ]);
    }

    /**
     * Наполняем индекс данными
     *
     * @param Advert $advert
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index(Advert $advert)
    {
        // -- Посчитаем регионы
        $regions = [];
        if ($region = $advert->region) {
            do {
                $regions[] = $region->id;
            } while ($region = $region->parent);
        }

        // -- Наполним индекс
        $this->client->index([
            'index' => 'adverts',
            'id'    => $advert->id,
            'body'  => [
                'id'           => $advert->id,
                'published_at' => $advert->published_at ? $advert->published_at->format(DATE_ATOM) : null,
                'title'        => $advert->title,
                'content'      => $advert->content,
                'price'        => $advert->price,
                'status'       => $advert->status,
                'categories'   => array_merge(
                    [$advert->category->id],
                    $advert->category->ancestors()->pluck('id')->toArray()
                ),
                'regions' => $regions,
                'values' => array_map(function (Value $value) {
                    return [
                        'attribute'    => $value->attribute_id,
                        'value_string' => (string)$value->value,
                        'value_int'    => (int)$value->value,
                    ];
                }, $advert->values()->getModels()),
            ],
        ]);
    }

    /**
     * Удаление объявления из индекса
     *
     * @param Advert $advert
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function remove(Advert $advert)
    {
        $this->client->indices()->delete([
            'index' => 'adverts',
            'id' => $advert->id,
        ]);
    }
}
