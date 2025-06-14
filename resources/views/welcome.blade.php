<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome | Inventory Management System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', Arial, sans-serif; background: #f8fafc; }
            .hero {
                background: linear-gradient(135deg, #357aff 60%, #5eead4 100%);
                color: #fff;
                padding: 80px 0 60px 0;
                text-align: center;
            }
            .hero h1 { font-size: 3rem; font-weight: 700; margin-bottom: 18px; }
            .hero p { font-size: 1.3rem; margin-bottom: 32px; }
            .hero .btn {
                font-size: 1.1rem;
                padding: 12px 32px;
                border-radius: 8px;
                margin: 0 10px;
                font-weight: 600;
                box-shadow: 0 2px 8px rgba(53, 122, 255, 0.10);
            }
            .features {
                padding: 60px 0 40px 0;
            }
            .feature-icon {
                font-size: 2.5rem;
                color: #357aff;
                margin-bottom: 18px;
            }
            .feature-card {
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 2px 12px rgba(53, 122, 255, 0.07);
                padding: 32px 24px;
                margin-bottom: 24px;
                text-align: center;
                height: 100%;
            }
            footer {
                background: #22223b;
                color: #fff;
                padding: 24px 0;
                text-align: center;
                margin-top: 40px;
            }
        </style>
    </head>
    <body>
        <section class="hero">
            <div class="container">
                <h1>Welcome to Premium Bakery</h1>
                <p>Effortlessly manage your inventory, orders, and analytics with our modern, secure, and user-friendly system.</p>
                <a href="{{ route('login') }}" class="btn btn-light text-primary"><i class="fas fa-sign-in-alt me-2"></i>Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Register</a>
            </div>
        </section>
        <section class="features">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-boxes"></i></div>
                            <h5>Inventory Tracking</h5>
                            <p>Monitor stock levels, get low-stock alerts, and keep your inventory organized in real time.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                            <h5>Analytics & Reports</h5>
                            <p>Visualize trends, sales, and performance with beautiful charts and downloadable reports.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-users"></i></div>
                            <h5>User Management</h5>
                            <p>Role-based access for admins and users, with secure authentication and easy onboarding.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <div class="container">
                &copy; {{ date('Y') }} InventoryPro. All rights reserved.
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
