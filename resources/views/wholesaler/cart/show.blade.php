@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4" style="color: var(--secondary-color);">
        <i class="bi bi-cart-check me-2"></i>{{ __('Shopping Cart') }}
    </h2>
@endsection

@section('content')
<div class="py-4 min-vh-100 product-card">
    <div class="container">
        <div class="bg-white shadow-lg rounded-3 p-4" style="border-top: 4px solid #e76f51;">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover" style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">Stock: {{ $item->product->stock }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>UGX {{ number_format($item->product->price) }}</td>
                                <td>
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control" style="width: 80px;">
                                    </form>
                                </td>
                                <td>UGX {{ number_format($item->product->price * $item->quantity) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: var(--danger-color); color: white;" onclick="return confirm('Are you sure you want to remove this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="{{ route('wholesaler.products.index') }}" class="btn btn-outline-primary" style="border-color: var(--primary-color); color: var(--primary-color);">
                        <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <h4 class="fw-bold mb-3">Total: UGX {{ number_format($items->sum(function($item) { return $item->product->price * $item->quantity; })) }}</h4>
                    <a href="{{ route('wholesaler.checkout.index') }}" class="btn btn-primary">
                        Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .form-control {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
@endsection
