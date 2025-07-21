@extends('layouts.supplier_app')

@push('styles')
    <style>
        :root {
            --primary-color: #a0522d; /* Sienna brown */
            --primary-light: #d2a079;
            --primary-dark: #6d3519;
            --secondary-color: #ff9800; /* Orange */
            --secondary-light: #ffc947;
            --secondary-dark: #c66900;
            --background-light: #fff9f2;
            --background-dark: #f5e6d8;
            --text-dark: #2b2d42;
            --text-light: #5a5a5a;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #f44336;
            --info-color: #2196f3;
        }

        .bakery-bg {
            background: linear-gradient(rgba(255, 249, 242, 0.95), rgba(255, 249, 242, 0.95)),
                url('https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .breadcrumb {
            background-color: var(--background-light);
            border-radius: 20px;
            padding: 0.5rem 1rem;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--text-dark);
            font-weight: 500;
        }

        /* Base Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
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
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
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
            background: var(--primary-light);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }

            .card-hover,
            .stat-card {
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
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
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
            0% {
                transform: rotate(0.0deg)
            }
            10% {
                transform: rotate(14.0deg)
            }
            20% {
                transform: rotate(-8.0deg)
            }
            30% {
                transform: rotate(14.0deg)
            }
            40% {
                transform: rotate(-4.0deg)
            }
            50% {
                transform: rotate(10.0deg)
            }
            60% {
                transform: rotate(0.0deg)
            }
            100% {
                transform: rotate(0.0deg)
            }
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
            background: rgba(255, 255, 255, 0.2);
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

        /* Custom buttons */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            color: white;
        }

        .btn-secondary-custom {
            background-color: var(--secondary-color);
            color: white;
            border: none;
        }

        .btn-secondary-custom:hover {
            background-color: var(--secondary-dark);
            color: white;
        }

        /* Status colors */
        .status-pending {
            background-color: #fff3e0;
            color: #ff6d00;
        }

        .status-confirmed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-fulfilled {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .status-rejected {
            background-color: #ffebee;
            color: #c62828;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4" style="color: var(--text-dark);">
            <i class="bi bi-house-door-fill me-2" style="color: var(--primary-color);"></i>{{ __('Supplier Dashboard') }}
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div class="py-4 min-vh-100 bakery-bg">
        <div class="container">
            <div class="bg-white shadow-lg rounded-3 p-4" style="border-top: 4px solid var(--primary-color); background-color: var(--background-light);">

                <!-- Header -->
                <header class="mb-4 text-center">
                    <div class="position-relative mb-3">
                        <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80"
                            class="rounded-circle border border-4 border-white shadow-sm"
                            style="width: 100px; height: 100px; object-fit: cover; border-color: var(--primary-color) !important;"
                            alt="Supplier">
                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2"
                            style="border: 3px solid white;"></span>
                    </div>
                    <h3 class="h4 fw-bold" style="color: var(--text-dark);">Welcome back, {{ Auth::user()->name }}! <span
                            class="wave">👋</span></h3>
                    <p class="text-muted">Manage your bakery's supply chain with real-time updates and easy tools.</p>
                </header>

                <!-- Bakery Stats Cards -->
                <div class="row g-4 mb-5">
                    <!-- Pending Requests Card -->
                    <div class="col-md-4">
                        <div class="h-100 p-4 rounded-3 shadow-sm card-hover"
                            style="background: linear-gradient(135deg, #fff8f0 0%, #ffebd6 100%); border-left: 4px solid var(--secondary-color); position: relative; overflow: hidden;">
                            <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                                <i class="fas fa-bread-slice" style="font-size: 6rem; color: var(--secondary-color);"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-center position-relative">
                                <div>
                                    <small class="text-uppercase"
                                        style="color: var(--secondary-color); font-weight: 600; letter-spacing: 0.5px;">Your Pending
                                        Requests</small>
                                    <div class="display-5 fw-bold mt-1" style="color: var(--text-dark);">{{ $pendingRequestsCount }}</div>
                                </div>
                                <div class="bg-white rounded-circle p-3 shadow-sm"
                                    style="color: var(--secondary-color); border: 2px solid #ffe0b2;">
                                    <i class="fas fa-hourglass-half fs-4"></i>
                                </div>
                            </div>
                            @if($pendingRequestsCount > 0)
                                <div class="mt-3 position-relative">
                                    <a href="{{ route('supplier.requests.pending') }}" class="btn btn-sm btn-secondary-custom"
                                        style="border-radius: 20px; padding: 0.25rem 1rem;">
                                        View All <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Confirmed Orders Card -->
                    <div class="col-md-4">
                        <div class="h-100 p-4 rounded-3 shadow-sm card-hover"
                            style="background: linear-gradient(135deg, #f5efe8 0%, #e8d9c8 100%); border-left: 4px solid var(--primary-color); position: relative; overflow: hidden;">
                            <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                                <i class="fas fa-check-circle" style="font-size: 6rem; color: var(--primary-color);"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-center position-relative">
                                <div>
                                    <small class="text-uppercase"
                                        style="color: var(--primary-color); font-weight: 600; letter-spacing: 0.5px;">Confirmed
                                        Orders</small>
                                    <div class="display-5 fw-bold mt-1" style="color: var(--text-dark);">
                                        {{ $confirmedRequestsCount }}</div>
                                </div>
                                <div class="bg-white rounded-circle p-3 shadow-sm"
                                    style="color: var(--primary-color); border: 2px solid #d7ccc8;">
                                    <i class="fas fa-check-circle fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-3 position-relative">
                                <a href="{{ route('supplier.requests.confirmed') }}" class="btn btn-sm btn-primary-custom"
                                    style="border-radius: 20px; padding: 0.25rem 1rem;">
                                    View All <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Fulfilled Orders Card -->
                    <div class="col-md-4">
                        <div class="h-100 p-4 rounded-3 shadow-sm card-hover"
                            style="background: linear-gradient(135deg, #f0e6d8 0%, #e0d0b8 100%); border-left: 4px solid #8d6e63; position: relative; overflow: hidden;">
                            <div class="position-absolute" style="top: -10px; right: -10px; opacity: 0.1;">
                                <i class="fas fa-truck" style="font-size: 6rem; color: #8d6e63;"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-center position-relative">
                                <div>
                                    <small class="text-uppercase"
                                        style="color: #8d6e63; font-weight: 600; letter-spacing: 0.5px;">Fulfilled
                                        Orders</small>
                                    <div class="display-5 fw-bold mt-1" style="color: var(--text-dark);">
                                        {{ $fulfilledRequestsCount }}</div>
                                </div>
                                <div class="bg-white rounded-circle p-3 shadow-sm"
                                    style="color: #8d6e63; border: 2px solid #d7ccc8;">
                                    <i class="fas fa-truck fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-3 position-relative">
                                <a href="{{ route('supplier.requests.fulfilled') }}" class="btn btn-sm"
                                    style="background-color: #8d6e63; color: white; border-radius: 20px; padding: 0.25rem 1rem;">
                                    View All <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bakery Quick Actions -->
                <div class="mt-5 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0" style="color: var(--text-dark); position: relative; display: inline-block;">
                            <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                                <i class="fas fa-bolt me-2" style="color: var(--primary-color);"></i>Quick Actions
                            </span>
                            <span
                                style="position: absolute; bottom: 5px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                        </h5>
                        <div class="text-end">
                            <a href="{{ route('supplier.requests.index') }}" class="text-decoration-none small"
                                style="color: var(--primary-color);">
                                View All Requests <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- New Request Card -->
                    <div class="col-md-4">
                        <a href="{{ route('supplier.requests.create') }}"
                            class="card h-100 border-0 text-decoration-none card-hover"
                            style="background: linear-gradient(135deg, var(--background-light) 0%, #ffebd6 100%); border-left: 4px solid var(--secondary-color) !important;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                        style="border: 2px solid #ffe0b2; color: var(--secondary-color);">
                                        <i class="fas fa-plus-circle fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">New Supply Request</h5>
                                        <p class="text-muted small mb-0">Start a new request from scratch with our easy
                                            form.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 text-end p-3">
                                <span class="btn btn-sm btn-secondary-custom"
                                    style="border-radius: 20px; padding: 0.35rem 1.25rem;">
                                    Create Now <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Chat with Admin Card -->
                    <div class="col-md-4">
                        <a href="{{ route('supplier.chat.admin') }}"
                            class="card h-100 border-0 text-decoration-none card-hover"
                            style="background: linear-gradient(135deg, var(--background-light) 0%, #e8d9c8 100%); border-left: 4px solid var(--primary-color) !important;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                        style="border: 2px solid #d7ccc8; color: var(--primary-color);">
                                        <i class="fas fa-comments fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">Chat with Admin</h5>
                                        <p class="text-muted small mb-0">Get support or ask questions directly to the admin.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 text-end p-3">
                                <span class="btn btn-sm btn-primary-custom"
                                    style="border-radius: 20px; padding: 0.35rem 1.25rem;">
                                    Chat Now <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- My Requests Card -->
                    <div class="col-md-4">
                        <a href="{{ route('supplier.requests.index') }}"
                            class="card h-100 border-0 text-decoration-none card-hover"
                            style="background: linear-gradient(135deg, var(--background-light) 0%, #e0d0b8 100%); border-left: 4px solid #8d6e63 !important;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                        style="border: 2px solid #d7ccc8; color: #8d6e63;">
                                        <i class="fas fa-list-ul fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">My Requests</h5>
                                        <p class="text-muted small mb-0">Track all your request statuses in one place.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 text-end p-3">
                                <span class="btn btn-sm"
                                    style="background-color: #8d6e63; color: white; border-radius: 20px; padding: 0.35rem 1.25rem;">
                                    View All <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                @if($recentRequests->count() > 0)
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mt-4 overflow-hidden" style="border-radius: 12px;">
                                <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold" style="color: var(--text-dark); position: relative;">
                                            <span
                                                style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                                                <i class="fas fa-history me-2" style="color: var(--primary-color);"></i>Recent Activity
                                            </span>
                                            <span
                                                style="position: absolute; bottom: 3px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                                        </h5>
                                        <a href="{{ route('supplier.requests.index') }}" class="text-decoration-none small"
                                            style="color: var(--primary-color);">
                                            View All Activity <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach($recentRequests as $request)
                                            <div class="list-group-item border-0 py-3 px-4"
                                                style="background-color: var(--background-light); border-bottom: 1px solid #e8d9c8 !important;">
                                                <div class="d-flex align-items-start">
                                                    @php
                                                        $statusClass = 'status-pending';
                                                        $icon = 'fa-hourglass-half';
                                                        $statusText = 'Pending';

                                                        if ($request->status === 'confirmed_by_manufacturer') {
                                                            $statusClass = 'status-confirmed';
                                                            $icon = 'fa-check-circle';
                                                            $statusText = 'Confirmed';
                                                        } elseif ($request->status === 'fulfilled') {
                                                            $statusClass = 'status-fulfilled';
                                                            $icon = 'fa-truck';
                                                            $statusText = 'Fulfilled';
                                                        } elseif ($request->status === 'rejected') {
                                                            $statusClass = 'status-rejected';
                                                            $icon = 'fa-times-circle';
                                                            $statusText = 'Rejected';
                                                        }
                                                    @endphp
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 {{ $statusClass }}"
                                                        style="width: 42px; height: 42px;">
                                                        <i class="fas {{ $icon }} fa-lg"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <h6 class="mb-0 fw-semibold" style="color: var(--text-dark);">
                                                                {{ $request->product_name }}
                                                            </h6>
                                                            <span class="badge rounded-pill px-3 py-1 {{ $statusClass }}"
                                                                style="font-size: 0.7rem; font-weight: 600;">
                                                                {{ $statusText }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-0 small text-muted">
                                                                <i class="fas fa-box-open me-1"></i> Qty: {{ $request->quantity }}
                                                                @if($request->notes)
                                                                    <span class="ms-2">•</span>
                                                                    <i
                                                                        class="fas fa-sticky-note ms-2 me-1"></i>{{ Str::limit($request->notes, 40) }}
                                                                @endif
                                                            </p>
                                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                                <i
                                                                    class="far fa-clock me-1"></i>{{ $request->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 text-center py-3"
                                    style="border-top: 1px solid #e8d9c8 !important;">
                                    <a href="{{ route('supplier.requests.index') }}" class="text-decoration-none fw-medium"
                                        style="color: var(--primary-color);">
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
                            <div class="card border-0 shadow-sm mt-4"
                                style="border-radius: 12px; background-color: var(--background-light); border: 2px dashed var(--primary-light);">
                                <div class="card-body text-center p-5">
                                    <div class="mb-3 empty-state-icon" style="font-size: 2.5rem; color: var(--primary-light);">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h5 class="fw-semibold mb-2" style="color: var(--text-dark);">No Recent Activity</h5>
                                    <p class="text-muted mb-4">Your recent supply requests will appear here</p>
                                    <a href="{{ route('supplier.requests.create') }}" class="btn btn-primary-custom"
                                        style="border-radius: 20px; padding: 0.5rem 1.5rem;">
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