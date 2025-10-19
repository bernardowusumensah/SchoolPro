<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Grading System Analysis ===\n\n";

// Check deliverable submissions
$submissions = App\Models\DeliverableSubmission::with(['student', 'deliverable.project'])->get();

echo "Total Submissions: " . $submissions->count() . "\n\n";

foreach ($submissions as $submission) {
    echo "--- Submission Details ---\n";
    echo "Student: " . $submission->student->name . "\n";
    echo "Project: " . $submission->deliverable->project->title . "\n";
    echo "Grade: " . ($submission->grade ?? 'Not graded') . "\n";
    echo "Letter: " . $submission->getGradeLetter() . "\n";
    echo "Status: " . $submission->status . "\n";
    echo "Feedback: " . ($submission->teacher_feedback ?? 'No feedback') . "\n";
    echo "Submitted: " . $submission->submitted_at->format('Y-m-d H:i') . "\n";
    echo "\n";
}

// Check project completion status
echo "=== Project Completion Status ===\n\n";
$projects = App\Models\Project::with('student')->get();

foreach ($projects as $project) {
    echo "Project: " . $project->title . "\n";
    echo "Student: " . $project->student->name . "\n"; 
    echo "Status: " . $project->status . "\n";
    echo "Final Grade: " . ($project->final_grade ?? 'Not graded') . "\n";
    echo "Completed At: " . ($project->completed_at ? $project->completed_at->format('Y-m-d H:i') : 'Not completed') . "\n";
    echo "\n";
}

// Show grade scale
echo "=== Grade Scale ===\n";
$testGrades = [95, 88, 82, 75, 68, 65, 55, 45];
foreach ($testGrades as $grade) {
    $submission = new App\Models\DeliverableSubmission(['grade' => $grade]);
    echo "$grade% = " . $submission->getGradeLetter() . "\n";
}