<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== GENERATING PROFILE PICTURES FOR NEW ADMINS ===" . PHP_EOL;

// Get the new admin users
$newAdmins = User::whereIn('email', [
    'academic.admin@schoolpro.edu',
    'registrar@schoolpro.edu', 
    'itsupport@schoolpro.edu'
])->get();

$profilesDir = __DIR__ . '/public/images/profiles/';

foreach ($newAdmins as $user) {
    // Generate initials
    $nameParts = explode(' ', $user->name);
    $initials = '';
    foreach ($nameParts as $part) {
        if (!empty($part) && $part !== 'Admin') {
            $initials .= strtoupper($part[0]);
        }
    }
    
    // Admin colors (blue theme variations)
    $colors = ['#4299e1', '#3182ce', '#2b77cb', '#2c5aa0'];
    $color = $colors[array_rand($colors)];
    
    // Create unique filename
    $filename = strtolower(str_replace([' ', '.'], ['_', '_'], $user->name)) . '_' . $user->id . '.svg';
    
    // Create SVG content
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <circle cx="50" cy="50" r="50" fill="' . $color . '"/>
  <text x="50" y="50" text-anchor="middle" dy="0.35em" fill="white" font-family="Arial, sans-serif" font-size="28" font-weight="bold">' . $initials . '</text>
</svg>';
    
    // Save SVG file
    $filepath = $profilesDir . $filename;
    file_put_contents($filepath, $svg);
    
    // Update user record
    $user->profile_picture = $filename;
    $user->save();
    
    echo "✅ Created profile for: {$user->name}" . PHP_EOL;
    echo "   Initials: {$initials}" . PHP_EOL;
    echo "   Color: {$color}" . PHP_EOL;
    echo "   File: {$filename}" . PHP_EOL . PHP_EOL;
}

echo "Profile pictures generated successfully!" . PHP_EOL;