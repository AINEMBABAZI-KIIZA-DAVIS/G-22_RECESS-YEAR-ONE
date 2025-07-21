@extends('layouts.supplier_app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4 text-secondary">
            {{ __('Create New Supply Request') }}
        </h2>
        <a href="{{ route('supplier.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<div class="py-4">
    <div class="container" style="max-width: 48rem;">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    New Supply Request Form
                </h5>
                <small class="text-muted">Please fill in the details below to submit your supply request</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('supplier.requests.store') }}">
                    @csrf

                    <!-- Product Name/Description -->
                    <div class="mb-4">
                        <label for="product_name" class="form-label fw-semibold">
                            {{ __('Product Name/Description') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="product_name" 
                               id="product_name" 
                               value="{{ old('product_name') }}" 
                               required
                               placeholder="Enter the name or description of the product you need"
                               class="form-control @error('product_name') is-invalid @enderror">
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Be specific about the product you need (e.g., "Whole Wheat Flour - 50kg bags")
                        </small>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label for="quantity" class="form-label fw-semibold">
                            {{ __('Quantity Requested') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   value="{{ old('quantity') }}" 
                                   required 
                                   min="1"
                                   placeholder="Enter the quantity you need"
                                   class="form-control @error('quantity') is-invalid @enderror">
                            <span class="input-group-text">units</span>
                        </div>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Specify the quantity in appropriate units (bags, kg, pieces, etc.)
                        </small>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">
                            {{ __('Additional Notes (Optional)') }}
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4"
                                  placeholder="Add any additional information, special requirements, or delivery preferences"
                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Include delivery preferences, quality specifications, or any special requirements
                        </small>
                    </div>

                    <!-- Request Summary -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Request Summary
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Supplier:</strong> {{ Auth::user()->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Email:</strong> {{ Auth::user()->email }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Request Date:</strong> {{ now()->format('M d, Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong> <span class="badge bg-warning text-dark">Pending</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('supplier.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
    }
    
    .form-control:focus {
        border-color: #a0522d;
        box-shadow: 0 0 0 0.2rem rgba(160, 82, 45, 0.25);
    }
    
    .btn-primary {
        background-color: #a0522d;
        border-color: #a0522d;
    }
    
    .btn-primary:hover {
        background-color: #8b4513;
        border-color: #8b4513;
    }
    
    .alert-info {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
    }
    
    .badge {
        font-size: 0.8em;
    }
</style>
@endpush
