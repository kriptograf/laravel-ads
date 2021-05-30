<?php

declare(strict_types=1);

namespace App\Services\Advert;

use App\Http\Requests\Adverts\SearchRequest;
use App\Models\Advert;
use App\Models\Category;
use App\Models\Region;
use Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис поиска объявлений в elasticsearch
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class SearchService
{
    /** @var Client  */
    private $client;

    /**
     * SearchService constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Поиск объявлений в elastic
     *
     * @param Category|null  $category
     * @param Region|null    $region
     * @param SearchRequest  $request
     * @param int            $perPage
     * @param int            $page
     *
     * @return SearchResult
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function search(?Category $category, ?Region $region, SearchRequest $request, int $perPage, int $page): SearchResult
    {
        $values = array_filter((array)$request->input('attrs'), function ($value) {
            return !empty($value['equals']) || !empty($value['from']) || !empty($value['to']);
        });

        $response = $this->client->search([
            'index' => 'adverts',
            'body' => [
                '_source' => ['id'],
                'from' => ($page - 1) * $perPage,
                'size' => $perPage,
                'sort' => empty($request['query']) ? [['published_at' => ['order' => 'desc']]] : [],
                'aggs' => [
                    'group_by_region' => [
                        'terms' => [
                            'field' => 'regions',
                        ],
                    ],
                    'group_by_category' => [
                        'terms' => [
                            'field' => 'categories',
                        ]
                    ],
                ],
                'query' => [
                    'bool' => [
                        'must' => array_merge(
                            [['term' => ['status' => Advert::STATUS_ACTIVE]]],
                            array_filter([
                                $category ? ['term' => ['categories' => $category->id]] : false,
                                $region ? ['term' => ['regions' => $region->id]] : false,
                                !empty($request['query']) ? ['multi_match' => [
                                    'query' => $request['query'],
                                    'fields' => ['title^3', 'content'],
                                ]] : false,
                            ]),
                            array_map(function ($value, $id) {
                                return [
                                    'nested' => [
                                        'path' => 'values',
                                        'query' => [
                                            'bool' => [
                                                'must' => array_values(array_filter([
                                                    ['match' => ['values.attribute' => $id]],
                                                    !empty($value['equals']) ? ['match' => ['values.value_string' => $value['equals']]] : false,
                                                    !empty($value['from']) ? ['range' => ['values.value_int' => ['gte' => $value['from']]]] : false,
                                                    !empty($value['to']) ? ['range' => ['values.value_int' => ['lte' => $value['to']]]] : false,
                                                ]))
                                            ],
                                        ],
                                    ],
                                ];
                            }, $values, array_keys($values))
                        ),
                    ],
                ],
            ],
        ]);

        // -- Идентификаторы найденных документов
        $ids = array_column($response['hits']['hits'], '_id');

        // -- Если нет идентификаторов, вернем пустой пагинатор
        if (!$ids) {
            $pagination =  new LengthAwarePaginator([], 0, $perPage, $page);
        } else {
            // -- Сортируем записи в том порядке в которм нам вернули их elasticsearch
            $items = Advert::active()
                ->with(['category', 'region'])
                ->whereIn('id', $ids)
                ->orderByRaw('array_position(ARRAY['.implode(',', $ids).']::int[], id::int)')
                ->get()
            ;

            $pagination = new LengthAwarePaginator($items, $response['hits']['total']['value'], $perPage, $page);
        }



        return new SearchResult(
            $pagination,
            array_column($response['aggregations']['group_by_region']['buckets'], 'doc_count', 'key'),
            array_column($response['aggregations']['group_by_category']['buckets'], 'doc_count', 'key')
        );
    }
}
