<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Admin Login Verification ===\n";
echo "================================\n\n";

try {
    // Check if admin user exists
    $admin = User::where('email', 'admin@gmail.com')->first();
    
    if ($admin) {
        echo "✅ Admin account found!\n";
        echo "ID: {$admin->id}\n";
        echo "Name: {$admin->name}\n";
        echo "Email: {$admin->email}\n";
        echo "Role: {$admin->role}\n";
        echo "Status: {$admin->status}\n";
        echo "Created: {$admin->created_at}\n\n";
        
        echo "=== Login Instructions ===\n";
        echo "1. Go to: http://127.0.0.1:8000/login\n";
        echo "2. Use credentials:\n";
        echo "   Email: admin@gmail.com\n";
        echo "   Password: password1234*\n\n";
        
        echo "=== Troubleshooting 419 Error ===\n";
        echo "If you still get 419 'Page Expired' error:\n";
        echo "1. Clear browser cache and cookies\n";
        echo "2. Try in incognito/private window\n";
        echo "3. Refresh the login page before attempting login\n";
        echo "4. Check that JavaScript is enabled in browser\n\n";
        
    } else {
        echo "❌ Admin account NOT found!\n";
        echo "Please run the admin creation script first.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}