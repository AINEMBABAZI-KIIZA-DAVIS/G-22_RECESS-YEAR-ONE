<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Vendor;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard with system overview
     */
    public function index()
    {
        // System Statistics
        $stats = [
            'total_products' => Product::count(),
            'total_vendors' => Vendor::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_production' => ProductionBatch::whereDate('production_date', today())->sum('quantity'),
            'low_stock_items' => Inventory::where('quantity', '<=', 10)->count(),
        ];

        // Recent Activities
        $recentActivities = [
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
            'recent_production' => ProductionBatch::with('product')->latest()->take(5)->get(),
            'recent_inventory' => Inventory::with('product')
                ->whereColumn('quantity', '<=', 'reorder_level')
                ->latest()
                ->take(5)
                ->get(),
        ];

        // Production Metrics
        $productionMetrics = [
            'daily_production' => $this->getDailyProduction(),
            'top_products' => $this->getTopProducts(),
            'production_yield' => $this->getProductionYield(),
        ];

        // Inventory Overview
        $inventoryOverview = [
            'stock_levels' => $this->getStockLevels(),
            'reorder_items' => Inventory::with('product')
                ->whereColumn('quantity', '<=', 'reorder_level')
                ->get(),
        ];

        // Vendor Performance
        $vendorPerformance = [
            'top_vendors' => $this->getTopVendors(),
            'vendor_ratings' => $this->getVendorRatings(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'productionMetrics',
            'inventoryOverview',
            'vendorPerformance'
        ));
    }

    /**
     * Get daily production data for the last 30 days
     */
    private function getDailyProduction()
    {
        return ProductionBatch::select(
            DB::raw('DATE(production_date) as date'),
            DB::raw('SUM(quantity) as total_quantity')
        )
        ->where('production_date', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    /**
     * Get top 5 best-selling products
     */
    private function getTopProducts()
    {
        return Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Calculate production yield (good vs waste)
     */
    private function getProductionYield()
    {
        return [
            'good' => ProductionBatch::sum('good_quantity'),
            'waste' => ProductionBatch::sum('waste_quantity'),
        ];
    }

    /**
     * Get current stock levels by category
     */
    private function getStockLevels()
    {
        return Product::select(
            'category',
            DB::raw('SUM(inventories.quantity) as total_quantity'),
            DB::raw('SUM(products.price * inventories.quantity) as total_value')
        )
        ->join('inventories', 'products.id', '=', 'inventories.product_id')
        ->groupBy('category')
        ->get();
    }

    /**
     * Get top performing vendors
     */
    private function getTopVendors()
    {
        return Vendor::select('vendors.*', DB::raw('COUNT(orders.id) as order_count'))
            ->leftJoin('orders', 'vendors.id', '=', 'orders.vendor_id')
            ->groupBy('vendors.id')
            ->orderBy('order_count', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get vendor performance ratings
     */
    private function getVendorRatings()
    {
        return Vendor::select(
            'vendors.id',
            'vendors.name',
            DB::raw('AVG(orders.rating) as avg_rating'),
            DB::raw('COUNT(orders.id) as total_orders')
        )
        ->leftJoin('orders', 'vendors.id', '=', 'orders.vendor_id')
        ->groupBy('vendors.id', 'vendors.name')
        ->having('total_orders', '>', 0)
        ->orderBy('avg_rating', 'desc')
        ->get();
    }

    /**
     * System settings page
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'bakery_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'working_hours' => 'required|string',
        ]);

        // Update settings in the database or config
        foreach ($validated as $key => $value) {
            setting([$key => $value])->save();
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully');
    }
}

