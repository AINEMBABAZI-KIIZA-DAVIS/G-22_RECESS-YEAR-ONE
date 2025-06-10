@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Products</h2>
    <form method="GET" action="{{ route('products.index') }}">
        <select name="category" onchange="this.form.submit()">
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
    </form>
    <div class="row mt-4">
        @foreach($products as $product)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">${{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
