<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('items')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('items.product'); // Eager load products for order items
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     * (Typically for updating status)
     */
    public function edit(Order $order)
    {
        // For simplicity, we'll allow editing status directly on a dedicated view
        // or you could use a modal on the show page.
        // For now, let's assume a simple status update form.
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled']; // Example statuses
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     * (Typically for updating status)
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled', // Validate against your defined statuses
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.show', $order)
                         ->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * (Generally, orders are not hard deleted, but marked as cancelled or archived)
     * For this example, we'll implement a soft delete if your model uses it,
     * or a hard delete if not. Let's assume no soft deletes for now.
     * Consider if you truly want to delete orders or just change status to 'cancelled'.
     */
    public function destroy(Order $order)
    {
        // Before deleting, consider implications:
        // - Inventory adjustments?
        // - Related records?
        // For now, a simple delete:
        // $order->items()->delete(); // Delete related order items if cascade is not set up
        // $order->delete();

        // A safer approach is to mark as cancelled:
        if ($order->status !== 'cancelled') {
            $order->status = 'cancelled';
            $order->save();
            return redirect()->route('admin.orders.index')
                             ->with('success', 'Order marked as cancelled.');
        }

        // If you truly want to delete:
        // $order->delete();
        // return redirect()->route('admin.orders.index')
        //                  ->with('success', 'Order deleted successfully.');
        
        return redirect()->route('admin.orders.index')
                         ->with('info', 'Order is already cancelled or no delete action configured.');
    }
}