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
        // Student Progress Analytics
        Schema::create('student_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('project_id')->constrained('projects');
            $table->date('week_starting');
            $table->integer('logs_submitted')->default(0);
            $table->integer('words_written')->default(0);
            $table->integer('attachments_uploaded')->default(0);
            $table->integer('feedback_received')->default(0);
            $table->integer('feedback_acknowledged')->default(0);
            $table->decimal('avg_submission_time_hours', 5, 2)->nullable(); // Hours from week start
            $table->json('activity_pattern')->nullable(); // Daily activity tracking
            $table->decimal('consistency_score', 3, 2)->default(0); // 0-1 score
            $table->timestamps();
            
            $table->unique(['student_id', 'project_id', 'week_starting']);
            $table->index(['week_starting']);
        });

        // Teacher Analytics Aggregates
        Schema::create('teacher_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users');
            $table->date('week_starting');
            $table->integer('students_supervised')->default(0);
            $table->integer('logs_reviewed')->default(0);
            $table->integer('feedback_provided')->default(0);
            $table->decimal('avg_response_time_hours', 5, 2)->default(0);
            $table->integer('at_risk_students')->default(0); // Students with low engagement
            $table->json('subject_areas')->nullable(); // Projects by category
            $table->decimal('workload_score', 3, 2)->default(0); // Relative workload indicator
            $table->timestamps();
            
            $table->unique(['teacher_id', 'week_starting']);
        });

        // Progress Milestones Tracking
        Schema::create('progress_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('milestone_type'); // 'weekly_goal', 'major_deliverable', 'custom'
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('target_date');
            $table->date('completed_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue']);
            $table->json('completion_evidence')->nullable(); // Log IDs, attachments
            $table->timestamps();
        });

        // Log Analysis Cache (for performance)
        Schema::create('log_analytics_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_id')->constrained('logs');
            $table->integer('word_count')->default(0);
            $table->integer('character_count')->default(0);
            $table->json('keyword_frequency')->nullable();
            $table->decimal('sentiment_score', 3, 2)->nullable(); // -1 to 1
            $table->json('topics_mentioned')->nullable(); // AI-extracted topics
            $table->boolean('has_questions')->default(false);
            $table->boolean('mentions_challenges')->default(false);
            $table->boolean('shows_progress')->default(false);
            $table->timestamps();
            
            $table->unique('log_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_analytics_cache');
        Schema::dropIfExists('progress_milestones');
        Schema::dropIfExists('teacher_analytics');
        Schema::dropIfExists('student_analytics');
    }
};
