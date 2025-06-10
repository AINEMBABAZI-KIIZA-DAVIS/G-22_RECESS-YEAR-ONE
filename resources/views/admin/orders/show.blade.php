@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Order Details: #{{ $order->id }}</h2>
        <div>
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary-gradient me-2"><i class="fas fa-truck-fast me-2"></i>Update Status</a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Orders</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Items</h5>
                    <span class="badge bg-{{ strtolower($order->status) == 'delivered' ? 'success' : (strtolower($order->status) == 'cancelled' ? 'danger' : (strtolower($order->status) == 'shipped' ? 'info' : (strtolower($order->status) == 'processing' ? 'primary' : 'secondary'))) }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->product->sku ?? 'N/A' }}</td>
                                    <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                </tr>
                                @php $total += ($item->unit_price * $item->quantity); @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">${{ number_format($total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Customer & Shipping</h5>
                </div>
                <div class="card-body">
                    <dl>
                        <dt>Customer Name:</dt>
                        <dd>{{ $order->customer_name }}</dd>

                        <dt>Customer Email:</dt>
                        <dd>{{ $order->customer_email }}</dd>

                        <dt>Shipping Address:</dt>
                        <dd>{{ $order->shipping_address }}</dd>

                        <dt>Order Date:</dt>
                        <dd>{{ $order->created_at->format('M d, Y H:i A') }}</dd>

                        <dt>Last Updated:</dt>
                        <dd>{{ $order->updated_at->format('M d, Y H:i A') }}</dd>
                    </dl>
                </div>
            </div>
             <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                     <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary-gradient w-100">Save Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-primary-gradient { /* Ensure this class is defined in your main layout or here */
        background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        box-shadow: 0 2px 8px rgba(53, 122, 255, 0.10);
        transition: background 0.3s, box-shadow 0.3s;
    }
    .btn-primary-gradient:hover {
        background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
        color: #fff;
    }
    dl dt {
        font-weight: 600;
        color: #495057;
    }
    .card-header h5 {
        font-size: 1.1rem;
    }
    .badge {
        font-size: 0.9em;
    }
</style>
@endpush