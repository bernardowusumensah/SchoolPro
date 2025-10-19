<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get all admin users
$admins = \App\Models\User::where('role', 'admin')->get(['id', 'name', 'email', 'created_at']);

echo "=== CURRENT ADMIN USERS ===" . PHP_EOL;
echo "Total admin users: " . $admins->count() . PHP_EOL . PHP_EOL;

foreach ($admins as $admin) {
    echo "ID: {$admin->id}" . PHP_EOL;
    echo "Name: {$admin->name}" . PHP_EOL;
    echo "Email: {$admin->email}" . PHP_EOL;
    echo "Created: {$admin->created_at->format('Y-m-d H:i:s')}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

// Get total users by role
$stats = \App\Models\User::selectRaw('role, COUNT(*) as count')
    ->groupBy('role')
    ->get();

echo PHP_EOL . "=== USER STATISTICS ===" . PHP_EOL;
foreach ($stats as $stat) {
    echo strtoupper($stat->role) . ": {$stat->count} users" . PHP_EOL;
}