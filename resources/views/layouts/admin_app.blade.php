<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Admin Panel</title>

    {{-- Bootstrap CSS (using CDN, replace with local if needed) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    {{-- Tailwind CSS & JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Additional pushed styles --}}
    @stack('styles')
</head>
<body>
    @yield('content')

    {{-- Bootstrap JS Bundle (Popper + Bootstrap JS) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
