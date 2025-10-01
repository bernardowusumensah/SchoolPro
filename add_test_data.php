<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$testStudent = \App\Models\User::where('email', 'test1@gmail.com')->first();
$testTeacher = \App\Models\User::where('email', 'test2@gmail.com')->first();

if ($testStudent && $testTeacher) {
    \App\Models\Project::create([
        'title' => 'Mobile Learning App',
        'description' => 'Cross-platform educational app for enhanced learning',
        'student_id' => $testStudent->id,
        'supervisor_id' => $testTeacher->id,
        'status' => 'Pending'
    ]);
    
    \App\Models\Project::create([
        'title' => 'E-commerce Platform',
        'description' => 'Full-stack e-commerce solution with payment integration',
        'student_id' => $testStudent->id,
        'supervisor_id' => $testTeacher->id,
        'status' => 'Approved',
        'approved_at' => now(),
        'approved_by' => $testTeacher->id
    ]);
    
    \App\Models\Project::create([
        'title' => 'Social Media Dashboard',
        'description' => 'Analytics dashboard for social media management',
        'student_id' => $testStudent->id,
        'supervisor_id' => $testTeacher->id,
        'status' => 'Rejected',
        'rejection_reason' => 'Scope too broad for capstone project'
    ]);
    
    echo "Test data created successfully!\n";
} else {
    echo "Test users not found!\n";
}