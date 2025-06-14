@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-secondary">
        {{ __('Create New Supply Request') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container" style="max-width: 48rem;">
        <div class="card shadow-sm">
            <div class="card-body border-bottom">
                <form method="POST" action="{{ route('supplier.requests.store') }}">
                    @csrf

                    <!-- Product Name/Description -->
                    <div class="mb-3">
                        <label for="product_name" class="form-label">
                            {{ __('Product Name/Description') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required
                               class="form-control @error('product_name') is-invalid @enderror" >
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">
                            {{ __('Quantity Requested') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required min="1"
                               class="form-control @error('quantity') is-invalid @enderror" >
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">{{ __('Additional Notes (Optional)') }}</label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <a href="{{ route('supplier.dashboard') }}" class="text-secondary text-decoration-underline me-3 small">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary text-uppercase fw-semibold px-4 py-2">
                            {{ __('Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
