@extends('layouts.supplier_app')

@section('content')
<div class="container-fluid chat-container">
    <div class="row h-100">
        <!-- Contacts Sidebar -->
        <div class="col-md-4 col-lg-3 px-0">
            <div class="contacts-sidebar h-100 d-flex flex-column">
                <!-- Filter and Search -->
                <div class="sidebar-header p-3 border-bottom">
                    <h5 class="mb-3">Messages</h5>
                    
                    <div class="search-box mt-3">
                        <input type="text" class="form-control rounded-pill" placeholder="Search contacts...">
                    </div>
                </div>

                <!-- Contact List -->
                <div class="contacts-list flex-grow-1 overflow-auto">
                    @foreach($contacts as $contact)
                        <a href="{{ route('supplier.chat.with', $contact->id) }}" 
                           class="contact-item d-flex align-items-center p-3 border-bottom {{ $contact->unread ? 'unread' : '' }}"
                           style="text-decoration: none; color: inherit;">
                            <div class="avatar-wrapper mr-3">
                                <img src="{{ $contact->avatar ? asset('storage/' . $contact->avatar) : asset('images/default-avatar.png') }}"
                                     alt="{{ $contact->name }}" class="rounded-circle avatar">
                            </div>
                            <div class="contact-info flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $contact->name }}</h6>
                                    @if($contact->unread)
                                        <span class="badge badge-pill badge-primary">{{ $contact->unread }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ ucfirst($contact->role) }}</small>
                                <p class="mb-0 text-truncate">
                                    @php
                                        $lastMessage = \App\Models\Message::where(function($q) use ($contact) {
                                            $q->where('sender_id', auth()->id())->where('receiver_id', $contact->id);
                                        })->orWhere(function($q) use ($contact) {
                                            $q->where('sender_id', $contact->id)->where('receiver_id', auth()->id());
                                        })->orderBy('created_at', 'desc')->first();
                                    @endphp
                                    @if($lastMessage)
                                        {{ Str::limit($lastMessage->message, 30) }}
                                    @else
                                        No messages yet
                                    @endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8 col-lg-9 px-0">
            <div class="chat-area h-100 d-flex flex-column">
                <!-- Chat Header -->
                <div class="chat-header d-flex align-items-center p-3 border-bottom">
                    <div class="current-contact d-flex align-items-center">
                        <i class="fas fa-user-shield fa-2x text-primary mr-3"></i>
                        <div>
                            <h5 class="mb-0">Administrators</h5>
                            <small class="text-muted">Select an admin to chat</small>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('supplier.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Admins List -->
                <div class="messages-container flex-grow-1 overflow-auto p-3">
                    @if($admins->count() > 0)
                        <div class="row">
                            @foreach($admins as $admin)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('images/default-avatar.png') }}"
                                                 alt="{{ $admin->name }}" class="rounded-circle avatar mb-3">
                                            <h6 class="card-title">{{ $admin->name }}</h6>
                                            <p class="card-text text-muted">{{ $admin->email }}</p>
                                            <a href="{{ route('supplier.chat.with', $admin->id) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-comments"></i> Chat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-user-shield fa-3x mb-3"></i>
                            <h4>No Administrators Available</h4>
                            <p>There are no administrators available for chat.</p>
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
.chat-container {
    height: calc(100vh - 100px);
}

.contacts-sidebar {
    border-right: 1px solid #dee2e6;
}

.contact-item {
    cursor: pointer;
    transition: background-color 0.2s;
}

.contact-item:hover {
    background-color: #f8f9fa;
}

.contact-item.active {
    background-color: #e3f2fd;
}

.contact-item.unread {
    background-color: #fff3cd;
}

.avatar {
    width: 40px;
    height: 40px;
    object-fit: cover;
}

.messages-container {
    height: 400px;
    overflow-y: auto;
}
</style>
@endpush 