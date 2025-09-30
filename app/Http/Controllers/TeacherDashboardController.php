<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Log;
use App\Models\Grade;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        // Get teacher-specific statistics aligned with user stories
        $teacherId = auth()->id();
        
        // Get assigned students (students with projects assigned to this teacher)
        $assignedStudents = User::where('role', 'student')
            ->whereHas('projects', function ($query) use ($teacherId) {
                $query->where('supervisor_id', $teacherId);
            })->get();

        $assignedStudentIds = $assignedStudents->pluck('id');
        
        // Teacher supervision statistics
        $stats = [
            // Students assigned to this teacher
            'assigned_students' => $assignedStudents->count(),
            
            // Projects with proposals pending review
            'pending_proposals' => Project::whereIn('student_id', $assignedStudentIds)
                ->where('status', 'Pending')
                ->count(),
            
            // Weekly logs that need review (recent logs from past 7 days)
            'unreviewed_logs' => Log::whereIn('student_id', $assignedStudentIds)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            
            // Final submissions awaiting grading (completed projects without grades)
            'pending_grading' => Project::whereIn('student_id', $assignedStudentIds)
                ->where('status', 'Completed')
                ->whereDoesntHave('grades')
                ->count(),
        ];

        // Get assigned students with their project details for the table
        $studentsWithProjects = User::where('role', 'student')
            ->whereHas('projects', function ($query) use ($teacherId) {
                $query->where('supervisor_id', $teacherId);
            })
            ->with(['projects' => function ($query) use ($teacherId) {
                $query->where('supervisor_id', $teacherId)->latest();
            }, 'logs' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function ($student) {
                $latestProject = $student->projects->first();
                $latestLog = $student->logs->first();
                
                // Determine action needed based on project status
                $actionNeeded = 'monitor_progress';
                if ($latestProject) {
                    switch ($latestProject->status) {
                        case 'Pending':
                            $actionNeeded = 'review_proposal';
                            break;
                        case 'Completed':
                            $actionNeeded = 'grade_submission';
                            break;
                        case 'Approved':
                            // Check if logs need review (recent activity)
                            if ($latestLog && $latestLog->created_at >= now()->subDays(7)) {
                                $actionNeeded = 'check_logs';
                            }
                            break;
                    }
                }
                
                $student->project_title = $latestProject ? $latestProject->title : 'No Project Assigned';
                $student->project_status = $latestProject ? $latestProject->status : 'not_started';
                $student->last_log_date = $latestLog ? $latestLog->created_at->diffForHumans() : 'No logs yet';
                $student->action_needed = $actionNeeded;
                
                return $student;
            });

        return view('dashboard.teacher', [
            'stats' => $stats,
            'students' => $studentsWithProjects
        ]);
    }
}
