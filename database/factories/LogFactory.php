<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogFactory extends Factory
{
    protected $model = Log::class;

    public function definition()
    {
        // Get a random project and its student
        $project = Project::inRandomOrder()->first();
        $student = $project ? User::find($project->student_id) : User::where('role', 'student')->inRandomOrder()->first();
        
        return [
            'project_id' => $project ? $project->id : Project::factory()->create()->id,
            'student_id' => $student ? $student->id : User::factory()->create(['role' => 'student'])->id,
            'content' => $this->faker->paragraph(3),
            'file_path' => $this->faker->optional(0.7)->randomElement(['report.pdf', 'progress_update.docx', 'presentation.pptx', null]),
            'created_at' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
        ];
    }
}
