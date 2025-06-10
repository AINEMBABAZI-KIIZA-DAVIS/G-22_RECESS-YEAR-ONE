@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Supplier Supply Requests</h2>
        {{-- Admin typically doesn't create these, they manage incoming ones --}}
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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Req. ID</th>
                            <th>Supplier</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplyRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->user->name ?? 'N/A' }} <br><small class="text-muted">{{ $request->user->email ?? '' }}</small></td>
                            <td>{{ $request->product_name }}</td>
                            <td>{{ $request->quantity }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    match(strtolower($request->status)) {
                                        'pending' => 'warning text-dark',
                                        'confirmed_by_manufacturer' => 'info',
                                        'rejected' => 'danger',
                                        'fulfilled' => 'success',
                                        default => 'secondary'
                                    } 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.supply-requests.show', $request) }}" class="btn btn-sm btn-outline-primary" title="View Details & Manage"><i class="fas fa-eye me-1"></i>Manage</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No supply requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $supplyRequests->links() }}
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
</style>
@endpush