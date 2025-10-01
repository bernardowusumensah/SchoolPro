<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // For SQLite, we need to change the column type
            $table->string('status')->default('Pending')->change();
        });
        
        // Update the constraint to include Draft status
        DB::statement("UPDATE projects SET status = 'Pending' WHERE status NOT IN ('Draft', 'Pending', 'Approved', 'Rejected', 'Completed')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Revert back to original enum
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending')->change();
        });
    }
};
