<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $users = User::all();

        foreach ($users as $user) {
            Profile::create([
                'user_id' => $user->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'location' => $faker->city,
                'photo' => $faker->imageUrl(),
            ]);
        }
    }
}
