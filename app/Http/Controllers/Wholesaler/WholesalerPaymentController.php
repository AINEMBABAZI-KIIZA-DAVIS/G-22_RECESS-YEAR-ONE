<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class WholesalerPaymentController extends Controller
{
    /**
     * Display a list of pending payments for the authenticated wholesaler.
     */
    public function index()
    {
        $pendingPayments = Order::where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->with(['items.product']) // Eager load product details via order items
            ->latest()
            ->paginate(10);

        return view('wholesaler.payments.index', compact('pendingPayments'));
    }

    /**
     * Show a specific pending order for review and payment.
     *
     * @param  \App\Models\Order  $order
     */
    public function show(Order $order)
    {
        // Restrict access to order if not owned by current user or already paid
        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        return view('wholesaler.payments.show', compact('order'));
    }

    /**
     * Process payment for a specific pending order.
     *
     * @param  \App\Models\Order  $order
     * @param  \Illuminate\Http\Request  $request
     */
    public function pay(Order $order, Request $request)
    {
        // Restrict access if unauthorized or order already paid
        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        // Validate incoming request
        $request->validate([
            'payment_method' => 'required|string|max:50'
        ]);

        // Update order with payment info
        $order->update([
            'payment_status'     => 'paid',
            'payment_method'     => $request->payment_method,
            'payment_reference'  => 'PAY-' . now()->format('YmdHis')
        ]);

        return redirect()
            ->route('wholesaler.orders.show', $order)
            ->with('success', 'Payment processed successfully!');
    }
}
