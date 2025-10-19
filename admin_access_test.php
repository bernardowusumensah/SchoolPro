<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== ADMIN ACCESS TROUBLESHOOTING ===\n";
echo "====================================\n\n";

// Check if admin user exists and credentials are correct
echo "1️⃣ **Checking Admin User**\n";
echo "─────────────────────────\n";

$admin = User::where('email', 'admin@gmail.com')->first();

if ($admin) {
    echo "✅ Admin user found:\n";
    echo "   ID: {$admin->id}\n";
    echo "   Name: {$admin->name}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n";
    echo "   Status: {$admin->status}\n";
    
    // Test password verification
    $passwordCheck = Hash::check('password1234*', $admin->password);
    echo "   Password Check: " . ($passwordCheck ? "✅ CORRECT" : "❌ INCORRECT") . "\n\n";
} else {
    echo "❌ Admin user not found!\n\n";
}

// Check available routes
echo "2️⃣ **Available Admin URLs**\n";
echo "─────────────────────────\n";
echo "🌐 Login: http://127.0.0.1:8000/login\n";
echo "🏠 Admin Dashboard: http://127.0.0.1:8000/dashboard/admin\n";
echo "👥 User Management: http://127.0.0.1:8000/admin/users\n";
echo "➕ Create User: http://127.0.0.1:8000/admin/users/create\n";
echo "📊 Projects Overview: http://127.0.0.1:8000/admin/projects\n\n";

// Check if Laravel development server is running
echo "3️⃣ **Server Status Check**\n";
echo "─────────────────────────\n";

// Create a simple test file to check server response
$testUrl = "http://127.0.0.1:8000";
echo "Testing server connection to: {$testUrl}\n";

// Simple curl test
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Server is running and responding (HTTP {$httpCode})\n";
} elseif ($httpCode > 0) {
    echo "⚠️  Server responded with HTTP {$httpCode}\n";
} else {
    echo "❌ Server is not responding - Please start with: php artisan serve\n";
}

echo "\n4️⃣ **Login Instructions**\n";
echo "─────────────────────────\n";
echo "1. Open: http://127.0.0.1:8000/login\n";
echo "2. Enter credentials:\n";
echo "   Email: admin@gmail.com\n";
echo "   Password: password1234*\n";
echo "3. After login, go to: http://127.0.0.1:8000/admin/users\n\n";

echo "5️⃣ **User Management Features Available**\n";
echo "─────────────────────────────────────\n";
echo "✅ View all users with filtering\n";
echo "✅ Create new users (students/teachers)\n";
echo "✅ Edit existing user details\n";
echo "✅ Activate/Deactivate users\n";
echo "✅ Delete users (with admin protection)\n";
echo "✅ Search and pagination\n\n";

echo "🚀 **Ready to manage users!**\n";