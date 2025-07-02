@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-credit-card me-2"></i>Payment for Order #{{ $order->id }}
            </h4>
            <a href="{{ route('wholesaler.payments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Payments
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Payment Details</h5>
                    <p><strong>Order Amount:</strong> UGX {{ number_format($order->total_amount) }}</p>
                    <p><strong>Due Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Order Summary</h5>
                    <p><strong>Items:</strong> {{ $order->items->count() }} items</p>
                    <p><strong>Shipping:</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Contact:</strong> {{ $order->contact_number }}</p>
                </div>
            </div>

            <div class="mt-4">
                <h5>Payment Methods</h5>
                <form method="POST" action="{{ route('wholesaler.payments.pay', $order) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Select Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-select">
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_reference" class="form-label">Payment Reference</label>
                        <input type="text" name="payment_reference" id="payment_reference" class="form-control" required>
                        <small class="text-muted">Enter your payment reference number</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-credit-card me-1"></i>Process Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
