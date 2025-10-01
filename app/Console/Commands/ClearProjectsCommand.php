<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClearProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:clear {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all projects from the database for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('force') || $this->confirm('Are you sure you want to delete ALL projects? This action cannot be undone.')) {
            
            // Get all projects with supporting documents to clean up files
            $projects = DB::table('projects')->whereNotNull('supporting_documents')->get();
            
            // Delete uploaded files
            foreach ($projects as $project) {
                $documents = json_decode($project->supporting_documents, true);
                if ($documents) {
                    foreach ($documents as $document) {
                        if (isset($document['path'])) {
                            Storage::disk('public')->delete($document['path']);
                        }
                    }
                }
            }
            
            // Clear all projects
            $deletedCount = DB::table('projects')->delete();
            
            // Reset auto-increment for MySQL
            DB::statement('ALTER TABLE projects AUTO_INCREMENT = 1');
            
            $this->info("Successfully deleted {$deletedCount} projects and cleaned up uploaded files.");
            $this->info('Projects table has been cleared and auto-increment reset.');
            
        } else {
            $this->info('Operation cancelled.');
        }
    }
}
