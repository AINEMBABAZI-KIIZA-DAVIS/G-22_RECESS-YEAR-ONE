@extends('layouts.vendor_app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4" style="color: var(--text-dark);">
            <i class="bi bi-file-earmark-text me-2" style="color: var(--primary-color);"></i>{{ __('Vendor Application') }}
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}" style="color: var(--primary-color);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Apply</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                        <h5 class="mb-0 fw-bold" style="color: var(--text-dark); position: relative;">
                            <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                                <i class="fas fa-clipboard-list me-2" style="color: var(--primary-color);"></i>Application Form
                            </span>
                            <span style="position: absolute; bottom: 3px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('vendor.apply.submit') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Company Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="color: var(--text-dark);">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control" required 
                                           style="border-radius: 8px; border: 1px solid var(--border-light);"
                                           placeholder="Enter your company name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="color: var(--text-dark);">Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" name="contact_email" class="form-control" required 
                                           style="border-radius: 8px; border: 1px solid var(--border-light);"
                                           placeholder="Enter your contact email">
                                </div>
                            </div>

                            <!-- Document Upload Section -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3" style="color: var(--text-dark);">
                                    <i class="fas fa-file-upload me-2" style="color: var(--primary-color);"></i>Required Documents
                                </h6>
                                <p class="text-muted small mb-4">Please upload the following documents in PDF format:</p>
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" style="color: var(--text-dark);">Annual Revenue PDF <span class="text-danger">*</span></label>
                                        <input type="file" name="annual_revenue_pdf" class="form-control" accept="application/pdf" required
                                               style="border-radius: 8px; border: 1px solid var(--border-light);">
                                        <small class="text-muted">Upload your annual revenue documentation</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" style="color: var(--text-dark);">Regulatory Compliance PDF <span class="text-danger">*</span></label>
                                        <input type="file" name="regulatory_pdf" class="form-control" accept="application/pdf" required
                                               style="border-radius: 8px; border: 1px solid var(--border-light);">
                                        <small class="text-muted">Upload regulatory compliance documents</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" style="color: var(--text-dark);">Reputation/References PDF <span class="text-danger">*</span></label>
                                        <input type="file" name="reputation_pdf" class="form-control" accept="application/pdf" required
                                               style="border-radius: 8px; border: 1px solid var(--border-light);">
                                        <small class="text-muted">Upload reputation and reference documents</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end align-items-center">
                                <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-secondary me-3" style="border-radius: 8px;">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" style="border-radius: 8px;">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection