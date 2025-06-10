<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class userproductDisplayController extends Controller
{
    public function index(Request $request)
    {
        $categories = ['Electronics', 'Books', 'Clothing', 'Furniture', 'Toys'];
        $selectedCategory = $request->input('category', 'Electronics');

        $products = Product::where('category', $selectedCategory)->get();

        return view('products.index', compact('categories', 'selectedCategory', 'products'));
    }
}
