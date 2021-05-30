<?php

namespace App\Console\Commands\Search;

use App\Models\Advert;
use App\Models\Banner;
use App\Services\Search\AdvertIndexer;
use App\Services\Search\BannerIndexer;
use Illuminate\Console\Command;

/**
 * Команда переиндексации объявлений
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex elasticsearch index';

    /** @var AdvertIndexer */
    private $advertIndexer;

    /** @var BannerIndexer  */
    private $bannerIndexer;

    /**
     * ReindexCommand constructor.
     *
     * @param AdvertIndexer $advertIndexer
     * @param BannerIndexer $bannerIndexer
     */
    public function __construct(AdvertIndexer $advertIndexer, BannerIndexer $bannerIndexer)
    {
        parent::__construct();

        $this->advertIndexer = $advertIndexer;
        $this->bannerIndexer = $bannerIndexer;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->advertIndexer->clear();

        // -- Наполним индекс объявлений данными
        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->advertIndexer->index($advert);
        }

        $this->bannerIndexer->clear();
        // -- Наполним индекс банноров данными
        foreach (Banner::active()->orderBy('id')->cursor() as $banner) {
            $this->bannerIndexer->index($banner);
        }

        return true;
    }
}
