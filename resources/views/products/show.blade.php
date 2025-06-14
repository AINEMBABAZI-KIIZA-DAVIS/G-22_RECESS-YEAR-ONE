@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p>Category: {{ $product->category }}</p>
            <p>Price: ${{ number_format($product->price, 2) }}</p>
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
@endsection
