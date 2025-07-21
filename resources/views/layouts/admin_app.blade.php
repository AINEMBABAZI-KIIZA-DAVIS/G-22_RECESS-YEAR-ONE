<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="user-id" content="{{ auth()->id() }}" />
    <title>Admin Panel - G-22 Recess</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #a0522d;
            --secondary-color: #ff9800;
            --accent-color: #8bc34a;
            --background-light: #fff8e1;
            --background-faint: #fefefe;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --border-light: #e9ecef;
            --shadow-soft: 0 2px 12px rgba(160, 82, 45, 0.08);
            --shadow-medium: 0 4px 20px rgba(160, 82, 45, 0.12);
            --gradient-primary: linear-gradient(135deg, #a0522d 0%, #ff9800 100%);
            --gradient-secondary: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--background-faint);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--gradient-primary) !important;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1030;
            box-shadow: var(--shadow-medium);
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(10px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .main-content {
            margin-left: 280px;
            background: var(--background-faint);
            min-height: 100vh;
            width: calc(100% - 280px);
            padding: 20px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 2px;
            border-radius: 12px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: 0.9rem;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar .nav-link:hover::before {
            left: 100%;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar .sidebar-heading {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 16px 0 8px 0;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }

        .sidebar .dropdown-menu {
            background: #fff;
            color: var(--text-dark);
            border-radius: 16px;
            min-width: 220px;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-light);
            padding: 8px 0;
        }

        .sidebar .dropdown-item {
            color: var(--text-dark);
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.2s ease;
        }

        .sidebar .dropdown-item:hover {
            background: var(--background-light);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            background: #fff;
        }

        .card:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--background-light);
            border-bottom: 1px solid var(--border-light);
            border-radius: 16px 16px 0 0 !important;
            font-weight: 600;
            color: var(--primary-color);
        }

        .btn {
            border-radius: 12px;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(160, 82, 45, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #8b4513 0%, #e68900 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(160, 82, 45, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: #fff;
            transform: translateY(-2px);
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
        }

        .table th {
            background: var(--background-light);
            color: var(--primary-color);
            font-weight: 600;
            border: none;
            padding: 16px 12px;
        }

        .table td {
            padding: 16px 12px;
            border-color: var(--border-light);
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: var(--background-light);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .badge {
            border-radius: 20px;
            font-weight: 500;
            padding: 6px 12px;
        }

        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: var(--shadow-soft);
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid var(--border-light);
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(160, 82, 45, 0.15);
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .dashboard-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
            text-align: center;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
        }

        .dashboard-card .icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 16px;
        }

        .dashboard-card .card-title {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .dashboard-card .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .quick-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .quick-actions .btn {
            border-radius: 12px;
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .recent-activity {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-soft);
        }

        .list-group-item {
            border: none;
            border-bottom: 1px solid var(--border-light);
            padding: 16px 0;
            transition: all 0.2s ease;
        }

        .list-group-item:hover {
            background: var(--background-light);
            transform: translateX(4px);
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        @media (max-width: 991px) {
            .sidebar {
                min-height: auto;
                position: static;
                width: 100%;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--background-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #8b4513;
        }

        /* Logout Button Styles */
        .logout-form {
            margin-top: auto;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px !important;
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
            margin-top: 4px !important;
            font-size: 0.9rem !important;
            padding: 8px 16px !important;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
            transform: translateX(4px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        .logout-btn:active {
            transform: translateX(2px) !important;
        }

        .border-light {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar py-3 px-3 d-flex flex-column">
                <div class="sidebar-heading mb-2">
                    <i class="fas fa-shield-alt me-2"></i>Admin Panel
                </div>
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                    href="{{ route('admin.products.index') }}"><i class="fas fa-boxes-stacked me-2"></i> Inventory</a>
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                    href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-cart me-2"></i> Orders</a>
                <a class="nav-link {{ request()->routeIs('admin.workers.*') ? 'active' : '' }}"
                    href="{{ route('admin.workers.index') }}"><i class="fas fa-users me-2"></i> Workers</a>
                <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}"
                    href="{{ route('admin.analytics.index') }}"><i class="fas fa-chart-line me-2"></i> Analytics</a>
                <a class="nav-link {{ request()->routeIs('admin.vendor-validation.*') ? 'active' : '' }}"
                    href="{{ route('admin.vendor-validation.index') }}"><i class="fas fa-user-check me-2"></i> Vendor
                    Validation</a>

                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                    href="{{ route('admin.reports.index') }}"><i class="fas fa-file-alt me-2"></i> View Reports</a>
                <a class="nav-link {{ request()->routeIs('admin.supply-requests.*') ? 'active' : '' }}"
                    href="{{ route('admin.supply-requests.index') }}"><i class="fas fa-paper-plane me-2"></i> View Sent
                    Requests</a>
                <!-- Chat Link -->
                <a class="nav-link {{ request()->is('chatify*') ? 'active' : '' }}"
                   href="{{ url('/chatify') }}">
                    <i class="fas fa-comments me-2"></i> Chat
                    <span class="badge bg-danger ms-1" id="chat-notification" style="display: none;">0</span>
                </a>
                <!-- Logout Section -->
                <div class="mt-auto pt-2">
                    <hr class="border-light opacity-25 my-2">
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start logout-btn" 
                                onclick="return confirm('Are you sure you want to logout?')">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="main-content">
                @yield('content')
            </main>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chat Notification Script -->
    <script>
        // Check for unread messages every 30 seconds
        setInterval(function () {
            fetch('/admin/chat/unread-count')
                .then(response => response.json())
                .then(data => {
                    const notification = document.getElementById('chat-notification');
                    if (data.count > 0) {
                        notification.textContent = data.count;
                        notification.style.display = 'inline';
                    } else {
                        notification.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching unread count:', error);
                });
        }, 30000);

        // Initial check on page load
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/admin/chat/unread-count')
                .then(response => response.json())
                .then(data => {
                    const notification = document.getElementById('chat-notification');
                    if (data.count > 0) {
                        notification.textContent = data.count;
                        notification.style.display = 'inline';
                    }
                })
                .catch(error => {
                    console.error('Error fetching unread count:', error);
                });
        });
    </script>

    @stack('scripts')
</body>

</html>