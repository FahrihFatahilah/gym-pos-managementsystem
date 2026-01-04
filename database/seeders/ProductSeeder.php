<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Air Mineral 600ml',
                'price' => 3000,
                'stock' => 50,
                'unit' => 'botol'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}