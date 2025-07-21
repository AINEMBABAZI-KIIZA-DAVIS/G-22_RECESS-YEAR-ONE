<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;

echo "=== Route Debug Test ===\n\n";

// Test 1: Check if user is authenticated
echo "1. Authentication Status:\n";
if (Auth::check()) {
    $user = Auth::user();
    echo "✅ User is authenticated\n";
    echo "   - User ID: {$user->id}\n";
    echo "   - Name: {$user->name}\n";
    echo "   - Email: {$user->email}\n";
    echo "   - Role: " . ($user->role ?? 'NULL') . "\n";
} else {
    echo "❌ User is NOT authenticated\n";
    echo "   You need to log in first.\n";
}

echo "\n";

// Test 2: Check if user has admin role
echo "2. Admin Role Check:\n";
if (Auth::check()) {
    $user = Auth::user();
    if ($user->role === 'admin') {
        echo "✅ User has admin role\n";
    } else {
        echo "❌ User does NOT have admin role\n";
        echo "   Current role: " . ($user->role ?? 'NULL') . "\n";
        echo "   You need to be an admin to access these routes.\n";
    }
} else {
    echo "❌ Cannot check role - user not authenticated\n";
}

echo "\n";

// Test 3: Check if routes exist
echo "3. Route Check:\n";
$routes = Route::getRoutes();
$adminChatRoutes = [];

foreach ($routes as $route) {
    if (str_contains($route->uri(), 'admin/chat')) {
        $adminChatRoutes[] = [
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'methods' => $route->methods()
        ];
    }
}

if (count($adminChatRoutes) > 0) {
    echo "✅ Found " . count($adminChatRoutes) . " admin chat routes:\n";
    foreach ($adminChatRoutes as $route) {
        echo "   - {$route['uri']} (Name: {$route['name']})\n";
    }
} else {
    echo "❌ No admin chat routes found\n";
}

echo "\n";

// Test 4: Check database connection
echo "4. Database Connection:\n";
try {
    $userCount = User::count();
    echo "✅ Database connection successful\n";
    echo "   - Total users: {$userCount}\n";
    
    $adminCount = User::where('role', 'admin')->count();
    echo "   - Admin users: {$adminCount}\n";
    
    $supplierCount = User::where('role', 'supplier')->count();
    echo "   - Supplier users: {$supplierCount}\n";
    
    $wholesalerCount = User::where('role', 'wholesaler')->count();
    echo "   - Wholesaler users: {$wholesalerCount}\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Check role column
echo "5. Role Column Check:\n";
try {
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM users LIKE 'role'");
    if (count($columns) > 0) {
        echo "✅ Role column exists in users table\n";
    } else {
        echo "❌ Role column does NOT exist in users table\n";
        echo "   Run: php artisan migrate\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking role column: " . $e->getMessage() . "\n";
}

echo "\n=== Debug Complete ===\n";
echo "\nTo access the chat routes, make sure:\n";
echo "1. You are logged in\n";
echo "2. Your user has 'admin' role\n";
echo "3. You are accessing the correct URLs:\n";
echo "   - /admin/chat/supplier\n";
echo "   - /admin/chat/wholesaler\n";
echo "   - /admin/chat/vendor\n"; 