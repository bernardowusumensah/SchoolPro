<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Creating Admin User Account ===\n";
echo "===================================\n\n";

try {
    // Check if admin user already exists
    $existingAdmin = User::where('email', 'admin@gmail.com')->first();
    
    if ($existingAdmin) {
        echo "⚠️  Admin user with email 'admin@gmail.com' already exists!\n";
        echo "User ID: {$existingAdmin->id}\n";
        echo "Name: {$existingAdmin->name}\n";
        echo "Role: {$existingAdmin->role}\n";
        echo "Status: {$existingAdmin->status}\n\n";
    } else {
        // Create new admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password1234*'),
            'role' => 'admin',
            'status' => 'active'
        ]);
        
        echo "✅ Admin user created successfully!\n";
        echo "User ID: {$admin->id}\n";
        echo "Name: {$admin->name}\n";
        echo "Email: {$admin->email}\n";
        echo "Role: {$admin->role}\n";
        echo "Status: {$admin->status}\n\n";
    }
    
    echo "=== Admin Login Credentials ===\n";
    echo "Email: admin@gmail.com\n";
    echo "Password: password1234*\n\n";
    
    echo "=== Current User Statistics ===\n";
    $totalUsers = User::count();
    $students = User::where('role', 'student')->count();
    $teachers = User::where('role', 'teacher')->count();
    $admins = User::where('role', 'admin')->count();
    
    echo "Total Users: {$totalUsers}\n";
    echo "Students: {$students}\n";
    echo "Teachers: {$teachers}\n";
    echo "Admins: {$admins}\n\n";
    
    echo "🎯 Next Step: Login to admin dashboard at /dashboard/admin\n";
    
} catch (Exception $e) {
    echo "❌ Error creating admin user: " . $e->getMessage() . "\n";
}