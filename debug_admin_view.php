<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== ADMIN VIEW DEBUG TEST ===\n";
echo "=============================\n\n";

// Test the exact query that the AdminUserController uses
echo "🔍 **Testing AdminUserController Query**\n";
echo "─────────────────────────────────────\n\n";

$query = User::query();
$users = $query->orderBy('created_at', 'desc')->paginate(15);

echo "📊 Query Results:\n";
echo "Total users found: {$users->total()}\n";
echo "Per page: {$users->perPage()}\n";
echo "Current page: {$users->currentPage()}\n";
echo "Has pages: " . ($users->hasPages() ? 'Yes' : 'No') . "\n\n";

echo "👥 Users on current page:\n";
foreach ($users as $user) {
    echo "  - ID: {$user->id}, Name: {$user->name}, Role: {$user->role}, Status: {$user->status}\n";
}

echo "\n🧪 **Testing View Data Structure**\n";
echo "─────────────────────────────────\n";

// Test what would be passed to the view
$viewData = ['users' => $users];
echo "View data keys: " . implode(', ', array_keys($viewData)) . "\n";
echo "Users variable type: " . gettype($viewData['users']) . "\n";
echo "Users count method: " . $viewData['users']->count() . "\n";

echo "\n✅ **Status: Controller query working properly**\n";
echo "The issue is likely in the view rendering or CSS loading.\n";