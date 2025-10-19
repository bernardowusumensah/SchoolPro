<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo "=== Direct Admin Login Test ===\n";
echo "===============================\n\n";

try {
    // Find admin user
    $admin = User::where('email', 'admin@gmail.com')->first();
    
    if (!$admin) {
        echo "❌ Admin user not found!\n";
        exit(1);
    }
    
    // Test password
    if (Hash::check('password1234*', $admin->password)) {
        echo "✅ Password verification successful!\n";
        echo "Admin account is working correctly.\n\n";
        
        // Check if user is active
        if ($admin->status === 'active') {
            echo "✅ Account status: Active\n";
        } else {
            echo "⚠️  Account status: {$admin->status}\n";
        }
        
        echo "\n=== Next Steps ===\n";
        echo "1. Close your browser completely\n";
        echo "2. Reopen browser and go to: http://127.0.0.1:8000/login\n";
        echo "3. Wait for page to load completely\n";
        echo "4. Enter credentials and login\n\n";
        
        echo "If 419 error persists, try:\n";
        echo "- Use Chrome/Firefox incognito mode\n";
        echo "- Disable browser extensions temporarily\n";
        echo "- Check browser console for JavaScript errors\n";
        
    } else {
        echo "❌ Password verification failed!\n";
        echo "The stored password doesn't match 'password1234*'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}