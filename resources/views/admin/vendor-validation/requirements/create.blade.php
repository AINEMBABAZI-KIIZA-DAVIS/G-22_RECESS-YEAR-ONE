@extends('layouts.admin_app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6" style="color:var(--primary-color); font-weight: 700;">
                        <i class="fas fa-plus me-3"></i>Add Validation Requirement
                    </h1>
                    <p class="text-muted mb-0">Create a new validation requirement for vendor applications</p>
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
            <form action="{{ route('admin.validation-requirements.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Requirement Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., annual_revenue_pdf_required">
                            <div class="form-text">Unique identifier for the requirement (snake_case)</div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                <option value="">Select Category</option>
                                <option value="documentation" {{ old('category') == 'documentation' ? 'selected' : '' }}>Documentation</option>
                                <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="contact" {{ old('category') == 'contact' ? 'selected' : '' }}>Contact</option>
                                <option value="financial" {{ old('category') == 'financial' ? 'selected' : '' }}>Financial</option>
                                <option value="compliance" {{ old('category') == 'compliance' ? 'selected' : '' }}>Compliance</option>
                                <option value="legal" {{ old('category') == 'legal' ? 'selected' : '' }}>Legal</option>
                                <option value="quality" {{ old('category') == 'quality' ? 'selected' : '' }}>Quality</option>
                                <option value="insurance" {{ old('category') == 'insurance' ? 'selected' : '' }}>Insurance</option>
                                <option value="reputation" {{ old('category') == 'reputation' ? 'selected' : '' }}>Reputation</option>
                            </select>
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
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="file_required" {{ old('type') == 'file_required' ? 'selected' : '' }}>File Required</option>
                                <option value="file_size" {{ old('type') == 'file_size' ? 'selected' : '' }}>File Size</option>
                                <option value="file_type" {{ old('type') == 'file_type' ? 'selected' : '' }}>File Type</option>
                                <option value="email_format" {{ old('type') == 'email_format' ? 'selected' : '' }}>Email Format</option>
                                <option value="text_validation" {{ old('type') == 'text_validation' ? 'selected' : '' }}>Text Validation</option>
                                <option value="content_check" {{ old('type') == 'content_check' ? 'selected' : '' }}>Content Check</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="weight" class="form-label">Weight (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" name="weight" value="{{ old('weight', 10) }}" 
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
                               {{ old('required') ? 'checked' : '' }}>
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
                        <i class="fas fa-save me-2"></i>Create Requirement
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
            'content_check': '{"min_pages": 1, "max_pages": 50}'
        };
        
        if (examples[this.value]) {
            validationRuleTextarea.value = examples[this.value];
        }
    });
});
</script>
@endpush 