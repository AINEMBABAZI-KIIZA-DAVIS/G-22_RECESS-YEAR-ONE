<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="user-id" content="{{ auth()->id() }}" />
    <title>Wholesaler Panel - G-22 Recess</title>
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

        .bg-cream {
            background: var(--background-light) !important;
        }

        .text-brown {
            color: var(--primary-color) !important;
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
                <i class="fas fa-industry me-2"></i>Wholesaler Panel
            </div>
                <a class="nav-link {{ request()->routeIs('wholesaler.dashboard') ? 'active' : '' }}"
                    href="{{ route('wholesaler.dashboard') }}"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
                <a class="nav-link {{ request()->routeIs('wholesaler.catalog') ? 'active' : '' }}"
                    href="{{ route('wholesaler.catalog') }}"><i class="fas fa-grid me-2"></i> Product Catalog</a>
                <a class="nav-link {{ request()->routeIs('wholesaler.order.form') ? 'active' : '' }}"
                    href="{{ route('wholesaler.order.form') }}"><i class="fas fa-bag-plus me-2"></i> Place Order</a>
                <a class="nav-link {{ request()->routeIs('wholesaler.orders.*') ? 'active' : '' }}"
                    href="{{ route('wholesaler.orders.index') }}"><i class="fas fa-list-ul me-2"></i> My Orders</a>
                <a class="nav-link {{ request()->routeIs('wholesaler.orders.track') ? 'active' : '' }}"
                    href="{{ route('wholesaler.orders.track') }}"><i class="fas fa-truck me-2"></i> Track Orders</a>
               
                <a class="nav-link {{ request()->routeIs('wholesaler.analytics') ? 'active' : '' }}"
                    href="{{ route('wholesaler.analytics') }}"><i class="fas fa-chart-bar me-2"></i> Analytics</a>
                <a class="nav-link {{ request()->routeIs('wholesaler.chat.admin') ? 'active' : '' }}"
                    href="{{ route('wholesaler.chat.admin') }}"><i class="fas fa-comments me-2"></i> Chat with Admin</a>
                
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

    @stack('scripts')
</body>

</html> 