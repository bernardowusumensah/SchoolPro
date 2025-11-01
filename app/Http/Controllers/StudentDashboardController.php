<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Log;
use App\Models\Grade;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // Get student-specific statistics aligned with user stories
        $studentId = auth()->id();
        
        // Get student's current project
        $currentProject = Project::where('student_id', $studentId)->latest()->first();
        
        // Get all student's projects
        $allProjects = Project::where('student_id', $studentId)
            ->with('supervisor')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate project statistics
        $projectStats = [
            'pending' => $allProjects->where('status', 'Pending')->count(),
            'approved' => $allProjects->where('status', 'Approved')->count(),
            'needs_revision' => $allProjects->where('status', 'Needs Revision')->count(),
            'rejected' => $allProjects->where('status', 'Rejected')->count(),
            'completed' => $allProjects->where('status', 'Completed')->count(),
        ];
        
        // Get student's weekly logs (only actual student logs, not system audit logs)
        $studentLogs = Log::where('student_id', $studentId)
            ->whereNotNull('content') // Only student weekly logs
            ->where('content', '!=', '') // Ensure content is not empty
            ->get();
        $weeklyLogs = $studentLogs->where('created_at', '>=', now()->subDays(84))->count(); // 12 weeks
        
        // Calculate days until deadline (assuming 16-week semester)
        $semesterStart = now()->subWeeks(8); // Assume we're mid-semester
        $semesterEnd = $semesterStart->copy()->addWeeks(16);
        $daysUntilDeadline = (int) now()->diffInDays($semesterEnd, false);
        
        // Calculate progress percentage based on project status and logs
        $progressPercentage = 0;
        if ($currentProject) {
            switch ($currentProject->status) {
                case 'Pending':
                    $progressPercentage = 10; // Proposal submitted
                    break;
                case 'Approved':
                    $progressPercentage = 30 + ($weeklyLogs * 4); // Base 30% + 4% per log
                    break;
                case 'Completed':
                    $progressPercentage = 100;
                    break;
                default:
                    $progressPercentage = 0;
            }
        }
        $progressPercentage = min($progressPercentage, 100);
        
        // Student dashboard statistics
        $stats = [
            // Current project status
            'project_status' => $currentProject ? $currentProject->status : 'Not Started',
            'proposal_status' => $currentProject ? $currentProject->status : 'Not Started',
            
            // Weekly logs tracking
            'weekly_logs' => $weeklyLogs,
            'required_logs' => 12, // Typical semester requirement
            
            // Deadline tracking
            'days_until_deadline' => max((int) $daysUntilDeadline, 0),
            
            // Progress analytics
            'progress_percentage' => $progressPercentage,
            'development_progress' => $currentProject && $currentProject->status == 'Approved' ? min(($weeklyLogs * 8), 100) : 0,
            'documentation_progress' => $currentProject && $currentProject->status == 'Approved' ? min(($weeklyLogs * 6), 100) : 0,
            'final_progress' => $currentProject && $currentProject->status == 'Completed' ? 100 : min(($weeklyLogs * 2), 30),
        ];
        
        // Get recent activities/notifications (can be expanded later)
        $recentActivities = [];
        if ($currentProject) {
            $recentActivities[] = [
                'type' => 'project',
                'message' => 'Project proposal ' . strtolower($currentProject->status),
                'date' => $currentProject->updated_at,
            ];
        }
        
        return view('dashboard.student', compact('stats', 'recentActivities', 'currentProject', 'allProjects', 'projectStats'));
    }
}
