@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Warning Alert for Service Unavailability -->
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6" style="color:var(--primary-color); font-weight: 700;">
                        <i class="fas fa-cogs me-3"></i>Validation Requirements
                    </h1>
                    <p class="text-muted mb-0">Manage vendor validation requirements and criteria</p>
                </div>
                <div>
                    <a href="{{ route('admin.validation-requirements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Requirement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Requirements Table Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Validation Requirements</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left: 24px;">Name</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Weight</th>
                            <th>Required</th>
                            <th>Status</th>
                            <th class="text-end" style="padding-right: 24px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requirements as $requirement)
                        <tr>
                            <td style="padding-left: 24px;">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-check-circle text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $requirement->name }}</div>
                                        <div class="text-muted small">{{ $requirement->description ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($requirement->category) }}</span>
                            </td>
                            <td>
                                <code>{{ $requirement->type }}</code>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 60px; height: 6px;">
                                        <div class="progress-bar" style="width: {{ $requirement->weight }}%"></div>
                                    </div>
                                    <span class="fw-medium">{{ $requirement->weight }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($requirement->required)
                                    <span class="badge bg-danger">Required</span>
                                @else
                                    <span class="badge bg-secondary">Optional</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $requirement->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($requirement->status) }}</span>
                            </td>
                            <td class="text-end" style="padding-right: 24px;">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.validation-requirements.edit', $requirement->name) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.validation-requirements.destroy', $requirement->name) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this requirement?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-cogs" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">No validation requirements found.</p>
                                    <small>Add requirements to configure vendor validation criteria.</small>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Requirement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this validation requirement?</p>
                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle requirement status
    document.querySelectorAll('.toggle-requirement').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const name = this.dataset.name;
            const active = this.checked;
            
            fetch(`/admin/validation-requirements/${name}/toggle`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ active: active })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.warning) {
                        // Show warning toast if Java service is unavailable
                        showToast(data.warning, 'warning');
                    }
                } else {
                    this.checked = !active; // Revert if failed
                    showToast('Failed to update requirement status', 'error');
                }
            })
            .catch(error => {
                this.checked = !active; // Revert if failed
                showToast('Error updating requirement status', 'error');
            });
        });
    });
});

function deleteRequirement(name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/admin/validation-requirements/${name}`;
    modal.show();
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}
</script>
@endpush 