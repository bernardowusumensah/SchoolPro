<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliverable_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deliverable_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->json('file_paths'); // Store multiple file paths
            $table->text('submission_note')->nullable();
            $table->timestamp('submitted_at');
            $table->enum('status', ['submitted', 'late', 'reviewed', 'approved', 'rejected'])->default('submitted');
            $table->decimal('grade', 5, 2)->nullable(); // Grade out of 100
            $table->text('teacher_feedback')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->integer('version')->default(1); // For resubmissions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliverable_submissions');
    }
};
