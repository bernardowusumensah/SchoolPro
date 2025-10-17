<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Deliverable;
use App\Models\DeliverableSubmission;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherDeliverableController extends Controller
{
    /**
     * Display teacher's deliverables management dashboard
     */
    public function index(): View
    {
        $teacher = Auth::user();
        
        // Get all projects supervised by this teacher
        $projects = $teacher->supervisedProjects()
            ->whereIn('status', ['approved', 'in_progress', 'completed'])
            ->with(['deliverables.submissions.student'])
            ->get();

        // Get pending submissions for review
        $pendingSubmissions = DeliverableSubmission::whereHas('deliverable.project', function($query) use ($teacher) {
            $query->where('supervisor_id', $teacher->id);
        })
        ->where(function($query) {
            $query->where('status', 'submitted')
                  ->orWhere('status', 'late');
        })
        ->with(['deliverable.project', 'student'])
        ->orderBy('submitted_at')
        ->get();

        // Get overdue deliverables
        $overdueDeliverables = Deliverable::whereHas('project', function($query) use ($teacher) {
            $query->where('supervisor_id', $teacher->id);
        })
        ->where('due_date', '<', now())
        ->where('status', 'pending')
        ->with(['project.student'])
        ->get();

        // Get recent submissions for activity feed
        $recentSubmissions = DeliverableSubmission::whereHas('deliverable.project', function($query) use ($teacher) {
            $query->where('supervisor_id', $teacher->id);
        })
        ->with(['deliverable.project.student', 'student'])
        ->orderBy('submitted_at', 'desc')
        ->limit(10)
        ->get();

        return view('teacher.deliverables.index', compact(
            'projects', 'pendingSubmissions', 'overdueDeliverables', 'recentSubmissions'
        ));
    }

    /**
     * Show deliverables for a specific project
     */
    public function projectDeliverables(Project $project): View
    {
        $teacher = Auth::user();
        
        // Check if teacher supervises this project
        if ($project->supervisor_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this project.');
        }

        $deliverables = $project->deliverables()
            ->with(['submissions.student'])
            ->orderBy('due_date')
            ->get();

        return view('teacher.deliverables.project', compact('project', 'deliverables'));
    }



    /**
     * Show submission for review
     */
    public function reviewSubmission(DeliverableSubmission $submission): View
    {
        $teacher = Auth::user();
        
        // Check access
        if ($submission->deliverable->project->supervisor_id !== $teacher->id) {
            abort(403);
        }

        return view('teacher.deliverables.review', compact('submission'));
    }

    /**
     * Store review and grade
     */
    public function storeReview(Request $request, DeliverableSubmission $submission): RedirectResponse
    {
        $teacher = Auth::user();
        
        if ($submission->deliverable->project->supervisor_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'teacher_feedback' => 'required|string|min:10',
            'status' => 'required|in:reviewed,approved,rejected'
        ]);

        $deliverable = $submission->deliverable;
        $project = $deliverable->project;

        $submission->update([
            'grade' => $request->grade,
            'teacher_feedback' => $request->teacher_feedback,
            'status' => $request->status,
            'reviewed_at' => now(),
            'reviewed_by' => $teacher->id
        ]);

        // Update deliverable status
        $deliverable->update([
            'status' => $request->status
        ]);

        // Prepare success message
        $message = 'Submission reviewed successfully!';
        
        if ($request->status === 'approved') {
            // Record the final grade in the project
            $project->update([
                'final_grade' => $request->grade,
                'status' => 'Completed',
                'completed_at' => now()
            ]);
            
            $message .= ' Project has been completed with a final grade of ' . $request->grade . '%!';
        } elseif ($request->status === 'rejected') {
            $message .= ' Student can resubmit their work for reevaluation.';
        }

        return redirect()->route('teacher.deliverables.index')
            ->with('success', $message);
    }

    /**
     * Download submitted file
     */
    public function downloadFile(DeliverableSubmission $submission, $fileIndex)
    {
        $teacher = Auth::user();
        
        // Check access
        if ($submission->deliverable->project->supervisor_id !== $teacher->id) {
            abort(403);
        }

        $filePaths = $submission->file_paths;
        if (!isset($filePaths[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $file = $filePaths[$fileIndex];
        $filePath = $file['path'];

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return Storage::disk('public')->download($filePath, $file['original_name']);
    }

    /**
     * Bulk create standard deliverables for a project
     */
    public function createStandardDeliverables(Project $project): RedirectResponse
    {
        $teacher = Auth::user();
        
        if ($project->supervisor_id !== $teacher->id) {
            abort(403);
        }

        $standardDeliverables = [
            [
                'title' => 'Project Proposal Review',
                'description' => 'Submit updated project proposal with supervisor feedback incorporated',
                'type' => 'document',
                'due_date' => now()->addDays(7),
                'weight_percentage' => 10
            ],
            [
                'title' => 'Mid-Project Milestone',
                'description' => 'Demonstrate 50% project completion with working prototype',
                'type' => 'milestone',
                'due_date' => now()->addDays(30),
                'weight_percentage' => 25
            ],
            [
                'title' => 'Final Presentation',
                'description' => 'Present completed project with demonstration',
                'type' => 'presentation',
                'due_date' => now()->addDays(60),
                'weight_percentage' => 25
            ],
            [
                'title' => 'Final Project Submission',
                'description' => 'Submit complete project with documentation and source code',
                'type' => 'final_project',
                'due_date' => now()->addDays(65),
                'weight_percentage' => 40
            ]
        ];

        foreach ($standardDeliverables as $deliverable) {
            Deliverable::create(array_merge($deliverable, [
                'project_id' => $project->id,
                'file_types_allowed' => ['pdf', 'docx', 'pptx', 'zip'],
                'max_file_size_mb' => 25
            ]));
        }

        return redirect()->route('teacher.deliverables.project', $project)
            ->with('success', 'Standard deliverables created successfully!');
    }
}
