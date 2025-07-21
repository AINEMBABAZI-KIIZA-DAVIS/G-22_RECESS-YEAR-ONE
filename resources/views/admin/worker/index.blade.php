@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6" style="color:var(--primary-color); font-weight: 700;">
                        <i class="fas fa-users me-3"></i>Worker Management
                    </h1>
                    <p class="text-muted mb-0">Manage your workforce and team members</p>
                </div>
                <a href="{{ route('admin.workers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Worker
                </a>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Workers Table Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Workers List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left: 24px;">Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Supply Center</th>
                            <th>Current Role</th>
                            <th class="text-end" style="padding-right: 24px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workers as $worker)
                            <tr>
                                <td style="padding-left: 24px;">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-user text-primary" style="font-size: 1.2rem;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $worker->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $worker->email }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $worker->position }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $worker->supply_center }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $worker->current_role }}</span>
                                </td>
                                <td class="text-end" style="padding-right: 24px;">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.workers.edit', $worker) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit Worker">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.workers.destroy', $worker) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this worker?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete Worker">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-users" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-3 mb-0">No workers found.</p>
                                        <small>Start by adding your first worker.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background: var(--background-light);
        transform: scale(1.01);
    }
    
    .badge {
        font-size: 0.85em;
        padding: 6px 12px;
        border-radius: 20px;
    }
</style>
@endpush
@endsection
