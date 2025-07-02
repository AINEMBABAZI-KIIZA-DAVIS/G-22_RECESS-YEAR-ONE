<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'sku' => 'BREAD-001',
                'name' => 'Whole Wheat Bread',
                'description' => 'Freshly baked whole wheat bread, perfect for sandwiches and toast.',
                'price' => 3.99,
                'quantity_in_stock' => 50,
                'low_stock_threshold' => 10,
                'category' => 'Bread',
                'imageUrl' => 'bread-whole-wheat.jpg'
            ],
            [
                'sku' => 'COOKIE-001',
                'name' => 'Chocolate Chip Cookies',
                'description' => 'Classic chocolate chip cookies, soft and chewy with rich chocolate chunks.',
                'price' => 2.50,
                'quantity_in_stock' => 100,
                'low_stock_threshold' => 20,
                'category' => 'Cookies',
                'imageUrl' => 'cookies-chocolate-chip.jpg'
            ],
            [
                'sku' => 'PASTRY-001',
                'name' => 'Cinnamon Rolls',
                'description' => 'Freshly baked cinnamon rolls with cream cheese frosting.',
                'price' => 4.50,
                'quantity_in_stock' => 30,
                'low_stock_threshold' => 5,
                'category' => 'Pastries',
                'imageUrl' => 'cinnamon-rolls.jpg'
            ],
            [
                'sku' => 'BREAD-002',
                'name' => 'Sourdough Baguette',
                'description' => 'Traditional French sourdough baguette with crispy crust.',
                'price' => 3.25,
                'quantity_in_stock' => 40,
                'low_stock_threshold' => 10,
                'category' => 'Bread',
                'imageUrl' => 'sourdough-baguette.jpg'
            ],
            [
                'sku' => 'MUFFIN-001',
                'name' => 'Blueberry Muffins',
                'description' => 'Moist blueberry muffins with a hint of lemon zest.',
                'price' => 2.75,
                'quantity_in_stock' => 45,
                'low_stock_threshold' => 10,
                'category' => 'Muffins',
                'imageUrl' => 'blueberry-muffins.jpg'
            ]
        ];

        foreach ($products as $product) {
            \DB::table('products')->insert($product);
        }
    }
}
