<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if (auth()->check()) {
            $user = auth()->user();
            $cart = $user->cart()->firstOrCreate([]);
            $item = $cart->items()->where('product_id', $productId)->first();

            if ($item) {
                $item->increment('quantity');
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => 1
                ]);
            }

            return redirect()->back()->with('success', 'Product added to your cart.');
        }

        // fallback to session cart for guests
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to session cart.');
    }

    public function show()
    {
        if (auth()->check()) {
            $items = auth()->user()->cart?->items()->with('product')->get() ?? collect();
            $total = $items->sum(fn($item) => $item->product->price * $item->quantity);
            return view('cart.index', compact('items', 'total'));
        }

        $cart = session()->get('cart', []);
        $total = array_reduce($cart, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);

        return view('cart.index', ['sessionCart' => $cart, 'total' => $total]);
    }

    public function remove($id)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cart = $user->cart;
            if ($cart) {
                $item = $cart->items()->where('product_id', $id)->first();
                if ($item) {
                    $item->delete();
                }
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }
        return redirect()->route('cart.show')->with('success', 'Item removed from cart.');
    }
}
