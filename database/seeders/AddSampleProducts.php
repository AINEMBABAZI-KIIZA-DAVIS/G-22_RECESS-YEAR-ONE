<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddSampleProducts extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'sku' => 'FLR-001',
                'name' => 'Premium White Flour',
                'category' => 'Bakery Ingredients',
                'description' => 'High-quality wheat flour, perfect for all your baking needs.',
                'price' => 2500,
                'quantity_in_stock' => 100,
                'low_stock_threshold' => 10,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'FLR-002',
                'name' => 'Organic Whole Wheat Flour',
                'category' => 'Bakery Ingredients',
                'description' => 'Organic whole wheat flour, rich in fiber and nutrients.',
                'price' => 3500,
                'quantity_in_stock' => 80,
                'low_stock_threshold' => 10,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'BS-001',
                'name' => 'Baking Soda',
                'category' => 'Leavening Agents',
                'description' => 'Pure baking soda for perfect leavening results.',
                'price' => 500,
                'quantity_in_stock' => 150,
                'low_stock_threshold' => 20,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ]
        ];

        foreach ($products as $product) {
            \DB::table('products')->insert($product);
        }
    }
}
