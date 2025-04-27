<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $users = User::all();
        $products = Product::all();

        for ($i = 0; $i < 50; $i++) {
            Review::create([
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'rating' => $faker->numberBetween(1, 5),
                'comment' => $faker->sentence,
            ]);
        }
    }
}
