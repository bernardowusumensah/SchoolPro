<?php

/**
 * Fix script: Create missing deliverables for already-approved projects
 * Run with: php fix_missing_deliverables.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\Deliverable;

echo "=== Fixing Missing Deliverables for Approved Projects ===\n\n";

// Find all approved projects without deliverables
$approvedProjects = Project::where('status', 'Approved')
    ->doesntHave('deliverables')
    ->get();

if ($approvedProjects->isEmpty()) {
    echo "✅ No approved projects missing deliverables. All good!\n";
    exit(0);
}

echo "Found {$approvedProjects->count()} approved project(s) without deliverables:\n\n";

foreach ($approvedProjects as $project) {
    echo "Project ID: {$project->id}\n";
    echo "  Title: {$project->title}\n";
    echo "  Student ID: {$project->student_id}\n";
    echo "  Approved At: {$project->approved_at}\n";
    
    // Calculate due date (8 weeks from approval or now)
    $approvalDate = $project->approved_at ?? now();
    $dueDate = $approvalDate->copy()->addWeeks(8);
    
    // Create deliverable
    $deliverable = Deliverable::create([
        'project_id' => $project->id,
        'title' => 'Final Project Deliverable - ' . $project->title,
        'description' => 'Submit your completed project including all required components and documentation. Work on your project and log progress regularly. Submit final deliverable when due.',
        'type' => 'final_project',
        'due_date' => $dueDate,
        'weight_percentage' => 100,
        'max_file_size_mb' => 50,
        'file_types_allowed' => ['pdf', 'doc', 'docx', 'zip', 'rar'],
        'requirements' => 'Submit your completed project with all documentation, code, and supporting materials.',
        'status' => 'Pending'
    ]);
    
    echo "  ✅ Created deliverable ID: {$deliverable->id}\n";
    echo "  Due Date: {$deliverable->due_date}\n";
    echo "\n";
}

echo "=== Fix Complete ===\n";
echo "\nSummary:\n";
echo "- Fixed {$approvedProjects->count()} project(s)\n";
echo "- Total deliverables now: " . Deliverable::count() . "\n";
echo "\n✅ Students can now submit their project deliverables!\n";
