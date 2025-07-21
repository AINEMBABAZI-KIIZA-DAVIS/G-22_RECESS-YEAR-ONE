<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class WholesalerDashboardController extends Controller
{
    /**
     * Display the wholesaler dashboard with summary data.
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();

            $orders = Order::where('user_id', $user->id)
                ->with('items.product')
                ->latest()
                ->take(5)
                ->get();

            // Top-selling products for this wholesaler
            $topProducts = collect([]);
            if ($orders->isNotEmpty()) {
                $topProducts = OrderItem::whereIn('order_id', $orders->pluck('id'))
                    ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                    ->groupBy('product_id')
                    ->orderByDesc('total_sold')
                    ->take(5)
                    ->with('product')
                    ->get()
                    ->map(function ($item) {
                        if ($item->product) {
                            $item->product->sold = $item->total_sold;
                            return $item->product;
                        }
                        return null;
                    })
                    ->filter();
            }

            $monthlySpend = Order::where('user_id', $user->id)
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('total_amount');

            $fulfillmentSpeed = Order::where('user_id', $user->id)
                ->avg('fulfillment_time');

            return view('wholesaler.dashboard', compact('orders', 'topProducts', 'monthlySpend', 'fulfillmentSpeed'));
        } catch (\Exception $e) {
            \Log::error('Error in dashboard: ' . $e->getMessage());
            
            // Return default values if there's an error
            $orders = collect([]);
            $topProducts = collect([]);
            $monthlySpend = 0;
            $fulfillmentSpeed = null;
            
            return view('wholesaler.dashboard', compact('orders', 'topProducts', 'monthlySpend', 'fulfillmentSpeed'))
                ->with('error', 'Unable to load dashboard data. Please try again later.');
        }
    }

    /**
     * Display the product catalog with optional filters.
     */
    public function catalog(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->paginate(12);
        
        // Get unique categories for the filter dropdown
        $categories = Product::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('wholesaler.catalog', compact('products', 'categories'));
    }

    /**
     * Show the order form for wholesalers to place new orders.
     */
    public function orderForm()
    {
        try {
            $products = Product::where('is_active', true)
                ->where('quantity_in_stock', '>', 0)
                ->orderBy('name')
                ->get();
            
            if ($products->isEmpty()) {
                return view('wholesaler.order_form', compact('products'))
                    ->with('warning', 'No active products available for ordering.');
            }
            
            return view('wholesaler.order_form', compact('products'));
        } catch (\Exception $e) {
            \Log::error('Error fetching products: ' . $e->getMessage());
            $products = collect([]);
            return view('wholesaler.order_form', compact('products'))
                ->with('error', 'Unable to load products. Please try again later.');
        }
    }

    /**
     * Handle submission of new orders.
     */
    public function submitOrder(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total_amount' => 0,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'shipping_address' => '',
        ]);

        $total = 0;
        $orderItems = [];

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);

            if ($product->quantity_in_stock < $item['quantity']) {
                return back()->withErrors(["Insufficient stock for product: {$product->name}"]);
            }

            $unitPrice = $product->price;
            $itemTotal = $unitPrice * $item['quantity'];

            // Apply bulk discount
            if ($item['quantity'] >= 50) {
                $itemTotal *= 0.90;
            }

            $orderItems[] = new OrderItem([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
            ]);

            $total += $itemTotal;

            // Update product stock
            $product->quantity_in_stock -= $item['quantity'];
            $product->save();
        }

        $order->update(['total_amount' => $total]);
        $order->items()->saveMany($orderItems);

        // Optional: notify admin
        // Mail::to('admin@bakery.com')->send(new NewOrderNotification($order));

        return redirect()->route('wholesaler.order.confirmation', $order->id);
    }

    /**
     * Upload and parse a CSV for bulk ordering.
     */
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $products = [];
        foreach ($data as $row) {
            if (isset($row[0], $row[1])) {
                $products[] = ['id' => $row[0], 'quantity' => $row[1]];
            }
        }

        return view('wholesaler.order_form', [
            'products' => Product::all(),
            'csvProducts' => $products,
        ]);
    }

    /**
     * Show confirmation page after order placement.
     */
    public function orderConfirmation($orderId)
    {
        try {
            $order = Order::with('items.product')->findOrFail($orderId);
            
            // Ensure the order belongs to the authenticated user
            if ($order->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this order.');
            }
            
            // Ensure order has items and total amount
            if ($order->items->isEmpty()) {
                return redirect()->route('wholesaler.dashboard')
                    ->with('error', 'Order has no items.');
            }
            
            // Validate that all items have products
            foreach ($order->items as $item) {
                if (!$item->product) {
                    return redirect()->route('wholesaler.dashboard')
                        ->with('error', 'Order contains invalid products.');
                }
            }
            
            // Ensure total amount is calculated
            if ($order->total_amount <= 0) {
                return redirect()->route('wholesaler.dashboard')
                    ->with('error', 'Order total amount is invalid.');
            }
            
            return view('wholesaler.order_confirmation', compact('order'));
        } catch (\Exception $e) {
            \Log::error('Error in order confirmation: ' . $e->getMessage());
            return redirect()->route('wholesaler.dashboard')
                ->with('error', 'Unable to load order confirmation. Please try again.');
        }
    }

    /**
     * Display order tracking for the wholesaler.
     */
    public function orderTracking()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->get();

        return view('wholesaler.order_tracking', compact('orders'));
    }

    /**
     * Show analytics related to wholesaler's orders and products.
     */
    public function analytics()
    {
        try {
            $user = Auth::user();

            $orders = Order::where('user_id', $user->id)->get();

            $monthlySpend = $orders->where('created_at', '>=', now()->startOfMonth())
                ->sum('total_amount');

            // Remove fulfillment_time calculation since the field doesn't exist
            $fulfillmentSpeed = null;

            $topProducts = collect([]);
            if ($orders->isNotEmpty()) {
                $topProducts = OrderItem::whereIn('order_id', $orders->pluck('id'))
                    ->selectRaw('product_id, SUM(quantity) as total')
                    ->groupBy('product_id')
                    ->orderByDesc('total')
                    ->with('product')
                    ->take(5)
                    ->get()
                    ->filter(function ($item) {
                        return $item->product !== null;
                    });
            }

            return view('wholesaler.analytics', compact('monthlySpend', 'fulfillmentSpeed', 'topProducts'));
        } catch (\Exception $e) {
            \Log::error('Error in analytics: ' . $e->getMessage());
            
            // Return default values if there's an error
            $monthlySpend = 0;
            $fulfillmentSpeed = null;
            $topProducts = collect([]);
            
            return view('wholesaler.analytics', compact('monthlySpend', 'fulfillmentSpeed', 'topProducts'))
                ->with('error', 'Unable to load analytics data. Please try again later.');
        }
    }

    /**
     * Export orders as PDF or Excel (not implemented).
     */
    public function exportOrders($type)
    {
        // Placeholder for export functionality
        return back()->with('info', 'Export feature is not implemented yet.');
    }
}
