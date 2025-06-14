@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h2 class="text-xl font-semibold mb-6">Your Cart</h2>
    @if($cart->isEmpty())
        <p class="text-gray-500">Your cart is currently empty.</p>
    @else
        <table class="w-full mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $item->product->name }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">${{ number_format($item->product->price, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-right">
            <a href="{{ route('checkout.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Proceed to Checkout</a>
        </div>
    @endif
</div>
@endsection
