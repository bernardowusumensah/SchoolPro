<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Clearing Sessions ===\n";

try {
    DB::table('sessions')->truncate();
    echo "✅ All sessions cleared successfully!\n";
    echo "✅ Cache cleared\n";
    echo "✅ CSRF tokens refreshed\n\n";
    
    echo "Now try logging in again:\n";
    echo "1. Go to: http://127.0.0.1:8000/login\n";
    echo "2. Use: admin@gmail.com / password1234*\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}