<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inventory Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f8fafc; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #357aff 60%, #5eead4 100%);
            color: #fff;
        }
        .sidebar .nav-link {
            color: #e0e7ff;
            font-weight: 500;
            margin-bottom: 8px;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #fff;
            color: #357aff;
        }
        .sidebar .sidebar-heading {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 24px 0 8px 0;
            color: #bae6fd;
        }
        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 32px;
        }
        .dashboard-card {
            flex: 1 1 220px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(53, 122, 255, 0.07);
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 220px;
        }
        .dashboard-card .icon {
            font-size: 28px;
            margin-bottom: 12px;
            color: #357aff;
        }
        .dashboard-card .card-title {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .dashboard-card .card-value {
            font-size: 28px;
            font-weight: 700;
            color: #22223b;
        }
        .quick-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        .quick-actions .btn {
            background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            box-shadow: 0 2px 8px rgba(53, 122, 255, 0.10);
            transition: background 0.3s, box-shadow 0.3s;
        }
        .quick-actions .btn:hover {
            background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
            color: #fff;
        }
        .recent-activity {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(53, 122, 255, 0.07);
            padding: 24px 20px;
            margin-bottom: 32px;
        }
        @media (max-width: 991px) {
            .dashboard-cards { flex-direction: column; gap: 16px; }
            .sidebar { min-height: auto; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-lg-2 col-md-3 d-md-block sidebar py-4 px-3 d-flex flex-column">
            <div class="sidebar-heading mb-3">Admin Panel</div>
            <a class="nav-link active" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
            <a class="nav-link" href="{{ route('admin.products.index') }}"><i class="fas fa-boxes-stacked me-2"></i> Inventory</a>
            <a class="nav-link" href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-cart me-2"></i> Orders</a>
            <a class="nav-link" href="{{ route('admin.workers.index') }}"><i class="fas fa-users me-2"></i> Workers</a>
            <a class="nav-link" href="{{ route('admin.analytics.index') }}"><i class="fas fa-chart-line me-2"></i> Analytics</a>
            <a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog me-2"></i> Settings</a>
         <a class="nav-link"><i class="fas fa-file-alt me-2"></i> View Reports</a> 
            <a class="nav-link" href="{{ route('supplier.requests.list') }}"><i class="fas fa-paper-plane me-2"></i> View Sent Requests</a>
            <a class="nav-link" href="#" onclick="alert('Chat coming soon!')"><i class="fas fa-comments me-2"></i> Chat</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="nav-link w-100 text-start" style="background:none; border:none; color:#e0e7ff;">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </nav>
        <!-- Main Content -->
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
            <h2 class="mb-4">Inventory Dashboard</h2>
            <!-- Analytics Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <div class="card-title">Total Products</div>
                    <div class="card-value">1,250</div>
                </div>
                <div class="dashboard-card">
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="card-title">Low Stock</div>
                    <div class="card-value text-warning">12</div>
                </div>
                <div class="dashboard-card">
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="card-title">Orders Today</div>
                    <div class="card-value">87</div>
                </div>
                <div class="dashboard-card">
                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="card-title">Revenue</div>
                    <div class="card-value text-success">$4,320</div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="quick-actions mb-4">
                <button class="btn"><i class="fas fa-plus me-2"></i> Add Product</button>
                <button class="btn"><i class="fas fa-file-alt me-2"></i> Generate Report</button>
                <button class="btn"><i class="fas fa-download me-2"></i> Export Data</button>
            </div>
            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="card p-3 h-100">
                        <h5 class="mb-3">Inventory Trends</h5>
                        <canvas id="inventoryChart" height="120"></canvas>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card p-3 h-100">
                        <h5 class="mb-3">Top Products</h5>
                        <canvas id="topProductsChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <!-- Recent Activity -->
            <div class="recent-activity">
                <h5 class="mb-3">Recent Activity</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Order #1234 placed by John Doe <span class="badge bg-success ms-2">Completed</span></li>
                    <li class="list-group-item">Product "Wireless Mouse" stock low <span class="badge bg-warning ms-2">Low Stock</span></li>
                    <li class="list-group-item">Order #1235 placed by Jane Smith <span class="badge bg-info ms-2">Processing</span></li>
                    <li class="list-group-item">Product "Laptop" added to inventory <span class="badge bg-primary ms-2">New</span></li>
                </ul>
            </div>
        </main>
    </div>
</div>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Inventory Trends Chart
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Stock Level',
                data: [1200, 1150, 1100, 1050, 1000, 950, 1250],
                borderColor: '#357aff',
                backgroundColor: 'rgba(53, 122, 255, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#357aff',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: false } }
        }
    });
    // Top Products Chart
    const ctx2 = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Laptop', 'Mouse', 'Keyboard', 'Monitor', 'Printer'],
            datasets: [{
                label: 'Units Sold',
                data: [320, 210, 180, 150, 120],
                backgroundColor: [
                    '#357aff', '#5eead4', '#fbbf24', '#f87171', '#a78bfa'
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            indexAxis: 'y',
            scales: { x: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>