<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Масло', 'slug' => 'maslo', 'description' => 'Ароматические масла'],
            ['name' => 'Спрей', 'slug' => 'sprey', 'description' => 'Ароматические спреи'],
            ['name' => 'Диффузор', 'slug' => 'diffuzor', 'description' => 'Аромадиффузоры'],
            ['name' => 'Бокс', 'slug' => 'boks', 'description' => 'Подарочные боксы'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
