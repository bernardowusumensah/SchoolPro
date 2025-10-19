<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== USER MANAGEMENT DEBUG PAGE ===\n";
echo "==================================\n\n";

// Display all users in the system
$users = User::all();

echo "📊 **CURRENT USERS IN SYSTEM ({$users->count()} total)**\n";
echo "─────────────────────────────────────────\n\n";

foreach ($users as $user) {
    echo "👤 **User ID: {$user->id}**\n";
    echo "   Name: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Role: " . strtoupper($user->role) . "\n";
    echo "   Status: " . strtoupper($user->status) . "\n";
    echo "   Created: {$user->created_at}\n";
    echo "   ─────────────────────────\n";
}

echo "\n📈 **STATISTICS**\n";
echo "─────────────────\n";
$studentCount = User::where('role', 'student')->count();
$teacherCount = User::where('role', 'teacher')->count();
$adminCount = User::where('role', 'admin')->count();
$activeCount = User::where('status', 'active')->count();
$inactiveCount = User::where('status', 'inactive')->count();

echo "Students: {$studentCount}\n";
echo "Teachers: {$teacherCount}\n";
echo "Admins: {$adminCount}\n";
echo "Active: {$activeCount}\n";
echo "Inactive: {$inactiveCount}\n\n";

echo "🔗 **DIRECT ACCESS LINKS**\n";
echo "──────────────────────────\n";
echo "After logging in with admin@gmail.com / password1234*\n\n";
echo "👥 All Users: http://127.0.0.1:8000/admin/users\n";
echo "➕ Create User: http://127.0.0.1:8000/admin/users/create\n";

if ($users->count() > 0) {
    $firstUser = $users->first();
    echo "👁️  View User: http://127.0.0.1:8000/admin/users/{$firstUser->id}\n";
    echo "✏️  Edit User: http://127.0.0.1:8000/admin/users/{$firstUser->id}/edit\n";
}

echo "\n🔐 **ADMIN CREDENTIALS**\n";
echo "──────────────────────\n";
echo "Email: admin@gmail.com\n";
echo "Password: password1234*\n\n";

echo "🚀 **STATUS: ALL USERS ACCESSIBLE VIA ADMIN PANEL**\n";