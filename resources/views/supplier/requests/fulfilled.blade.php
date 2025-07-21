@extends('layouts.supplier_app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Fulfilled Requests</h2>
                <a href="{{ route('supplier.requests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Request
                </a>
            </div>

            @if($requests->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Notes</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <strong>{{ $request->product_name }}</strong>
                                            </td>
                                            <td>{{ $request->quantity }}</td>
                                            <td>
                                                @if($request->notes)
                                                    <span class="text-muted">{{ Str::limit($request->notes, 50) }}</span>
                                                @else
                                                    <span class="text-muted">No notes</span>
                                                @endif
                                            </td>
                                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-success">Fulfilled</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No Fulfilled Requests</h5>
                    <p class="text-muted">You don't have any fulfilled requests yet.</p>
                    <a href="{{ route('supplier.requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Your First Request
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection