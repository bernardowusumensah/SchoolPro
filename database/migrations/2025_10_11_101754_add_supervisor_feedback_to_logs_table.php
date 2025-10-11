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
        Schema::table('logs', function (Blueprint $table) {
            // Basic supervisor feedback fields
            $table->text('supervisor_feedback')->nullable()->after('file_path');
            $table->timestamp('feedback_date')->nullable()->after('supervisor_feedback');
            
            // Teacher rating system
            $table->enum('supervisor_rating', ['Excellent', 'Good', 'Satisfactory', 'Needs Improvement'])->nullable()->after('feedback_date');
            
            // Private teacher notes (not visible to students)
            $table->text('private_notes')->nullable()->after('supervisor_rating');
            
            // Flag for logs requiring follow-up
            $table->boolean('requires_followup')->default(false)->after('private_notes');
            
            // Track when feedback was last updated
            $table->timestamp('feedback_updated_at')->nullable()->after('requires_followup');
            
            // Add indexes for better query performance
            $table->index(['supervisor_rating']);
            $table->index(['requires_followup']);
            $table->index(['feedback_updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['supervisor_rating']);
            $table->dropIndex(['requires_followup']);
            $table->dropIndex(['feedback_updated_at']);
            
            // Drop columns
            $table->dropColumn([
                'supervisor_feedback', 
                'feedback_date',
                'supervisor_rating',
                'private_notes',
                'requires_followup',  
                'feedback_updated_at'
            ]);
        });
    }
};
