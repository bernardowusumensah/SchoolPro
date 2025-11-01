<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\Deliverable;

echo "=== Current Projects Status ===\n\n";

$projects = Project::select('id', 'title', 'status', 'student_id')->get();

if ($projects->isEmpty()) {
    echo "No projects found in database.\n";
} else {
    foreach ($projects as $project) {
        $deliverableCount = $project->deliverables()->count();
        echo "Project ID: {$project->id}\n";
        echo "  Title: {$project->title}\n";
        echo "  Status: {$project->status}\n";
        echo "  Student ID: {$project->student_id}\n";
        echo "  Deliverables: {$deliverableCount}\n";
        echo "\n";
    }
}

echo "\n=== Total Deliverables in Database ===\n";
$totalDeliverables = Deliverable::count();
echo "Total: {$totalDeliverables}\n";

if ($totalDeliverables > 0) {
    echo "\nDeliverable Details:\n";
    Deliverable::all()->each(function($d) {
        echo "  - ID: {$d->id}, Project ID: {$d->project_id}, Title: {$d->title}, Status: {$d->status}\n";
    });
}
