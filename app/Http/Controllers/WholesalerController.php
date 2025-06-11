<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WholesalerController extends Controller
{
    public function index()
    {
        // Dummy product data
        $products = [
            (object)[
                'id' => 1,
                'name' => 'Premium Rice (50kg)',
                'price' => 95000,
                'image' => 'https://via.placeholder.com/150',
                'description' => 'High-quality long grain rice for bulk buyers.',
            ],
            (object)[
                'id' => 2,
                'name' => 'Cooking Oil (20L)',
                'price' => 120000,
                'image' => 'https://via.placeholder.com/150',
                'description' => 'Refined vegetable oil ideal for commercial use.',
            ],
            (object)[
                'id' => 3,
                'name' => 'Wheat Flour (25kg)',
                'price' => 85000,
                'image' => 'https://via.placeholder.com/150',
                'description' => 'Top-quality flour suitable for baking and cooking.',
            ],
        ];

        return view('wholesaler.dashboard', compact('products'));
    }
}
