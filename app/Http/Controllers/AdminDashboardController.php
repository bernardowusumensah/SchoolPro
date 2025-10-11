<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get summary statistics for dashboard cards
        $stats = [
            'active_users' => User::where('status', 'active')->count(),
            'active_projects' => Project::where('status', 'Approved')->count(),
            'pending_approvals' => Project::where('status', 'Pending')->count(),
            'system_alerts' => 2, // Mock system alerts count
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_projects' => Project::count(),
        ];

        // Get recent users for the activity table (last 10)
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        // Get recent projects for oversight section (last 10)
        $recentProjects = Project::with(['student', 'supervisor'])->orderBy('created_at', 'desc')->take(10)->get();

        return view('dashboard.admin', compact('stats', 'recentUsers', 'recentProjects'));
    }

    /**
     * Show all projects across the platform
     */
    public function projects(Request $request)
    {
        $query = Project::with(['student', 'supervisor']);

        // Filter by user role if specified
        if ($request->filled('user_role')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('role', $request->user_role);
            });
        }

        // Search by project name or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('supervisor', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $projects = $query->paginate(15);

        return view('admin.projects', compact('projects'));
    }
}
