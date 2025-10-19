<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CREATING ADDITIONAL ADMIN USERS ===" . PHP_EOL;

// Check if we need to add admin_level column
$hasAdminLevel = \Illuminate\Support\Facades\Schema::hasColumn('users', 'admin_level');
if (!$hasAdminLevel) {
    echo "Adding admin_level column to users table..." . PHP_EOL;
    \Illuminate\Support\Facades\Schema::table('users', function ($table) {
        $table->enum('admin_level', ['super', 'admin', 'moderator'])->default('admin')->after('role');
    });
}

// Update existing System Administrator to super admin
$sysAdmin = \App\Models\User::where('email', 'admin@gmail.com')->first();
if ($sysAdmin && $hasAdminLevel) {
    $sysAdmin->admin_level = 'super';
    $sysAdmin->save();
    echo "Updated System Administrator to Super Admin level" . PHP_EOL;
}

// Create additional admin users
$newAdmins = [
    [
        'name' => 'Academic Administrator',
        'email' => 'academic.admin@schoolpro.edu',
        'password' => 'Academic2024!',
        'role' => 'admin',
        'admin_level' => 'admin',
        'status' => 'active',
        'profile_picture' => null
    ],
    [
        'name' => 'Registrar Admin',
        'email' => 'registrar@schoolpro.edu', 
        'password' => 'Registrar2024!',
        'role' => 'admin',
        'admin_level' => 'admin',
        'status' => 'active',
        'profile_picture' => null
    ],
    [
        'name' => 'IT Support Admin',
        'email' => 'itsupport@schoolpro.edu',
        'password' => 'ITSupport2024!',
        'role' => 'admin', 
        'admin_level' => 'moderator',
        'status' => 'active',
        'profile_picture' => null
    ]
];

foreach ($newAdmins as $adminData) {
    $existing = \App\Models\User::where('email', $adminData['email'])->first();
    
    if (!$existing) {
        $admin = \App\Models\User::create([
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($adminData['password']),
            'role' => $adminData['role'],
            'admin_level' => $adminData['admin_level'] ?? 'admin',
            'status' => $adminData['status'],
            'profile_picture' => $adminData['profile_picture'],
            'email_verified_at' => now()
        ]);
        
        echo "✅ Created: {$admin->name} ({$admin->email})" . PHP_EOL;
        echo "   Password: {$adminData['password']}" . PHP_EOL;
        echo "   Level: {$admin->admin_level}" . PHP_EOL . PHP_EOL;
    } else {
        echo "⚠️  Already exists: {$adminData['name']} ({$adminData['email]})" . PHP_EOL;
    }
}

// Display final admin structure
echo "=== FINAL ADMIN STRUCTURE ===" . PHP_EOL;
$admins = \App\Models\User::where('role', 'admin')->orderBy('admin_level')->get();

foreach ($admins as $admin) {
    $level = $admin->admin_level ?? 'admin';
    echo "🔹 {$admin->name} ({$admin->email})" . PHP_EOL;
    echo "   Level: " . strtoupper($level) . PHP_EOL;
    echo "   Created: {$admin->created_at->format('Y-m-d H:i')}" . PHP_EOL . PHP_EOL;
}

echo "Total Admins: " . $admins->count() . PHP_EOL;