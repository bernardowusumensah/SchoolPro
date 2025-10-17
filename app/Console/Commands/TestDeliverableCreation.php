<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Deliverable;

class TestDeliverableCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:deliverable-creation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test automatic deliverable creation when project is approved';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing automatic deliverable creation...');
        
        // Find a project that isn't approved yet
        $project = Project::where('status', '!=', 'approved')->first();
        
        if (!$project) {
            $this->warn('No non-approved projects found. All projects may already be approved.');
            return;
        }
        
        $this->info("Found project: {$project->title}");
        $this->info("Current status: {$project->status}");
        
        $deliverablesBefore = $project->deliverables()->count();
        $this->info("Deliverables before: {$deliverablesBefore}");
        
        // Update project to approved (this should trigger the observer)
        $project->update(['status' => 'approved']);
        
        $deliverablesAfter = $project->fresh()->deliverables()->count();
        $this->info("Deliverables after: {$deliverablesAfter}");
        
        if ($deliverablesAfter > $deliverablesBefore) {
            $deliverable = $project->deliverables()->latest()->first();
            $this->info("✅ Deliverable auto-created successfully!");
            $this->info("Title: {$deliverable->title}");
            $this->info("Due date: {$deliverable->due_date}");
            $this->info("Weight: {$deliverable->weight_percentage}%");
            $this->info("Type: {$deliverable->type}");
        } else {
            $this->error("❌ No deliverable was auto-created. Check the observer.");
        }
    }
}
