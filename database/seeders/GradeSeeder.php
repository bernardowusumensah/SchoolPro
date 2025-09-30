<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;   // 👈 ADD THIS LINE

class GradeSeeder extends Seeder
{
    public function run(): void
    {
    \App\Models\Grade::factory()->count(10)->create();
    }
}
