@extends('layouts.app')

@push('head')
@endpush

@section('header')
    <h2 class="fw-semibold fs-4" style="color: #2b2d42;">
        <i class="bi bi-grid-3x3-gap-fill me-2"></i>{{ __('Wholesaler Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-4 min-vh-100 product-card">
    <div class="container">
        <div class="bg-white shadow-lg rounded-3 p-4" style="border-top: 4px solid #e76f51;">
            <header class="mb-5 text-center dashboard-header py-5">
                <div class="position-relative mb-3">
                    <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80" class="rounded-circle border border-4 border-white shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border-color: #e76f51 !important;" alt="Wholesaler">
                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2" style="border: 3px solid white;"></span>
                </div>
                <h3 class="h3 fw-bold" style="color: #2b2d42;">Welcome back, {{ Auth::user()->name }}! <span class="wave">👋</span></h3>
                <p class="text-muted">Manage your wholesale orders and inventory with ease.</p>
            </header>

            <!-- Quick Actions -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <a href="{{ route('wholesaler.products.index') }}" class="card h-100 border-0 shadow-sm text-decoration-none hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-inline-flex mb-3" style="width: 60px; height: 60px; background: linear-gradient(145deg, rgba(231,111,81,0.1), rgba(231,111,81,0.2));">
                                <i class="fas fa-store fs-3 m-auto"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #2b2d42;">Shop Products</h5>
                            <p class="text-muted small mb-0">Browse and order products for your business.</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                            <span class="btn btn-sm" style="background-color: #e76f51; color: white; border-radius: 20px;">Shop Now <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('wholesaler.cart.show') }}" class="card h-100 border-0 shadow-sm text-decoration-none hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-inline-flex mb-3" style="width: 60px; height: 60px; background: linear-gradient(145deg, rgba(255,107,107,0.1), rgba(255,107,107,0.2));">
                                <i class="fas fa-shopping-cart fs-3 m-auto"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #2b2d42;">View Cart</h5>
                            <p class="text-muted small mb-0">See your current cart and proceed to checkout.</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                            <span class="btn btn-sm btn-outline-secondary" style="border-radius: 20px;">View Cart <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('wholesaler.checkout.index') }}" class="card h-100 border-0 shadow-sm text-decoration-none hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-inline-flex mb-3" style="width: 60px; height: 60px; background: linear-gradient(145deg, rgba(78,191,196,0.1), rgba(78,191,196,0.2));">
                                <i class="fas fa-credit-card fs-3 m-auto"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #2b2d42;">Checkout</h5>
                            <p class="text-muted small mb-0">Complete your purchase and place orders.</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                            <span class="btn btn-sm btn-outline-secondary" style="border-radius: 20px;">Checkout <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mt-5">
                <h4 class="h5 fw-bold mb-4" style="color: #2b2d42;">
                    <i class="bi bi-cart-check me-2"></i>Order Summary
                </h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #2b2d42;">Active Orders</h5>
                                <p class="text-muted small mb-3">Current orders in progress</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning text-dark me-2">{{ $activeOrders ?? 0 }}</span>
                                    <a href="{{ route('wholesaler.orders.index') }}" class="text-decoration-none text-muted">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #2b2d42;">Pending Payments</h5>
                                <p class="text-muted small mb-3">Amount due for current orders</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger text-white me-2">UGX {{ number_format($pendingPayments ?? 0) }}</span>
                                    <a href="{{ route('wholesaler.payments.index') }}" class="text-decoration-none text-muted">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #2b2d42;">Recent Orders</h5>
                                <p class="text-muted small mb-3">Completed orders in the last 30 days</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success text-white me-2">{{ $recentOrders ?? 0 }}</span>
                                    <a href="{{ route('wholesaler.orders.history') }}" class="text-decoration-none text-muted">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Availability -->
            <div class="mt-5">
                <h4 class="h5 fw-bold mb-4" style="color: #2b2d42;">
                    <i class="bi bi-box-seam me-2"></i>Stock Availability
                </h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #2b2d42;">In Stock Items</h5>
                                <p class="text-muted small mb-3">Products currently available</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success text-white me-2">{{ $inStockItems ?? 0 }}</span>
                                    <a href="{{ route('wholesaler.products.index') }}" class="text-decoration-none text-muted">
                                        View Products <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #2b2d42;">Low Stock Alert</h5>
                                <p class="text-muted small mb-3">Items running low on stock</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning text-dark me-2">{{ $lowStockItems ?? 0 }}</span>
                                    <a href="{{ route('wholesaler.products.low-stock') }}" class="text-decoration-none text-muted">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #2b2d42;">Out of Stock</h5>
                                <p class="text-muted small mb-3">Items currently unavailable</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger text-white me-2">{{ $outOfStockItems ?? 0 }}</span>
                                    <a href="{{ route('wholesaler.products.out-of-stock') }}" class="text-decoration-none text-muted">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Products -->
            <div class="mt-5">
                <h4 class="h5 fw-bold mb-4" style="color: #2b2d42;">
                    <i class="bi bi-box-seam me-2"></i>Available Products
                </h4>
                <div class="row g-4">
                    @foreach ($products as $product)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color: #2b2d42;">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">{{ $product->description }}</p>
                                <p class="fw-bold text-success mb-3">UGX {{ number_format($product->price) }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}" style="background: linear-gradient(145deg, {{ $product->stock > 0 ? 'rgba(78,191,196,0.1)' : 'rgba(217,4,41,0.1)' }}, {{ $product->stock > 0 ? 'rgba(78,191,196,0.2)' : 'rgba(217,4,41,0.2)' }});">
                                        {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </div>
                                    <span class="text-muted small">Stock: {{ $product->stock }}</span>
                                </div>
                                <form method="POST" action="{{ route('wholesaler.cart.add', ['productId' => $product->id]) }}" class="mt-auto" id="cartForm_{{ $product->id }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="price" value="{{ $product->price }}">
                                    <input type="hidden" name="stock" value="{{ $product->stock }}">
                                    
                                    @if($product->stock > 0)
                                        <button type="submit" class="btn w-100" style="background-color: #e76f51; color: white;">
                                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    @else
                                        <button type="button" class="btn w-100 btn-outline-secondary" disabled>
                                            <i class="bi bi-cart-x me-2"></i>Out of Stock
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all cart forms
    document.querySelectorAll('form[id^="cartForm_"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                // Disable the button to prevent double submission
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass"></i> Adding...';
                
                // Submit the form
                this.submit();
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 1rem;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    }
    .wave {
        animation-name: wave-animation;
        animation-duration: 2.5s;
        animation-iteration-count: infinite;
        transform-origin: 70% 70%;
        display: inline-block;
    }
    @keyframes wave-animation {
        0% { transform: rotate(0.0deg) }
        10% { transform: rotate(14.0deg) }
        20% { transform: rotate(-8.0deg) }
        30% { transform: rotate(14.0deg) }
        40% { transform: rotate(-4.0deg) }
        50% { transform: rotate(10.0deg) }
        60% { transform: rotate(0.0deg) }
        100% { transform: rotate(0.0deg) }
    }
</style>
@endpush
@endsection
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
