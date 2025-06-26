@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-primary">
        {{ __('Wholesaler Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-4 bg-light min-vh-100">
    <div class="container">
        <div class="bg-white shadow rounded p-4">

            <!-- Header -->
            <header class="mb-4">
                <h3 class="h4 fw-bold text-primary">Welcome, {{ Auth::user()->name }} 👋</h3>
                <p class="text-muted">Manage your wholesale orders and inventory with ease.</p>
            </header>

            <!-- Quick Actions -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <a href="{{ route('wholesaler.products.index') }}" class="d-block text-decoration-none p-3 rounded bg-primary-subtle border-start border-primary border-4 shadow-sm text-primary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 fw-bold">Shop Products</h5>
                            <i class="fas fa-store fs-3 text-primary"></i>
                        </div>
                        <p class="mb-0 small text-muted">Browse and order products for your business.</p>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('wholesaler.cart.show') }}" class="d-block text-decoration-none p-3 rounded bg-primary-subtle border-start border-info border-4 shadow-sm text-primary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 fw-bold">View Cart</h5>
                            <i class="fas fa-shopping-cart fs-3 text-info"></i>
                        </div>
                        <p class="mb-0 small text-muted">See your current cart and proceed to checkout.</p>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('wholesaler.checkout.index') }}" class="d-block text-decoration-none p-3 rounded bg-primary-subtle border-start border-success border-4 shadow-sm text-primary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 fw-bold">Checkout</h5>
                            <i class="fas fa-credit-card fs-3 text-success"></i>
                        </div>
                        <p class="mb-0 small text-muted">Complete your purchase and place orders.</p>
                    </a>
                </div>
            </div>

            <!-- Product Section -->
            <div>
                <h4 class="h5 fw-bold text-primary mb-3">Available Products</h4>
                <div class="row g-4">
                    @foreach ($products as $product)
                    <div class="col-md-4">
                        <div class="bg-white border border-light rounded shadow-sm p-3 h-100 d-flex flex-column">
                            <div class="bg-light rounded overflow-hidden mb-3 text-center" style="height: 160px;">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid h-100 object-fit-cover">
                            </div>
                            <h5 class="fw-bold text-primary">{{ $product->name }}</h5>
                            <p class="text-muted small">{{ $product->description }}</p>
                            <p class="fw-semibold text-success mb-3">UGX {{ number_format($product->price) }}</p>
                            <form method="POST" action="{{ route('wholesaler.cart.add', ['productId' => $product->id]) }}" class="mt-auto">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
