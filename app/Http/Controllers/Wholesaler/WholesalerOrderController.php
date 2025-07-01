<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class WholesalerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->where('status', '!=', 'completed')
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('wholesaler.orders.index', compact('orders'));
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('wholesaler.orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('wholesaler.orders.show', compact('order'));
    }
}
