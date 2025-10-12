<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class TeacherLogController extends Controller
{
    /**
     * Display a listing of logs from assigned students.
     */
    public function index(Request $request): View
    {
        $teacherId = Auth::id();
        
        // Get all projects supervised by this teacher
        $supervisedProjectIds = Project::where('supervisor_id', $teacherId)->pluck('id');
        
        // Build query for logs from supervised projects (student weekly logs only)
        $query = Log::whereIn('project_id', $supervisedProjectIds)
            ->whereNotNull('content') // Only student weekly logs, not system audit logs
            ->where('content', '!=', '') // Ensure content is not empty
            ->with(['student', 'project'])
            ->latest();

        // Apply filters
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'unreviewed':
                    $query->whereNull('supervisor_feedback');
                    break;
                case 'reviewed':
                    $query->whereNotNull('supervisor_feedback');
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
            }
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $logs = $query->paginate(15);
        
        // Get filter options
        $assignedStudents = User::where('role', 'student')
            ->whereHas('projects', function ($q) use ($teacherId) {
                $q->where('supervisor_id', $teacherId);
            })
            ->orderBy('name')
            ->get();

        $supervisedProjects = Project::where('supervisor_id', $teacherId)
            ->with('student')
            ->orderBy('title')
            ->get();

        return view('teacher.logs.index', compact('logs', 'assignedStudents', 'supervisedProjects'));
    }

    /**
     * Display logs that need review (unreviewed).
     * Only shows student weekly logs, not system audit logs.
     */
    public function unreviewed(): View
    {
        $teacherId = Auth::id();
        
        $supervisedProjectIds = Project::where('supervisor_id', $teacherId)->pluck('id');
        
        $unreviewedLogs = Log::whereIn('project_id', $supervisedProjectIds)
            ->whereNull('supervisor_feedback')
            ->whereNotNull('content') // Only student weekly logs, not system audit logs
            ->where('content', '!=', '') // Ensure content is not empty
            ->with(['student', 'project'])
            ->latest()
            ->paginate(15);

        return view('teacher.logs.unreviewed', compact('unreviewedLogs'));
    }

    /**
     * Display the specified log.
     */
    public function show(Log $log): View
    {
        $teacherId = Auth::id();
        
        // Ensure teacher can only view logs from their supervised projects
        if ($log->project->supervisor_id !== $teacherId) {
            abort(403, 'Unauthorized access to this log.');
        }

        $log->load(['student', 'project']);
        
        // Get previous logs from the same student for context
        $previousLogs = Log::where('student_id', $log->student_id)
            ->where('project_id', $log->project_id)
            ->where('id', '!=', $log->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('teacher.logs.show', compact('log', 'previousLogs'));
    }

    /**
     * Provide feedback on a log entry.
     */
    public function provideFeedback(Request $request, Log $log): JsonResponse
    {
        $teacherId = Auth::id();
        
        // Ensure teacher can only provide feedback on logs from their supervised projects
        if ($log->project->supervisor_id !== $teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this log.'
            ], 403);
        }

        $request->validate([
            'supervisor_feedback' => 'required|string|min:10|max:2000',
        ], [
            'supervisor_feedback.min' => 'Feedback must be at least 10 characters long.',
            'supervisor_feedback.max' => 'Feedback cannot exceed 2000 characters.',
        ]);

        $log->update([
            'supervisor_feedback' => $request->supervisor_feedback,
            'feedback_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback provided successfully!',
            'feedback' => $log->supervisor_feedback,
            'feedback_date' => $log->feedback_date->format('M j, Y g:i A'),
        ]);
    }

    /**
     * Mark a log as reviewed without detailed feedback.
     */
    public function markReviewed(Log $log): JsonResponse
    {
        $teacherId = Auth::id();
        
        // Ensure teacher can only mark logs from their supervised projects as reviewed
        if ($log->project->supervisor_id !== $teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this log.'
            ], 403);
        }

        $log->update([
            'supervisor_feedback' => 'Reviewed - No specific feedback provided.',
            'feedback_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log marked as reviewed successfully!',
        ]);
    }

    /**
     * Display logs for a specific student.
     */
    public function studentLogs(User $student): View
    {
        $teacherId = Auth::id();
        
        // Ensure teacher supervises this student
        $hasSupervisedProject = Project::where('student_id', $student->id)
            ->where('supervisor_id', $teacherId)
            ->exists();

        if (!$hasSupervisedProject) {
            abort(403, 'Unauthorized access to this student\'s logs.');
        }

        $logs = Log::where('student_id', $student->id)
            ->whereHas('project', function ($query) use ($teacherId) {
                $query->where('supervisor_id', $teacherId);
            })
            ->with(['project'])
            ->latest()
            ->paginate(15);

        return view('teacher.logs.student-logs', compact('logs', 'student'));
    }

    /**
     * Display logs for a specific project.
     */
    public function projectLogs(Project $project): View
    {
        $teacherId = Auth::id();
        
        // Ensure teacher supervises this project
        if ($project->supervisor_id !== $teacherId) {
            abort(403, 'Unauthorized access to this project\'s logs.');
        }

        $logs = Log::where('project_id', $project->id)
            ->with(['student'])
            ->latest()
            ->paginate(15);

        return view('teacher.logs.project-logs', compact('logs', 'project'));
    }

    /**
     * Rate a log entry.
     */
    public function rateLog(Request $request, Log $log): JsonResponse
    {
        $teacherId = Auth::id();
        
        // Ensure teacher can only rate logs from their supervised projects
        if ($log->project->supervisor_id !== $teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this log.'
            ], 403);
        }

        $request->validate([
            'supervisor_rating' => 'required|in:Excellent,Good,Satisfactory,Needs Improvement',
        ]);

        $log->update([
            'supervisor_rating' => $request->supervisor_rating,
            'feedback_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log rated successfully!',
            'rating' => $log->supervisor_rating,
            'badge_color' => $log->getRatingBadgeColor(),
            'rating_icon' => $log->getRatingIcon(),
        ]);
    }

    /**
     * Update existing feedback for a log.
     */
    public function updateFeedback(Request $request, Log $log): JsonResponse
    {
        $teacherId = Auth::id();
        
        // Ensure teacher can only update feedback on logs from their supervised projects
        if ($log->project->supervisor_id !== $teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this log.'
            ], 403);
        }

        $request->validate([
            'supervisor_feedback' => 'required|string|min:10|max:2000',
        ]);

        $log->update([
            'supervisor_feedback' => $request->supervisor_feedback,
            'feedback_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback updated successfully!',
            'feedback' => $log->supervisor_feedback,
            'updated_at' => $log->feedback_updated_at->format('M j, Y g:i A'),
        ]);
    }

    /**
     * Add or update private notes for a log.
     */
    public function updatePrivateNotes(Request $request, Log $log): JsonResponse
    {
        $teacherId = Auth::id();
        
        // Ensure teacher can only add notes to logs from their supervised projects
        if ($log->project->supervisor_id !== $teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this log.'
            ], 403);
        }

        $request->validate([
            'private_notes' => 'nullable|string|max:1000',
        ]);

        $log->update([
            'private_notes' => $request->private_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Private notes updated successfully!',
            'notes' => $log->private_notes,
        ]);
    }

    /**
     * Toggle follow-up requirement for a log.
     */
    public function toggleFollowup(Log $log): JsonResponse
    {
        $teacherId = Auth::id();
        
        // Ensure teacher can only toggle follow-up for logs from their supervised projects
        if ($log->project->supervisor_id !== $teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this log.'
            ], 403);
        }

        $log->update([
            'requires_followup' => !$log->requires_followup,
        ]);

        return response()->json([
            'success' => true,
            'message' => $log->requires_followup ? 'Log flagged for follow-up.' : 'Follow-up flag removed.',
            'requires_followup' => $log->requires_followup,
        ]);
    }

    /**
     * Export logs to PDF.
     */
    public function exportLogs(Request $request): \Illuminate\Http\Response
    {
        $teacherId = Auth::id();
        
        // Get all projects supervised by this teacher
        $supervisedProjectIds = Project::where('supervisor_id', $teacherId)->pluck('id');
        
        // Build query for logs from supervised projects
        $query = Log::whereIn('project_id', $supervisedProjectIds)
            ->with(['student', 'project']);

        // Apply filters
        if ($request->filled('rating')) {
            $query->where('supervisor_rating', $request->rating);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        // Create CSV content
        $csvContent = "Student Name,Project Title,Log Date,Rating,Feedback,Requires Follow-up\n";
        
        foreach ($logs as $log) {
            $csvContent .= sprintf(
                '"%s","%s","%s","%s","%s","%s"' . "\n",
                $log->student->name ?? 'N/A',
                $log->project->title ?? 'N/A',
                $log->created_at->format('Y-m-d H:i:s'),
                $log->supervisor_rating ?? 'Not Rated',
                str_replace('"', '""', $log->supervisor_feedback ?? 'No feedback'),
                $log->requires_followup ? 'Yes' : 'No'
            );
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="student_logs_' . date('Y-m-d') . '.csv"');
    }

    /**
     * Get analytics data for logs.
     */
    public function analytics(): View
    {
        $teacherId = Auth::id();
        
        // Get all projects supervised by this teacher
        $supervisedProjectIds = Project::where('supervisor_id', $teacherId)->pluck('id');
        
        $totalLogs = Log::whereIn('project_id', $supervisedProjectIds)->count();
        $reviewedLogs = Log::whereIn('project_id', $supervisedProjectIds)->reviewed()->count();
        $unreviewedLogs = $totalLogs - $reviewedLogs;
        
        // Rating distribution
        $ratingStats = Log::whereIn('project_id', $supervisedProjectIds)
            ->whereNotNull('supervisor_rating')
            ->groupBy('supervisor_rating')
            ->selectRaw('supervisor_rating, count(*) as count')
            ->pluck('count', 'supervisor_rating')
            ->toArray();

        // Logs requiring follow-up
        $followupLogs = Log::whereIn('project_id', $supervisedProjectIds)
            ->where('requires_followup', true)
            ->count();

        // Recent activity (last 30 days)
        $recentActivity = Log::whereIn('project_id', $supervisedProjectIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Student performance overview
        $studentStats = Log::whereIn('project_id', $supervisedProjectIds)
            ->with('student')
            ->get()
            ->groupBy('student_id')
            ->map(function ($logs) {
                $student = $logs->first()->student;
                return [
                    'name' => $student->name,
                    'total_logs' => $logs->count(),
                    'avg_rating' => $this->calculateAverageRating($logs),
                    'follow_up_needed' => $logs->where('requires_followup', true)->count()
                ];
            })
            ->values();

        return view('teacher.logs.analytics', compact(
            'totalLogs', 'reviewedLogs', 'unreviewedLogs', 
            'ratingStats', 'followupLogs', 'recentActivity', 'studentStats'
        ));
    }

    /**
     * Calculate average rating score for logs.
     */
    private function calculateAverageRating($logs): float
    {
        $ratedLogs = $logs->whereNotNull('supervisor_rating');
        if ($ratedLogs->isEmpty()) return 0;

        $ratingValues = [
            'Excellent' => 4,
            'Good' => 3,
            'Satisfactory' => 2,
            'Needs Improvement' => 1
        ];

        $totalScore = $ratedLogs->sum(function ($log) use ($ratingValues) {
            return $ratingValues[$log->supervisor_rating] ?? 0;
        });

        return round($totalScore / $ratedLogs->count(), 2);
    }
}
