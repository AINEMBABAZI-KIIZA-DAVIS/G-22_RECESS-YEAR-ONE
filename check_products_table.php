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
    'database'  => env('DB_DATABASE', 'forge'),
    'username'  => env('DB_USERNAME', 'forge'),
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
    echo "Successfully connected to the database.\n";
    
    // Check if products table exists
    $tableExists = $pdo->query("SHOW TABLES LIKE 'products'")->rowCount() > 0;
    
    if ($tableExists) {
        echo "Products table exists.\n";
        
        // Get table structure
        $columns = $pdo->query('DESCRIBE products')->fetchAll(PDO::FETCH_COLUMN);
        echo "Columns in products table: " . implode(', ', $columns) . "\n";
        
        // Count existing products
        $count = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        echo "Number of products in table: " . $count . "\n";
    } else {
        echo "Products table does not exist.\n";
    }
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
