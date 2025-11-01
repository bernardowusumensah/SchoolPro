<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Deliverable;

$deliverable = Deliverable::find(1);
if ($deliverable) {
    $deliverable->status = 'Pending';
    $deliverable->save();
    echo "✅ Updated deliverable status to 'Pending'\n";
} else {
    echo "❌ Deliverable not found\n";
}
