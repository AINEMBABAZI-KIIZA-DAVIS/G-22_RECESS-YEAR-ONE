<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WholesalerController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $activeOrders = \App\Models\Order::where('user_id', auth()->id())
            ->where('status', '!=', 'completed')
            ->count();

        $pendingPayments = \App\Models\Order::where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->sum('total_amount');

        $recentOrders = \App\Models\Order::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Get stock statistics
        $inStockItems = \App\Models\Product::where('stock', '>', 0)->count();
        $lowStockItems = \App\Models\Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $outOfStockItems = \App\Models\Product::where('stock', 0)->count();

        // Get available products
        $products = \App\Models\Product::where('stock', '>', 0)
            ->with('images')
            ->latest()
            ->take(6)
            ->get();

        return view('wholesaler.dashboard', compact(
            'products',
            'activeOrders',
            'pendingPayments',
            'recentOrders',
            'inStockItems',
            'lowStockItems',
            'outOfStockItems'
        ));
    }
}
