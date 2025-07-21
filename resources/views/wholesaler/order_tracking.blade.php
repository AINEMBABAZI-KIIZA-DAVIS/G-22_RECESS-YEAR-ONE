@extends('layouts.wholesaler_app')
@section('title', 'Order Tracking')
@section('content')
    <div class="container py-4">
        <h2 class="mb-4" style="color:#a0522d;"><i class="bi bi-truck me-2"></i>Order Tracking</h2>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Status</th>
                        <th>Pipeline</th>
                        <th>Date</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><a href="{{ route('wholesaler.orders.show', $order->id) }}">#{{ $order->id }}</a></td>
                            <td><span
                                    class="badge bg-{{ $order->status == 'Delivered' ? 'success' : ($order->status == 'Pending' ? 'warning' : 'info') }}">{{ $order->status }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $stages = ['Pending', 'Confirmed', 'In Production', 'Quality Check', 'Out for Delivery', 'Delivered'];
                                        $current = array_search($order->status, $stages);
                                    @endphp
                                    @foreach($stages as $i => $stage)
                                        <span
                                            class="badge rounded-pill mx-1
                                                @if($order->status == 'Delivered')
                                                    bg-success text-white fw-bold
                                                @elseif($i < $current)
                                                    bg-primary
                                                @elseif($i == $current)
                                                    bg-success text-white fw-bold
                                                @else
                                                    bg-secondary
                                                @endif
                                            "
                                            @if($order->status == 'Delivered' || $i == $current) style="font-size:1.1em; border:2px solid #198754;" @endif
                                        >
                                            @if($order->status == 'Delivered')
                                                <i class="bi bi-check-circle me-1"></i>
                                            @elseif($i < $current)
                                                <i class="bi bi-check2 me-1"></i>
                                            @elseif($i == $current)
                                                <i class="bi bi-arrow-right-circle me-1"></i>
                                            @endif
                                            {{ $stage }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>UGX {{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection