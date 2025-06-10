@extends('layouts.app') {{-- Assuming a general app layout --}}

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Supply Request Details') }} #{{ $supplyRequest->id }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Request ID: #{{ $supplyRequest->id }}</h3>
                        <p class="text-sm text-gray-500">Requested on: {{ $supplyRequest->created_at->format('F d, Y \a\t H:i A') }}</p>
                    </div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        @if($supplyRequest->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                        @if($supplyRequest->status == 'confirmed_by_manufacturer') bg-green-100 text-green-800 @endif
                        @if($supplyRequest->status == 'rejected') bg-red-100 text-red-800 @endif
                        @if($supplyRequest->status == 'fulfilled') bg-blue-100 text-blue-800 @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $supplyRequest->status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-1">Product Information</h4>
                        <p><strong class="text-gray-600">Name/Description:</strong> {{ $supplyRequest->product_name }}</p>
                        <p><strong class="text-gray-600">Quantity Requested:</strong> {{ $supplyRequest->quantity }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-1">Dates</h4>
                        <p><strong class="text-gray-600">Confirmed by Manufacturer:</strong> {{ $supplyRequest->confirmed_at ? $supplyRequest->confirmed_at->format('F d, Y') : 'Not yet confirmed' }}</p>
                        <p><strong class="text-gray-600">Fulfilled:</strong> {{ $supplyRequest->fulfilled_at ? $supplyRequest->fulfilled_at->format('F d, Y') : 'Not yet fulfilled' }}</p>
                    </div>
                </div>

                @if($supplyRequest->notes)
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-700 mb-1">Your Notes:</h4>
                    <p class="text-gray-600 bg-gray-50 p-3 rounded-md">{{ $supplyRequest->notes }}</p>
                </div>
                @endif

                @if($supplyRequest->manufacturer_notes)
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-700 mb-1">Manufacturer Notes:</h4>
                    <p class="text-gray-600 bg-yellow-50 p-3 rounded-md">{{ $supplyRequest->manufacturer_notes }}</p>
                </div>
                @endif

                <div class="mt-8 border-t pt-6">
                    <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:text-blue-900 underline">
                        &larr; Back to Requests List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection