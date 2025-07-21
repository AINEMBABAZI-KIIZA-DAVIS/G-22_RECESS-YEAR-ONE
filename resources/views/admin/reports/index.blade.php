@extends('layouts.admin_app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Reports</h2>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Orders</div>
                    <div class="card-body">
                        <p>Today: <strong>{{ $ordersDaily }}</strong> (UGX {{ number_format($ordersDailyTotal, 2) }})</p>
                        <p>This Week: <strong>{{ $ordersWeekly }}</strong>
                            (UGX {{ number_format($ordersWeeklyTotal, 2) }})</p>
                        <p>This Month: <strong>{{ $ordersMonthly }}</strong>
                            (UGX {{ number_format($ordersMonthlyTotal, 2) }})</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Products Added</div>
                    <div class="card-body">
                        <p>Today: <strong>{{ $productsDaily }}</strong></p>
                        <p>This Week: <strong>{{ $productsWeekly }}</strong></p>
                        <p>This Month: <strong>{{ $productsMonthly }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Workers Added</div>
                    <div class="card-body">
                        <p>Today: <strong>{{ $workersDaily }}</strong></p>
                        <p>This Week: <strong>{{ $workersWeekly }}</strong></p>
                        <p>This Month: <strong>{{ $workersMonthly }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">Top 5 Products by Sales (This Month)</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Total Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->total_sold }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No sales data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection