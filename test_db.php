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

// Set the event dispatcher used by Eloquent models... (you can leave this out if you don't use events)
// $db->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods...
$db->setAsGlobal();

// Setup the Eloquent ORM...
$db->bootEloquent();

try {
    // Test the connection
    $pdo = $db->getConnection()->getPdo();
    echo "Successfully connected to the database: " . $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";
    
    // List all tables
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo "Current tables in database: " . implode(', ', $tables) . "\n";
    
} catch (\PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
