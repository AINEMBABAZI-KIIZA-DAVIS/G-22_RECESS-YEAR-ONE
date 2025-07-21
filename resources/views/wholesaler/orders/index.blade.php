@extends('layouts.wholesaler_app')

@section('header')
    <h2 class="fw-semibold fs-4" style="color: var(--secondary-color);">
        <i class="bi bi-cart-check me-2"></i>{{ __('Active Orders') }}
    </h2>
@endsection

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-cart-check me-2"></i>Active Orders
            </h4>
            <a href="{{ route('wholesaler.orders.history') }}" class="btn btn-outline-secondary">
                View Order History
            </a>
        </div>
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No active orders found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'processing' ? 'warning' : 'danger') }}" style="background: linear-gradient(145deg, {{ $order->status === 'completed' ? 'rgba(78,191,196,0.1)' : ($order->status === 'processing' ? 'rgba(255,107,107,0.1)' : 'rgba(217,4,41,0.1)') }}, {{ $order->status === 'completed' ? 'rgba(78,191,196,0.2)' : ($order->status === 'processing' ? 'rgba(255,107,107,0.2)' : 'rgba(217,4,41,0.2)') }});">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </td>
                                <td>{{ $order->items->count() }} items</td>
                                <td>UGX {{ number_format($order->total_amount) }}</td>
                                <td>
                                    <a href="{{ route('wholesaler.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
