@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6" style="color:var(--primary-color); font-weight: 700;">
                        <i class="fas fa-boxes-stacked me-3"></i>Product Inventory
                    </h1>
                    <p class="text-muted mb-0">Manage your product catalog and inventory levels</p>
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Product
                </a>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Products Table Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Product List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left: 24px;">SKU</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock Level</th>
                            <th>Low Stock Threshold</th>
                            <th class="text-end" style="padding-right: 24px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td style="padding-left: 24px;">
                                <span class="badge bg-light text-dark">{{ $product->sku }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-box text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="text-decoration-none fw-medium text-dark">
                                            {{ $product->name }}
                                        </a>
                                        <div class="text-muted small">{{ Str::limit($product->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-success">UGX {{ number_format($product->price, 2) }}</span>
                            </td>
                            <td>
                                @if($product->quantity_in_stock <= ($product->low_stock_threshold ?? 10))
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $product->quantity_in_stock }}
                                    </span>
                                @elseif($product->quantity_in_stock == 0)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Out of Stock
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $product->quantity_in_stock }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted">{{ $product->low_stock_threshold ?? 'N/A' }}</span>
                            </td>
                            <td class="text-end" style="padding-right: 24px;">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Delete Product">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-box-open" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">No products found.</p>
                                    <small>Start by adding your first product.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center py-3 border-top">
                    {{ $products->links() }}
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
</style>
@endpush
@endsection