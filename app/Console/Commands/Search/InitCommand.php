<?php

namespace App\Console\Commands\Search;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;

/**
 * Первоначальное создание индекса
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inint elasticsearch index';

    /** @var Client  */
    private $client;

    /**
     * InitCommand constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->indexAdverts();

        $this->indexBanners();

        return true;
    }

    private function indexAdverts()
    {
        try {
            // -- Удалим старый индекс
            $this->client->indices()->delete([
                'index' => 'adverts',
            ]);
        } catch(Missing404Exception $e){}

        // -- Создадим новый индекс
        $this->client->indices()->create([
            'index' => 'adverts',
            'body' => [
                'mappings' => [
                    '_source' => [
                        'enabled' => true,
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                        ],
                        'published_at' => [
                            'type' => 'date',
                        ],
                        'title' => [
                            'type' => 'text',
                        ],
                        'content' => [
                            'type' => 'text',
                        ],
                        'price' => [
                            'type' => 'integer',
                        ],
                        'status' => [
                            'type' => 'keyword',
                        ],
                        'categories' => [
                            'type' => 'integer',
                        ],
                        'regions' => [
                            'type' => 'integer',
                        ],
                        'values' => [
                            'type' => 'nested',
                            'properties' => [
                                'attribute' => [
                                    'type' => 'integer',
                                ],
                                'value_string' => [
                                    'type' => 'keyword',
                                ],
                                'value_int' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                    ],
                ],
                'settings' => [
                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '&=> and '
                                ],
                            ],
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_number' => true,
                            ],
                            'trigrams' => [
                                'type' => 'ngram',
                                'min_gram' => 4,
                                'max_gram' => 5,
                            ],
                        ],
                        'analyzer' => [
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace',
                                ],
                                'tokenizer' => 'whitespace',
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter',
                                    'trigrams',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function indexBanners()
    {
        try {
            // -- Удалим старый индекс
            $this->client->indices()->delete([
                'index' => 'banners',
            ]);
        } catch(Missing404Exception $e){}

        // -- Создадим новый индекс
        $this->client->indices()->create([
            'index' => 'banners',
            'body' => [
                'mappings' => [
                    '_source' => [
                        'enabled' => true,
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                        ],
                        'status' => [
                            'type' => 'keyword',
                        ],
                        'format' => [
                            'type' => 'keyword',
                        ],
                        'categories' => [
                            'type' => 'integer',
                        ],
                        'regions' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
