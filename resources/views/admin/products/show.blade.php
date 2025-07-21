@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Product Details: {{ $product->name }}</h2>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary-gradient me-2"><i class="fas fa-edit me-2"></i>Edit Product</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <dl class="row">
                        <dt class="col-sm-3">SKU:</dt>
                        <dd class="col-sm-9">{{ $product->sku }}</dd>

                        <dt class="col-sm-3">Name:</dt>
                        <dd class="col-sm-9">{{ $product->name }}</dd>

                        <dt class="col-sm-3">Description:</dt>
                        <dd class="col-sm-9">{{ $product->description ?: 'N/A' }}</dd>

                        <dt class="col-sm-3">Price:</dt>
                        <dd class="col-sm-9">UGX {{ number_format($product->price, 2) }}</dd>

                        <dt class="col-sm-3">Quantity in Stock:</dt>
                        <dd class="col-sm-9">{{ $product->quantity_in_stock }}</dd>

                        <dt class="col-sm-3">Low Stock Threshold:</dt>
                        <dd class="col-sm-9">{{ $product->low_stock_threshold ?? 'Not set' }}</dd>

                        <dt class="col-sm-3">Created At:</dt>
                        <dd class="col-sm-9">{{ $product->created_at->format('M d, Y H:i A') }}</dd>

                        <dt class="col-sm-3">Last Updated:</dt>
                        <dd class="col-sm-9">{{ $product->updated_at->format('M d, Y H:i A') }}</dd>
                    </dl>
                </div>
                <div class="col-md-4">
                    {{-- Placeholder for product image or other related info --}}
                    <div class="text-center p-3 border rounded bg-light">
                        <i class="fas fa-image fa-5x text-muted"></i>
                        <p class="mt-2 text-muted">Product Image Placeholder</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- You could add related information here, like recent orders containing this product --}}
    {{--
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Recent Orders</h5>
        </div>
        <div class="card-body">
            <p>Order history for this product will be displayed here.</p>
        </div>
    </div>
    --}}
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
</style>
@endpush