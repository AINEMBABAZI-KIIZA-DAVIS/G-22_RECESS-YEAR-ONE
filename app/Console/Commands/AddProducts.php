<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class AddProducts extends Command
{
    protected $signature = 'products:add';
    protected $description = 'Add sample products to the database';

    public function handle()
    {
        $products = [
            [
                'sku' => 'FLR-001',
                'name' => 'Premium White Flour',
                'category' => 'Bakery Ingredients',
                'description' => 'High-quality wheat flour, perfect for all your baking needs.',
                'price' => 25000,
                'quantity_in_stock' => 100,
                'low_stock_threshold' => 10,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ],
            [
                'sku' => 'FLR-002',
                'name' => 'Organic Whole Wheat Flour',
                'category' => 'Bakery Ingredients',
                'description' => 'Organic whole wheat flour, rich in fiber and nutrients.',
                'price' => 35000,
                'quantity_in_stock' => 80,
                'low_stock_threshold' => 10,
                'imageUrl' => 'https://images.unsplash.com/photo-1546832653-3b7fb00827d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
            $this->info('Added product: ' . $product['name']);
        }

        $this->info('Products added successfully!');
        return 0;
    }
}
