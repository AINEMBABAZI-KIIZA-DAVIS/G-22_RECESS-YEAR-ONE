<?php

$envFile = __DIR__ . '/.env';
$exampleFile = __DIR__ . '/.env.example';

// Read the current .env file
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Replace APP_NAME
    $newEnvContent = preg_replace(
        '/^APP_NAME=.*/m',
        'APP_NAME="Premium Bakery"',
        $envContent
    );
    
    // Save the updated content
    file_put_contents($envFile, $newEnvContent);
    echo "Updated .env file with new application name.\n";
} else {
    echo "Error: .env file not found.\n";
}

// Also update the .env.example file for future reference
if (file_exists($exampleFile)) {
    $exampleContent = file_get_contents($exampleFile);
    $newExampleContent = preg_replace(
        '/^APP_NAME=.*/m',
        'APP_NAME="Premium Bakery"',
        $exampleContent
    );
    file_put_contents($exampleFile, $newExampleContent);
    echo "Updated .env.example file with new application name.\n";
}

echo "Please run 'php artisan config:clear' to clear the configuration cache.\n";
