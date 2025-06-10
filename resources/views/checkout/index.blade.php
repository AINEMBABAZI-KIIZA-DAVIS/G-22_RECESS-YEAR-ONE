@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Checkout</h2>
    <form action="{{ route('checkout.place') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">Shipping Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <h4>Order Summary</h4>
        <ul>
            @foreach($items as $item)
                <li>{{ $item['name'] }} x {{ $item['quantity'] }} - ${{ $item['subtotal'] }}</li>
            @endforeach
        </ul>
        <h5>Total: ${{ number_format($total, 2) }}</h5>
        <button type="submit" class="btn btn-success">Place Order</button>
    </form>
</div>
@endsection
