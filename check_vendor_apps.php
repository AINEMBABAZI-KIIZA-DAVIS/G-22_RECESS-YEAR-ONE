<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\VendorApplication;

echo "Vendor Applications:\n";
$applications = VendorApplication::all(['id', 'company_name', 'status', 'validation_results']);

foreach ($applications as $app) {
    echo "ID: {$app->id}, Company: {$app->company_name}, Status: {$app->status}\n";
    if ($app->validation_results) {
        echo "  Validation Results: {$app->validation_results}\n";
    }
    echo "\n";
} 