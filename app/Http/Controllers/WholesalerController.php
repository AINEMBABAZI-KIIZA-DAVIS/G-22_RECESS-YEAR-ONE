<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class WholesalerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'wholesaler') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $topProducts = Product::where('is_active', true)
            ->orderBy('quantity_in_stock', 'desc')
            ->take(5)
            ->get();
            
        $monthlySpend = Order::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
            
        return view('wholesaler.dashboard', compact('orders', 'topProducts', 'monthlySpend'));
    }

    public function catalog(Request $request)
    {
        $query = Product::where('is_active', true);
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }
        
        $products = $query->paginate(12);
        return view('wholesaler.catalog', compact('products'));
    }

    public function orderForm()
    {
        $products = Product::where('is_active', true)
            ->where('quantity_in_stock', '>', 0)
            ->get();
            
        return view('wholesaler.order_form', compact('products'));
    }

    public function submitOrder(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        
        // Validate stock first
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            if ($product->quantity_in_stock < $item['quantity']) {
                return back()->withErrors([
                    "stock" => "Product {$product->name} has insufficient stock (available: {$product->quantity_in_stock})"
                ])->withInput();
            }
        }
        
        $user = Auth::user();
        $order = new Order();
        $order->user_id = $user->id;
        $order->customer_name = $user->name;
        $order->customer_email = $user->email;
        $order->status = 'pending';
        $order->payment_status = 'unpaid';
        $order->total_amount = 0;
        $order->save();
        
        $total = 0;
        $items = [];
        
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $price = $product->price * $item['quantity'];
            
            if ($item['quantity'] >= 50) {
                $price *= 0.9; // 10% bulk discount
            }
            
            $orderItem = new OrderItem([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $price,
                'tax_rate' => $product->tax_rate,
            ]);
            
            $items[] = $orderItem;
            $total += $price;
            $product->quantity_in_stock -= $item['quantity'];
            $product->save();
        }
        
        $order->total_amount = $total;
        $order->save();
        $order->items()->saveMany($items);
        
        return redirect()->route('wholesaler.order.confirmation', $order->id);
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        $products = [];
        foreach ($data as $row) {
            if (count($row) >= 2 && is_numeric($row[0])) {
                $products[] = [
                    'id' => (int)$row[0],
                    'quantity' => (int)$row[1]
                ];
            }
        }
        
        return view('wholesaler.order_form', [
            'products' => Product::where('is_active', true)->get(),
            'csvProducts' => $products,
        ]);
    }

    public function orderConfirmation(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('wholesaler.order_confirmation', compact('order'));
    }

    public function orderTracking()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('wholesaler.order_tracking', compact('orders'));
    }

    public function analytics()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get();
        
        $monthlySpend = $orders->where('created_at', '>=', now()->startOfMonth())
            ->sum('total_amount');
            
        $topProducts = OrderItem::whereIn('order_id', $orders->pluck('id'))
            ->selectRaw('product_id, sum(quantity) as total_quantity, sum(price) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->with('product')
            ->get();
            
        return view('wholesaler.analytics', compact('monthlySpend', 'topProducts'));
    }

    public function exportOrders($type)
    {
        if (!in_array($type, ['pdf', 'excel'])) {
            return back()->with('error', 'Invalid export type');
        }
        
        return back()->with('success', "Export to {$type} will be processed shortly");
    }
}