@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-5" style="color:var(--primary-color); font-weight: 700;">
                <i class="fas fa-chart-pie me-3"></i>Admin Dashboard
            </h1>
            <p class="lead text-muted">Welcome back! Monitor your inventory, track orders, and manage your business operations.</p>
        </div>
    </div>

    <!-- Basic Stats Cards -->
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="icon"><i class="fas fa-box"></i></div>
            <div class="card-title">Total Products</div>
            <div class="card-value">{{ number_format($stats['total_products']) }}</div>
        </div>
        <div class="dashboard-card">
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="card-title">Low Stock</div>
            <div class="card-value text-warning">{{ number_format($stats['low_stock']) }}</div>
        </div>
        <div class="dashboard-card">
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="card-title">Orders Today</div>
            <div class="card-value">{{ number_format($stats['orders_today']) }}</div>
        </div>
        <div class="dashboard-card">
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="card-title">Revenue</div>
            <div class="card-value text-success">UGX {{ number_format($stats['revenue_today'], 2) }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add Product
        </a>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
            <i class="fas fa-file-alt me-2"></i> Generate Report
        </button>
        <a href="{{ route('admin.products.export') }}" class="btn btn-outline-primary">
            <i class="fas fa-download me-2"></i> Export Data
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-shopping-cart me-2"></i> View Orders
        </a>
        <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-chart-line me-2"></i> Analytics
        </a>
    </div>

    <!-- Generate Report Modal -->
    <div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateReportModalLabel">
                        <i class="fas fa-file-alt me-2"></i>Generate Report
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm">
                        <div class="mb-3">
                            <label for="reportType" class="form-label">Report Type</label>
                            <select class="form-select" id="reportType" name="reportType" required>
                                <option value="">Select Report Type</option>
                                <option value="sales">Sales Report</option>
                                <option value="inventory">Inventory Report</option>
                                <option value="orders">Orders Report</option>
                                <option value="products">Products Report</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateRange" class="form-label">Date Range</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="endDate" name="endDate" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="downloadReport()">
                        <i class="fas fa-download me-2"></i>Download Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <h5 class="mb-3">
            <i class="fas fa-clock me-2"></i>Recent Activity
        </h5>
        <ul class="list-group list-group-flush">
            @foreach($recentOrders as $order)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Order #{{ $order->id }} placed by {{ $order->user->name ?? 'N/A' }}
                    </div>
                    <span class="badge bg-success">{{ ucfirst($order->status ?? 'Completed') }}</span>
                </li>
            @endforeach
            @foreach($recentInventory as $inv)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                        Product "{{ $inv->name ?? 'N/A' }}" stock low
                    </div>
                    <span class="badge bg-warning">Low Stock</span>
                </li>
            @endforeach
            @foreach($recentProducts as $prod)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-plus-circle me-2 text-success"></i>
                        Product "{{ $prod->name }}" added to inventory
                    </div>
                    <span class="badge bg-primary">New</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
<script>
    function downloadReport() {
        // Modern UX: show loading, validate, then trigger download
        const type = document.getElementById('reportType').value;
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        
        if (!type || !start || !end) {
            // Show modern toast notification
            showToast('Please select all report options.', 'warning');
            return;
        }
        
        // Show loading state
        const downloadBtn = document.querySelector('#generateReportModal .btn-primary');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
        downloadBtn.disabled = true;
        
        // Simulate report generation
        setTimeout(() => {
            showToast(`Report for ${type} from ${start} to ${end} will be generated.`, 'success');
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('generateReportModal'));
            modal.hide();
        }, 2000);
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
</script>
@endpush
@endsection