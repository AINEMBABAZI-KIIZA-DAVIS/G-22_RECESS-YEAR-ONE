@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Supplier Request Details</h2>
        <div>
            <a href="{{ route('admin.supplier-requests.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
            @if($supplierRequest->status === 'pending')
                <form action="{{ route('admin.supplier-requests.approve', $supplierRequest) }}" 
                      method="POST" 
                      class="d-inline">
                    @csrf
                    <button type="submit" 
                            class="btn btn-success me-2"
                            onclick="return confirm('Are you sure you want to approve this request?')">
                        <i class="fas fa-check me-2"></i>Approve
                    </button>
                </form>
                
                <form action="{{ route('admin.supplier-requests.reject', $supplierRequest) }}" 
                      method="POST" 
                      class="d-inline">
                    @csrf
                    <button type="submit" 
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to reject this request?')">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                </form>
            @endif
            
            @if(in_array($supplierRequest->status, ['pending', 'approved']))
                <form action="{{ route('admin.supplier-requests.fulfill', $supplierRequest) }}" 
                      method="POST" 
                      class="d-inline ms-2">
                    @csrf
                    <button type="submit" 
                            class="btn btn-info"
                            onclick="return confirm('Are you sure you want to mark this request as fulfilled?')">
                        <i class="fas fa-truck me-2"></i>Mark as Fulfilled
                    </button>
                </form>
            @endif
        </div>
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

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Request Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Request ID:</dt>
                        <dd class="col-sm-8">#{{ $supplierRequest->id }}</dd>

                        <dt class="col-sm-4">Supplier Name:</dt>
                        <dd class="col-sm-8">{{ $supplierRequest->user->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Supplier Email:</dt>
                        <dd class="col-sm-8">{{ $supplierRequest->user->email ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Product Name:</dt>
                        <dd class="col-sm-8">{{ $supplierRequest->product_name }}</dd>

                        <dt class="col-sm-4">Quantity Requested:</dt>
                        <dd class="col-sm-8">{{ $supplierRequest->quantity }}</dd>

                        <dt class="col-sm-4">Notes:</dt>
                        <dd class="col-sm-8">{{ $supplierRequest->notes ?: 'No notes provided' }}</dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ 
                                match(strtolower($supplierRequest->status)) {
                                    'pending' => 'warning text-dark',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'fulfilled' => 'info',
                                    default => 'secondary'
                                } 
                            }}">
                                {{ ucfirst($supplierRequest->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Requested On:</dt>
                        <dd class="col-sm-8">{{ $supplierRequest->created_at->format('M d, Y H:i A') }}</dd>

                        <dt class="col-sm-4">Last Updated:</dt>
                        <dd class="col-sm-8">{{ $supplierRequest->updated_at->format('M d, Y H:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($supplierRequest->status === 'pending')
                            <form action="{{ route('admin.supplier-requests.approve', $supplierRequest) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-success w-100"
                                        onclick="return confirm('Are you sure you want to approve this request?')">
                                    <i class="fas fa-check me-2"></i>Approve Request
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.supplier-requests.reject', $supplierRequest) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-danger w-100"
                                        onclick="return confirm('Are you sure you want to reject this request?')">
                                    <i class="fas fa-times me-2"></i>Reject Request
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($supplierRequest->status, ['pending', 'approved']))
                            <form action="{{ route('admin.supplier-requests.fulfill', $supplierRequest) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-info w-100"
                                        onclick="return confirm('Are you sure you want to mark this request as fulfilled?')">
                                    <i class="fas fa-truck me-2"></i>Mark as Fulfilled
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('admin.supplier-requests.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-list me-2"></i>Back to List
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
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 0.8em;
    }
</style>
@endpush 