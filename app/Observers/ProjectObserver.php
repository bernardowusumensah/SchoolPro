<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\Deliverable;

class ProjectObserver
{
    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        // If project status changed to 'approved', create a single deliverable
        if ($project->isDirty('status') && $project->status === 'approved') {
            $this->createProjectDeliverable($project);
        }
    }

    /**
     * Create a single deliverable for the approved project
     */
    private function createProjectDeliverable(Project $project): void
    {
        // Check if deliverable already exists for this project
        if ($project->deliverables()->count() > 0) {
            return;
        }

        // Calculate due date (4 weeks from approval)
        $dueDate = now()->addWeeks(4);

        Deliverable::create([
            'project_id' => $project->id,
            'title' => 'Project Deliverable - ' . $project->title,
            'description' => 'Complete your project deliverable including all required components and documentation.',
            'type' => 'final_project',
            'due_date' => $dueDate,
            'weight_percentage' => 100, // Single deliverable gets full weight
            'max_file_size_mb' => 50, // 50MB limit
            'file_types_allowed' => ['pdf', 'doc', 'docx', 'zip', 'rar'],
            'requirements' => 'Submit your completed project with all documentation, code, and supporting materials.',
        ]);
    }
}