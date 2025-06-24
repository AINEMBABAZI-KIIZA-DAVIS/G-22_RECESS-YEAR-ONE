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
                'name' => 'Premium Cupcakes ',
                'price' => 50000,
                'image' => 'https://images.unsplash.com/photo-1583823140300-13976ffcd426?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'description' => 'Delicious cupcakes available for bulk buyers.',
            ],
            (object)[
                'id' => 2,
                'name' => 'Premium Cookies',
                'price' => 30000,
                'image' => 'https://plus.unsplash.com/premium_photo-1668863373830-c50ca78857cb?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'description' => 'Freshly baked cookies available in bulk.',
            ],
            (object)[
                'id' => 3,
                'name' => 'Yummy Bread',
                'price' => 20000,
                'image' => 'https://plus.unsplash.com/premium_photo-1710108920120-b4c0463bb82d?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'description' => 'Freshly baked bread available for wholesale.',
            ],
        ];

        return view('wholesaler.dashboard', compact('products'));
    }
}
