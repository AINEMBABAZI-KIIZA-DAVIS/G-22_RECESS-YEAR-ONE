<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Http\Controllers\Controller;
=======
>>>>>>> e72e3ddb854304ac41a2bc0bbe2697d461996cb6
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products
    public function index()
    {
        return response()->json(['products' => Product::all()]);
    }

    // POST /products
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity_in_stock' => 'required|integer',
            'low_stock_threshold' => 'required|integer',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // PUT /products/{product}
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity_in_stock' => 'required|integer',
        ]);

        $product->update($validated);

        return response()->json($product);
    }
}
