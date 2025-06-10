@extends('layouts.admin_app') {{-- Assuming you have an admin layout, or use your main app layout --}}

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Products</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary-gradient"><i class="fas fa-plus me-2"></i>Add Product</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Low Stock Threshold</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none fw-medium">{{ $product->name }}</a>
                            </td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity_in_stock }}</td>
                            <td>{{ $product->low_stock_threshold ?? 'N/A' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info me-1" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No products found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $products->links() }}
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
</style>
@endpush