<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->has('status') && in_array($request->status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'attention'])) {
            if ($request->status === 'attention') {
                $query->whereIn('status', ['pending', 'processing']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $orders = $query->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        if ($order->status !== 'cancelled') {
            $order->status = 'cancelled';
            $order->save();
            return redirect()->route('admin.orders.index')->with('success', 'Order marked as cancelled.');
        }

        return redirect()->route('admin.orders.index')->with('info', 'Order already cancelled.');
    }
}
