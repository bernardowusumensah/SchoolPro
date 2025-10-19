<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== GENERATING UNIQUE PROFILE PICTURES ===\n";
echo "==========================================\n\n";

// Get all users
$users = User::all();

// Define color schemes for different users
$colors = [
    'admin' => ['#4A90E2', '#fff'],  // Blue
    'teacher' => ['#FF6B6B', '#fff'], // Red
    'student' => ['#4ECDC4', '#fff'], // Teal
];

$studentColors = ['#FF9F43', '#6C5CE7', '#A29BFE', '#74B9FF', '#00B894', '#FDCB6E'];
$teacherColors = ['#E17055', '#81ECEC', '#FD79A8', '#FDCB6E', '#6C5CE7'];

echo "📸 Generating profile pictures for {$users->count()} users...\n\n";

foreach ($users as $index => $user) {
    // Generate initials from name
    $names = explode(' ', $user->name);
    $initials = '';
    foreach ($names as $name) {
        $initials .= strtoupper(substr($name, 0, 1));
    }
    $initials = substr($initials, 0, 2); // Max 2 initials
    
    // Choose color based on role and user
    if ($user->role === 'admin') {
        $bgColor = '#4A90E2';
    } elseif ($user->role === 'teacher') {
        $bgColor = $teacherColors[$index % count($teacherColors)];
    } else {
        $bgColor = $studentColors[$index % count($studentColors)];
    }
    
    // Create SVG content
    $svg = '<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <circle cx="50" cy="50" r="50" fill="' . $bgColor . '"/>
  <text x="50" y="60" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="32" font-weight="bold">' . $initials . '</text>
</svg>';
    
    // Create filename
    $filename = strtolower(str_replace(' ', '_', $user->name)) . '_' . $user->id . '.svg';
    $filepath = __DIR__ . '/public/images/profiles/' . $filename;
    
    // Save SVG file
    file_put_contents($filepath, $svg);
    
    // Update user's profile picture in database
    $user->profile_picture = $filename;
    $user->save();
    
    echo "✅ {$user->name} ({$user->role}): {$filename}\n";
    echo "   Initials: {$initials}, Color: {$bgColor}\n";
}

echo "\n🎨 **Profile Picture Generation Complete!**\n";
echo "==========================================\n";
echo "✅ All {$users->count()} users now have unique profile pictures\n";
echo "✅ Pictures are based on user initials and role colors\n";
echo "✅ Files saved to /public/images/profiles/\n";
echo "✅ Database updated with profile picture filenames\n\n";

echo "🌈 **Color Scheme:**\n";
echo "🔵 Admin: Blue theme\n";
echo "🔴 Teachers: Red/Orange/Purple themes\n";
echo "🟢 Students: Teal/Orange/Blue themes\n";