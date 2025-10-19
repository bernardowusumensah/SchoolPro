<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Updating admin password...\n";

// Find the admin user
$admin = User::where('email', 'admin@gmail.com')->first();

if ($admin) {
    // Update password
    $admin->password = Hash::make('password1234*');
    $admin->save();
    
    echo "✅ Admin password updated successfully!\n";
    echo "Email: {$admin->email}\n";
    echo "New password: password1234*\n";
    echo "Role: {$admin->role}\n";
    echo "Status: {$admin->status}\n";
} else {
    echo "❌ Admin user not found!\n";
}