@extends('layouts.admin_app')

@section('title', 'AI-Powered Analytics')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="fas fa-chart-line me-3"></i>AI-Powered Analytics Dashboard
            </h2>
            <p class="text-muted mb-0">Advanced analytics powered by machine learning</p>
        </div>
        <div>
            <button type="button" class="btn btn-outline-primary" onclick="refreshAnalytics()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <button type="button" class="btn btn-outline-success" onclick="exportAnalytics()">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Main Analytics Cards -->
    <div class="row mb-4">
        <!-- Demand Forecast -->
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-chart-line text-primary fs-4 me-2"></i>
                        <h6 class="mb-0">Demand Forecast</h6>
                    </div>
                    <h3 class="text-primary mb-2">{{ number_format($analyticsData['demand_forecast']['predicted_demand'] ?? 0) }}</h3>
                    <p class="text-muted mb-1">
                        <i class="fas fa-arrow-{{ ($analyticsData['demand_forecast']['trend'] ?? 'increasing') == 'increasing' ? 'up' : 'down' }} text-{{ ($analyticsData['demand_forecast']['trend'] ?? 'increasing') == 'increasing' ? 'success' : 'danger' }}"></i>
                        {{ ucfirst($analyticsData['demand_forecast']['trend'] ?? 'increasing') }} trend
                    </p>
                    <small class="text-muted">Accuracy: {{ $analyticsData['demand_forecast']['accuracy'] ?? 0 }}%</small>
                </div>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-users text-success fs-4 me-2"></i>
                        <h6 class="mb-0">Customer Segments</h6>
                    </div>
                    <h3 class="text-success mb-2">{{ number_format($analyticsData['summary_stats']['total_customers'] ?? 0) }}</h3>
                    <p class="text-muted mb-0">Across {{ $analyticsData['summary_stats']['segment_count'] ?? 0 }} segments</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-dollar-sign text-info fs-4 me-2"></i>
                        <h6 class="mb-0">Total Revenue</h6>
                    </div>
                    <h3 class="text-info mb-2">${{ number_format($analyticsData['summary_stats']['total_revenue'] ?? 0, 2) }}</h3>
                    <p class="text-muted mb-0">From {{ $analyticsData['summary_stats']['total_customers'] ?? 0 }} customers</p>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-box text-warning fs-4 me-2"></i>
                        <h6 class="mb-0">Top Products</h6>
                    </div>
                    <h3 class="text-warning mb-2">{{ count($analyticsData['top_products'] ?? []) }}</h3>
                    <p class="text-muted mb-0">Best performing products</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Analytics Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="analyticsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-chart-pie me-2"></i>Overview
                            </button>
                        </li>
                       
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="analyticsTabContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row">
                                <!-- Customer Segments Chart -->
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Customer Segments</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="customerSegmentsChart" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- Top Products Chart -->
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-box me-2"></i>Top Products</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="topProductsChart" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- Demand Forecast Details -->
                                <div class="col-md-12 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Demand Forecast Analysis</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 text-center">
                                                    <h4 class="text-primary">{{ number_format($analyticsData['demand_forecast']['predicted_demand'] ?? 0) }}</h4>
                                                    <p class="text-muted">Predicted demand: {{ number_format($analyticsData['demand_forecast']['predicted_demand'] ?? 0) }} units</p>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <h4 class="text-{{ ($analyticsData['demand_forecast']['trend'] ?? 'increasing') == 'increasing' ? 'success' : 'danger' }}">
                                                        <i class="fas fa-arrow-{{ ($analyticsData['demand_forecast']['trend'] ?? 'increasing') == 'increasing' ? 'up' : 'down' }}"></i>
                                                    </h4>
                                                    <p class="text-muted">Trend direction: {{ ucfirst($analyticsData['demand_forecast']['trend'] ?? 'increasing') }}</p>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <h4 class="text-info">{{ $analyticsData['demand_forecast']['accuracy'] ?? 0 }}%</h4>
                                                    <p class="text-muted">Model accuracy: {{ $analyticsData['demand_forecast']['accuracy'] ?? 0 }}%</p>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    @if(isset($analyticsData['demand_forecast']['confidence_lower']))
                                                        <h4 class="text-warning">{{ number_format($analyticsData['demand_forecast']['confidence_lower']) }} - {{ number_format($analyticsData['demand_forecast']['confidence_upper']) }}</h4>
                                                        <p class="text-muted">Confidence interval: {{ number_format($analyticsData['demand_forecast']['confidence_lower']) }} - {{ number_format($analyticsData['demand_forecast']['confidence_upper']) }} units</p>
                                                    @else
                                                        <h4 class="text-warning">N/A</h4>
                                                        <p class="text-muted">Confidence interval not available</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Statistics -->
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Summary Statistics</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 text-center">
                                                    <h5 class="text-success">{{ $analyticsData['summary_stats']['high_value_customers'] ?? 0 }}</h5>
                                                    <p class="text-muted">High-value customers: {{ $analyticsData['summary_stats']['high_value_customers'] ?? 0 }} customers</p>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <h5 class="text-danger">{{ $analyticsData['summary_stats']['at_risk_customers'] ?? 0 }}</h5>
                                                    <p class="text-muted">At-risk customers: {{ $analyticsData['summary_stats']['at_risk_customers'] ?? 0 }} customers need attention</p>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <h5 class="text-info">{{ $analyticsData['summary_stats']['segment_count'] ?? 0 }}</h5>
                                                    <p class="text-muted">Customer segments: {{ $analyticsData['summary_stats']['segment_count'] ?? 0 }} distinct groups</p>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <h5 class="text-primary">${{ number_format($analyticsData['summary_stats']['total_revenue'] ?? 0, 2) }}</h5>
                                                    <p class="text-muted">Revenue distribution across {{ $analyticsData['summary_stats']['segment_count'] ?? 0 }} segments</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory AI Tab -->
                        <div class="tab-pane fade" id="inventory" role="tabpanel">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading AI-powered inventory optimization...</p>
                            </div>
                        </div>

                        <!-- Sales Prediction Tab -->
                        <div class="tab-pane fade" id="sales" role="tabpanel">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading AI-powered sales predictions...</p>
                            </div>
                        </div>

                        <!-- Customer CLV Tab -->
                        <div class="tab-pane fade" id="customers" role="tabpanel">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading customer lifetime value analysis...</p>
                            </div>
                        </div>

                        <!-- Market Trends Tab -->
                        <div class="tab-pane fade" id="trends" role="tabpanel">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading market trends analysis...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1000;">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary" onclick="refreshAnalytics()">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>
        <button type="button" class="btn btn-outline-success" onclick="exportAnalytics()">
            <i class="fas fa-download me-2"></i>Export
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare ML analytics data for JavaScript
const topProductsData = @json($analyticsData['top_products'] ?? []);
const customerSegmentsData = @json($analyticsData['customer_segments'] ?? []);

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadTabContent();
});

// Initialize analytics dashboard
function initializeCharts() {
    // Customer Segments Chart
    if (customerSegmentsData.length > 0) {
        const customerSegmentsCtx = document.getElementById('customerSegmentsChart');
        new Chart(customerSegmentsCtx, {
            type: 'doughnut',
            data: {
                labels: customerSegmentsData.map(segment => segment.segment_name),
                datasets: [{
                    data: customerSegmentsData.map(segment => segment.count),
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Top Products Chart
    if (topProductsData.length > 0) {
        const topProductsCtx = document.getElementById('topProductsChart');
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: topProductsData.map(product => product.product_name),
                datasets: [{
                    label: 'Revenue ($)',
                    data: topProductsData.map(product => product.total_revenue),
                    backgroundColor: 'rgba(160, 82, 45, 0.8)',
                    borderColor: 'rgba(160, 82, 45, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

// Load tab content dynamically
function loadTabContent() {
    const tabs = ['inventory', 'sales', 'customers', 'trends'];
    
    tabs.forEach(tab => {
        const tabElement = document.getElementById(`${tab}-tab`);
        if (tabElement) {
            tabElement.addEventListener('click', function() {
                loadAnalyticsData(tab);
            });
        }
    });
}

// Load analytics data for specific tabs
function loadAnalyticsData(type) {
    const tabContent = document.getElementById(type);
    const loadingHtml = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading AI-powered ${type} analysis...</p>
        </div>
    `;
    
    tabContent.innerHTML = loadingHtml;
    
    
    const endpoint = endpointMap[type] || type;
    
    fetch(`/admin/analytics/${endpoint}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderAnalyticsContent(type, data.data);
            } else {
                tabContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Failed to load ${type} data: ${data.error}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading analytics data:', error);
            tabContent.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading ${type} data: ${error.message}
                </div>
            `;
        });
}

// Render analytics content based on type
function renderAnalyticsContent(type, data) {
    const tabContent = document.getElementById(type);
    
    switch(type) {
        case 'inventory':
            renderInventoryOptimization(tabContent, data);
            break;
        case 'sales':
            renderSalesPrediction(tabContent, data);
            break;
        case 'customers':
            renderCustomerCLV(tabContent, data);
            break;
        case 'trends':
            renderMarketTrends(tabContent, data);
            break;
    }
}

// Render inventory optimization content
function renderInventoryOptimization(container, data) {
    container.innerHTML = `
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Low Stock Alerts</h6>
                    </div>
                    <div class="card-body">
                        ${data.low_stock_alerts && data.low_stock_alerts.length > 0 ? 
                            data.low_stock_alerts.map(alert => `
                                <div class="alert alert-${alert.urgency === 'high' ? 'danger' : 'warning'} mb-2">
                                    <strong>${alert.product_name}</strong><br>
                                    Current: ${alert.current_stock} | Recommended: ${alert.recommended_order}
                                </div>
                            `).join('') : 
                            '<p class="text-muted">No low stock alerts</p>'
                        }
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-line text-info me-2"></i>Reorder Suggestions</h6>
                    </div>
                    <div class="card-body">
                        ${data.reorder_suggestions && data.reorder_suggestions.length > 0 ? 
                            data.reorder_suggestions.map(suggestion => `
                                <div class="alert alert-info mb-2">
                                    <strong>${suggestion.product_name}</strong><br>
                                    Order: ${suggestion.recommended_order} units | Cost: $${suggestion.estimated_cost}
                                </div>
                            `).join('') : 
                            '<p class="text-muted">No reorder suggestions</p>'
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Render sales prediction content
function renderSalesPrediction(container, data) {
    container.innerHTML = `
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Sales Predictions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            ${Object.entries(data.predictions || {}).map(([period, prediction]) => `
                                <div class="col-md-3 mb-3">
                                    <div class="text-center">
                                        <h4 class="text-primary">${prediction.predicted_demand}</h4>
                                        <p class="text-muted">${period.replace('_', ' ')}</p>
                                        <small class="text-${prediction.trend === 'increasing' ? 'success' : 'danger'}">
                                            ${prediction.trend} (${prediction.accuracy}% accuracy)
                                        </small>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Render customer CLV content
function renderCustomerCLV(container, data) {
    container.innerHTML = `
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-user-friends me-2"></i>Top Customers by CLV</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Total Spent</th>
                                        <th>Orders</th>
                                        <th>CLV</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${(data.top_customers || []).map(customer => `
                                        <tr>
                                            <td>${customer.name}</td>
                                            <td>${customer.email}</td>
                                            <td>$${customer.total_spent}</td>
                                            <td>${customer.total_orders}</td>
                                            <td><strong>$${customer.clv}</strong></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Render market trends content
function renderMarketTrends(container, data) {
    container.innerHTML = `
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-trending-up me-2"></i>Market Trends</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            ${(data.trends || []).map(trend => `
                                <div class="col-md-3 mb-3">
                                    <div class="text-center">
                                        <h5 class="text-${trend.trend_direction === 'increasing' ? 'success' : 'danger'}">
                                            ${trend.metric}
                                        </h5>
                                        <p class="text-muted">${trend.trend_direction} (${trend.trend_strength}%)</p>
                                        <small>Current: ${trend.current_value}</small>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Refresh analytics data
function refreshAnalytics() {
    location.reload();
}

// Export analytics data
function exportAnalytics() {
    const analyticsData = {
        demand_forecast: @json($analyticsData['demand_forecast'] ?? []),
        customer_segments: @json($analyticsData['customer_segments'] ?? []),
        top_products: @json($analyticsData['top_products'] ?? []),
        summary_stats: @json($analyticsData['summary_stats'] ?? []),
        @if(isset($analyticsData['insights']))
        insights: @json($analyticsData['insights']),
        @endif
        export_date: new Date().toISOString()
    };
    
    const dataStr = JSON.stringify(analyticsData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = `analytics_report_${new Date().toISOString().split('T')[0]}.json`;
    link.click();
    
    showToast('Analytics report exported successfully!', 'success');
}

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

console.log('AI-Powered Analytics Dashboard loaded successfully!');
</script>
@endpush