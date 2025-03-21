<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => "Louis Vuitton L’Immensité",
                'description' => 'Свежий и элегантный аромат с нотами имбиря, грейпфрута и амбры.',
                'price' => 250.00,
                'brand' => 'Louis Vuitton',
                'quantity' => 500, // Количество
                'unit' => 'ml', // Единица измерения
                'is_new' => true,
                'gender' => 'uni',
                'category_id' => 1,
                'volume_options' => [3, 6],
                'scent' => ['имбирь', 'грейпфрут', 'амбра'],
                'image_url' => ['lv_immensite_1.jpg', 'lv_immensite_2.jpg'],
                'rating' => 4.8,
                'discount' => 10
            ],
            [
                'name' => 'Bvlgari Tygar',
                'description' => 'Яркий и энергичный аромат с аккордами грейпфрута, древесины и амбры.',
                'price' => 220.00,
                'brand' => 'Bvlgari',
                'quantity' => 400,
                'unit' => 'ml',
                'is_new' => false,
                'category_id' => 1,
                'volume_options' => [3, 6],
                'scent' => ['грейпфрут', 'древесина', 'амбровые ноты'],
                'image_url' => ['bvlgari_tygar_1.jpg', 'bvlgari_tygar_2.jpg'],
                'rating' => 4.7,
                'discount' => 15
            ],
            [
                'name' => 'Tiziana Terenzi Gumin',
                'description' => 'Теплый и чувственный аромат с фруктовыми и цветочными нотами.',
                'price' => 270.00,
                'brand' => 'Tiziana Terenzi',
                'quantity' => 600,
                'unit' => 'ml',
                'is_new' => true,
                'category_id' => 1,
                'volume_options' => [3, 6, 9],
                'scent' => ['цитрус', 'цветы', 'древесина'],
                'image_url' => ['terenzi_gumin_1.jpg', 'terenzi_gumin_2.jpg'],
                'rating' => 4.9,
                'discount' => 20
            ],
            [
                'name' => 'Dani box',
                'description' => 'Теплый и чувственный аромат с фруктовыми и цветочными нотами.',
                'price' => 5000,
                'brand' => 'Tiziana Terenzi',
                'quantity' => 600,
                'unit' => 'pcs',
                'is_new' => true,
                'category_id' => 1,
                'volume_options' => [3, 6, 9],
                'scent' => ['цитрус', 'цветы', 'древесина'],
                'image_url' => ['terenzi_gumin_1.jpg', 'terenzi_gumin_2.jpg'],
                'rating' => 4.9,
                'discount' => 20
            ],
        ];


        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
