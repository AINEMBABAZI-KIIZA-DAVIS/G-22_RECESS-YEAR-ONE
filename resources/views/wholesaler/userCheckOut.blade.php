@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout</h2>

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <form action="{{ route('checkout.place') }}" method="POST">
        @csrf
        <div>
            <label>Address:</label><br>
            <input type="text" name="address" required>
        </div>
        <div>
            <label>City:</label><br>
            <input type="text" name="city" required>
        </div>
        <div>
            <label>Country:</label><br>
            <input type="text" name="country" required>
        </div>
        <div>
            <label>Postal Code:</label><br>
            <input type="text" name="postal_code" required>
        </div>

        <hr>
        <h3>Order Summary</h3>
        <ul>
            @foreach($items as $item)
                <li>{{ $item->product->name }} × {{ $item->quantity }} - ${{ number_format($item->product->price * $item->quantity, 2) }}</li>
            @endforeach
        </ul>
        <p><strong>Total:</strong> ${{ number_format($total, 2) }}</p>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>
@endsection
