<?php
/**
 * Create Admin User for Both Local and Railway
 * 
 * Usage:
 * - Local: php create_admin_railway.php
 * - Railway: railway run php create_admin_railway.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "    Creating Admin User Account\n";
echo "==============================================\n\n";

// Get environment info
$environment = env('APP_ENV', 'local');
$dbConnection = env('DB_CONNECTION', 'mysql');
$dbHost = env('DB_HOST', '127.0.0.1');
$dbDatabase = env('DB_DATABASE', 'schoolpro');

echo "Environment: {$environment}\n";
echo "Database: {$dbConnection} @ {$dbHost}/{$dbDatabase}\n\n";

// Admin credentials
$adminData = [
    'name' => 'System Administrator',
    'email' => 'admin@gmail.com',
    'password' => 'password1234*',
    'role' => 'admin',
    'status' => 'active'
];

echo "Creating admin with:\n";
echo "  Name: {$adminData['name']}\n";
echo "  Email: {$adminData['email']}\n";
echo "  Role: {$adminData['role']}\n\n";

try {
    // Test database connection
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n\n";
    
    // Check if admin user already exists
    $existingAdmin = User::where('email', $adminData['email'])->first();
    
    if ($existingAdmin) {
        echo "⚠️  Admin user already exists!\n";
        echo "   User ID: {$existingAdmin->id}\n";
        echo "   Name: {$existingAdmin->name}\n";
        echo "   Email: {$existingAdmin->email}\n";
        echo "   Role: {$existingAdmin->role}\n";
        echo "   Status: {$existingAdmin->status}\n\n";
        
        // Ask if we should update the password
        echo "Do you want to update the password? (yes/no): ";
        if (php_sapi_name() === 'cli') {
            $handle = fopen("php://stdin", "r");
            $line = trim(fgets($handle));
            fclose($handle);
            
            if (strtolower($line) === 'yes' || strtolower($line) === 'y') {
                $existingAdmin->password = Hash::make($adminData['password']);
                $existingAdmin->save();
                echo "✅ Password updated successfully!\n\n";
            } else {
                echo "Password not updated.\n\n";
            }
        } else {
            // For Railway or non-interactive mode, update password
            $existingAdmin->password = Hash::make($adminData['password']);
            $existingAdmin->save();
            echo "✅ Password updated successfully!\n\n";
        }
    } else {
        // Create new admin user
        $admin = User::create([
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => Hash::make($adminData['password']),
            'role' => $adminData['role'],
            'status' => $adminData['status']
        ]);
        
        echo "✅ Admin user created successfully!\n";
        echo "   User ID: {$admin->id}\n";
        echo "   Name: {$admin->name}\n";
        echo "   Email: {$admin->email}\n";
        echo "   Role: {$admin->role}\n";
        echo "   Status: {$admin->status}\n\n";
    }
    
    echo "==============================================\n";
    echo "         Admin Login Credentials\n";
    echo "==============================================\n";
    echo "Email:    {$adminData['email']}\n";
    echo "Password: {$adminData['password']}\n";
    echo "==============================================\n\n";
    
    // Display user statistics
    echo "==============================================\n";
    echo "         Database Statistics\n";
    echo "==============================================\n";
    $totalUsers = User::count();
    $students = User::where('role', 'student')->count();
    $teachers = User::where('role', 'teacher')->count();
    $admins = User::where('role', 'admin')->count();
    
    echo "Total Users:  {$totalUsers}\n";
    echo "Students:     {$students}\n";
    echo "Teachers:     {$teachers}\n";
    echo "Admins:       {$admins}\n";
    echo "==============================================\n\n";
    
    // List all admin users
    $allAdmins = User::where('role', 'admin')->get();
    if ($allAdmins->count() > 0) {
        echo "All Admin Users:\n";
        echo "----------------------------------------------\n";
        foreach ($allAdmins as $user) {
            echo "  {$user->id}. {$user->name} ({$user->email}) - {$user->status}\n";
        }
        echo "----------------------------------------------\n\n";
    }
    
    echo "🎯 Next Step: Login to admin dashboard\n";
    echo "   Local: http://localhost/login\n";
    echo "   Railway: https://your-app.railway.app/login\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
