@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-primary">
        {{ __('Supplier Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-4 bg-light min-vh-100">
    <div class="container">
        <div class="bg-white shadow rounded p-4">

            <!-- Header -->
            <header class="mb-4">
                <h3 class="h4 fw-bold text-primary">Welcome, {{ Auth::user()->name }} 👋</h3>
                <p class="text-muted">Manage your supply chain with real-time updates and easy tools.</p>
            </header>

            <!-- Statistics Section -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="bg-primary-subtle border-start border-primary border-4 p-3 rounded shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-primary text-uppercase">Pending Requests</small>
                                <div class="fs-3 fw-bold text-primary">{{ $pendingRequestsCount }}</div>
                            </div>
                            <i class="fas fa-hourglass-half text-primary fs-2"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-primary-subtle border-start border-info border-4 p-3 rounded shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-primary text-uppercase">Confirmed</small>
                                <div class="fs-3 fw-bold text-primary">{{ $confirmedRequestsCount }}</div>
                            </div>
                            <i class="fas fa-check-circle text-info fs-2"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-primary-subtle border-start border-success border-4 p-3 rounded shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-primary text-uppercase">Fulfilled</small>
                                <div class="fs-3 fw-bold text-primary">{{ $fulfilledRequestsCount }}</div>
                            </div>
                            <i class="fas fa-truck text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.create') }}" class="btn btn-primary d-block text-center p-4 shadow rounded-3 text-decoration-none">
                        <i class="fas fa-plus-circle fs-3 d-block mb-2"></i>
                        <h5 class="fw-semibold">New Supply Request</h5>
                        <p class="text-white-50 small">Start a new request from scratch.</p>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.list') }}" class="btn btn-outline-primary d-block text-center p-4 shadow rounded-3 text-decoration-none">
                        <i class="fas fa-list-alt fs-3 d-block mb-2"></i>
                        <h5 class="fw-semibold">My Requests</h5>
                        <p class="text-muted small">Track all your request statuses.</p>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('supplier.requests.confirmed') }}" class="btn btn-outline-primary d-block text-center p-4 shadow rounded-3 text-decoration-none">
                        <i class="fas fa-thumbs-up fs-3 d-block mb-2"></i>
                        <h5 class="fw-semibold">Confirmed Requests</h5>
                        <p class="text-muted small">See what’s approved by the manufacturer.</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
