@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-secondary">
        {{ __('Confirmed Supply Requests') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body border-bottom">
                <h3 class="h5 mb-4">Requests Confirmed by Manufacturer</h3>

                @if($requests->isEmpty())
                    <p class="text-muted">You have no supply requests currently confirmed by the manufacturer.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light text-uppercase text-secondary small">
                                <tr>
                                    <th scope="col">Request ID</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Confirmed On</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                <tr>
                                    <td>#{{ $request->id }}</td>
                                    <td class="fw-medium">{{ $request->product_name }}</td>
                                    <td>{{ $request->quantity }}</td>
                                    <td>
                                        <span class="badge bg-success text-uppercase small">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $request->confirmed_at ? $request->confirmed_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('supplier.requests.show', $request) }}" class="link-primary">
                                            View Details
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

                <div class="mt-4">
                    <a href="{{ route('supplier.dashboard') }}" class="small text-decoration-underline text-secondary">
                        &larr; Back to Supplier Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
