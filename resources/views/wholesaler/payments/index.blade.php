@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-credit-card me-2"></i>Pending Payments
            </h4>
            <a href="{{ route('wholesaler.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Orders
            </a>
        </div>
        <div class="card-body">
            @if($pendingPayments->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No pending payments found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Due Date</th>
                                <th>Amount Due</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingPayments as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>UGX {{ number_format($order->total_amount) }}</td>
                                <td>
                                    <span class="badge bg-warning">
                                        Pending
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('wholesaler.payments.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-credit-card me-1"></i>Pay Now
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $pendingPayments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
