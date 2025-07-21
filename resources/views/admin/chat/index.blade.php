@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-comments me-2"></i>Chat Management
                </h2>
                <div>
                    <a href="{{ route('admin.chat.sent') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-paper-plane me-2"></i>View Sent Messages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Suppliers Chat Area -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-tie fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Suppliers</h5>
                            <small>Manage supplier communications</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Active Suppliers</span>
                        <span class="badge bg-primary">{{ $contacts->where('role', 'supplier')->count() }}</span>
                    </div>
                    <p class="text-muted small mb-3">
                        Chat with suppliers to discuss inventory, orders, and supply chain management.
                    </p>
                    <div class="d-grid">
                        <a href="{{ url('/chatify') }}" class="btn btn-primary">
                            <i class="fas fa-comments me-2"></i>Open Chatify
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wholesalers Chat Area -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-industry fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Wholesalers</h5>
                            <small>Manage wholesaler communications</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Active Wholesalers</span>
                        <span class="badge bg-success">{{ $contacts->where('role', 'wholesaler')->count() }}</span>
                    </div>
                    <p class="text-muted small mb-3">
                        Chat with wholesalers to discuss bulk orders, pricing, and distribution.
                    </p>
                    <div class="d-grid">
                        <a href="{{ url('/chatify') }}" class="btn btn-success">
                            <i class="fas fa-comments me-2"></i>Open Chatify
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendors Chat Area -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-truck fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Vendors</h5>
                            <small>Manage vendor communications</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Active Vendors</span>
                        <span class="badge bg-info">{{ $contacts->where('role', 'vendor')->count() }}</span>
                    </div>
                    <p class="text-muted small mb-3">
                        Chat with vendors to discuss partnerships, services, and collaborations.
                    </p>
                    <div class="d-grid">
                        <a href="{{ url('/chatify') }}" class="btn btn-info">
                            <i class="fas fa-comments me-2"></i>Open Chatify
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Chat Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $contacts->where('role', 'supplier')->count() }}</h4>
                                <small class="text-muted">Suppliers</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ $contacts->where('role', 'wholesaler')->count() }}</h4>
                                <small class="text-muted">Wholesalers</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-end">
                                <h4 class="text-info mb-1">{{ $contacts->where('role', 'vendor')->count() }}</h4>
                                <small class="text-muted">Vendors</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div>
                                <h4 class="text-warning mb-1">{{ $contacts->sum('unread') }}</h4>
                                <small class="text-muted">Unread Messages</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Chat Activity
                    </h6>
                </div>
                <div class="card-body">
                    @if($contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Contact</th>
                                        <th>Role</th>
                                        <th>Last Message</th>
                                        <th>Unread</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contacts->take(5) as $contact)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $contact->avatar ? asset('storage/' . $contact->avatar) : asset('images/default-avatar.png') }}"
                                                         alt="{{ $contact->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                    <span>{{ $contact->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $contact->role == 'supplier' ? 'primary' : ($contact->role == 'wholesaler' ? 'success' : 'info') }}">
                                                    {{ ucfirst($contact->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $lastMessage = \App\Models\Message::where(function($q) use ($contact) {
                                                        $q->where('sender_id', auth()->id())->where('receiver_id', $contact->id);
                                                    })->orWhere(function($q) use ($contact) {
                                                        $q->where('sender_id', $contact->id)->where('receiver_id', auth()->id());
                                                    })->orderBy('created_at', 'desc')->first();
                                                @endphp
                                                @if($lastMessage)
                                                    <small class="text-muted">{{ $lastMessage->created_at->diffForHumans() }}</small>
                                                @else
                                                    <small class="text-muted">No messages</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($contact->unread > 0)
                                                    <span class="badge bg-danger">{{ $contact->unread }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{
                                                    $contact->role == 'wholesaler' ? route('admin.chat.wholesaler', $contact->id) :
                                                    ($contact->role == 'supplier' ? route('admin.chat.supplier', $contact->id) :
                                                    ($contact->role == 'vendor' ? route('admin.chat.vendor', $contact->id) : '#'))
                                                }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-comments me-1"></i>Chat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>No contacts available</h5>
                            <p class="text-muted">There are no users to chat with at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.card-header {
    border-bottom: none;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
}
</style>
@endpush
