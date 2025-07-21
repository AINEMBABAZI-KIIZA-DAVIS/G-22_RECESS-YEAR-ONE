@extends('layouts.wholesaler_app')

@section('content')
<div class="max-w-xl mx-auto py-10">
    <h2 class="text-xl font-semibold mb-6">Place Order</h2>
    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-2">Select Product</label>
            <select name="product_id" class="w-full border px-4 py-2 rounded">
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - UGX {{ number_format($product->price, 2) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-2">Quantity</label>
            <input type="number" name="quantity" min="1" value="1" class="w-full border px-4 py-2 rounded">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Submit Order</button>
    </form>
</div>
@endsection
