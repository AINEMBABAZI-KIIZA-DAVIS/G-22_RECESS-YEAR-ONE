<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class WholesalerPaymentController extends Controller
{
    public function index()
    {
        $pendingPayments = Order::where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('wholesaler.payments.index', compact('pendingPayments'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        return view('wholesaler.payments.show', compact('order'));
    }

    public function pay(Order $order, Request $request)
    {
        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        // Process payment logic here
        // For now, we'll just update the payment status
        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'payment_reference' => 'PAY-' . now()->format('YmdHis')
        ]);

        return redirect()->route('wholesaler.orders.show', $order)
            ->with('success', 'Payment processed successfully!');
    }
}
