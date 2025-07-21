@extends('layouts.vendor_app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4" style="color: var(--text-dark);">
            <i class="bi bi-house-door-fill me-2" style="color: var(--primary-color);"></i>{{ __('Vendor Dashboard') }}
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, var(--background-light) 0%, #ffebd6 100%);">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80"
                                    class="rounded-circle border border-4 border-white shadow-sm"
                                    style="width: 100px; height: 100px; object-fit: cover; border-color: var(--primary-color) !important;"
                                    alt="Vendor">
                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2"
                                    style="border: 3px solid white;"></span>
                            </div>
                        </div>
                        <h3 class="h4 fw-bold mb-3" style="color: var(--text-dark);">Welcome back, {{ Auth::user()->name }}! <span class="wave">👋</span></h3>
                        <p class="text-muted mb-4">Manage your vendor application and track your validation status.</p>
                        
                        <!-- Application Status Card -->
                        <div class="d-inline-block">
                            <div class="card border-0 shadow-sm" style="border-radius: 12px; background: white;">
                                <div class="card-body p-4">
                                    <h6 class="fw-semibold mb-2" style="color: var(--text-dark);">Application Status</h6>
                                    <span class="badge rounded-pill px-4 py-2" 
                                        style="background-color: {{ $application ? ($application->status == 'approved' ? '#4caf50' : ($application->status == 'visit_scheduled' ? '#2196f3' : '#ff9800')) : '#6c757d' }}; color: white; font-size: 0.9rem; font-weight: 600;">
                                        {{ $application ? ucfirst(str_replace('_', ' ', $application->status)) : 'No Application' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-5 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0" style="color: var(--text-dark); position: relative; display: inline-block;">
                    <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                        <i class="fas fa-bolt me-2" style="color: var(--primary-color);"></i>Quick Actions
                    </span>
                    <span
                        style="position: absolute; bottom: 5px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                </h5>
            </div>
        </div>

        <div class="row g-4">
            <!-- Apply as Vendor Card -->
            <div class="col-md-6">
                <a href="{{ route('vendor.apply') }}"
                    class="card h-100 border-0 text-decoration-none card-hover"
                    style="background: linear-gradient(135deg, var(--background-light) 0%, #ffebd6 100%); border-left: 4px solid var(--secondary-color) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                style="border: 2px solid #ffe0b2; color: var(--secondary-color);">
                                <i class="fas fa-file-alt fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">Apply as Vendor</h5>
                                <p class="text-muted small mb-0">Submit your vendor application with required documents.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 text-end p-3">
                        <span class="btn btn-sm btn-secondary-custom"
                            style="border-radius: 20px; padding: 0.35rem 1.25rem;">
                            Apply Now <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>
            
            <!-- Check Status Card -->
            <div class="col-md-6">
                <a href="{{ route('vendor.status') }}"
                    class="card h-100 border-0 text-decoration-none card-hover"
                    style="background: linear-gradient(135deg, var(--background-light) 0%, #e8d9c8 100%); border-left: 4px solid var(--primary-color) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-white rounded-circle p-3 shadow-sm me-3"
                                style="border: 2px solid #d7ccc8; color: var(--primary-color);">
                                <i class="fas fa-info-circle fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1" style="color: var(--text-dark);">Application Status</h5>
                                <p class="text-muted small mb-0">Check the status of your vendor application.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 text-end p-3">
                        <span class="btn btn-sm btn-primary-custom"
                            style="border-radius: 20px; padding: 0.35rem 1.25rem;">
                            Check Status <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Application Details -->
        @if($application)
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                        <h5 class="mb-0 fw-bold" style="color: var(--text-dark); position: relative;">
                            <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                                <i class="fas fa-clipboard-list me-2" style="color: var(--primary-color);"></i>Application Details
                            </span>
                            <span style="position: absolute; bottom: 3px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-semibold" style="color: var(--text-dark);">Company Name:</label>
                                    <p class="text-muted mb-0">{{ $application->company_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold" style="color: var(--text-dark);">Contact Email:</label>
                                    <p class="text-muted mb-0">{{ $application->contact_email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-semibold" style="color: var(--text-dark);">Application Date:</label>
                                    <p class="text-muted mb-0">{{ $application->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold" style="color: var(--text-dark);">Last Updated:</label>
                                    <p class="text-muted mb-0">{{ $application->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection