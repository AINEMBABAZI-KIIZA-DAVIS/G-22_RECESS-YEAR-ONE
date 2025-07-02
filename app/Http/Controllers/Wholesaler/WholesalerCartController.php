<?php

namespace App\Http\Controllers\Wholesaler;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

class WholesalerCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'This product is out of stock.');
        }

        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cart = auth()->user()->cart()->firstOrCreate([]);
        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $newQuantity = $item->quantity + $request->input('quantity', 1);
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Cannot add more than available stock.');
            }
            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $request->input('quantity', 1)
            ]);
        }

        return redirect()->back()->with('success', 'Product added to your cart.');

        $cart = auth()->user()->cart()->firstOrCreate([]);
        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $newQuantity = $item->quantity + 1;
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Cannot add more than available stock.');
            }
            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Product added to your cart.');
    }

    public function show()
    {
        $cart = auth()->user()->cart()->firstOrCreate([]);
        $items = $cart->items()->with('product')->get();
        
        return view('wholesaler.cart.show', compact('items'));
        $cart = auth()->user()->cart()->firstOrCreate([]);
        $items = $cart->items()->with('product')->get();
        
        return view('wholesaler.cart.show', compact('items'));
    }

    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        
        if ($item->cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $item->delete();
        
        return redirect()->back()->with('success', 'Item removed from cart.');
        $item = CartItem::findOrFail($id);
        
        if ($item->cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $item->delete();
        
        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
