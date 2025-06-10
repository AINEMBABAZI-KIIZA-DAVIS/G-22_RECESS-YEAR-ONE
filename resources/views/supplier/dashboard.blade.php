@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-blue-800 leading-tight">
        {{ __('Supplier Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-12 bg-blue-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-8">

            <header class="mb-8">
                <h3 class="text-2xl font-bold text-blue-900">Welcome, {{ Auth::user()->name }} 👋</h3>
                <p class="text-blue-700 mt-1">Manage your supply chain with real-time updates and easy tools.</p>
            </header>

            {{-- Statistics Section --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                <div class="bg-blue-100 border-l-4 border-blue-500 p-5 rounded-md shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-800 uppercase tracking-wide">Pending Requests</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $pendingRequestsCount }}</p>
                        </div>
                        <i class="fas fa-hourglass-half text-blue-600 text-3xl" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="bg-blue-100 border-l-4 border-blue-600 p-5 rounded-md shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-800 uppercase tracking-wide">Confirmed</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $confirmedRequestsCount }}</p>
                        </div>
                        <i class="fas fa-check-circle text-blue-600 text-3xl" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="bg-blue-100 border-l-4 border-blue-700 p-5 rounded-md shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-800 uppercase tracking-wide">Fulfilled</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $fulfilledRequestsCount }}</p>
                        </div>
                        <i class="fas fa-truck text-blue-600 text-3xl" aria-hidden="true"></i>
                    </div>
                </div>
            </section>

            {{-- Actions Section --}}
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('supplier.requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg text-center shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    <i class="fas fa-plus-circle text-2xl mb-2"></i>
                    <h4 class="font-semibold text-lg">New Supply Request</h4>
                    <p class="text-sm text-blue-100">Start a new request from scratch.</p>
                </a>

                <a href="{{ route('supplier.requests.list') }}" class="bg-white border border-blue-200 hover:border-blue-400 text-blue-800 p-6 rounded-lg text-center shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-100">
                    <i class="fas fa-list-alt text-2xl mb-2"></i>
                    <h4 class="font-semibold text-lg">My Requests</h4>
                    <p class="text-sm text-blue-600">Track all your request statuses.</p>
                </a>

                <a href="{{ route('supplier.requests.confirmed') }}" class="bg-white border border-blue-200 hover:border-blue-400 text-blue-800 p-6 rounded-lg text-center shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-100 lg:col-span-1 md:col-span-2">
                    <i class="fas fa-thumbs-up text-2xl mb-2"></i>
                    <h4 class="font-semibold text-lg">Confirmed Requests</h4>
                    <p class="text-sm text-blue-600">See what’s approved by the manufacturer.</p>
                </a>
            </section>

        </div>
    </div>
</div>

{{-- FontAwesome CDN --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
