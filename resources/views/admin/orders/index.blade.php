@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Orders</h2>
        {{-- Typically, admins don't create orders from scratch here, but manage existing ones --}}
        {{-- <a href="{{ route('admin.orders.create') }}" class="btn btn-primary-gradient"><i class="fas fa-plus me-2"></i>Add Order</a> --}}
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Total Items</th>
                            <th>Order Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_email }}</td>
                            <td><span class="badge bg-{{ strtolower($order->status) == 'delivered' ? 'success' : (strtolower($order->status) == 'cancelled' ? 'danger' : (strtolower($order->status) == 'shipped' ? 'info' : (strtolower($order->status) == 'processing' ? 'primary' : 'secondary'))) }}">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->items->sum('quantity') }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info me-1" title="View Details"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary me-1" title="Update Status"><i class="fas fa-truck-fast"></i></a>
                                {{-- Delete/Cancel form --}}
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to mark this order as cancelled? This action might be irreversible depending on setup.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel Order"><i class="fas fa-times-circle"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-primary-gradient { /* Ensure this class is defined in your main layout or here */
        background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 8px rgba(53, 122, 255, 0.10);
        transition: background 0.3s, box-shadow 0.3s;
    }
    .btn-primary-gradient:hover {
        background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
        color: #fff;
    }
    .table th {
        font-weight: 600;
        color: #495057;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.7em;
    }
</style>
@endpush