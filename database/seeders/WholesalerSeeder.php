<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factory;

class WholesalerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test wholesaler user
        $wholesaler = User::create([
            'name' => 'Test Wholesaler',
            'email' => 'wholesaler@test.com',
            'password' => Hash::make('password'),
            'role' => 'wholesaler',
        ]);

        // Create some sample products
        $products = [
            [
                'sku' => 'CRS-001',
                'name' => 'Fresh Croissants',
                'category' => 'Bakery Products',
                'description' => 'Delicious butter croissants',
                'price' => 5000,
                'quantity_in_stock' => 100,
                'imageUrl' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80'
            ],
            [
                'sku' => 'DON-001',
                'name' => 'Chocolate Donuts',
                'category' => 'Bakery Products',
                'description' => 'Rich chocolate glazed donuts',
                'price' => 3000,
                'quantity_in_stock' => 75,
                'imageUrl' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80'
            ],
            [
                'sku' => 'MUF-001',
                'name' => 'Blueberry Muffins',
                'category' => 'Bakery Products',
                'description' => 'Freshly baked blueberry muffins',
                'price' => 4000,
                'quantity_in_stock' => 50,
                'imageUrl' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80'
            ],
            [
                'sku' => 'CUP-001',
                'name' => 'Vanilla Cupcakes',
                'category' => 'Bakery Products',
                'description' => 'Classic vanilla cupcakes',
                'price' => 3500,
                'quantity_in_stock' => 25,
                'imageUrl' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80'
            ],
            [
                'sku' => 'TAR-001',
                'name' => 'Strawberry Tart',
                'category' => 'Bakery Products',
                'description' => 'Fresh strawberry tart',
                'price' => 8000,
                'quantity_in_stock' => 15,
                'imageUrl' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80'
            ],
        ];

        // Create products
        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create a sample cart with items
        $cart = Cart::create(['user_id' => $wholesaler->id]);
        
        // Add items to cart
        $cartItems = [
            ['product_id' => 1, 'quantity' => 5],
            ['product_id' => 2, 'quantity' => 3],
        ];
        
        foreach ($cartItems as $item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity']
            ]);
        }

        // Create some sample orders
        $orders = [
            [
                'user_id' => $wholesaler->id,
                'total_amount' => 65000,
                'payment_status' => 'pending',
                'payment_method' => 'bank_transfer',
                'contact_number' => '+256771234567',
                'status' => 'processing',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $wholesaler->id,
                'total_amount' => 45000,
                'payment_status' => 'paid',
                'payment_method' => 'mobile_money',
                'contact_number' => '+256771234567',
                'status' => 'completed',
                'created_at' => now()->subDays(5),
            ],
        ];

        // Create orders with items
        foreach ($orders as $orderData) {
            $order = Order::create($orderData);
            
            // Add items to order
            $orderItems = [
                ['product_id' => 3, 'quantity' => 10, 'price' => 4000],
                ['product_id' => 4, 'quantity' => 5, 'price' => 3500],
            ];
            
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        }
    }
}
