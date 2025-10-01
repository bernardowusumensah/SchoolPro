<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherProjectController extends Controller
{
    /**
     * Display pending project proposals for review
     */
    public function pendingProposals()
    {
        $teacherId = Auth::id();
        
        $pendingProjects = Project::where('supervisor_id', $teacherId)
            ->where('status', 'Pending')
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.projects.pending', [
            'projects' => $pendingProjects,
            'title' => 'Pending Project Proposals'
        ]);
    }

    /**
     * Display all projects assigned to this teacher
     */
    public function index()
    {
        $teacherId = Auth::id();
        
        $projects = Project::where('supervisor_id', $teacherId)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.projects.index', [
            'projects' => $projects,
            'title' => 'My Supervised Projects'
        ]);
    }

    /**
     * Show detailed view of a project proposal
     */
    public function show(Project $project)
    {
        // Ensure teacher can only view their assigned projects
        if ($project->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized to view this project.');
        }

        $project->load(['student']);

        return view('teacher.projects.show', [
            'project' => $project,
            'title' => 'Project Proposal Details'
        ]);
    }

    /**
     * Approve a project proposal
     */
    public function approve(Request $request, Project $project)
    {
        // Ensure teacher can only approve their assigned projects
        if ($project->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized to approve this project.');
        }

        $request->validate([
            'approval_comments' => 'nullable|string|max:1000'
        ]);

        $project->update([
            'status' => 'Approved',
            'approval_comments' => $request->approval_comments,
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        return redirect()->route('teacher.projects.pending')
            ->with('success', 'Project proposal approved successfully!');
    }

    /**
     * Reject a project proposal
     */
    public function reject(Request $request, Project $project)
    {
        // Ensure teacher can only reject their assigned projects
        if ($project->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized to reject this project.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $project->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        return redirect()->route('teacher.projects.pending')
            ->with('success', 'Project proposal rejected with feedback.');
    }

    /**
     * Request revision for a project proposal
     */
    public function requestRevision(Request $request, Project $project)
    {
        // Ensure teacher can only request revision for their assigned projects
        if ($project->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized to request revision for this project.');
        }

        $request->validate([
            'revision_notes' => 'required|string|max:1000'
        ]);

        $project->update([
            'status' => 'Needs Revision',
            'revision_notes' => $request->revision_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        return redirect()->route('teacher.projects.pending')
            ->with('success', 'Revision requested. Student will be notified.');
    }

    /**
     * Download uploaded files for a project
     */
    public function downloadFile(Project $project, $filename)
    {
        // Ensure teacher can only download files from their assigned projects
        if ($project->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized to download files from this project.');
        }

        $filePath = "projects/{$project->id}/{$filename}";
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($filePath);
    }

    /**
     * View all projects with specific status
     */
    public function byStatus($status)
    {
        $teacherId = Auth::id();
        $validStatuses = ['pending', 'approved', 'rejected', 'needs-revision', 'completed'];
        
        if (!in_array($status, $validStatuses)) {
            abort(404, 'Invalid status.');
        }

        $statusMap = [
            'pending' => 'Pending',
            'approved' => 'Approved', 
            'rejected' => 'Rejected',
            'needs-revision' => 'Needs Revision',
            'completed' => 'Completed'
        ];

        $projects = Project::where('supervisor_id', $teacherId)
            ->where('status', $statusMap[$status])
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.projects.by-status', [
            'projects' => $projects,
            'status' => $statusMap[$status],
            'title' => $statusMap[$status] . ' Projects'
        ]);
    }
}