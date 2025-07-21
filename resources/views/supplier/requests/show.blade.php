@extends('layouts.supplier_app')

@section('header')
    <h2 class="fw-semibold fs-4 text-secondary">
        {{ __('Supply Request Details') }} #{{ $supplyRequest->id }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container-lg">
        <div class="card shadow-sm">
            <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h3 class="fs-5 fw-medium mb-1">Request ID: #{{ $supplyRequest->id }}</h3>
                        <p class="small text-secondary mb-0">Requested on: {{ $supplyRequest->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @php
                        $statusClass = match($supplyRequest->status) {
                            'pending' => 'bg-warning bg-opacity-10 text-warning',
                            'confirmed_by_manufacturer' => 'bg-success bg-opacity-10 text-success',
                            'rejected' => 'bg-danger bg-opacity-10 text-danger',
                            'fulfilled' => 'bg-primary bg-opacity-10 text-primary',
                            default => 'bg-secondary bg-opacity-10 text-secondary',
                        };
                    @endphp
                    <span class="badge rounded-pill px-3 py-1 fs-7 fw-semibold {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $supplyRequest->status)) }}
                    </span>
                </div>

                <div class="row gy-4">
                    <div class="col-md-6">
                        <h4 class="fw-semibold text-secondary mb-2">Product Information</h4>
                        <p><strong class="text-secondary">Name/Description:</strong> {{ $supplyRequest->product_name }}</p>
                        <p><strong class="text-secondary">Quantity Requested:</strong> {{ $supplyRequest->quantity }}</p>
                    </div>
                    <div class="col-md-6">
                        <h4 class="fw-semibold text-secondary mb-2">Dates</h4>
                        <p><strong class="text-secondary">Confirmed by Manufacturer:</strong> {{ $supplyRequest->confirmed_at ? $supplyRequest->confirmed_at->format('F d, Y') : 'Not yet confirmed' }}</p>
                        <p><strong class="text-secondary">Fulfilled:</strong> {{ $supplyRequest->fulfilled_at ? $supplyRequest->fulfilled_at->format('F d, Y') : 'Not yet fulfilled' }}</p>
                    </div>
                </div>

                @if($supplyRequest->notes)
                <div class="mt-4">
                    <h4 class="fw-semibold text-secondary mb-2">Your Notes:</h4>
                    <p class="text-secondary bg-light p-3 rounded">{{ $supplyRequest->notes }}</p>
                </div>
                @endif

                @if($supplyRequest->manufacturer_notes)
                <div class="mt-4">
                    <h4 class="fw-semibold text-secondary mb-2">Manufacturer Notes:</h4>
                    <p class="text-warning bg-warning bg-opacity-10 p-3 rounded">{{ $supplyRequest->manufacturer_notes }}</p>
                </div>
                @endif

                <div class="mt-5 border-top pt-4">
                    <a href="{{ url()->previous() }}" class="small text-primary text-decoration-underline">
                        &larr; Back to Requests List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
