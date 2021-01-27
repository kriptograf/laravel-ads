<?php

namespace App\Console\Commands\Advert;

use App\Models\Advert;
use App\Services\Advert\AdvertService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advert:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close expired advert';

    private $service;

    /**
     * ExpireCommand constructor.
     *
     * @param AdvertService $service
     */
    public function __construct(AdvertService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $success = 0;
        foreach (Advert::active()->where('expires_at', '<', Carbon::now())->cursor() as $advert) {
            try{
                $this->service->close($advert->id);
            }catch(\DomainException $e){
                $this->error($e->getMessage());
                $success = 1;
            }
        }

        return $success;
    }
}
