<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;

class checkOutController extends Controller
{
    public function showForm()
    {
        $cart = auth()->user()->cart;
        $items = $cart?->items()->with('product')->get();

        if (!$items || $items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return view('checkout.form', compact('items', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string',
        ]);

        $user = auth()->user();
        $cart = $user->cart;
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        $order = $user->orders()->create(['total' => $total]);

        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        $order->shipment()->create($request->only(['address', 'city', 'country', 'postal_code']));

        // Clear cart
        $cart->items()->delete();

        return redirect()->route('orders.thankyou')->with('success', 'Order placed successfully!');
    }
}
