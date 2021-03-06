<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Run docker-compose exec php php artisan db:seed --class=UserTableSeeder
     * @return void
     */
    public function run()
    {
        User::factory()->times(100)->create();
    }
}
