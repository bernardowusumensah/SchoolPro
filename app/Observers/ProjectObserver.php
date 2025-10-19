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
        // When project is approved, create deliverable with future due date
        if ($project->isDirty('status') && $project->status === 'approved') {
            $this->createProjectDeliverable($project);
        }
    }

    /**
     * Create deliverable with project due date when approved
     */
    private function createProjectDeliverable(Project $project): void
    {
        // Check if deliverable already exists for this project
        if ($project->deliverables()->count() > 0) {
            return;
        }

        // Set deliverable due date (8 weeks from approval for project work)
        $dueDate = now()->addWeeks(8);

        Deliverable::create([
            'project_id' => $project->id,
            'title' => 'Final Project Deliverable - ' . $project->title,
            'description' => 'Submit your completed project including all required components and documentation. Work on your project and log progress regularly. Submit final deliverable when due.',
            'type' => 'final_project',
            'due_date' => $dueDate,
            'weight_percentage' => 100, // Single deliverable gets full weight
            'max_file_size_mb' => 50, // 50MB limit
            'file_types_allowed' => ['pdf', 'doc', 'docx', 'zip', 'rar'],
            'requirements' => 'Submit your completed project with all documentation, code, and supporting materials.',
            'status' => 'pending'
        ]);
    }
}