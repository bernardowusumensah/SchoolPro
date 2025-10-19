<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$admin = User::where('email', 'admin@gmail.com')->first();
if ($admin) {
    $admin->super_admin = true;
    $admin->save();
    echo "System Administrator is now the only super admin.\n";
} else {
    echo "System Administrator not found.\n";
}
