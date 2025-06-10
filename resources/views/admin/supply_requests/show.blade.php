@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Supply Request #{{ $supplyRequest->id }}</h2>
        <a href="{{ route('admin.supply-requests.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Requests</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Request Details</h5>
                    <span class="badge bg-{{ 
                        match(strtolower($supplyRequest->status)) {
                            'pending' => 'warning text-dark',
                            'confirmed_by_manufacturer' => 'info',
                            'rejected' => 'danger',
                            'fulfilled' => 'success',
                            default => 'secondary'
                        } 
                    }}">{{ ucfirst(str_replace('_', ' ', $supplyRequest->status)) }}</span>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Requested By (Supplier):</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->user->name ?? 'N/A' }} ({{ $supplyRequest->user->email ?? 'N/A' }})</dd>

                        <dt class="col-sm-4">Product Name/Description:</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->product_name }}</dd>

                        <dt class="col-sm-4">Quantity Requested:</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->quantity }}</dd>

                        <dt class="col-sm-4">Supplier Notes:</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->notes ?: 'N/A' }}</dd>
                        
                        <hr class="my-3">

                        <dt class="col-sm-4">Date Requested:</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->created_at->format('M d, Y H:i A') }}</dd>

                        <dt class="col-sm-4">Date Confirmed:</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->confirmed_at ? $supplyRequest->confirmed_at->format('M d, Y H:i A') : 'N/A' }}</dd>

                        <dt class="col-sm-4">Date Fulfilled:</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->fulfilled_at ? $supplyRequest->fulfilled_at->format('M d, Y H:i A') : 'N/A' }}</dd>
                        
                        <dt class="col-sm-4">Manufacturer Notes:</dt>
                        <dd class="col-sm-8">{{ $supplyRequest->manufacturer_notes ?: 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Manage Request</h5>
                </div>
                <div class="card-body">
                    @if($supplyRequest->status == 'pending')
                        <form action="{{ route('admin.supply-requests.confirm', $supplyRequest) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <label for="confirm_manufacturer_notes" class="form-label">Notes for Confirmation (Optional):</label>
                            <textarea name="manufacturer_notes" id="confirm_manufacturer_notes" class="form-control form-control-sm mb-2" rows="2" placeholder="e.g., Expected delivery in 5 days.">{{ $supplyRequest->manufacturer_notes }}</textarea>
                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-check-circle me-2"></i>Confirm Request</button>
                        </form>
                        <hr>
                        <form action="{{ route('admin.supply-requests.reject', $supplyRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-2">
                                <label for="reject_manufacturer_notes" class="form-label">Reason for Rejection <span class="text-danger">*</span>:</label>
                                <textarea name="manufacturer_notes" id="reject_manufacturer_notes" class="form-control form-control-sm @error('manufacturer_notes') is-invalid @enderror" rows="2" required placeholder="e.g., Item out of stock.">{{ old('manufacturer_notes', $supplyRequest->manufacturer_notes) }}</textarea>
                                @error('manufacturer_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-danger w-100"><i class="fas fa-times-circle me-2"></i>Reject Request</button>
                        </form>
                    @elseif($supplyRequest->status == 'confirmed_by_manufacturer')
                        <form action="{{ route('admin.supply-requests.fulfill', $supplyRequest) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PATCH')
                             <label for="fulfill_manufacturer_notes" class="form-label">Notes for Fulfillment (Optional):</label>
                            <textarea name="manufacturer_notes" id="fulfill_manufacturer_notes" class="form-control form-control-sm mb-2" rows="2" placeholder="e.g., Shipped via DHL tracking #123.">{{ $supplyRequest->manufacturer_notes }}</textarea>
                            <button type="submit" class="btn btn-primary-gradient w-100"><i class="fas fa-truck me-2"></i>Mark as Fulfilled</button>
                        </form>
                         <hr>
                        <p class="text-muted">To reject this confirmed request, please provide a reason:</p>
                        <form action="{{ route('admin.supply-requests.reject', $supplyRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-2">
                                <label for="reject_confirmed_manufacturer_notes" class="form-label">Reason for Rejection <span class="text-danger">*</span>:</label>
                                <textarea name="manufacturer_notes" id="reject_confirmed_manufacturer_notes" class="form-control form-control-sm @error('manufacturer_notes') is-invalid @enderror" rows="2" required placeholder="e.g., Issue with confirmed stock.">{{ old('manufacturer_notes') }}</textarea>
                                 @error('manufacturer_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-danger w-100"><i class="fas fa-times-circle me-2"></i>Reject Confirmed Request</button>
                        </form>
                    @elseif($supplyRequest->status == 'rejected')
                        <p class="text-danger"><i class="fas fa-times-circle me-2"></i>This request has been rejected.</p>
                        @if($supplyRequest->manufacturer_notes) <p class="mt-2"><strong>Reason:</strong> {{ $supplyRequest->manufacturer_notes }}</p> @endif
                    @elseif($supplyRequest->status == 'fulfilled')
                        <p class="text-success"><i class="fas fa-check-double me-2"></i>This request has been fulfilled.</p>
                         @if($supplyRequest->manufacturer_notes) <p class="mt-2"><strong>Notes:</strong> {{ $supplyRequest->manufacturer_notes }}</p> @endif
                    @else
                        <p class="text-muted">No actions available for current status.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    dl dt { font-weight: 600; color: #495057; }
    .card-header h5 { font-size: 1.1rem; }
    .badge { font-size: 0.9em; }
    .btn-primary-gradient {
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
</style>
@endpush