@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6" style="color:var(--primary-color); font-weight: 700;">
                        <i class="fas fa-shopping-cart me-3"></i>Order Management
                    </h1>
                    <p class="text-muted mb-0">Track and manage customer orders</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Orders</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label">Filter by Status:</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Orders</option>
                        <option value="attention" {{ request('status') == 'attention' ? 'selected' : '' }}>
                            <i class="fas fa-exclamation-triangle"></i> Needs Attention
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            <i class="fas fa-clock"></i> Pending
                        </option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                            <i class="fas fa-cog"></i> Processing
                        </option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>
                            <i class="fas fa-truck"></i> Shipped
                        </option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                            <i class="fas fa-check-circle"></i> Delivered
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            <i class="fas fa-times-circle"></i> Cancelled
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    @if(request('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Orders List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left: 24px;">Order ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Total Items</th>
                            <th>Order Date</th>
                            <th class="text-end" style="padding-right: 24px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr class="{{ in_array($order->status, ['pending', 'processing']) ? 'table-warning' : '' }}">
                            <td style="padding-left: 24px;">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-shopping-bag text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold">#{{ $order->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">{{ $order->customer_name }}</div>
                                    <div class="text-muted small">{{ $order->customer_phone ?? 'No phone' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $order->customer_email }}</span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                        'shipped' => 'info',
                                        'processing' => 'primary',
                                        'pending' => 'warning',
                                        'attention' => 'warning'
                                    ];
                                    $statusIcons = [
                                        'delivered' => 'check-circle',
                                        'cancelled' => 'times-circle',
                                        'shipped' => 'truck',
                                        'processing' => 'cog',
                                        'pending' => 'clock',
                                        'attention' => 'exclamation-triangle'
                                    ];
                                    $color = $statusColors[strtolower($order->status)] ?? 'secondary';
                                    $icon = $statusIcons[strtolower($order->status)] ?? 'question-circle';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="fas fa-{{ $icon }} me-1"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $order->items->sum('quantity') }} items</span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ $order->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td class="text-end" style="padding-right: 24px;">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Update Status">
                                        <i class="fas fa-truck-fast"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to mark this order as cancelled?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Cancel Order">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-shopping-cart" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">No orders found.</p>
                                    <small>Orders will appear here once customers start placing them.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center py-3 border-top">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background: var(--background-light);
        transform: scale(1.01);
    }
    
    .table-warning {
        background: rgba(255, 193, 7, 0.05);
    }
    
    .badge {
        font-size: 0.85em;
        padding: 6px 12px;
        border-radius: 20px;
    }
    
    .pagination {
        gap: 4px;
    }
    
    .page-link {
        border-radius: 8px;
        border: none;
        color: var(--primary-color);
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background: var(--background-light);
        color: var(--primary-color);
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background: var(--gradient-primary);
        color: #fff;
        border: none;
    }
    
    .form-select option {
        padding: 8px 12px;
    }
</style>
@endpush
@endsection
