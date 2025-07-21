@extends('layouts.vendor_app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold fs-4" style="color: var(--text-dark);">
            <i class="bi bi-info-circle me-2" style="color: var(--primary-color);"></i>{{ __('Application Status') }}
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}" style="color: var(--primary-color);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Status</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if($app)
                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                            <h5 class="mb-0 fw-bold" style="color: var(--text-dark); position: relative;">
                                <span style="position: relative; z-index: 1; background: var(--background-light); padding-right: 15px;">
                                    <i class="fas fa-clipboard-check me-2" style="color: var(--primary-color);"></i>Application Status
                                </span>
                                <span style="position: absolute; bottom: 3px; left: 0; right: 0; height: 8px; background: linear-gradient(90deg, var(--primary-light), var(--primary-color)); z-index: 0; border-radius: 4px;"></span>
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Status Badge -->
                            <div class="text-center mb-4">
                                <span class="badge rounded-pill px-4 py-2" 
                                    style="background-color: {{ $app->status == 'approved' ? '#4caf50' : ($app->status == 'visit_scheduled' ? '#2196f3' : '#ff9800') }}; color: white; font-size: 1.1rem; font-weight: 600;">
                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </div>

                            <!-- Application Details -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-semibold" style="color: var(--text-dark);">Company Name:</label>
                                        <p class="text-muted mb-0">{{ $app->company_name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-semibold" style="color: var(--text-dark);">Contact Email:</label>
                                        <p class="text-muted mb-0">{{ $app->contact_email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-semibold" style="color: var(--text-dark);">Application Date:</label>
                                        <p class="text-muted mb-0">{{ $app->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-semibold" style="color: var(--text-dark);">Last Updated:</label>
                                        <p class="text-muted mb-0">{{ $app->updated_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Validation Results -->
                            @if($app->validation_results)
                                @php
                                    $validationData = $app->validation_results;
                                @endphp
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3" style="color: var(--text-dark);">
                                        <i class="fas fa-check-circle me-2" style="color: var(--primary-color);"></i>Validation Results
                                    </h6>
                                    <div class="card" style="background-color: var(--background-light); border-radius: 8px;">
                                        <div class="card-body">
                                            @if(isset($validationData['score']))
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <strong style="color: var(--text-dark);">Validation Score:</strong>
                                                        <span class="badge bg-primary">{{ number_format($validationData['score'], 1) }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar" style="width: {{ $validationData['score'] }}%"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if(isset($validationData['passed_requirements']) && count($validationData['passed_requirements']) > 0)
                                                <div class="mb-3">
                                                    <h6 class="text-success small mb-2">
                                                        <i class="fas fa-check me-1"></i>Passed Requirements
                                                    </h6>
                                                    <ul class="list-unstyled small">
                                                        @foreach($validationData['passed_requirements'] as $requirement)
                                                            <li class="text-success">
                                                                <i class="fas fa-check-circle me-1"></i>{{ $requirement }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(isset($validationData['failed_requirements']) && count($validationData['failed_requirements']) > 0)
                                                <div class="mb-3">
                                                    <h6 class="text-danger small mb-2">
                                                        <i class="fas fa-times me-1"></i>Failed Requirements
                                                    </h6>
                                                    <ul class="list-unstyled small">
                                                        @foreach($validationData['failed_requirements'] as $requirement)
                                                            <li class="text-danger">
                                                                <i class="fas fa-times-circle me-1"></i>{{ $requirement }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(isset($validationData['warnings']) && count($validationData['warnings']) > 0)
                                                <div class="mb-3">
                                                    <h6 class="text-warning small mb-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Warnings
                                                    </h6>
                                                    <ul class="list-unstyled small">
                                                        @foreach($validationData['warnings'] as $warning)
                                                            <li class="text-warning">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $warning }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Scheduled Visit -->
                            @if($app->scheduled_visit_at)
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3" style="color: var(--text-dark);">
                                        <i class="fas fa-calendar-alt me-2" style="color: var(--primary-color);"></i>Facility Visit Scheduled
                                    </h6>
                                    <div class="card" style="background-color: var(--background-light); border-radius: 8px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <strong style="color: var(--text-dark);">Date:</strong>
                                                        <p class="text-muted mb-0">{{ $app->scheduled_visit_at->format('l, F d, Y') }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <strong style="color: var(--text-dark);">Time:</strong>
                                                        <p class="text-muted mb-0">{{ $app->scheduled_visit_at->format('h:i A') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3 p-3" style="background-color: var(--primary-light); border-radius: 6px;">
                                                <p class="mb-0 small" style="color: var(--primary-color);">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <strong>Important:</strong> Please ensure your facility is prepared for the visit. 
                                                    Our team will arrive at the scheduled time to conduct the facility inspection.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="text-center">
                                <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: var(--background-light); border: 2px dashed var(--primary-light);">
                        <div class="card-body text-center p-5">
                            <div class="mb-3" style="font-size: 2.5rem; color: var(--primary-light);">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: var(--text-dark);">No Application Found</h5>
                            <p class="text-muted mb-4">You haven't submitted a vendor application yet.</p>
                            <a href="{{ route('vendor.apply') }}" class="btn btn-primary" style="border-radius: 8px;">
                                <i class="fas fa-plus-circle me-2"></i>Apply Now
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 