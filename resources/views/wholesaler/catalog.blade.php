@extends('layouts.wholesaler_app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4" style="color: var(--text-dark);">
            <i class="bi bi-grid me-2" style="color: var(--primary-color);"></i>{{ __('Product Catalog') }}
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('wholesaler.dashboard') }}" style="color: var(--primary-color);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Catalog</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">

        <!-- Search and Filter Section -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: var(--text-dark);">Category</label>
                        <select name="category" class="form-select" style="border-radius: 8px;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @if(request('category') == $category) selected @endif>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--text-dark);">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, ingredient, or tag..."
                            value="{{ request('search') }}" style="border-radius: 8px;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: var(--text-dark);">&nbsp;</label>
                        <button class="btn btn-primary w-100" style="border-radius: 8px;">
                            <i class="bi bi-search me-2"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Products Grid -->
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 card-hover" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-semibold" style="color: var(--text-dark);">{{ $product->name }}</h5>
                                <span class="badge rounded-pill px-3 py-1" 
                                    style="background-color: {{ $product->quantity_in_stock > 10 ? '#4caf50' : ($product->quantity_in_stock > 0 ? '#ff9800' : '#6c757d') }}; color: white; font-size: 0.7rem; font-weight: 600;">
                                    {{ $product->quantity_in_stock > 10 ? 'Available' : ($product->quantity_in_stock > 0 ? 'Low Stock' : 'Out of Stock') }}
                                </span>
                            </div>
                            <p class="card-text small text-muted mb-2">{{ $product->category }}</p>
                            <p class="card-text" style="color: var(--text-muted);">{{ $product->description }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fw-bold" style="color: var(--primary-color); font-size: 1.1rem;">UGX {{ number_format($product->price, 2) }}</span>
                                <span class="text-muted small">Stock: {{ $product->quantity_in_stock }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: var(--background-light); border: 2px dashed var(--primary-light);">
                        <div class="card-body text-center p-5">
                            <div class="mb-3" style="font-size: 2.5rem; color: var(--primary-light);">
                                <i class="fas fa-search"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: var(--text-dark);">No Products Found</h5>
                            <p class="text-muted mb-0">Try adjusting your search criteria or browse all categories.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-4">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection