<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminDashboardController extends Controller
{
    private $mlApiUrl = 'http://localhost:5000/api/ml';

    /**
     * Show the admin dashboard with system overview
     */
    public function index()
    {
        try {
            // Get basic stats from database
            $stats = [
                'total_products' => Product::count(),
                'low_stock' => Product::where('quantity_in_stock', '<=', 10)->count(),
                'orders_today' => Order::whereDate('created_at', today())->count(),
                'revenue_today' => Order::whereDate('created_at', today())->sum('total_amount'),
            ];
        } catch (\Exception $e) {
            $stats = [
                'total_products' => 0,
                'low_stock' => 0,
                'orders_today' => 0,
                'revenue_today' => 0,
            ];
        }

        // Get ML-powered analytics
        $mlAnalytics = $this->getMLAnalytics();

        // Get recent data from database
        $recentOrders = $this->getRecentOrders();
        $recentInventory = $this->getRecentInventory();
        $recentProducts = $this->getRecentProducts();

        return view('admin.dashboard', [
            'stats' => $stats,
            'mlAnalytics' => $mlAnalytics,
            'recentOrders' => $recentOrders,
            'recentInventory' => $recentInventory,
            'recentProducts' => $recentProducts,
        ]);
    }

    private function getMLAnalytics()
    {
        try {
            // Get analytics summary from ML API
            $response = Http::timeout(10)->get($this->mlApiUrl . '/analytics-summary');
            
            if ($response->successful()) {
                $data = $response->json()['data'];
                
                return [
                    'demand_forecast' => $data['demand_forecast'] ?? [],
                    'top_products' => $data['top_products'] ?? [],
                    'customer_segments' => $data['customer_segments'] ?? [],
                    'summary_stats' => $data['summary_stats'] ?? [],
                    'insights' => $data['insights'] ?? [],
                    'data_source' => $data['data_source'] ?? 'database'
                ];
            }
            
            throw new \Exception('Failed to fetch ML analytics');
        } catch (\Exception $e) {
            // Return sample data if ML API is not available
            return [
                'demand_forecast' => [
                    'predicted_demand' => 1250,
                    'trend' => 'increasing',
                    'accuracy' => 87.5,
                    'confidence_lower' => 1100,
                    'confidence_upper' => 1400,
                    'data_source' => 'sample'
                ],
                'top_products' => [
                    [
                        'product_name' => 'Premium Coffee Beans',
                        'total_units_sold' => 450,
                        'total_revenue' => 6750.00,
                        'total_orders' => 89
                    ],
                    [
                        'product_name' => 'Organic Tea Selection',
                        'total_units_sold' => 320,
                        'total_revenue' => 4800.00,
                        'total_orders' => 67
                    ],
                    [
                        'product_name' => 'Artisan Bread Mix',
                        'total_units_sold' => 280,
                        'total_revenue' => 4200.00,
                        'total_orders' => 54
                    ]
                ],
                'customer_segments' => [
                    [
                        'segment_name' => 'High-Value Loyal',
                        'count' => 25,
                        'avg_recency' => 5.2,
                        'avg_frequency' => 8.5,
                        'avg_monetary' => 450.75,
                        'total_revenue' => 11268.75
                    ],
                    [
                        'segment_name' => 'At-Risk',
                        'count' => 15,
                        'avg_recency' => 45.8,
                        'avg_frequency' => 2.1,
                        'avg_monetary' => 120.30,
                        'total_revenue' => 1804.50
                    ],
                    [
                        'segment_name' => 'New Customers',
                        'count' => 30,
                        'avg_recency' => 2.1,
                        'avg_frequency' => 1.8,
                        'avg_monetary' => 95.25,
                        'total_revenue' => 2857.50
                    ],
                    [
                        'segment_name' => 'Regular Customers',
                        'count' => 30,
                        'avg_recency' => 12.8,
                        'avg_frequency' => 4.2,
                        'avg_monetary' => 180.50,
                        'total_revenue' => 5415.00
                    ]
                ],
                'summary_stats' => [
                    'total_customers' => 100,
                    'total_revenue' => 21345.75,
                    'segment_count' => 4,
                    'high_value_customers' => 25,
                    'at_risk_customers' => 15
                ],
                'insights' => [
                    'demand_trend' => 'Demand is increasing with 87.5% accuracy',
                    'customer_distribution' => 'Customers distributed across 4 segments',
                    'revenue_insight' => 'Total revenue: $21,345.75 from 100 customers',
                    'data_quality' => 'Using sample data sources'
                ],
                'data_source' => 'sample'
            ];
        }
    }

    private function getRecentOrders()
    {
        try {
            return Order::with('user')->latest()->take(5)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getRecentInventory()
    {
        try {
            return Product::where('quantity_in_stock', '<=', 10)
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getRecentProducts()
    {
        try {
            return Product::latest()->take(5)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function getMLData(Request $request)
    {
        try {
            $endpoint = $request->get('endpoint');
            $params = $request->except('endpoint');
            
            $response = Http::timeout(10)->get($this->mlApiUrl . '/' . $endpoint, $params);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get ML data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function databaseStatus()
    {
        try {
            $response = Http::timeout(10)->get($this->mlApiUrl . '/database-status');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get database status']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
