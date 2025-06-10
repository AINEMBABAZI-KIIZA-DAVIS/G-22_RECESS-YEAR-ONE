@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Update Order Status: #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Order Details</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Current Status: <span class="badge bg-{{ strtolower($order->status) == 'delivered' ? 'success' : (strtolower($order->status) == 'cancelled' ? 'danger' : (strtolower($order->status) == 'shipped' ? 'info' : (strtolower($order->status) == 'processing' ? 'primary' : 'secondary'))) }}">{{ ucfirst($order->status) }}</span></h5>
        </div>
        <div class="card-body">
            <p class="mb-3">Use the form below to update the status of this order.</p>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row align-items-end">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <label for="status" class="form-label">New Order Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select new status...</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ old('status', $order->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary-gradient w-100">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-primary-gradient { /* Ensure this class is defined in your main layout or here */
        background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        box-shadow: 0 2px 8px rgba(53, 122, 255, 0.10);
        transition: background 0.3s, box-shadow 0.3s;
    }
    .btn-primary-gradient:hover {
        background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
        color: #fff;
    }
    .form-label {
        font-weight: 500;
    }
    .badge {
        font-size: 0.9em;
    }
</style>
@endpush