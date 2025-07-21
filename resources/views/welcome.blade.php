<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome | Premium Bakery</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <style>
            :root {
                --primary-color: #e76f51;
                --primary-hover: #d65c3e;
                --text-dark: #2b2d42;
                --text-muted: #6c757d;
                --border-radius: 12px;
                --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            }
            
            body { 
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
                color: var(--text-dark);
                line-height: 1.6;
                background-color: #f9f9f9;
            }
            
            .hero {
                position: relative;
                background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                            url('https://images.pexels.com/photos/31597725/pexels-photo-31597725/free-photo-of-freshly-baked-sugary-buns-on-trays.jpeg');
                background-size: cover;
                background-position: center;
                color: #fff;
                padding: 100px 0;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            
            .hero::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 100px;
                background: linear-gradient(to bottom, transparent, #f9f9f9);
                z-index: 1;
            }
            
            .hero-content {
                position: relative;
                z-index: 2;
                max-width: 800px;
                margin: 0 auto;
                padding: 0 20px;
            }
            
            .hero h1 { 
                font-size: 3.5rem; 
                font-weight: 800; 
                margin-bottom: 1.5rem;
                letter-spacing: -0.5px;
                line-height: 1.2;
            }
            
            .hero p { 
                font-size: 1.25rem; 
                margin-bottom: 2.5rem;
                opacity: 0.9;
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .btn {
                font-weight: 600;
                border-radius: var(--border-radius);
                padding: 0.85rem 2rem;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
                z-index: 1;
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
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(231, 111, 81, 0.3);
                color: white;
            }
            
            .btn-outline-light {
                border: 2px solid rgba(255, 255, 255, 0.2);
                background: transparent;
                color: white;
            }
            
            .btn-outline-light:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
                border-color: rgba(255, 255, 255, 0.3);
            }
            
            .features {
                padding: 80px 0;
                background-color: #fff;
            }
            
            .section-title {
                text-align: center;
                margin-bottom: 3.5rem;
            }
            
            .section-title h2 {
                font-size: 2.25rem;
                font-weight: 800;
                margin-bottom: 1rem;
                color: var(--text-dark);
            }
            
            .section-title p {
                color: var(--text-muted);
                max-width: 600px;
                margin: 0 auto;
                font-size: 1.1rem;
            }
            
            .feature-card {
                background: #fff;
                border-radius: var(--border-radius);
                box-shadow: var(--box-shadow);
                padding: 2.5rem 2rem;
                margin-bottom: 1.5rem;
                text-align: center;
                height: 100%;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid rgba(0, 0, 0, 0.03);
            }
            
            .feature-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, rgba(231, 111, 81, 0.1), rgba(244, 162, 97, 0.1));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                font-size: 2rem;
                color: var(--primary-color);
            }
            
            .feature-card h5 {
                font-weight: 700;
                margin-bottom: 1rem;
                color: var(--text-dark);
                font-size: 1.25rem;
            }
            
            .feature-card p {
                color: var(--text-muted);
                margin-bottom: 0;
            }
            
            footer {
                background: #2b2d42;
                color: #fff;
                padding: 2.5rem 0;
                text-align: center;
            }
            
            .footer-logo {
                font-size: 1.5rem;
                font-weight: 800;
                margin-bottom: 1.5rem;
                display: inline-block;
                background: linear-gradient(135deg, var(--primary-color), #f4a261);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .footer-links {
                display: flex;
                justify-content: center;
                gap: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .footer-links a {
                color: rgba(255, 255, 255, 0.7);
                text-decoration: none;
                transition: color 0.3s ease;
            }
            
            .footer-links a:hover {
                color: white;
            }
            
            .social-links {
                display: flex;
                justify-content: center;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            
            .social-links a {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                color: white;
                transition: all 0.3s ease;
            }
            
            .social-links a:hover {
                background: var(--primary-color);
                transform: translateY(-3px);
            }
            
            .copyright {
                color: rgba(255, 255, 255, 0.5);
                font-size: 0.9rem;
                margin-top: 1.5rem;
            }
            
            /* Responsive styles */
            @media (max-width: 767.98px) {
                .hero {
                    padding: 80px 0;
                }
                
                .hero h1 {
                    font-size: 2.5rem;
                }
                
                .hero p {
                    font-size: 1.1rem;
                }
                
                .btn {
                    padding: 0.75rem 1.5rem;
                    font-size: 0.95rem;
                }
                
                .features {
                    padding: 60px 0;
                }
                
                .section-title h2 {
                    font-size: 1.75rem;
                }
                
                .feature-card {
                    padding: 2rem 1.5rem;
                }
                
                .feature-icon {
                    width: 70px;
                    height: 70px;
                    font-size: 1.75rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to Premium Bakery</h1>
                <p>Experience the finest artisanal breads and pastries, crafted with passion and the finest ingredients.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light">
                        <i class="bi bi-person-plus me-2"></i>Register
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <div class="section-title">
                    <h2>Why Choose Premium Bakery?</h2>
                    <p>Discover what makes our bakery management system the perfect choice for your business</p>
                </div>
                
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-basket"></i>
                            </div>
                            <h5>Fresh Ingredients</h5>
                            <p>We source only the finest, freshest ingredients to ensure the highest quality in every bite.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5>Expert Bakers</h5>
                            <p>Our skilled bakers bring years of experience and passion to create delicious treats.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h5>Freshly Baked</h5>
                            <p>Enjoy our products fresh from the oven, made throughout the day for optimal taste.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h5>Fast Delivery</h5>
                            <p>Get your favorite baked goods delivered fresh to your doorstep with our quick service.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h5>Quality Guarantee</h5>
                            <p>We stand behind the quality of our products with a 100% satisfaction guarantee.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-emoji-heart-eyes"></i>
                            </div>
                            <h5>Customer Love</h5>
                            <p>Join thousands of satisfied customers who choose Premium Bakery for their daily bread.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-5 bg-light">
            <div class="container text-center py-4">
                <h2 class="mb-4">Ready to Experience the Difference?</h2>
                <p class="lead mb-4">Join our community of happy customers and taste the Premium Bakery difference today.</p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Create an Account
                </a>
            </div>
        </section>

        <!-- Map Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-4">Our Location</h2>
                <div id="map" style="height: 450px; width: 100%;"></div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="footer-logo">Premium Bakery</div>
                
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="#">Menu</a>
                    <a href="#">About Us</a>
                    <a href="#">Contact</a>
                    <a href="{{ route('login') }}">Login</a>
                </div>
                
                <div class="social-links">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-pinterest"></i></a>
                </div>
                
                <div class="copyright">
                    © {{ date('Y') }} Premium Bakery. All rights reserved.
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Add smooth scrolling to all links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
            
            // Add animation on scroll
            document.addEventListener('DOMContentLoaded', function() {
                const featureCards = document.querySelectorAll('.feature-card');
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = 1;
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, { threshold: 0.1 });
                
                featureCards.forEach((card, index) => {
                    card.style.opacity = 0;
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = `all 0.5s ease ${index * 0.1}s`;
                    observer.observe(card);
                });
            });

            // Initialize Leaflet map
            var map = L.map('map').setView([0.3314, 32.5706], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            var greenIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            L.marker([0.3314, 32.5706], {icon: greenIcon}).addTo(map)
                .bindPopup('Makerere University College of Computing and Information Sciences')
                .openPopup();
        </script>
    </body>
</html>
