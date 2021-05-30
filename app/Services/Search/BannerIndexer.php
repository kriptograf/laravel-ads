<?php

declare(strict_types=1);

namespace App\Services\Search;

use App\Models\Banner;
use App\Models\Region;
use Elasticsearch\Client;

class BannerIndexer
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
            'index' => 'banners',
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
     * @param Banner $banner
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function index(Banner $banner)
    {
        // -- Посчитаем регионы и подрегионы по убывающей
        $regions = [];
        if ($banner->region) {
            $regions = [$banner->region->id];
            $childrens = $regions;
            while ($childrens = Region::whereIn('parent_id', $childrens)->pluck('id')->toArray()) {
                $regions = array_merge($regions, $childrens);
            }
        }

        // -- Наполним индекс
        $this->client->index([
            'index' => 'banners',
            'id'    => $banner->id,
            'body'  => [
                'id'           => $banner->id,
                'status'       => $banner->status,
                'format'       => $banner->format,
                'categories'   => array_merge(
                    [$banner->category->id],
                    $banner->category->descendants()->pluck('id')->toArray()
                ),
                'regions' => $regions,
            ],
        ]);
    }

    /**
     * Удаление баннера из индекса
     *
     * @param Banner $banner
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function remove(Banner $banner)
    {
        $this->client->indices()->delete([
            'index' => 'banners',
            'id' => $banner->id,
        ]);
    }
}
