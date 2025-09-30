<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        $student = User::where('role', 'student')->inRandomOrder()->first();
        $supervisor = User::whereIn('role', ['teacher', 'admin'])->inRandomOrder()->first();
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'student_id' => $student ? $student->id : User::factory()->create(['role' => 'student'])->id,
            'supervisor_id' => $supervisor ? $supervisor->id : User::factory()->create(['role' => 'teacher'])->id,
            'status' => $this->faker->randomElement(['Pending', 'Approved', 'Rejected', 'Completed']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
