<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create specific test users first
        \App\Models\User::create([
            'name' => 'Test Student',
            'email' => 'test1@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        \App\Models\User::create([
            'name' => 'Test Teacher',
            'email' => 'test2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'status' => 'active',
        ]);

        \App\Models\User::create([
            'name' => 'Test Admin',
            'email' => 'test3@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Then create random users
        \App\Models\User::factory()
            ->count(17)
            ->state(function () {
                return [
                    'role' => fake()->randomElement(['student', 'teacher', 'admin']),
                    'status' => fake()->randomElement(['active', 'inactive']),
                ];
            })
            ->create();
    }
}
