@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-secondary">
        {{ __('My Supply Requests') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container-xl">
        <div class="card shadow-sm">
            <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fs-5 fw-medium mb-0">All My Requests</h3>
                    <a href="{{ route('supplier.requests.create') }}" class="btn btn-primary btn-sm text-uppercase fw-semibold px-3 py-2">
                        {{ __('New Request') }}
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success rounded-2">
                        {{ session('success') }}
                    </div>
                @endif

                @if($requests->isEmpty())
                    <p class="text-secondary">You have not made any supply requests yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light text-secondary text-uppercase small">
                                <tr>
                                    <th scope="col">Request ID</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Requested On</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                <tr>
                                    <td class="text-secondary">#{{ $request->id }}</td>
                                    <td class="fw-semibold">{{ $request->product_name }}</td>
                                    <td class="text-secondary">{{ $request->quantity }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($request->status) {
                                                'pending' => 'bg-warning bg-opacity-10 text-warning',
                                                'confirmed_by_manufacturer' => 'bg-success bg-opacity-10 text-success',
                                                'rejected' => 'bg-danger bg-opacity-10 text-danger',
                                                'fulfilled' => 'bg-primary bg-opacity-10 text-primary',
                                                default => 'bg-secondary bg-opacity-10 text-secondary',
                                            };
                                        @endphp
                                        <span class="badge rounded-pill px-2 py-1 fs-7 fw-semibold {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-secondary">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('supplier.requests.show', $request->id) }}" class="link-primary text-decoration-none small">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
