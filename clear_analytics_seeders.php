<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configure the database connection
$db = new DB;

$db->addConnection([
    'driver'    => 'mysql',
    'host'      => env('DB_HOST', '127.0.0.1'),
    'port'      => env('DB_PORT', '3306'),
    'database'  => env('DB_DATABASE', 'laravel'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', ''),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods...
$db->setAsGlobal();

// Setup the Eloquent ORM...
$db->bootEloquent();

try {
    // Test the connection
    $pdo = $db->getConnection()->getPdo();
    echo "✅ Successfully connected to the database.\n";
    
    // Clear analytics seeder data
    echo "\n🧹 Clearing analytics seeder data...\n";
    
    // Check if tables exist and clear them
    $tables = ['inventory_levels', 'top_products'];
    
    foreach ($tables as $table) {
        $tableExists = $pdo->query("SHOW TABLES LIKE '$table'")->rowCount() > 0;
        
        if ($tableExists) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "  - Clearing $table table ($count records)...\n";
            $pdo->query("DELETE FROM $table");
            echo "  ✅ Cleared $table table\n";
        } else {
            echo "  - Table $table does not exist (skipping)\n";
        }
    }
    
    echo "\n🎯 Analytics data will now be provided by the ML API instead of seeders.\n";
    echo "📊 The admin dashboard and analytics pages will use real-time ML predictions.\n";
    echo "🔗 Make sure the ML API is running on http://localhost:5000\n";
    
} catch (\PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 