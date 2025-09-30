<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition()
    {
        $project = Project::inRandomOrder()->first();
        $teacher = User::where('role', 'teacher')->inRandomOrder()->first();
        return [
            'project_id' => $project ? $project->id : null,
            'teacher_id' => $teacher ? $teacher->id : null,
            'grade' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'F']),
            'feedback' => $this->faker->sentence(10),
            'created_at' => now(),
        ];
    }
}
