@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-cart-check me-2"></i>Order #{{ $order->id }}
            </h4>
            <a href="{{ route('wholesaler.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Orders
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Order Details</h5>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : 'success') }}">
                        {{ ucfirst($order->status) }}
                    </span></p>
                    <p><strong>Payment Status:</strong> <span class="badge bg-{{ $order->payment_status === 'pending' ? 'warning' : 'success' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span></p>
                </div>
                <div class="col-md-6">
                    <h5>Payment Details</h5>
                    <p><strong>Total Amount:</strong> UGX {{ number_format($order->total_amount) }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p><strong>Payment Reference:</strong> {{ $order->payment_reference }}</p>
                </div>
            </div>

            <div class="mt-4">
                <h5>Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->description }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>UGX {{ number_format($item->unit_price) }}</td>
                                <td>UGX {{ number_format($item->quantity * $item->unit_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>UGX {{ number_format($order->total_amount) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
