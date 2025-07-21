@extends('layouts.admin_app')

@section('content')
@if(!isset($applications) && isset($app))
    @php $applications = [$app]; @endphp
@endif

<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6" style="color:var(--primary-color); font-weight: 700;">
                        <i class="fas fa-user-check me-3"></i>Vendor Applications
                    </h1>
                    <p class="text-muted mb-0">Review and manage vendor applications</p>
                </div>
                <div>
                    <a href="{{ route('admin.validation-requirements.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-cogs me-2"></i>Manage Requirements
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Alert -->
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Automatic Visit Scheduling:</strong> Facility visits are automatically scheduled by the Java validation service 
        exactly one week from the application submission date. No manual scheduling is required.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Vendor Applications Table Card -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Applications List</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="filterByStatus('all')">
                        <i class="fas fa-list me-1"></i>All
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterByStatus('pending')">
                        <i class="fas fa-clock me-1"></i>Pending
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="filterByStatus('visit_scheduled')">
                        <i class="fas fa-calendar-check me-1"></i>Visit Scheduled
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterByStatus('approved')">
                        <i class="fas fa-check-circle me-1"></i>Approved
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterByStatus('rejected')">
                        <i class="fas fa-times-circle me-1"></i>Rejected
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left: 24px;">ID</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Visit Scheduled Date</th>
                            <th class="text-end" style="padding-right: 24px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td style="padding-left: 24px;">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-building text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold">#{{ $app->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">{{ $app->company_name }}</div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">{{ $app->contact_email ?? ($app->email ?? '-') }}</div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'approved' => 'success',
                                        'visit_scheduled' => 'info',
                                        'pending' => 'warning',
                                        'rejected' => 'danger'
                                    ];
                                    $statusIcons = [
                                        'approved' => 'check-circle',
                                        'visit_scheduled' => 'calendar-check',
                                        'pending' => 'clock',
                                        'rejected' => 'times-circle'
                                    ];
                                    $color = $statusColors[$app->status] ?? 'secondary';
                                    $icon = $statusIcons[$app->status] ?? 'question-circle';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="fas fa-{{ $icon }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">{{ $app->created_at->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ $app->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($app->scheduled_visit_at)
                                        <div class="fw-medium text-success">
                                            <i class="fas fa-calendar-check me-1"></i>{{ $app->scheduled_visit_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-muted small">{{ $app->scheduled_visit_at->format('h:i A') }}</div>
                                        @if($app->scheduled_visit_at->isPast())
                                            <div class="text-warning small">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Visit Overdue
                                            </div>
                                        @elseif($app->scheduled_visit_at->diffInDays(now()) <= 1)
                                            <div class="text-info small">
                                                <i class="fas fa-clock me-1"></i>Visit Today/Tomorrow
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-times me-1"></i>Not Scheduled
                                        </div>
                                        @if($app->status === 'visit_scheduled')
                                            <div class="text-warning small">
                                                <i class="fas fa-exclamation-circle me-1"></i>Visit Required
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="text-end" style="padding-right: 24px;">
                                <a href="{{ route('admin.vendor-validation.show', $app->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-building" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0">No vendor applications found.</p>
                                    <small>Vendor applications will appear here once submitted.</small>
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

<!-- Vendor Detail Modals -->
<!-- Modal 1 -->
<div class="modal fade" id="vendorModal1" tabindex="-1" aria-labelledby="vendorModal1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendorModal1Label">
                    <i class="fas fa-building me-2"></i>Vendor Details - ABC Electronics Ltd.
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="vendor-details">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="detail-section">
                                <i class="fas fa-info-circle me-2"></i>Vendor Information
                            </h6>
                            <div class="detail-item">
                                <strong>Company Name:</strong> ABC Electronics Ltd.
                            </div>
                            <div class="detail-item">
                                <strong>Contact Person:</strong> John Smith
                            </div>
                            <div class="detail-item">
                                <strong>Email:</strong> john.smith@abcelectronics.com
                            </div>
                            <div class="detail-item">
                                <strong>Phone:</strong> +1 (555) 123-4567
                            </div>
                            <div class="detail-item">
                                <strong>Address:</strong> 123 Tech Street, Silicon Valley, CA 94025
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="detail-section">
                                <i class="fas fa-briefcase me-2"></i>Business Details
                            </h6>
                            <div class="detail-item">
                                <strong>Products to Vendor:</strong> Electronic Components, Circuit Boards, Sensors
                            </div>
                            <div class="detail-item">
                                <strong>Financial Capacity:</strong> $500,000 - $1,000,000
                            </div>
                            <div class="detail-item">
                                <strong>Duration of Vending:</strong> 5 years
                            </div>
                            <div class="detail-item">
                                <strong>Business License:</strong> CA-ELEC-2024-001
                            </div>
                            <div class="detail-item">
                                <strong>Tax ID:</strong> 12-3456789
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="detail-section">
                                <i class="fas fa-file-alt me-2"></i>Additional Information
                            </h6>
                            <p>ABC Electronics Ltd. is a well-established supplier of electronic components with a strong track record in the industry. They specialize in high-quality electronic parts and have served major tech companies in the Silicon Valley area.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="rejectVendor('ABC Electronics Ltd.')">
                    <i class="fas fa-times me-2"></i>Reject
                </button>
                <button type="button" class="btn btn-success" onclick="approveVendor('ABC Electronics Ltd.')">
                    <i class="fas fa-check me-2"></i>Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2 -->
<div class="modal fade" id="vendorModal2" tabindex="-1" aria-labelledby="vendorModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendorModal2Label">
                    <i class="fas fa-building me-2"></i>Vendor Details - Tech Solutions Inc.
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="vendor-details">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="detail-section">
                                <i class="fas fa-info-circle me-2"></i>Vendor Information
                            </h6>
                            <div class="detail-item">
                                <strong>Company Name:</strong> Tech Solutions Inc.
                            </div>
                            <div class="detail-item">
                                <strong>Contact Person:</strong> Sarah Johnson
                            </div>
                            <div class="detail-item">
                                <strong>Email:</strong> sarah.johnson@techsolutions.com
                            </div>
                            <div class="detail-item">
                                <strong>Phone:</strong> +1 (555) 987-6543
                            </div>
                            <div class="detail-item">
                                <strong>Address:</strong> 456 Innovation Drive, Austin, TX 78701
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="detail-section">
                                <i class="fas fa-briefcase me-2"></i>Business Details
                            </h6>
                            <div class="detail-item">
                                <strong>Products to Vendor:</strong> Software Solutions, IT Services, Cloud Infrastructure
                            </div>
                            <div class="detail-item">
                                <strong>Financial Capacity:</strong> $250,000 - $500,000
                            </div>
                            <div class="detail-item">
                                <strong>Duration of Vending:</strong> 3 years
                            </div>
                            <div class="detail-item">
                                <strong>Business License:</strong> TX-TECH-2024-002
                            </div>
                            <div class="detail-item">
                                <strong>Tax ID:</strong> 98-7654321
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="detail-section">
                                <i class="fas fa-file-alt me-2"></i>Additional Information
                            </h6>
                            <p>Tech Solutions Inc. provides innovative software solutions and IT services to businesses of all sizes. They have a strong focus on cloud infrastructure and digital transformation services.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="rejectVendor('Tech Solutions Inc.')">
                    <i class="fas fa-times me-2"></i>Reject
                </button>
                <button type="button" class="btn btn-success" onclick="approveVendor('Tech Solutions Inc.')">
                    <i class="fas fa-check me-2"></i>Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3 -->
<div class="modal fade" id="vendorModal3" tabindex="-1" aria-labelledby="vendorModal3Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendorModal3Label">
                    <i class="fas fa-building me-2"></i>Vendor Details - Global Supplies Co.
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="vendor-details">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="detail-section">
                                <i class="fas fa-info-circle me-2"></i>Vendor Information
                            </h6>
                            <div class="detail-item">
                                <strong>Company Name:</strong> Global Supplies Co.
                            </div>
                            <div class="detail-item">
                                <strong>Contact Person:</strong> Michael Chen
                            </div>
                            <div class="detail-item">
                                <strong>Email:</strong> michael.chen@globalsupplies.com
                            </div>
                            <div class="detail-item">
                                <strong>Phone:</strong> +1 (555) 456-7890
                            </div>
                            <div class="detail-item">
                                <strong>Address:</strong> 789 Commerce Blvd, New York, NY 10001
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="detail-section">
                                <i class="fas fa-briefcase me-2"></i>Business Details
                            </h6>
                            <div class="detail-item">
                                <strong>Products to Vendor:</strong> Office Supplies, Industrial Equipment, Raw Materials
                            </div>
                            <div class="detail-item">
                                <strong>Financial Capacity:</strong> $1,000,000 - $2,500,000
                            </div>
                            <div class="detail-item">
                                <strong>Duration of Vending:</strong> 8 years
                            </div>
                            <div class="detail-item">
                                <strong>Business License:</strong> NY-SUPPLY-2024-003
                            </div>
                            <div class="detail-item">
                                <strong>Tax ID:</strong> 45-6789012
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="detail-section">
                                <i class="fas fa-file-alt me-2"></i>Additional Information
                            </h6>
                            <p>Global Supplies Co. is a leading supplier of office supplies and industrial equipment with a nationwide distribution network. They have established partnerships with major manufacturers and maintain high quality standards.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="rejectVendor('Global Supplies Co.')">
                    <i class="fas fa-times me-2"></i>Reject
                </button>
                <button type="button" class="btn btn-success" onclick="approveVendor('Global Supplies Co.')">
                    <i class="fas fa-check me-2"></i>Approve
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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
    
    .detail-section {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--background-light);
    }
    
    .detail-item {
        margin-bottom: 12px;
        padding: 8px 0;
    }
    
    .detail-item strong {
        color: var(--text-dark);
        display: inline-block;
        min-width: 140px;
    }
    
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: var(--shadow-medium);
    }
    
    .modal-header {
        background: var(--background-light);
        border-bottom: 1px solid var(--border-light);
        border-radius: 16px 16px 0 0;
    }
    
    .modal-footer {
        background: var(--background-light);
        border-top: 1px solid var(--border-light);
        border-radius: 0 0 16px 16px;
    }
    
    /* Filter button styles */
    .btn-group .btn {
        border-radius: 20px !important;
        margin: 0 2px;
        font-size: 0.85rem;
        padding: 6px 12px;
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .btn-group .btn.active {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    /* Status indicator styles */
    .text-warning.small {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .text-info.small {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    /* Table row status indicators */
    .table tbody tr.overdue {
        border-left: 4px solid #ffc107;
    }
    
    .table tbody tr.urgent {
        border-left: 4px solid #dc3545;
    }
    
    .table tbody tr.scheduled {
        border-left: 4px solid #17a2b8;
    }
</style>
@endpush

@push('scripts')
<script>
function approveVendor(vendorName) {
    if (confirm(`Are you sure you want to approve ${vendorName}?`)) {
        // Here you would typically make an AJAX call to update the backend
        showToast(`${vendorName} has been approved successfully!`, 'success');
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
        modal.hide();
        // Update the UI to show approved status
        updateVendorStatus(vendorName, 'approved');
    }
}

function rejectVendor(vendorName) {
    if (confirm(`Are you sure you want to reject ${vendorName}?`)) {
        // Here you would typically make an AJAX call to update the backend
        showToast(`${vendorName} has been rejected.`, 'warning');
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
        modal.hide();
        // Update the UI to show rejected status
        updateVendorStatus(vendorName, 'rejected');
    }
}

function updateVendorStatus(vendorName, status) {
    // Update the status in the table
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const companyCell = row.querySelector('td:nth-child(2)');
        if (companyCell && companyCell.textContent.includes(vendorName)) {
            const statusCell = row.querySelector('td:nth-child(3)');
            if (statusCell) {
                const badge = statusCell.querySelector('.badge');
                if (badge) {
                    badge.className = `badge bg-${status === 'approved' ? 'success' : 'danger'}`;
                    badge.innerHTML = `<i class="fas fa-${status === 'approved' ? 'check-circle' : 'times-circle'} me-1"></i>${status.charAt(0).toUpperCase() + status.slice(1)}`;
                }
            }
        }
    });
}

function filterByStatus(status) {
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(3)');
        if (statusCell) {
            const badge = statusCell.querySelector('.badge');
            if (badge) {
                const statusText = badge.textContent.toLowerCase().replace(/\s+/g, '_');
                if (status === 'all' || statusText.includes(status)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        }
    });
    
    // Update filter button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Show count
    showToast(`Showing ${visibleCount} applications`, 'info');
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
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

// Initialize with all applications visible
document.addEventListener('DOMContentLoaded', function() {
    // Set active state for "All" button
    document.querySelector('.btn-group .btn:first-child').classList.add('active');
    
    // Add visual indicators to table rows
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const visitCell = row.querySelector('td:nth-child(5)');
        if (visitCell) {
            const overdueIndicator = visitCell.querySelector('.text-warning.small');
            const urgentIndicator = visitCell.querySelector('.text-info.small');
            
            if (overdueIndicator && overdueIndicator.textContent.includes('Overdue')) {
                row.classList.add('overdue');
            } else if (urgentIndicator && urgentIndicator.textContent.includes('Today/Tomorrow')) {
                row.classList.add('urgent');
            } else if (visitCell.querySelector('.text-success')) {
                row.classList.add('scheduled');
            }
        }
    });
    
    // Add click handlers for better UX
    rows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons or links
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            
            // Highlight the row
            rows.forEach(r => r.classList.remove('table-active'));
            this.classList.add('table-active');
        });
    });
});
</script>
@endpush
@endsection 