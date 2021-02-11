<?php

namespace App\Console\Commands\Search;

use App\Models\Advert;
use App\Services\Search\AdvertIndexer;
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
    private $indexer;

    /**
     * Create a new command instance.
     *
     * @param AdvertIndexer $indexer
     *
     * @return void
     */
    public function __construct(AdvertIndexer $indexer)
    {
        parent::__construct();

        $this->indexer = $indexer;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->indexer->clear();

        // -- Наполним индекс данными
        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->indexer->index($advert);
        }

        return true;
    }
}
