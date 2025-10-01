<?php
// Test script to verify CRUD operations
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SchoolPro CRUD Operations Test ===\n\n";

// Test 1: Check projects data
echo "1. Current Projects Status:\n";
$projects = \App\Models\Project::with('student', 'supervisor')->get();
foreach ($projects as $project) {
    echo "   ID: {$project->id} | Title: " . substr($project->title, 0, 30) . "... | Status: {$project->status}\n";
}

echo "\n2. Statistics:\n";
$pending = \App\Models\Project::where('status', 'Pending')->count();
$approved = \App\Models\Project::where('status', 'Approved')->count();
$needsRevision = \App\Models\Project::where('status', 'Needs Revision')->count();
$rejected = \App\Models\Project::where('status', 'Rejected')->count();

echo "   Pending: {$pending}\n";
echo "   Approved: {$approved}\n"; 
echo "   Needs Revision: {$needsRevision}\n";
echo "   Rejected: {$rejected}\n";
echo "   Total: " . ($pending + $approved + $needsRevision + $rejected) . "\n";

echo "\n3. Deletion Protection Check:\n";
// Check which projects can be deleted
$pendingProjects = \App\Models\Project::where('status', 'Pending')->get();
$nonPendingProjects = \App\Models\Project::whereNotIn('status', ['Pending'])->get();

echo "   Projects that CAN be deleted (Pending): " . $pendingProjects->count() . "\n";
echo "   Projects that CANNOT be deleted (Non-Pending): " . $nonPendingProjects->count() . "\n";

echo "\n4. Database Schema Check:\n";
// Check if approval workflow fields exist
$sample = \App\Models\Project::first();
if ($sample) {
    $attributes = $sample->getAttributes();
    $workflowFields = ['approval_comments', 'rejection_reason', 'approved_at', 'approved_by', 'reviewed_at', 'reviewed_by'];
    foreach ($workflowFields as $field) {
        $exists = array_key_exists($field, $attributes) ? "✓" : "✗";
        echo "   {$field}: {$exists}\n";
    }
}

echo "\n=== Test Complete ===\n";