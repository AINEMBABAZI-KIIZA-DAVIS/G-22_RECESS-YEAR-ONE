<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== Simple Route Test ===\n\n";

// Test 1: Check if you can access admin dashboard
echo "1. Testing admin dashboard access:\n";
echo "   Try accessing: http://your-domain/admin/dashboard\n";
echo "   If this works, the admin routes are working.\n";
echo "   If this gives 404, there's a route configuration issue.\n";
echo "   If this gives 403, there's an authentication/role issue.\n\n";

// Test 2: Check current user
echo "2. Current user status:\n";
if (Auth::check()) {
    $user = Auth::user();
    echo "   ✅ Logged in as: {$user->name} ({$user->email})\n";
    echo "   Role: " . ($user->role ?? 'NULL') . "\n";
    
    if ($user->role === 'admin') {
        echo "   ✅ You have admin role - routes should work\n";
    } else {
        echo "   ❌ You don't have admin role - routes will give 403\n";
        echo "   You need to update your user role to 'admin' in the database.\n";
    }
} else {
    echo "   ❌ Not logged in - you need to log in first\n";
}

echo "\n";

// Test 3: Check if admin users exist
echo "3. Admin users in database:\n";
try {
    $admins = User::where('role', 'admin')->get();
    if ($admins->count() > 0) {
        echo "   ✅ Found " . $admins->count() . " admin users:\n";
        foreach ($admins as $admin) {
            echo "     - {$admin->name} ({$admin->email})\n";
        }
    } else {
        echo "   ❌ No admin users found in database\n";
        echo "   You need to create an admin user or update an existing user's role.\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error checking admin users: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Quick fix suggestions
echo "4. Quick fixes to try:\n";
echo "   a) If not logged in: Go to /login and log in\n";
echo "   b) If logged in but not admin: Update your user role in database\n";
echo "   c) If role column missing: Run 'php artisan migrate'\n";
echo "   d) If routes not found: Check if Laravel is running properly\n";

echo "\n=== Test Complete ===\n"; 