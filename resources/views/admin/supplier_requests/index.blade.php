@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Supplier Requests</h2>
        <a href="{{ route('admin.supply-requests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Supply Requests
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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Req. ID</th>
                            <th>Supplier Name</th>
                            <th>Email</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplierRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->user->name ?? 'N/A' }}</td>
                            <td>{{ $request->user->email ?? 'N/A' }}</td>
                            <td>{{ $request->product_name }}</td>
                            <td>{{ $request->quantity }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    match(strtolower($request->status)) {
                                        'pending' => 'warning text-dark',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'fulfilled' => 'info',
                                        default => 'secondary'
                                    } 
                                }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.supplier-requests.show', $request) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($request->status === 'pending')
                                        <form action="{{ route('admin.supplier-requests.approve', $request) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-success" 
                                                    title="Approve Request"
                                                    onclick="return confirm('Are you sure you want to approve this request?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.supplier-requests.reject', $request) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Reject Request"
                                                    onclick="return confirm('Are you sure you want to reject this request?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(in_array($request->status, ['pending', 'approved']))
                                        <form action="{{ route('admin.supplier-requests.fulfill', $request) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-info" 
                                                    title="Mark as Fulfilled"
                                                    onclick="return confirm('Are you sure you want to mark this request as fulfilled?')">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No supplier requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $supplierRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        color: #495057;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.7em;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endpush 