<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== CREATING ADDITIONAL ADMIN USERS ===" . PHP_EOL;

// Create additional admin users
$newAdmins = [
    [
        'name' => 'Academic Administrator',
        'email' => 'academic.admin@schoolpro.edu',
        'password' => 'Academic2024!',
        'role' => 'admin',
        'status' => 'active'
    ],
    [
        'name' => 'Registrar Admin',
        'email' => 'registrar@schoolpro.edu', 
        'password' => 'Registrar2024!',
        'role' => 'admin',
        'status' => 'active'
    ],
    [
        'name' => 'IT Support Admin',
        'email' => 'itsupport@schoolpro.edu',
        'password' => 'ITSupport2024!',
        'role' => 'admin',
        'status' => 'active'
    ]
];

foreach ($newAdmins as $adminData) {
    $existing = User::where('email', $adminData['email'])->first();
    
    if (!$existing) {
        $admin = User::create([
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => Hash::make($adminData['password']),
            'role' => $adminData['role'],
            'status' => $adminData['status'],
            'email_verified_at' => now()
        ]);
        
        echo "✅ Created: {$admin->name} ({$admin->email})" . PHP_EOL;
        echo "   Password: {$adminData['password']}" . PHP_EOL . PHP_EOL;
    } else {
        echo "⚠️  Already exists: {$adminData['name']} ({$adminData['email']})" . PHP_EOL;
    }
}

// Display final admin structure
echo "=== FINAL ADMIN STRUCTURE ===" . PHP_EOL;
$adminRole = 'admin';
$admins = User::where('role', $adminRole)->get();

foreach ($admins as $admin) {
    echo "🔹 {$admin->name} ({$admin->email})" . PHP_EOL;
    echo "   Created: {$admin->created_at->format('Y-m-d H:i')}" . PHP_EOL . PHP_EOL;
}

echo "Total Admins: " . $admins->count() . PHP_EOL;