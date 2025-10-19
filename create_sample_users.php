<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Creating Sample Users for Admin Demo ===\n";
echo "===========================================\n\n";

try {
    // Create sample students
    $students = [
        [
            'name' => 'Alice Johnson',
            'email' => 'alice.johnson@student.edu',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'status' => 'active'
        ],
        [
            'name' => 'Bob Smith',
            'email' => 'bob.smith@student.edu',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'status' => 'active'
        ],
        [
            'name' => 'Carol Davis',
            'email' => 'carol.davis@student.edu',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'status' => 'inactive' // Inactive for testing
        ]
    ];

    // Create sample teachers
    $teachers = [
        [
            'name' => 'Dr. Emily Wilson',
            'email' => 'emily.wilson@faculty.edu',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
            'status' => 'active'
        ],
        [
            'name' => 'Prof. Michael Brown',
            'email' => 'michael.brown@faculty.edu',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
            'status' => 'active'
        ]
    ];

    echo "Creating Students:\n";
    foreach ($students as $student) {
        // Check if user already exists
        $existing = User::where('email', $student['email'])->first();
        if (!$existing) {
            $user = User::create($student);
            echo "✅ Created: {$user->name} ({$user->email}) - Status: {$user->status}\n";
        } else {
            echo "⚠️  Already exists: {$student['name']} ({$student['email']})\n";
        }
    }

    echo "\nCreating Teachers:\n";
    foreach ($teachers as $teacher) {
        // Check if user already exists
        $existing = User::where('email', $teacher['email'])->first();
        if (!$existing) {
            $user = User::create($teacher);
            echo "✅ Created: {$user->name} ({$user->email}) - Role: {$user->role}\n";
        } else {
            echo "⚠️  Already exists: {$teacher['name']} ({$teacher['email']})\n";
        }
    }

    echo "\n=== Updated User Statistics ===\n";
    $totalUsers = User::count();
    $students = User::where('role', 'student')->count();
    $teachers = User::where('role', 'teacher')->count();
    $admins = User::where('role', 'admin')->count();
    $activeUsers = User::where('status', 'active')->count();
    $inactiveUsers = User::where('status', 'inactive')->count();

    echo "Total Users: {$totalUsers}\n";
    echo "├── Students: {$students}\n";
    echo "├── Teachers: {$teachers}\n";
    echo "└── Admins: {$admins}\n\n";
    echo "Status Distribution:\n";
    echo "├── Active: {$activeUsers}\n";
    echo "└── Inactive: {$inactiveUsers}\n\n";

    echo "=== Admin Testing Scenarios ===\n";
    echo "Now you can test admin features:\n\n";
    
    echo "1. **View All Users:**\n";
    echo "   → Go to: http://127.0.0.1:8000/admin/users\n";
    echo "   → See all created users with role badges\n\n";
    
    echo "2. **Filter by Role:**\n";
    echo "   → Use role dropdown to filter by Student, Teacher, Admin\n";
    echo "   → Notice color coding: Green=Student, Blue=Teacher, Purple=Admin\n\n";
    
    echo "3. **Filter by Status:**\n";
    echo "   → Filter by 'inactive' to see Carol Davis (inactive student)\n";
    echo "   → Test activate/deactivate functionality\n\n";
    
    echo "4. **Search Functionality:**\n";
    echo "   → Search for 'Alice' to find Alice Johnson\n";
    echo "   → Search for '@faculty.edu' to find all teachers\n\n";
    
    echo "5. **User Management:**\n";
    echo "   → Click 'View' to see individual user profiles\n";
    echo "   → Click 'Edit' to modify user information\n";
    echo "   → Try deactivate/activate on student accounts\n\n";
    
    echo "6. **Create New User:**\n";
    echo "   → Click 'Create New User' button\n";
    echo "   → Test creating additional students or teachers\n\n";
    
    echo "🎯 **Test Login Credentials:**\n";
    echo "Students (password: student123):\n";
    echo "├── alice.johnson@student.edu\n";
    echo "├── bob.smith@student.edu\n";
    echo "└── carol.davis@student.edu (inactive)\n\n";
    echo "Teachers (password: teacher123):\n";
    echo "├── emily.wilson@faculty.edu\n";
    echo "└── michael.brown@faculty.edu\n\n";
    
    echo "Admin (password: password1234*):\n";
    echo "└── admin@gmail.com\n\n";

    echo "Status: ✅ SAMPLE USERS CREATED\n";
    echo "Ready for comprehensive admin testing!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}