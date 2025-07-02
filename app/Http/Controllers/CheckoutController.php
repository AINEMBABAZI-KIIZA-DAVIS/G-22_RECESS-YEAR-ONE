<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('wholesaler.checkout', compact('cartItems', 'total'));
    }

    public function place(Request $request)
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'shipping_address' => $request->shipping_address,
            'status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            }),
            'contact_number' => $request->contact_number
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price
            ]);

            // Update product stock
            $cartItem->product->decrement('stock', $cartItem->quantity);
        }

        // Clear cart
        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('wholesaler.orders.show', $order)
            ->with('success', 'Order placed successfully! Please proceed to payment.');
    }
}
