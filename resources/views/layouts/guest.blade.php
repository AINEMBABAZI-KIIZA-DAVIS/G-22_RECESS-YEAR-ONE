<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
            html, body {
                height: 100%;
            }
            body.guest-auth-layout {
                font-family: 'Inter', Arial, sans-serif;
                background: linear-gradient(135deg, #357aff 60%, #5eead4 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0;
                padding: 1rem; /* Add some padding for smaller screens */
            }
            .auth-card-container {
                width: 100%;
                max-width: 450px; /* Max width for the card */
            }
            .auth-card {
                background: #fff;
                border-radius: 16px; /* Rounded corners like dashboard cards */
                box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15); /* Softer shadow */
                padding: 2rem 2.5rem; /* More padding */
                border: none; /* Remove default Bootstrap card border */
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
                color: #495057; /* Standard label color */
                margin-bottom: 0.5rem;
            }
            .form-control {
                border-radius: 8px; /* Rounded inputs */
                border: 1px solid #ced4da;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .form-control:focus {
                border-color: #357aff;
                box-shadow: 0 0 0 0.2rem rgba(53, 122, 255, 0.25);
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
        </style>
    </head>
    <body class="guest-auth-layout">
        <div class="auth-card-container">
            <div class="card auth-card">
                <div class="card-body">
                    {{-- Logo removed as per request --}}
                    {{-- You can add a title here if needed, e.g., <h2 class="auth-card-title">{{ config('app.name', 'Laravel') }}</h2> --}}
                    {{ $slot }}
                </div>
            </div>
        </div>
        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
