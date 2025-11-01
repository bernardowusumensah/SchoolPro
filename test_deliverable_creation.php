<?php

/**
 * Test script to verify deliverable auto-creation on project approval
 * Run with: php test_deliverable_creation.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\Deliverable;

echo "=== Testing Deliverable Auto-Creation ===\n\n";

// Find a pending project
$project = Project::where('status', 'Pending')->first();

if (!$project) {
    echo "❌ No pending projects found. Please create a pending project first.\n";
    exit(1);
}

echo "Found pending project:\n";
echo "  ID: {$project->id}\n";
echo "  Title: {$project->title}\n";
echo "  Status: {$project->status}\n";
echo "  Student ID: {$project->student_id}\n";
echo "\n";

// Check if deliverable already exists
$existingDeliverables = $project->deliverables()->count();
echo "Existing deliverables for this project: {$existingDeliverables}\n\n";

// Approve the project using Eloquent save (which triggers observer)
echo "Approving project using Eloquent save()...\n";
$project->status = 'Approved';
$project->approved_at = now();
$project->approved_by = 1; // Using admin ID
$project->save();

echo "✅ Project approved!\n\n";

// Check if deliverable was created
$deliverables = $project->deliverables()->get();
echo "Deliverables after approval: {$deliverables->count()}\n";

if ($deliverables->count() > 0) {
    echo "\n✅ SUCCESS! Deliverable was automatically created:\n";
    foreach ($deliverables as $deliverable) {
        echo "  - ID: {$deliverable->id}\n";
        echo "  - Title: {$deliverable->title}\n";
        echo "  - Type: {$deliverable->type}\n";
        echo "  - Status: {$deliverable->status}\n";
        echo "  - Due Date: {$deliverable->due_date}\n";
    }
} else {
    echo "\n❌ FAILED! No deliverable was created.\n";
    echo "Check storage/logs/laravel.log for observer messages.\n";
}

echo "\n=== Test Complete ===\n";
