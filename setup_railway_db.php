<?php
/**
 * Setup Railway Database - Run migrations and seed
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "Setting up Railway database...\n\n";

try {
    // Run migrations
    echo "Running migrations...\n";
    Artisan::call('migrate:fresh', ['--force' => true]);
    echo Artisan::output();
    
    // Run seeders
    echo "\nRunning seeders...\n";
    Artisan::call('db:seed', ['--force' => true]);
    echo Artisan::output();
    
    echo "\n✅ Database setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
