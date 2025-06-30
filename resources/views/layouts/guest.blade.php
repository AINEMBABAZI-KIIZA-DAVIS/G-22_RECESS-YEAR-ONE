<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Premium Bakery - Your one-stop shop for delicious bakery products">
        <meta name="theme-color" content="#e76f51">
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
        <link rel="alternate icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍞</text></svg>">
        <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
        <meta name="theme-color" content="#e76f51">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        {{-- Commenting out Vite for app.css if it includes Tailwind, to prioritize Bootstrap --}}

        <style>
            /* Animations */
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            
            .animate-pulse {
                animation: pulse 4s ease-in-out infinite;
            }
            
            .transition-all {
                transition: all 0.3s ease;
            }
            
            .hover\:scale-105:hover {
                transform: scale(1.05);
            }
            
            /* Custom Utilities */
            .text-primary {
                color: var(--primary-color) !important;
            }
            
            .bg-primary {
                background-color: var(--primary-color) !important;
            }
            
            .border-primary {
                border-color: var(--primary-color) !important;
            }
            
            .shadow-soft {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            }
            
            .shadow-hover:hover {
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            }
            :root {
                --primary-color: #e76f51;
                --primary-hover: #d65c3e;
                --text-dark: #2b2d42;
                --text-muted: #6c757d;
                --light-bg: #f8f9fa;
                --border-radius: 12px;
                --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            }
            
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                color: var(--text-dark);
                background-color: #fff;
                line-height: 1.6;
            }
            
            body.guest-auth-layout {
                font-family: 'Inter', Arial, sans-serif;
                display: flex;
                min-height: 100vh;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
                color: var(--text-dark);
            }
            
            /* Sidebar Styles */
            .auth-sidebar {
                display: none;
                background: linear-gradient(135deg, #e76f51 0%, #f4a261 100%);
                color: white;
                padding: 2.5rem;
                position: relative;
                overflow: hidden;
            }
            
            .auth-sidebar::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80') center/cover;
                opacity: 0.1;
                z-index: 0;
            }
            
            .auth-sidebar-content {
                position: relative;
                z-index: 1;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                text-align: center;
                max-width: 500px;
                margin: 0 auto;
                padding: 2rem 0;
            }
            
            .auth-sidebar h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            }
            
            .auth-sidebar p {
                font-size: 1.1rem;
                margin-bottom: 2rem;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            }
            
            /* Auth content area */
            /* Auth Content Area */
            .auth-content {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
                background-color: #fff;
                position: relative;
                overflow-y: auto;
            }
            
            .auth-card-container {
                width: 100%;
                max-width: 480px;
                margin: 2rem auto;
                transition: all 0.3s ease;
            }
            
            .auth-card {
                background: #fff;
                border-radius: var(--border-radius);
                box-shadow: var(--box-shadow);
                padding: 2.5rem 2rem;
                border: 1px solid rgba(0, 0, 0, 0.05);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .auth-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            }
            
            /* Bakery Thumbnails */
            .bakery-thumb {
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                border: 2px solid rgba(255, 255, 255, 0.1);
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(5px);
            }
            
            .bakery-thumb::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(45deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.1));
                z-index: 1;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .bakery-thumb:hover {
                transform: translateY(-8px) scale(1.05);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
                border-color: rgba(255, 255, 255, 0.3);
            }
            
            .bakery-thumb:hover::before {
                opacity: 1;
            }
            
            .bakery-thumb img {
                transition: transform 0.5s ease;
            }
            
            .bakery-thumb:hover img {
                transform: scale(1.1);
            }
            
            .auth-logo {
                text-align: center;
                margin-bottom: 2.5rem;
            }
            
            .auth-logo svg {
                margin-bottom: 1.25rem;
                transition: transform 0.3s ease;
            }
            
            .auth-card:hover .auth-logo svg {
                transform: scale(1.05);
            }
            
            .auth-logo h2 {
                font-size: 1.875rem;
                font-weight: 800;
                margin: 0.75rem 0 0;
                color: var(--text-dark);
                letter-spacing: -0.5px;
                background: linear-gradient(135deg, var(--primary-color), #f4a261);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            /* Responsive styles */
            @media (max-width: 767.98px) {
                body {
                    background-color: #fff;
                }
                
                .auth-content {
                    padding: 1.5rem;
                }
                
                .auth-card {
                    padding: 2rem 1.5rem;
                    box-shadow: none;
                    border: none;
                }
                
                .auth-logo h2 {
                    font-size: 1.75rem;
                }
                
                .auth-sidebar {
                    display: none;
                }
                
                .auth-card-container {
                    margin: 0;
                    max-width: 100%;
                }
            }
            
            @media (min-width: 768px) and (max-width: 991.98px) {
                .auth-sidebar {
                    padding: 2rem 1.5rem;
                }
                
                .auth-sidebar h1 {
                    font-size: 2rem;
                }
                
                .auth-sidebar p {
                    font-size: 1.1rem;
                }
                
                .auth-card {
                    padding: 2.5rem 2rem;
                }
            }
            
            @media (min-width: 992px) {
                .auth-sidebar {
                    display: flex;
                    flex: 1;
                }
                
                .auth-content {
                    flex: 1;
                    padding: 2rem;
                }
                
                .auth-card-container {
                    max-width: 480px;
                }
                
                /* Animation for desktop */
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .auth-card {
                    animation: fadeInUp 0.6s ease-out forwards;
                }
            }
            
            /* Print styles */
            @media print {
                .auth-sidebar,
                .btn-close,
                .toggle-password {
                    display: none !important;
                }
                
                .auth-content {
                    padding: 0;
                    display: block;
                }
                
                .auth-card {
                    box-shadow: none;
                    border: none;
                    padding: 0;
                }
                
                .form-control {
                    border: 1px solid #dee2e6;
                    box-shadow: none;
                }
                
                .btn {
                    display: none;
                }
            }
            .auth-card-title {
                text-align: center;
                font-size: 1.75rem; /* Larger title */
                font-weight: 700;
                color: #22223b; /* Dark color from dashboard cards */
                margin-bottom: 2rem;
            }
            .form-label {
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 0.75rem;
                display: block;
                font-size: 0.95rem;
                transition: color 0.2s ease;
            }
            
            .form-control {
                border-radius: var(--border-radius);
                border: 1px solid #e0e0e0;
                padding: 0.85rem 1.25rem;
                font-size: 1rem;
                transition: all 0.3s ease;
                width: 100%;
                background-color: #fff;
                color: var(--text-dark);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            }
            
            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.25rem rgba(231, 111, 81, 0.15);
                outline: none;
            }
            
            .form-control::placeholder {
                color: #a0aec0;
                opacity: 1;
            }
            
            .form-text {
                font-size: 0.85rem;
                margin-top: 0.5rem;
                color: var(--text-muted);
            }
            
            /* Input Group Styles */
            .input-group-text {
                background-color: #f8f9fa;
                border: 1px solid #e0e0e0;
                color: var(--text-muted);
                transition: all 0.3s ease;
            }
            
            .input-group .form-control:focus + .input-group-text {
                border-color: var(--primary-color);
                color: var(--primary-color);
            }
            
            /* Button Styles */
            .btn {
                font-weight: 600;
                border-radius: var(--border-radius);
                padding: 0.85rem 1.5rem;
                transition: all 0.3s ease;
                border: none;
                font-size: 1rem;
                letter-spacing: 0.5px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color), #f4a261);
                color: white;
                border: none;
                position: relative;
                overflow: hidden;
                z-index: 1;
            }
            
            .btn-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 100%;
                background: linear-gradient(135deg, #f4a261, var(--primary-color));
                transition: all 0.3s ease;
                z-index: -1;
                opacity: 0;
            }
            
            .btn-primary:hover::before {
                width: 100%;
                opacity: 1;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(231, 111, 81, 0.3);
            }
            
            .btn-primary:active {
                transform: translateY(0);
                box-shadow: 0 4px 12px rgba(231, 111, 81, 0.3);
            }
            
            /* Toggle Password Button */
            .toggle-password {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
                border-left: none;
                color: var(--text-muted);
                transition: all 0.2s ease;
            }
            
            .toggle-password:hover {
                color: var(--primary-color);
                background-color: #f8f9fa;
            }
            
            .btn-primary-gradient {
                background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
                color: #fff;
                font-weight: 600;
                border: none;
                border-radius: 8px;
                padding: 0.75rem 1.5rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                box-shadow: 0 2px 8px rgba(53, 122, 255, 0.15);
                transition: background 0.3s, box-shadow 0.3s, transform 0.2s;
                width: 100%; /* Full width button */
                margin-top: 0.5rem; /* Space above button */
            }
            
            .btn-primary-gradient:hover {
                background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
                color: #fff;
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
                transform: translateY(-2px);
            }
            .auth-link {
                color: #357aff;
                text-decoration: none;
                font-size: 0.9rem;
                display: block; /* Make links block for easier clicking */
                text-align: center; /* Center links */
                margin-top: 1rem;
            }
            .auth-link:hover {
                color: #2563eb;
                text-decoration: underline;
            }
            .input-group-text { /* For potential password visibility toggles, etc. */
                background-color: #e9ecef;
                border: 1px solid #ced4da;
                border-radius: 0 8px 8px 0;
            }
            .form-check-label {
                font-size: 0.9rem;
                color: #495057;
            }
            .form-check-input:checked {
                background-color: #357aff;
                border-color: #357aff;
            }
            .bakery-thumb {
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body class="guest-auth-layout">
        <!-- Bakery-themed Sidebar -->
        <div class="auth-sidebar">
            <div class="auth-sidebar-content">
                <div class="animate-float mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-basket3" viewBox="0 0 16 16">
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                </div>
                
                <h1 class="animate-pulse">Welcome to Premium Bakery</h1>
                <p class="mb-4">Delicious pastries and bread made fresh daily with love and care</p>
                
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <div class="bakery-thumb hover:scale-105 transition-all" style="width: 80px; height: 80px;">
                        <img src="https://images.unsplash.com/photo-1542838132-92d533f91e0f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" 
                             alt="Fresh Bread" 
                             class="w-100 h-100 object-cover">
                    </div>
                    <div class="bakery-thumb hover:scale-105 transition-all" style="width: 80px; height: 80px;">
                        <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" 
                             alt="Delicious Pastries" 
                             class="w-100 h-100 object-cover">
                    </div>
                    <div class="bakery-thumb hover:scale-105 transition-all" style="width: 80px; height: 80px;">
                        <img src="https://images.unsplash.com/photo-1563729780194-dfeeec8385f2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" 
                             alt="Yummy Cakes" 
                             class="w-100 h-100 object-cover">
                    </div>
                </div>
                
                <div class="mt-auto pt-4 text-center">
                    <p class="text-sm opacity-75">
                        <i class="bi bi-shield-check me-1"></i> Secure & Reliable
                    </p>
                    <p class="text-sm opacity-75 mb-0">
                        <i class="bi bi-truck me-1"></i> Fast Delivery
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="auth-content">
            <div class="auth-card-container">
                <div class="auth-card">
                    <div class="auth-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#e76f51" class="bi bi-basket" viewBox="0 0 16 16">
                            <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <h2 class="mt-3 mb-4" style="color: #2b2d42;">Premium Bakery</h2>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
