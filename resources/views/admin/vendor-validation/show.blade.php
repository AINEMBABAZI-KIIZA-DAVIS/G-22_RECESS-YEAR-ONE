@extends('layouts.admin_app')
@section('title', 'Vendor Application Details')
@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6" style="color:var(--primary-color); font-weight: 700;">
                        <i class="fas fa-building me-3"></i>Vendor Application Details
                    </h1>
                    <p class="text-muted mb-0">Review vendor application and validation results</p>
                </div>
                <div>
                    <a href="{{ route('admin.vendor-validation.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Applications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Application Details -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Application Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Company Name:</strong></div>
                        <div class="col-sm-8">{{ $app->company_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Contact Email:</strong></div>
                        <div class="col-sm-8">{{ $app->contact_email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Status:</strong></div>
                        <div class="col-sm-8">
                            @php
                                $statusColors = [
                                    'approved' => 'success',
                                    'requires_visit' => 'info',
                                    'pending' => 'warning',
                                    'rejected' => 'danger'
                                ];
                                $color = $statusColors[$app->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Submitted:</strong></div>
                        <div class="col-sm-8">{{ $app->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Submitted Documents</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ asset('storage/' . $app->annual_revenue_pdf) }}" target="_blank"
                           class="btn btn-outline-primary">
                            <i class="fas fa-file-pdf me-2"></i>Annual Revenue PDF
                        </a>
                        <a href="{{ asset('storage/' . $app->regulatory_pdf) }}" target="_blank"
                           class="btn btn-outline-primary">
                            <i class="fas fa-file-pdf me-2"></i>Regulatory PDF
                        </a>
                        <a href="{{ asset('storage/' . $app->reputation_pdf) }}" target="_blank"
                           class="btn btn-outline-primary">
                            <i class="fas fa-file-pdf me-2"></i>Reputation PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validation Results -->
        <div class="col-md-6">
            @if($app->validation_results)
                @php
                    $validationData = $app->validation_results;
                @endphp
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Validation Results</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($validationData['score']))
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Validation Score:</strong>
                                    <span class="badge bg-primary">{{ number_format($validationData['score'], 1) }}%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $validationData['score'] }}%"></div>
                                </div>
                            </div>
                        @endif

                        @if(isset($validationData['passed_requirements']) && count($validationData['passed_requirements']) > 0)
                            <div class="mb-3">
                                <h6 class="text-success"><i class="fas fa-check me-2"></i>Passed Requirements</h6>
                                <ul class="list-unstyled">
                                    @foreach($validationData['passed_requirements'] as $requirement)
                                        <li><i class="fas fa-check-circle text-success me-2"></i>{{ $requirement }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(isset($validationData['failed_requirements']) && count($validationData['failed_requirements']) > 0)
                            <div class="mb-3">
                                <h6 class="text-danger"><i class="fas fa-times me-2"></i>Failed Requirements</h6>
                                <ul class="list-unstyled">
                                    @foreach($validationData['failed_requirements'] as $requirement)
                                        <li><i class="fas fa-times-circle text-danger me-2"></i>{{ $requirement }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(isset($validationData['warnings']) && count($validationData['warnings']) > 0)
                            <div class="mb-3">
                                <h6 class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Warnings</h6>
                                <ul class="list-unstyled">
                                    @foreach($validationData['warnings'] as $warning)
                                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>{{ $warning }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(isset($validationData['detailed_results']))
                            <div class="mt-3">
                                <h6><i class="fas fa-list me-2"></i>Detailed Results</h6>
                                <pre class="bg-light p-3 rounded" style="font-size: 0.875rem;">{{ $validationData['detailed_results'] }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Validation Status</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-clock text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mt-3 mb-0 text-muted">Validation results not available yet</p>
                    </div>
                </div>
            @endif

            @if($app->scheduled_visit_at)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Facility Visit</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Scheduled:</strong> {{ $app->scheduled_visit_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Admin Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Schedule Visit -->
                        
</div>
@endsection