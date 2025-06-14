@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10">
    <h2 class="text-xl font-semibold mb-6">Checkout</h2>
    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Shipping Address</label>
            <textarea name="shipping_address" class="w-full border rounded px-4 py-2" required></textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Payment Method</label>
            <select name="payment_method" class="w-full border rounded px-4 py-2">
                <option value="cash_on_delivery">Cash on Delivery</option>
                <option value="mobile_money">Mobile Money</option>
            </select>
        </div>
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Place Order</button>
    </form>
</div>
@endsection
