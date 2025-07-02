<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$db = $app->make('db');

// Test database connection
try {
    $pdo = $db->connection()->getPdo();
    echo "Connected to database: " . $db->connection()->getDatabaseName() . "\n";
    
    // List all tables
    $tables = $db->select('SHOW TABLES');
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- " . reset($table) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
