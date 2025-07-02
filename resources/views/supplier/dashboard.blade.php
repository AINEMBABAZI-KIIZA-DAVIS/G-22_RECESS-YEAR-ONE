@extends('layouts.app')

@push('styles')
<style>
    .bakery-bg {
        background: linear-gradient(rgba(255, 245, 235, 0.95), rgba(255, 245, 235, 0.95)), 
                    url('https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .breadcrumb {
        background-color: #fff8f0;
        border-radius: 20px;
        padding: 0.5rem 1rem;
    }
    .breadcrumb-item a {
        color: #e76f51;
        text-decoration: none;
    }
    .breadcrumb-item.active {
        color: #2b2d42;
        font-weight: 500;
    }
</style>
@endpush

@section('header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4" style="color: #2b2d42;">
            <i class="bi bi-house-door-fill me-2" style="color: #e76f51;"></i>{{ __('Supplier Dashboard') }}
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="py-4 min-vh-100 bakery-bg">
    <div class="container">
        <div class="bg-white shadow-lg rounded-3 p-4" style="border-top: 4px solid #e76f51; background-color: #fff9f5;">

            <!-- Header -->
            <header class="mb-4 text-center">
                <div class="position-relative mb-3">
                    <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80" class="rounded-circle border border-4 border-white shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border-color: #e76f51 !important;" alt="Supplier">
                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2" style="border: 3px solid white;"></span>
                </div>
                <h3 class="h4 fw-bold" style="color: #2b2d42;">Welcome back, {{ Auth::user()->name }}! <span class="wave">👋</span></h3>
                <p class="text-muted">Manage your bakery's supply chain with real-time updates and easy tools.</p>
            </header>

            <!-- Bakery Stats Cards -->
            <div class="row g-4 mb-5">
                <!-- Pending Requests Card -->
                <div class="col-md-4">
                    <div class="h-100 p-4 rounded-3 shadow-sm card-hover" style="background: linear-gradient(135deg, #fff5f0 0%, #ffebdd 100%); border-left: 4px solid #e76f51; position: relative; overflow: hidden;">
                        <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                            <i class="fas fa-bread-slice" style="font-size: 6rem; color: #e76f51;"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center position-relative">
                            <div>
                                <small class="text-uppercase" style="color: #e76f51; font-weight: 600; letter-spacing: 0.5px;">Your Pending Requests</small>
                                <div class="display-5 fw-bold mt-1" style="color: #2b2d42;">{{ $pendingRequestsCount }}</div>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm" style="color: #e76f51; border: 2px solid #ffd8c9;">
                                <i class="fas fa-hourglass-half fs-4"></i>
                            </div>
                        </div>
                        @if($pendingRequestsCount > 0)
                        <div class="mt-3 position-relative">
                            <a href="{{ route('supplier.requests.pending') }}" class="btn btn-sm" style="background-color: #e76f51; color: white; border-radius: 20px; padding: 0.25rem 1rem;">
                                View All <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Confirmed Orders Card -->
                <div class="col-md-4">
                    <div class="h-100 p-4 rounded-3 shadow-sm card-hover" style="background: linear-gradient(135deg, #f0f7ff 0%, #e0f0ff 100%); border-left: 4px solid #5a8dee; position: relative; overflow: hidden;">
                        <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                            <i class="fas fa-check-circle" style="font-size: 6rem; color: #5a8dee;"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center position-relative">
                            <div>
                                <small class="text-uppercase" style="color: #5a8dee; font-weight: 600; letter-spacing: 0.5px;">Confirmed Orders</small>
                                <div class="display-5 fw-bold mt-1" style="color: #2b2d42;">{{ $confirmedRequestsCount }}</div>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm" style="color: #5a8dee; border: 2px solid #d0e1ff;">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3 position-relative">
                            <a href="{{ route('supplier.requests.confirmed') }}" class="btn btn-sm" style="background-color: #5a8dee; color: white; border-radius: 20px; padding: 0.25rem 1rem;">
                                View All <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Fulfilled Orders Card -->
                <div class="col-md-4">
                    <div class="h-100 p-4 rounded-3 shadow-sm card-hover" style="background: linear-gradient(135deg, #f0fff4 0%, #e0ffe7 100%); border-left: 4px solid #10b981; position: relative; overflow: hidden;">
                        <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                            <i class="fas fa-truck" style="font-size: 6rem; color: #10b981;"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center position-relative">
                            <div>
                                <small class="text-uppercase" style="color: #10b981; font-weight: 600; letter-spacing: 0.5px;">Fulfilled Orders</small>
                                <div class="display-5 fw-bold mt-1" style="color: #2b2d42;">{{ $fulfilledRequestsCount }}</div>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm" style="color: #10b981; border: 2px solid #b8f5d0;">
                                <i class="fas fa-truck fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3 position-relative">
                            <a href="{{ route('supplier.requests.fulfilled') }}" class="btn btn-sm" style="background-color: #10b981; color: white; border-radius: 20px; padding: 0.25rem 1rem;">
                                View All <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bakery Quick Actions -->
            <div class="mt-5 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0" style="color: #2b2d42; position: relative; display: inline-block;">
                        <span style="position: relative; z-index: 1; background: #fff9f5; padding-right: 15px;">
                            <i class="fas fa-bolt me-2" style="color: #e76f51;"></i>Quick Actions
                        </span>
                        <span style="position: absolute; bottom: 5px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, #ffd8c9, #ffb79a); z-index: 0; border-radius: 4px;"></span>
                    </h5>
                    <div class="text-end">
                        <a href="{{ route('supplier.requests.index') }}" class="text-decoration-none small" style="color: #e76f51;">
                            View All Requests <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- New Request Card -->
                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.create') }}" class="card h-100 border-0 text-decoration-none card-hover" style="background: linear-gradient(135deg, #fff9f5 0%, #fff0e8 100%); border-left: 4px solid #e76f51 !important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-white rounded-circle p-3 shadow-sm me-3" style="border: 2px solid #ffd8c9; color: #e76f51;">
                                    <i class="fas fa-plus-circle fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-semibold mb-1" style="color: #2b2d42;">New Supply Request</h5>
                                    <p class="text-muted small mb-0">Start a new request from scratch with our easy form.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-end p-3">
                            <span class="btn btn-sm" style="background-color: #e76f51; color: white; border-radius: 20px; padding: 0.35rem 1.25rem;">
                                Create Now <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- My Requests Card -->
                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.index') }}" class="card h-100 border-0 text-decoration-none card-hover" style="background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%); border-left: 4px solid #5a8dee !important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-white rounded-circle p-3 shadow-sm me-3" style="border: 2px solid #d0e1ff; color: #5a8dee;">
                                    <i class="fas fa-list-ul fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-semibold mb-1" style="color: #2b2d42;">My Requests</h5>
                                    <p class="text-muted small mb-0">Track all your request statuses in one place.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-end p-3">
                            <span class="btn btn-sm" style="background-color: #5a8dee; color: white; border-radius: 20px; padding: 0.35rem 1.25rem;">
                                View All <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Confirmed Orders Card -->
                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.confirmed') }}" class="card h-100 border-0 text-decoration-none card-hover" style="background: linear-gradient(135deg, #f5fff9 0%, #e8fff0 100%); border-left: 4px solid #10b981 !important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-white rounded-circle p-3 shadow-sm me-3" style="border: 2px solid #b8f5d0; color: #10b981;">
                                    <i class="fas fa-check-circle fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-semibold mb-1" style="color: #2b2d42;">Confirmed Orders</h5>
                                    <p class="text-muted small mb-0">See what's been approved and ready for fulfillment.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-end p-3">
                            <span class="btn btn-sm" style="background-color: #10b981; color: white; border-radius: 20px; padding: 0.35rem 1.25rem;">
                                View Orders <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity with Bakery Theme -->
            @if($recentRequests->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm mt-4 overflow-hidden" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold" style="color: #2b2d42; position: relative;">
                                    <span style="position: relative; z-index: 1; background: #fff9f5; padding-right: 15px;">
                                        <i class="fas fa-history me-2" style="color: #e76f51;"></i>Recent Activity
                                    </span>
                                    <span style="position: absolute; bottom: 3px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, #ffd8c9, #ffb79a); z-index: 0; border-radius: 4px;"></span>
                                </h5>
                                <a href="{{ route('supplier.requests.index') }}" class="text-decoration-none small" style="color: #e76f51;">
                                    View All Activity <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($recentRequests as $request)
                                <div class="list-group-item border-0 py-3 px-4" style="background-color: #fff9f5; border-bottom: 1px solid #ffede4 !important;">
                                    <div class="d-flex align-items-start">
                                        @php
                                            $icon = 'fa-hourglass-half';
                                            $color = '#e67e22';
                                            $bgColor = '#fdf0e8';
                                            $statusText = 'Pending';
                                            
                                            if($request->status === 'confirmed_by_manufacturer') {
                                                $icon = 'fa-check-circle';
                                                $color = '#27ae60';
                                                $bgColor = '#e8f8f0';
                                                $statusText = 'Confirmed';
                                            } elseif($request->status === 'fulfilled') {
                                                $icon = 'fa-truck';
                                                $color = '#2980b9';
                                                $bgColor = '#e8f2f9';
                                                $statusText = 'Fulfilled';
                                            } elseif($request->status === 'rejected') {
                                                $icon = 'fa-times-circle';
                                                $color = '#e74c3c';
                                                $bgColor = '#fdedec';
                                                $statusText = 'Rejected';
                                            }
                                        @endphp
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px; background-color: {{ $bgColor }};">
                                            <i class="fas {{ $icon }} fa-lg" style="color: {{ $color }};"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="mb-0 fw-semibold" style="color: #2b2d42;">
                                                    {{ $request->product_name }}
                                                </h6>
                                                <span class="badge rounded-pill px-3 py-1" style="background-color: {{ $bgColor }}; color: {{ $color }}; font-size: 0.7rem; font-weight: 600;">
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0 small text-muted">
                                                    <i class="fas fa-box-open me-1"></i> Qty: {{ $request->quantity }}
                                                    @if($request->notes)
                                                        <span class="ms-2">•</span>
                                                        <i class="fas fa-sticky-note ms-2 me-1"></i>{{ Str::limit($request->notes, 40) }}
                                                    @endif
                                                </p>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    <i class="far fa-clock me-1"></i>{{ $request->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-center py-3" style="border-top: 1px solid #ffede4 !important;">
                            <a href="{{ route('supplier.requests.index') }}" class="text-decoration-none fw-medium" style="color: #e76f51;">
                                <i class="fas fa-list-ul me-1"></i> View All Your Requests
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px; background-color: #fff9f5; border: 2px dashed #ffd8c9;">
                        <div class="card-body text-center p-5">
                            <div class="mb-3" style="font-size: 2.5rem; color: #ffd8c9;">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #2b2d42;">No Recent Activity</h5>
                            <p class="text-muted mb-4">Your recent supply requests will appear here</p>
                            <a href="{{ route('supplier.requests.create') }}" class="btn" style="background-color: #e76f51; color: white; border-radius: 20px; padding: 0.5rem 1.5rem;">
                                <i class="fas fa-plus-circle me-2"></i>Create Your First Request
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Base Styles */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
    }
    
    /* Card Hover Effects */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    .card-hover:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    
    .card-hover:hover:before {
        opacity: 1;
    }
    
    /* Stat Cards */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        z-index: 1;
        border: none !important;
    }
    
    .stat-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #e76f51, #ff9a8b);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
    }
    
    .stat-card:hover:before {
        opacity: 1;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .card-hover, .stat-card {
            border-radius: 10px;
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        h5 {
            font-size: 1.1rem;
        }
    }
    
    /* Animation for Empty State */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .empty-state-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Custom Badge Styles */
    .status-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.35em 0.8em;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Wave Animation */
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
    
    /* Button Hover Effects */
    .btn-hover-effect {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-hover-effect:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease, opacity 0.3s ease;
        opacity: 0;
    }
    
    .btn-hover-effect:hover:after {
        width: 200px;
        height: 200px;
        opacity: 1;
    }
</style>
@endpush
