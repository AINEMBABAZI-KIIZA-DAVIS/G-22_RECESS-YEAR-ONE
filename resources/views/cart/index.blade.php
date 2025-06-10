@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Your Cart</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(isset($items))
        @foreach($items as $item)
            <div class="cart-item mb-3">
                <h4>{{ $item->product->name }}</h4>
                <p>Price: ${{ $item->product->price }}</p>
                <p>Quantity: {{ $item->quantity }}</p>
                <p>Subtotal: ${{ $item->product->price * $item->quantity }}</p>
                <form action="{{ route('cart.remove', $item->product_id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                </form>
            </div>
        @endforeach
    @elseif(isset($sessionCart))
        @foreach($sessionCart as $id => $item)
            <div class="cart-item mb-3">
                <h4>{{ $item['name'] }}</h4>
                <p>Price: ${{ $item['price'] }}</p>
                <p>Quantity: {{ $item['quantity'] }}</p>
                <p>Subtotal: ${{ $item['price'] * $item['quantity'] }}</p>
                <form action="{{ route('cart.remove', $id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                </form>
            </div>
        @endforeach
    @endif
    <h3>Total: ${{ number_format($total, 2) }}</h3>
    <a href="{{ route('checkout.index') }}" class="btn btn-primary">Proceed to Checkout</a>
</div>
@endsection
