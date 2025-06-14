@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <h2 class="mb-4">Analytics Dashboard</h2>

    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-chart-line me-2"></i>Under Construction!</h4>
        <p>This section is currently under development. Detailed analytics and reports will be available here soon.</p>
        <hr>
        <p class="mb-0">You'll be able to see insights into sales trends, popular products, customer behavior, and more.</p>
    </div>

    {{-- Placeholder for future charts and data --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                    <h5 class="card-title">Sales Overview Chart</h5>
                    <p class="card-text text-muted">Coming Soon</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                    <h5 class="card-title">User Activity Metrics</h5>
                    <p class="card-text text-muted">Coming Soon</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection