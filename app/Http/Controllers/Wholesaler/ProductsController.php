<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('wholesaler.products.index', compact('products'));
    }

    public function lowStock()
    {
        $products = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('wholesaler.products.low-stock', compact('products'));
    }

    public function outOfStock()
    {
        $products = Product::where('stock', 0)
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('wholesaler.products.out-of-stock', compact('products'));
    }

    public function show(Product $product)
    {
        return view('wholesaler.products.show', compact('product'));
    }
}
