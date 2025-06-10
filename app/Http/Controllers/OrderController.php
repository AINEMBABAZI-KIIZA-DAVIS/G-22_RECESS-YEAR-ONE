<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // GET /orders
    public function index()
    {
        return response()->json(['orders' => Order::with('items.product')->get()]);
    }

    // POST /orders
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'shipping_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $order = Order::create(Arr::except($validated, ['items']));

                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);

                    if ($product->quantity_in_stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product {$product->name}");
                    }

                    $product->decrement('quantity_in_stock', $item['quantity']);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                    ]);
                }
            });

            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Order creation failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // PUT /orders/{order}
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
        ]);

        $order->update($validated);

        return response()->json(['message' => 'Status updated']);
    }
}
