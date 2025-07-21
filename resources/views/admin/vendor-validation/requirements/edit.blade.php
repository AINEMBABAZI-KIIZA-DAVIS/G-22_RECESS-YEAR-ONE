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
                        <i class="fas fa-edit me-3"></i>Edit Validation Requirement
                    </h1>
                    <p class="text-muted mb-0">Update validation requirement: {{ $requirement['name'] }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.validation-requirements.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Requirements
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Requirement Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.validation-requirements.update', $name) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Requirement Key</label>
                            <input type="text" class="form-control" value="{{ $name }}" readonly>
                            <div class="form-text">Unique identifier (cannot be changed)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                   id="category" name="category" value="{{ old('category', $requirement->category) }}" 
                                   placeholder="e.g., documentation">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('type') is-invalid @enderror" 
                                   id="type" name="type" value="{{ old('type', $requirement->type) }}" 
                                   placeholder="e.g., file_required">
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="weight" class="form-label">Weight (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" name="weight" value="{{ old('weight', $requirement->weight) }}" 
                                   min="1" max="100">
                            <div class="form-text">Importance weight for scoring (1-100)</div>
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="required" name="required" value="1" 
                               {{ old('required', $requirement->required) ? 'checked' : '' }}>
                        <label class="form-check-label" for="required">
                            This is a required requirement (failing this will reject the application)
                        </label>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.validation-requirements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Requirement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate validation rule examples based on validation type
    const validationTypeSelect = document.getElementById('validation_type');
    const validationRuleTextarea = document.getElementById('validation_rule');
    
    validationTypeSelect.addEventListener('change', function() {
        const examples = {
            'file_required': '{"file_type": "pdf", "max_size_mb": 10}',
            'file_size': '{"max_size_mb": 10}',
            'file_type': '{"allowed_types": ["pdf", "doc", "docx"]}',
            'email_format': '{"pattern": "^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$"}',
            'text_validation': '{"min_length": 2, "max_length": 255, "pattern": "^[A-Za-z0-9\\s&.-]+$"}',
            'content_check': '{"min_pages": 1, "max_pages": 50}',
            'document_verification': '{"file_type": "pdf", "max_size_mb": 10}',
            'financial_assessment': '{"min_revenue": 100000, "file_type": "pdf"}',
            'certification_check': '{"certification_type": "ISO", "file_type": "pdf"}',
            'insurance_verification': '{"coverage_amount": 1000000, "file_type": "pdf"}',
            'reference_check': '{"min_references": 3, "contact_info_required": true}'
        };
        
        if (examples[this.value]) {
            validationRuleTextarea.value = examples[this.value];
        }
    });
});
</script>
@endpush 