@extends('layouts.wholesaler_app')
@section('title', 'Order Confirmation')
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="mb-3" style="color:#a0522d;"><i class="bi bi-receipt me-2"></i>Order Confirmation</h2>
                        <p class="lead">Thank you for your order! Your order ID is <b>#{{ $order->id }}</b>.</p>
                        <p>Estimated Delivery: <b>{{ $order->delivery_eta ?? 'TBD' }}</b></p>
                        <hr>
                        <h5>Order Summary</h5>
                        <ul class="list-group mb-3">
                            @foreach($order->items as $item)
                                @php
                                    $itemTotal = $item->unit_price * $item->quantity;
                                    // Apply bulk discount if quantity >= 50
                                    if ($item->quantity >= 50) {
                                        $itemTotal *= 0.90;
                                    }
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                    <span>UGX {{ number_format($itemTotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold">UGX {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="mt-4">
                            <a href="#" onclick="window.print()" class="btn btn-outline-secondary me-2"><i
                                    class="bi bi-printer"></i> Print</a>
                            <a href="{{ route('wholesaler.export.orders', ['type' => 'pdf']) }}"
                                class="btn btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i> Download PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection