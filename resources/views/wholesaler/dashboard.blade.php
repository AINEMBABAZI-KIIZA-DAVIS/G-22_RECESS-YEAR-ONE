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
                <h3 class="text-2xl font-bold text-blue-900 text-left">Welcome, {{ Auth::user()->name }} 👋</h3>
                <p class="text-blue-700 mt-1 text-left">Manage your wholesale orders and inventory with ease.</p>
            </header>

            <section class="mb-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Shop Products -->
        <a href="{{ route('wholesaler.products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-blue p-6 rounded-lg text-left shadow-md transition transform hover:scale-105">
            <div class="flex flex-col items-left gap-1">
                <i class="fas fa-store text-2xl"></i>
                <h4 class="font-semibold text-lg mt-1">Shop Products</h4>
            </div>
            <p class="text-sm text-blue-100 mt-2">Browse and order products for your business.</p>
        </a>

        <!-- View Cart -->
        <a href="{{ route('wholesaler.cart.show') }}" class="bg-white border border-blue-200 hover:border-blue-400 text-blue-800 p-6 rounded-lg text-left shadow-md transition transform hover:scale-105">
            <div class="flex flex-col items-left gap-1">
                <i class="fas fa-shopping-cart text-2xl"></i>
                <h4 class="font-semibold text-lg mt-1">View Cart</h4>
            </div>
            <p class="text-sm text-blue-600 mt-2">See your current cart and proceed to checkout.</p>
        </a>

        <!-- Checkout -->
        <a href="{{ route('wholesaler.checkout.index') }}" class="bg-white border border-blue-200 hover:border-blue-400 text-blue-800 p-6 rounded-lg text-left shadow-md transition transform hover:scale-105">
            <div class="flex flex-col items-left gap-1">
                <i class="fas fa-credit-card text-2xl"></i>
                <h4 class="font-semibold text-lg mt-1">Checkout</h4>
            </div>
            <p class="text-sm text-blue-600 mt-2">Complete your purchase and place orders.</p>
        </a>
    </div>
</section>


            <section>
                <h4 class="text-xl font-semibold text-blue-900 mb-4">Available Products</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-md transition">
                        <div class="h-48 overflow-hidden mb-3 rounded-lg"> <!-- Fixed height container -->
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                class="w-full h-32 object-cover object-center"> <!-- Uniform image sizing -->
                        </div>
                        <h5 class="font-bold text-lg text-blue-800">{{ $product->name }}</h5>
                        <p class="text-gray-600 text-sm mb-2">{{ $product->description }}</p>
                        <p class="font-semibold text-green-600 mb-3">UGX {{ number_format($product->price) }}</p>
                        <form method="POST" action="{{ route('wholesaler.cart.add', ['productId' => $product->id]) }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-blue px-4 py-2 rounded hover:bg-blue-700 transition">
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