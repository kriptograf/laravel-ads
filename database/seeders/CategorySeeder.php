<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(10)->create()->each(function (Category $category) {
            $counts = [0, random_int(3, 7)];
            $category->children()->saveMany(Category::factory()->count(array_rand($counts))->create()->each(function (Category $category) {
                $counts2 = [0, random_int(3, 10)];
                $category->children()->saveMany(Category::factory()->count(array_rand($counts2))->create());
            }));
        });
    }
}
