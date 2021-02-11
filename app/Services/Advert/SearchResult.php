<?php

declare(strict_types=1);

namespace App\Services\Advert;

use Illuminate\Contracts\Pagination\Paginator;

/**
 * Агрегируем результаты поиска в объект
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class SearchResult
{
    /** @var Paginator  */
    public $adverts;
    /** @var array  */
    public $regionsCounts;
    /** @var array  */
    public $categoriesCounts;

    /**
     * SearchResult constructor.
     *
     * @param Paginator $adverts
     * @param array     $regionsCounts
     * @param array     $categoriesCounts
     */
    public function __construct(Paginator $adverts, array $regionsCounts, array $categoriesCounts)
    {
        $this->adverts = $adverts;
        $this->regionsCounts = $regionsCounts;
        $this->categoriesCounts = $categoriesCounts;
    }
}