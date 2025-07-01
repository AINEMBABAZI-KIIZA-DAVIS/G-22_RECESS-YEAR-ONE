@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>Available Products
            </h4>
            <div>
                <a href="{{ route('wholesaler.products.low-stock') }}" class="btn btn-outline-warning me-2">
                    <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                </a>
                <a href="{{ route('wholesaler.products.out-of-stock') }}" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-1"></i>Out of Stock
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($products->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No products available at the moment.
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="position-relative">
                                @if($product->stock <= 10)
                                    <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                                        Low Stock
                                    </span>
                                @endif
                                <img src="{{ $product->imageUrl }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 200px; object-fit: cover;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="color: #2b2d42;">{{ $product->name }}</h5>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="h5 mb-0">UGX {{ number_format($product->price) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-{{ $product->stock <= 10 ? 'warning' : 'success' }} me-2">
                                            {{ $product->stock }} in stock
                                        </span>
                                        <a href="{{ route('wholesaler.products.show', $product) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
