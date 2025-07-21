<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnalyticsController extends Controller
{
    private $mlApiUrl = 'http://localhost:5000/api/ml';

    public function index()
    {
        try {
            // Fetch analytics data from ML API
            $analyticsData = $this->getAnalyticsData();
            
            return view('admin.analytics.index', compact('analyticsData'));
        } catch (\Exception $e) {
            // Fallback to demo data if ML API is not available
            $analyticsData = $this->getDemoData();
            return view('admin.analytics.index', compact('analyticsData'));
        }
    }

    private function getAnalyticsData()
    {
        try {
            // Get comprehensive analytics summary
            $response = Http::timeout(10)->get($this->mlApiUrl . '/analytics-summary');
            
            if ($response->successful()) {
                return $response->json()['data'];
            }
            
            throw new \Exception('Failed to fetch analytics data');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getDemoData()
    {
        return [
            'demand_forecast' => [
                'predicted_demand' => 1250,
                'trend' => 'increasing',
                'accuracy' => 87.5,
                'confidence_lower' => 1100,
                'confidence_upper' => 1400
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
                'revenue_insight' => 'Total revenue: $21,345.75 from 100 customers'
            ]
        ];
    }

    public function demandForecast(Request $request)
    {
        try {
            $productId = $request->get('product_id');
            $days = $request->get('days', 30);
            
            $response = Http::timeout(10)->get($this->mlApiUrl . '/demand-forecast', [
                'product_id' => $productId,
                'days' => $days
            ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get forecast']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function customerSegments()
    {
        try {
            $response = Http::timeout(10)->get($this->mlApiUrl . '/customer-segments');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get segments']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function topProducts(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            
            $response = Http::timeout(10)->get($this->mlApiUrl . '/top-products', [
                'limit' => $limit
            ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get top products']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function inventoryOptimization()
    {
        try {
            $response = Http::timeout(10)->get($this->mlApiUrl . '/inventory-optimization');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get inventory optimization']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function salesPrediction()
    {
        try {
            $response = Http::timeout(10)->get($this->mlApiUrl . '/sales-prediction');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get sales prediction']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function customerLifetimeValue()
    {
        try {
            $response = Http::timeout(10)->get($this->mlApiUrl . '/customer-lifetime-value');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get customer lifetime value']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function marketTrends()
    {
        try {
            $response = Http::timeout(10)->get($this->mlApiUrl . '/market-trends');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['success' => false, 'error' => 'Failed to get market trends']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}