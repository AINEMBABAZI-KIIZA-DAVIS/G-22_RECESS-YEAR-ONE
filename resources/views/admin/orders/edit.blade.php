@extends('layouts.admin_app')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Edit Order Status: #{{ $order->id }}</h3>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary-gradient">Save Changes</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary ms-2">Back</a>
    </form>
</div>
@endsection
