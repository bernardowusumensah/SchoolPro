<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Project;
use App\Models\Log;
use App\Models\Grade;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();
        
        $allProjects = Project::where("supervisor_id", $teacherId)->get();
        
        $assignedStudents = User::where("role", "student")
            ->whereHas("projects", function ($query) use ($teacherId) {
                $query->where("supervisor_id", $teacherId);
            })->get();

        $assignedStudentIds = $assignedStudents->pluck("id");
        
        // Get supervised project IDs for accurate log counting
        $supervisedProjectIds = $allProjects->pluck('id');
        
        $stats = [
            "assigned_students" => $assignedStudents->count(),
            "pending_proposals" => $allProjects->where("status", "Pending")->count(),
            "approved_projects" => $allProjects->where("status", "Approved")->count(),
            "needs_revision" => $allProjects->where("status", "Needs Revision")->count(),
            "rejected_proposals" => $allProjects->where("status", "Rejected")->count(),
            "pending_grading" => $allProjects->where("status", "Completed")->count(),
            "unreviewed_logs" => Log::whereIn("project_id", $supervisedProjectIds)
                ->whereNull('supervisor_feedback')
                ->whereNotNull('content') // Only student weekly logs, not system audit logs
                ->where('content', '!=', '') // Ensure content is not empty
                ->count(),
            "total_projects" => $allProjects->count(),
        ];

        $recentProposals = Project::where("supervisor_id", $teacherId)
            ->with(["student"])
            ->where(function ($query) {
                $query->where("status", "Pending")
                      ->orWhere("status", "Needs Revision")
                      ->orWhere("created_at", ">=", now()->subDays(7));
            })
            ->orderBy("created_at", "desc")
            ->limit(10)
            ->get();

        $studentsWithProjects = User::where("role", "student")
            ->whereHas("projects", function ($query) use ($teacherId) {
                $query->where("supervisor_id", $teacherId);
            })
            ->with(["projects" => function ($query) use ($teacherId) {
                $query->where("supervisor_id", $teacherId)->latest();
            }, "logs" => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function ($student) {
                $latestProject = $student->projects->first();
                $latestLog = $student->logs->first();
                
                $actionNeeded = "monitor_progress";
                $actionPriority = "low";
                
                if ($latestProject) {
                    switch ($latestProject->status) {
                        case "Pending":
                            $actionNeeded = "review_proposal";
                            $actionPriority = "high";
                            break;
                        case "Needs Revision":
                            $actionNeeded = "check_revision";
                            $actionPriority = "medium";
                            break;
                        case "Approved":
                            $actionNeeded = "monitor_progress";
                            $actionPriority = "low";
                            break;
                        case "Completed":
                            $actionNeeded = "provide_grading";
                            $actionPriority = "high";
                            break;
                    }
                }
                
                $student->latest_project_status = $latestProject ? $latestProject->status : "No project";
                $student->project_submitted = $latestProject ? $latestProject->created_at->diffForHumans() : "N/A";
                $student->last_log_date = $latestLog ? $latestLog->created_at->diffForHumans() : "No logs yet";
                $student->action_needed = $actionNeeded;
                $student->action_priority = $actionPriority;
                
                return $student;
            });

        return view("dashboard.teacher", [
            "stats" => $stats,
            "students" => $studentsWithProjects,
            "recentProposals" => $recentProposals
        ]);
    }

    public function proposals()
    {
        $teacherId = auth()->id();
        
        $proposals = Project::where('supervisor_id', $teacherId)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('teacher.proposals.index', compact('proposals'));
    }

    public function show(Project $project)
    {
        if ($project->supervisor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this proposal.');
        }

        $project->load(['student', 'supervisor']);
        return view('teacher.proposal-details', compact('project'));
    }

    public function approve(Request $request, Project $project)
    {
        if ($project->supervisor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this proposal.');
        }

        $request->validate([
            'approval_comments' => 'nullable|string|max:1000'
        ]);

        $project->update([
            'status' => 'Approved',
            'approval_comments' => $request->approval_comments,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proposal approved successfully.'
        ]);
    }

    public function reject(Request $request, Project $project)
    {
        if ($project->supervisor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this proposal.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $project->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proposal rejected successfully.'
        ]);
    }

    public function requestRevision(Request $request, Project $project)
    {
        if ($project->supervisor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this proposal.');
        }

        $request->validate([
            'revision_comments' => 'required|string|max:1000'
        ]);

        $project->update([
            'status' => 'Needs Revision',
            'rejection_reason' => $request->revision_comments,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Revision requested successfully.'
        ]);
    }

    public function destroy(Project $project)
    {
        if ($project->supervisor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this proposal.');
        }

        // BUSINESS LOGIC: Prevent deletion of projects that have been reviewed
        if ($project->status !== 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a project that has already been reviewed. Projects can only be deleted while in "Pending" status to maintain audit trail.'
            ], 422);
        }

        $projectTitle = $project->title;
        $studentName = $project->student->name;
        
        // Delete associated files if any
        if ($project->supporting_documents) {
            $documents = json_decode($project->supporting_documents, true);
            if (is_array($documents)) {
                foreach ($documents as $document) {
                    if (isset($document['path']) && Storage::disk('public')->exists($document['path'])) {
                        Storage::disk('public')->delete($document['path']);
                    }
                }
            }
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => "Project '{$projectTitle}' by {$studentName} has been deleted successfully."
        ]);
    }


}