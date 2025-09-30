<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Create specific test project for our test users
        $testStudent = \App\Models\User::where('email', 'test1@gmail.com')->first();
        $testTeacher = \App\Models\User::where('email', 'test2@gmail.com')->first();
        
        if ($testStudent && $testTeacher) {
            \App\Models\Project::create([
                'title' => 'AI-Powered Student Assessment System',
                'description' => 'Developing an intelligent assessment platform for educational institutions using machine learning algorithms.',
                'student_id' => $testStudent->id,
                'supervisor_id' => $testTeacher->id,
                'status' => 'Approved',
            ]);
            
            \App\Models\Project::create([
                'title' => 'Mobile Learning Application',
                'description' => 'Creating a cross-platform mobile app for enhanced learning experiences.',
                'student_id' => $testStudent->id,
                'supervisor_id' => $testTeacher->id,
                'status' => 'Pending',
            ]);
        }

        // Create random projects
        \App\Models\Project::factory()->count(8)->create();
    }
}
