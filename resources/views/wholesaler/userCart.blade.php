<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            background: #fff;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item h4 {
            margin: 0;
        }

        .cart-item p {
            margin: 5px 0;
            color: #555;
        }

        .remove-form {
            margin-top: 10px;
        }

        .total {
            text-align: right;
            margin-top: 30px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff7f50;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #e76e40;
        }

        .empty-cart {
            text-align: center;
            color: #888;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Your Shopping Cart</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @php
        $hasItems = isset($items) ? $items->count() > 0 : isset($sessionCart) && count($sessionCart) > 0;
    @endphp

    @if($hasItems)

        {{-- Authenticated users --}}
        @isset($items)
            @foreach($items as $item)
                <div class="cart-item">
                    <div class="cart-item-details">
                        <h4>{{ $item->product->name }}</h4>
                        <p>Price: ${{ number_format($item->product->price, 2) }}</p>
                        <p>Quantity: {{ $item->quantity }}</p>
                        <p>Subtotal: ${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        <form method="POST" action="{{ route('cart.remove', $item->product->id) }}" class="remove-form">
                            @csrf
                            <button type="submit" class="btn">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endisset

        {{-- Guest users --}}
        @isset($sessionCart)
            @foreach($sessionCart as $id => $item)
                <div class="cart-item">
                    <div class="cart-item-details">
                        <h4>{{ $item['name'] }}</h4>
                        <p>Price: ${{ number_format($item['price'], 2) }}</p>
                        <p>Quantity: {{ $item['quantity'] }}</p>
                        <p>Subtotal: ${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        <form method="POST" action="{{ route('cart.remove', $id) }}" class="remove-form">
                            @csrf
                            <button type="submit" class="btn">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endisset

        <div class="total">
            Total: ${{ number_format($total, 2) }}
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <a href="{{ route('products.index') }}" class="btn">Continue Shopping</a>
            <a href="{{ route('checkout.form') }}" class="btn">Proceed to Checkout</a>
        </div>

    @else
        <div class="empty-cart">
            <p>Your cart is currently empty.</p>
            <a href="{{ route('products.index') }}" class="btn">Browse Products</a>
        </div>
    @endif
</div>
</body>
</html>
