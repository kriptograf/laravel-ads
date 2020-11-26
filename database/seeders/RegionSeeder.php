<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Run docker-compose exec php php artisan db:seed --class=RegionSeeder
     * @return void
     */
    public function run()
    {
        Region::factory()->count(10)->create()->each(function (Region $region) {
            $region->children()->saveMany(Region::factory()->count(random_int(3, 10))->create()->each(function (Region $region) {
                $region->children()->saveMany(Region::factory()->count(random_int(3, 10))->make());
            }));
        });
    }
}
