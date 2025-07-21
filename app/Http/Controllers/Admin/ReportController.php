<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Worker;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        // For now, just return a simple view or message
        // Later, implement PDF/Excel report generation
        return response()->json(['message' => 'Report generation coming soon!']);
    }

    public function index()
    {
        // Date ranges
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Orders
        $ordersDaily = Order::whereDate('created_at', $today)->count();
        $ordersWeekly = Order::where('created_at', '>=', $startOfWeek)->count();
        $ordersMonthly = Order::where('created_at', '>=', $startOfMonth)->count();
        $ordersDailyTotal = Order::whereDate('created_at', $today)->sum('total_amount');
        $ordersWeeklyTotal = Order::where('created_at', '>=', $startOfWeek)->sum('total_amount');
        $ordersMonthlyTotal = Order::where('created_at', '>=', $startOfMonth)->sum('total_amount');

        // Products
        $productsDaily = Product::whereDate('created_at', $today)->count();
        $productsWeekly = Product::where('created_at', '>=', $startOfWeek)->count();
        $productsMonthly = Product::where('created_at', '>=', $startOfMonth)->count();

        // Workers
        $workersDaily = Worker::whereDate('created_at', $today)->count();
        $workersWeekly = Worker::where('created_at', '>=', $startOfWeek)->count();
        $workersMonthly = Worker::where('created_at', '>=', $startOfMonth)->count();

        // Top 5 products by sales (monthly) - Fixed ambiguous column issue
        $topProducts = Order::where('orders.created_at', '>=', $startOfMonth)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', \DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'ordersDaily',
            'ordersWeekly',
            'ordersMonthly',
            'ordersDailyTotal',
            'ordersWeeklyTotal',
            'ordersMonthlyTotal',
            'productsDaily',
            'productsWeekly',
            'productsMonthly',
            'workersDaily',
            'workersWeekly',
            'workersMonthly',
            'topProducts'
        ));
    }
}