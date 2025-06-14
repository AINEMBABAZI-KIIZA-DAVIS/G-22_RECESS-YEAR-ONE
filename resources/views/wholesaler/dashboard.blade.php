@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-blue-800 leading-tight">
        {{ __('Wholesaler Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12 bg-blue-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-8">
            <header class="mb-8">
                <h3 class="text-2xl font-bold text-blue-900">Welcome, {{ Auth::user()->name }} 👋</h3>
                <p class="text-blue-700 mt-1">Manage your wholesale orders and inventory with ease.</p>
            </header>

            <section class="mb-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg text-center shadow-md transition transform hover:scale-105">
                        <i class="fas fa-store text-2xl mb-2"></i>
                        <h4 class="font-semibold text-lg">Shop Products</h4>
                        <p class="text-sm text-blue-100">Browse and order products for your business.</p>
                    </a>
                    <a href="{{ route('cart.show') }}" class="bg-white border border-blue-200 hover:border-blue-400 text-blue-800 p-6 rounded-lg text-center shadow-md transition transform hover:scale-105">
                        <i class="fas fa-shopping-cart text-2xl mb-2"></i>
                        <h4 class="font-semibold text-lg">View Cart</h4>
                        <p class="text-sm text-blue-600">See your current cart and proceed to checkout.</p>
                    </a>
                    <a href="{{ route('checkout.index') }}" class="bg-white border border-blue-200 hover:border-blue-400 text-blue-800 p-6 rounded-lg text-center shadow-md transition transform hover:scale-105">
                        <i class="fas fa-credit-card text-2xl mb-2"></i>
                        <h4 class="font-semibold text-lg">Checkout</h4>
                        <p class="text-sm text-blue-600">Complete your purchase and place orders.</p>
                    </a>
                </div>
            </section>

            <section>
                <h4 class="text-xl font-semibold text-blue-900 mb-4">Available Products</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded mb-3">
                        <h5 class="font-bold text-lg text-blue-800">{{ $product->name }}</h5>
                        <p class="text-gray-600 text-sm mb-2">{{ $product->description }}</p>
                        <p class="font-semibold text-green-600 mb-3">UGX {{ number_format($product->price) }}</p>
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
