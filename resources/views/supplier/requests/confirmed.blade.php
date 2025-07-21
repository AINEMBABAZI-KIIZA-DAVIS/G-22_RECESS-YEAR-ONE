@extends('layouts.supplier_app')

@section('header')
    <h2 class="fw-semibold fs-4 text-secondary">
        {{ __('Approved Supply Requests') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="h5 mb-1">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Approved Supply Requests
                        </h3>
                        <p class="text-muted mb-0">Requests that have been approved by the manufacturer</p>
                    </div>
                    <a href="{{ route('supplier.requests.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>New Request
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($requests->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No Approved Requests</h5>
                        <p class="text-muted">You have no supply requests currently approved by the manufacturer.</p>
                        <a href="{{ route('supplier.requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New Request
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="fw-semibold">Request ID</th>
                                    <th scope="col" class="fw-semibold">Product</th>
                                    <th scope="col" class="fw-semibold">Quantity</th>
                                    <th scope="col" class="fw-semibold">Status</th>
                                    <th scope="col" class="fw-semibold">Approved On</th>
                                    <th scope="col" class="fw-semibold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $request->id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $request->product_name }}</div>
                                        @if($request->notes)
                                            <small class="text-muted">{{ Str::limit($request->notes, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $request->quantity }} units</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Approved
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $request->updated_at->format('M d, Y') }}
                                        </div>
                                        <small class="text-muted">{{ $request->updated_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('supplier.requests.show', $request) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                @endif

                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('supplier.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <a href="{{ route('supplier.requests.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i>View All Requests
                        </a>
                    </div>
                </div>
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
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }
    
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.7em;
    }
    
    .btn-outline-primary:hover {
        background-color: #a0522d;
        border-color: #a0522d;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(160, 82, 45, 0.05);
    }
</style>
@endpush
