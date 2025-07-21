@extends('layouts.wholesaler_app')
@section('title', 'Analytics')
@section('content')
    <div class="container py-4">
        <h2 class="mb-4" style="color:#a0522d;"><i class="bi bi-bar-chart me-2"></i>Analytics</h2>
        
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        @endif
        
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Monthly Spend</h5>
                        <p class="card-text h4">UGX {{ number_format($monthlySpend ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Avg. Fulfillment Speed</h5>
                        <p class="card-text h4">{{ $fulfillmentSpeed ? round($fulfillmentSpeed, 1) . ' hrs' : 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Top Products</h5>
                        @if($topProducts && $topProducts->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($topProducts as $item)
                                    @if($item->product)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $item->product->name }}</span>
                                            <span class="badge bg-primary rounded-pill">{{ $item->total }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No order data available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        @if(Auth::user()->orders && Auth::user()->orders->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-cream text-brown"><b>Order History</b></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(Auth::user()->orders as $order)
                                <tr>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td><span
                                            class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>UGX {{ number_format($order->total_amount ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No Orders Yet</h5>
                    <p class="text-muted">You haven't placed any orders yet.</p>
                    <a href="{{ route('wholesaler.order.form') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Place Your First Order
                    </a>
                </div>
            </div>
        @endif
        
        <a href="{{ route('wholesaler.export.orders', ['type' => 'excel']) }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel me-2"></i>Export as Excel
        </a>
    </div>
    
    <style>
        .bg-cream {
            background: #fff8e1 !important;
        }

        .text-brown {
            color: #a0522d !important;
        }
    </style>
@endsection