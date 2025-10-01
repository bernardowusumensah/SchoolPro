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
        Schema::table('projects', function (Blueprint $table) {
            // Project categorization and planning
            $table->string('category')->nullable()->after('description');
            $table->text('objectives')->nullable()->after('category');
            $table->text('methodology')->nullable()->after('objectives');
            $table->text('expected_outcomes')->nullable()->after('methodology');
            
            // Timeline and resources
            $table->date('expected_start_date')->nullable()->after('expected_outcomes');
            $table->date('expected_completion_date')->nullable()->after('expected_start_date');
            $table->integer('estimated_hours')->nullable()->after('expected_completion_date');
            $table->text('required_resources')->nullable()->after('estimated_hours');
            
            // Technology and tools
            $table->json('technology_stack')->nullable()->after('required_resources');
            $table->text('tools_and_software')->nullable()->after('technology_stack');
            
            // Supporting documents
            $table->json('supporting_documents')->nullable()->after('tools_and_software');
            
            // Draft functionality
            $table->boolean('is_draft')->default(false)->after('supporting_documents');
            $table->timestamp('draft_saved_at')->nullable()->after('is_draft');
            
            // Feedback and revisions
            $table->text('supervisor_feedback')->nullable()->after('draft_saved_at');
            $table->text('revision_notes')->nullable()->after('supervisor_feedback');
            $table->integer('revision_count')->default(0)->after('revision_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'objectives', 
                'methodology',
                'expected_outcomes',
                'expected_start_date',
                'expected_completion_date',
                'estimated_hours',
                'required_resources',
                'technology_stack',
                'tools_and_software',
                'supporting_documents',
                'is_draft',
                'draft_saved_at',
                'supervisor_feedback',
                'revision_notes',
                'revision_count'
            ]);
        });
    }
};
