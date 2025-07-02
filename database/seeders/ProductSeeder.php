<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products
        Product::truncate();
        
        // Create sample bakery products
        $products = [
            [
                'sku' => 'FLR-001',
                'name' => 'Premium White Flour',
                'category' => 'Bakery Ingredients',
                'description' => 'High-quality wheat flour, perfect for all your baking needs.',
                'price' => 25000,
                'quantity_in_stock' => 100,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'FLR-002',
                'name' => 'Organic Whole Wheat Flour',
                'category' => 'Bakery Ingredients',
                'description' => 'Organic whole wheat flour, rich in fiber and nutrients.',
                'price' => 35000,
                'quantity_in_stock' => 80,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'BS-001',
                'name' => 'Baking Soda',
                'category' => 'Leavening Agents',
                'description' => 'Pure baking soda for perfect leavening results.',
                'price' => 5000,
                'quantity_in_stock' => 150,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'BP-001',
                'name' => 'Baking Powder',
                'category' => 'Leavening Agents',
                'description' => 'Double-acting baking powder for reliable results.',
                'price' => 7000,
                'quantity_in_stock' => 120,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'VE-001',
                'name' => 'Vanilla Extract',
                'category' => 'Flavorings',
                'description' => 'Pure vanilla extract, perfect for adding flavor to your baked goods.',
                'price' => 12000,
                'quantity_in_stock' => 90,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'CP-001',
                'name' => 'Cocoa Powder',
                'category' => 'Flavorings',
                'description' => 'High-quality cocoa powder for rich chocolate flavor.',
                'price' => 18000,
                'quantity_in_stock' => 75,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'SG-001',
                'name' => 'Granulated Sugar',
                'category' => 'Sweeteners',
                'description' => 'Granulated sugar for all your baking needs.',
                'price' => 15000,
                'quantity_in_stock' => 150,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'SL-001',
                'name' => 'Fine Sea Salt',
                'category' => 'Seasonings',
                'description' => 'Fine sea salt for bakery use.',
                'price' => 3000,
                'quantity_in_stock' => 200,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'YE-001',
                'name' => 'Active Dry Yeast',
                'category' => 'Leavening Agents',
                'description' => 'Active dry yeast for bread making.',
                'price' => 6000,
                'quantity_in_stock' => 100,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'BT-001',
                'name' => 'Unsalted Butter',
                'category' => 'Fats',
                'description' => 'Unsalted butter for baking.',
                'price' => 20000,
                'quantity_in_stock' => 85,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
