<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== UPDATING ADMIN PROFILE PICTURE ===\n";
echo "=====================================\n\n";

// Find the admin user
$admin = User::where('email', 'admin@gmail.com')->first();

if ($admin) {
    // Set a default profile picture
    $admin->profile_picture = 'default-admin.svg';
    $admin->save();
    
    echo "✅ Admin profile picture updated!\n";
    echo "User: {$admin->name}\n";
    echo "Email: {$admin->email}\n";
    echo "Profile Picture: {$admin->profile_picture}\n";
} else {
    echo "❌ Admin user not found!\n";
}

echo "\n🖼️ Profile picture has been set for the admin user.\n";