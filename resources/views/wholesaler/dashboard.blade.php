@extends('layouts.wholesaler_app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4" style="color: var(--text-dark);">
            <i class="bi bi-house-door-fill me-2" style="color: var(--primary-color);"></i>{{ __('Wholesaler Dashboard') }}
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-5" style="color:var(--primary-color);">🍞 Wholesaler Dashboard</h1>
                <p class="lead">Welcome, {{ Auth::user()->name }}! Manage your orders, track inventory, and view analytics
                    here.</p>
            </div>
        </div>
        <div class="row g-4 mb-5">
            <!-- Monthly Spend Card -->
            <div class="col-md-3">
                <div class="h-100 p-4 rounded-3 shadow-sm card-hover"
                    style="background: linear-gradient(135deg, #fff8f0 0%, #ffebd6 100%); border-left: 4px solid var(--secondary-color); position: relative; overflow: hidden;">
                    <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                        <i class="fas fa-cart-shopping" style="font-size: 6rem; color: var(--secondary-color);"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div>
                            <small class="text-uppercase"
                                style="color: var(--secondary-color); font-weight: 600; letter-spacing: 0.5px;">Monthly Spend</small>
                            <div class="display-5 fw-bold mt-1" style="color: var(--text-dark);">UGX {{ number_format($monthlySpend, 2) }}</div>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm"
                            style="color: var(--secondary-color); border: 2px solid #ffe0b2;">
                            <i class="fas fa-cart-shopping fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avg. Fulfillment Card -->
            <div class="col-md-3">
                <div class="h-100 p-4 rounded-3 shadow-sm card-hover"
                    style="background: linear-gradient(135deg, #f5efe8 0%, #e8d9c8 100%); border-left: 4px solid var(--primary-color); position: relative; overflow: hidden;">
                    <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                        <i class="fas fa-clock" style="font-size: 6rem; color: var(--primary-color);"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div>
                            <small class="text-uppercase"
                                style="color: var(--primary-color); font-weight: 600; letter-spacing: 0.5px;">Avg. Fulfillment</small>
                            <div class="display-5 fw-bold mt-1" style="color: var(--text-dark);">
                                {{ $fulfillmentSpeed ? round($fulfillmentSpeed, 1) . ' hrs' : 'N/A' }}</div>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm"
                            style="color: var(--primary-color); border: 2px solid #d7ccc8;">
                            <i class="fas fa-clock fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Card -->
            <div class="col-md-3">
                <div class="h-100 p-4 rounded-3 shadow-sm card-hover"
                    style="background: linear-gradient(135deg, #f0e6d8 0%, #e0d0b8 100%); border-left: 4px solid #8d6e63; position: relative; overflow: hidden;">
                    <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                        <i class="fas fa-truck" style="font-size: 6rem; color: #8d6e63;"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div>
                            <small class="text-uppercase"
                                style="color: #8d6e63; font-weight: 600; letter-spacing: 0.5px;">Recent Orders</small>
                            <div class="display-5 fw-bold mt-1" style="color: var(--text-dark);">
                                {{ $orders->count() }}</div>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm"
                            style="color: #8d6e63; border: 2px solid #d7ccc8;">
                            <i class="fas fa-truck fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products Card -->
            <div class="col-md-3">
                <div class="h-100 p-4 rounded-3 shadow-sm card-hover"
                    style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-left: 4px solid #4caf50; position: relative; overflow: hidden;">
                    <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                        <i class="fas fa-star" style="font-size: 6rem; color: #4caf50;"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div>
                            <small class="text-uppercase"
                                style="color: #4caf50; font-weight: 600; letter-spacing: 0.5px;">Top Products</small>
                            <div class="display-5 fw-bold mt-1" style="color: var(--text-dark);">
                                {{ $topProducts->count() }}</div>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm"
                            style="color: #4caf50; border: 2px solid #c8e6c9;">
                            <i class="fas fa-star fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quick Actions -->
        <div class="mt-5 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0" style="color: var(--text-dark); position: relative; display: inline-block;">
                    <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                        <i class="fas fa-bolt me-2" style="color: var(--primary-color);"></i>Quick Actions
                    </span>
                    <span
                        style="position: absolute; bottom: 5px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                </h5>
            </div>
        </div>

        <div class="row g-4">
            <!-- Product Catalog Card -->
            <div class="col-md-4">
                <a href="{{ route('wholesaler.catalog') }}"
                    class="card h-100 border-0 text-decoration-none card-hover"
                    style="background: linear-gradient(135deg, var(--background-light) 0%, #ffebd6 100%); border-left: 4px solid var(--secondary-color) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                style="border: 2px solid #ffe0b2; color: var(--secondary-color);">
                                <i class="fas fa-grid fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">Product Catalog</h5>
                                <p class="text-muted small mb-0">Browse and search through available products.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 text-end p-3">
                        <span class="btn btn-sm btn-secondary-custom"
                            style="border-radius: 20px; padding: 0.35rem 1.25rem;">
                            Browse Now <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>
            
            <!-- Place Order Card -->
            <div class="col-md-4">
                <a href="{{ route('wholesaler.order.form') }}"
                    class="card h-100 border-0 text-decoration-none card-hover"
                    style="background: linear-gradient(135deg, var(--background-light) 0%, #e8d9c8 100%); border-left: 4px solid var(--primary-color) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                style="border: 2px solid #d7ccc8; color: var(--primary-color);">
                                <i class="fas fa-bag-plus fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">Place Order</h5>
                                <p class="text-muted small mb-0">Create a new order with our easy form.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 text-end p-3">
                        <span class="btn btn-sm btn-primary-custom"
                            style="border-radius: 20px; padding: 0.35rem 1.25rem;">
                            Order Now <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            <!-- Track Orders Card -->
            <div class="col-md-4">
                <a href="{{ route('wholesaler.orders.track') }}"
                    class="card h-100 border-0 text-decoration-none card-hover"
                    style="background: linear-gradient(135deg, var(--background-light) 0%, #e0d0b8 100%); border-left: 4px solid #8d6e63 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                style="border: 2px solid #d7ccc8; color: #8d6e63;">
                                <i class="fas fa-truck fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">Track Orders</h5>
                                <p class="text-muted small mb-0">Monitor the status of your orders.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 text-end p-3">
                        <span class="btn btn-sm"
                            style="background-color: #8d6e63; color: white; border-radius: 20px; padding: 0.35rem 1.25rem;">
                            Track Now <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <!-- Recent Activity -->
        <div class="row mt-5">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold" style="color: var(--text-dark); position: relative;">
                                <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                                    <i class="fas fa-history me-2" style="color: var(--primary-color);"></i>Recent Orders
                                </span>
                                <span style="position: absolute; bottom: 3px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                            </h5>
                            <div>
                                <a href="{{ route('wholesaler.order.form') }}" class="btn btn-sm btn-success me-2">
                                    <i class="bi bi-bag-plus"></i> Place Order
                                </a>
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color: var(--primary-color); font-weight: 600;">Order #</th>
                                        <th style="color: var(--primary-color); font-weight: 600;">Status</th>
                                        <th style="color: var(--primary-color); font-weight: 600;">Total</th>
                                        <th style="color: var(--primary-color); font-weight: 600;">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td><a href="{{ route('wholesaler.orders.show', $order->id) }}" style="color: var(--primary-color); text-decoration: none;">#{{ $order->id }}</a></td>
                                            <td>
                                                <span class="badge rounded-pill px-3 py-1" 
                                                    style="background-color: {{ $order->status == 'Delivered' ? '#4caf50' : ($order->status == 'Pending' ? '#ff9800' : '#2196f3') }}; color: white; font-size: 0.7rem; font-weight: 600;">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td style="font-weight: 600;">UGX {{ number_format($order->total_amount, 2) }}</td>
                                            <td style="color: var(--text-muted);">{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                        <h5 class="mb-0 fw-bold" style="color: var(--text-dark); position: relative;">
                            <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                                <i class="fas fa-star me-2" style="color: var(--primary-color);"></i>Top Products
                            </span>
                            <span style="position: absolute; bottom: 3px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($topProducts as $product)
                                <div class="list-group-item border-0 py-3 px-4" style="background-color: var(--background-light); border-bottom: 1px solid #e8d9c8 !important;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span style="font-weight: 600; color: var(--text-dark);">{{ $product->name }}</span>
                                        <span class="badge rounded-pill px-3 py-1" style="background-color: var(--primary-color); color: white; font-size: 0.7rem; font-weight: 600;">
                                            Sold: {{ $product->sold ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection