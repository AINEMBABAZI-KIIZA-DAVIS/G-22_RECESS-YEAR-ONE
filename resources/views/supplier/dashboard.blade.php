@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4" style="color: #2b2d42;">
        <i class="bi bi-grid-3x3-gap-fill me-2"></i>{{ __('Supplier Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-4 min-vh-100" style="background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container">
        <div class="bg-white shadow-lg rounded-3 p-4" style="border-top: 4px solid #e76f51;">

            <!-- Header -->
            <header class="mb-4 text-center">
                <div class="position-relative mb-3">
                    <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80" class="rounded-circle border border-4 border-white shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border-color: #e76f51 !important;" alt="Supplier">
                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2" style="border: 3px solid white;"></span>
                </div>
                <h3 class="h4 fw-bold" style="color: #2b2d42;">Welcome back, {{ Auth::user()->name }}! <span class="wave">👋</span></h3>
                <p class="text-muted">Manage your bakery's supply chain with real-time updates and easy tools.</p>
            </header>

            <!-- Statistics Section -->
            <!-- Stats Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="h-100 p-4 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #fff8f6 0%, #fff0ed 100%); border-left: 4px solid #e76f51;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-uppercase" style="color: #e76f51; font-weight: 600;">Your Pending Requests</small>
                                <div class="fs-2 fw-bold mt-1" style="color: #2b2d42;">{{ $pendingRequestsCount }}</div>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm" style="color: #e76f51;">
                                <i class="fas fa-hourglass-half fs-4"></i>
                            </div>
                        </div>
                        @if($pendingRequestsCount > 0)
                        <div class="mt-3">
                            <a href="{{ route('supplier.requests.index', ['status' => 'pending']) }}" class="text-decoration-none small" style="color: #e76f51;">
                                View all pending requests <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="h-100 p-4 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #3b82f6;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-uppercase" style="color: #3b82f6; font-weight: 600;">Confirmed</small>
                                <div class="fs-2 fw-bold mt-1" style="color: #2b2d42;">{{ $confirmedRequestsCount }}</div>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm" style="color: #3b82f6;">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted"><i class="bi bi-arrow-up-circle-fill text-success"></i> 8% from last month</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="h-100 p-4 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 4px solid #10b981;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-uppercase" style="color: #10b981; font-weight: 600;">Fulfilled</small>
                                <div class="fs-2 fw-bold mt-1" style="color: #2b2d42;">{{ $fulfilledRequestsCount }}</div>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm" style="color: #10b981;">
                                <i class="fas fa-truck fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted"><i class="bi bi-arrow-up-circle-fill text-success"></i> 15% from last month</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h5 class="mt-5 mb-3 fw-bold" style="color: #2b2d42;">Quick Actions</h5>
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.create') }}" class="card h-100 border-0 shadow-sm text-decoration-none hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-inline-flex mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-plus-circle fs-3 m-auto"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #2b2d42;">New Supply Request</h5>
                            <p class="text-muted small mb-0">Start a new request from scratch with our easy form.</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                            <span class="btn btn-sm" style="background-color: #e76f51; color: white; border-radius: 20px;">Create Now <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.list') }}" class="card h-100 border-0 shadow-sm text-decoration-none hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 d-inline-flex mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-list-alt fs-3 m-auto"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #2b2d42;">My Requests</h5>
                            <p class="text-muted small mb-0">Track all your request statuses in one place.</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                            <span class="btn btn-sm btn-outline-secondary" style="border-radius: 20px;">View All <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.confirmed') }}" class="card h-100 border-0 shadow-sm text-decoration-none hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-inline-flex mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-thumbs-up fs-3 m-auto"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #2b2d42;">Confirmed Orders</h5>
                            <p class="text-muted small mb-0">See what's been approved and ready for fulfillment.</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                            <span class="btn btn-sm btn-outline-secondary" style="border-radius: 20px;">View Orders <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($recentRequests->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white border-0 pt-4">
                            <h5 class="mb-0" style="color: #2b2d42;">
                                <i class="bi bi-clock-history me-2"></i>Your Recent Requests
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($recentRequests as $request)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $icon = 'bi-hourglass';
                                            $color = '#ff9800';
                                            if($request->status === 'confirmed_by_manufacturer') {
                                                $icon = 'bi-check-circle';
                                                $color = '#4caf50';
                                            } elseif($request->status === 'fulfilled') {
                                                $icon = 'bi-check2-all';
                                                $color = '#2196f3';
                                            } elseif($request->status === 'rejected') {
                                                $icon = 'bi-x-circle';
                                                $color = '#f44336';
                                            }
                                        @endphp
                                        <div class="rounded-circle p-2 me-3" style="background-color: {{ $color }}20;">
                                            <i class="bi {{ $icon }}" style="color: {{ $color }};"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0" style="color: #2b2d42;">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}: {{ $request->product_name }}
                                                </h6>
                                                <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 text-muted small">
                                                Quantity: {{ $request->quantity }}
                                                @if($request->notes)
                                                    • {{ Str::limit($request->notes, 50) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-center py-3">
                            <a href="{{ route('supplier.requests.index') }}" class="text-decoration-none" style="color: #e76f51;">
                                View all your requests <i class="bi bi-arrow-right"></i>
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
