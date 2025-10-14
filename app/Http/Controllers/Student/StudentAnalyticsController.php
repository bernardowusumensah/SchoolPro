<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentAnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show student progress analytics dashboard
     */
    public function index(Request $request)
    {
        try {
            $student = Auth::user();
            
            // Get date range from request or default to last 3 months
            $startDate = Carbon::parse($request->get('start_date', Carbon::now()->subMonths(3)->format('Y-m-d')));
            $endDate = Carbon::parse($request->get('end_date', Carbon::now()->format('Y-m-d')));
            
            // Get real analytics data from AnalyticsService
            $analytics = $this->analyticsService->getStudentProgressAnalytics($student, $startDate, $endDate);

            // Ensure we have valid analytics data
            if (!$analytics || !isset($analytics['overview'])) {
                throw new \Exception('Analytics data is not available');
            }

            // Add chart data for visualization
            $chartData = $this->prepareChartData($analytics);

            return view('student.analytics.index', compact(
                'analytics', 
                'chartData', 
                'startDate', 
                'endDate'
            ));
        } catch (\Exception $e) {
            // For debugging - let's see what the error is
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Get weekly progress data for AJAX requests
     */
    public function getWeeklyData(Request $request)
    {
        $student = Auth::user();
        $weeks = $request->get('weeks', 12);
        
        $startDate = Carbon::now()->subWeeks($weeks);
        $endDate = Carbon::now();
        
        $analytics = $this->analyticsService->getStudentProgressAnalytics(
            $student, 
            $startDate, 
            $endDate
        );

        return response()->json([
            'weekly_trends' => $analytics['weekly_trends'],
            'engagement_patterns' => $analytics['engagement_patterns']
        ]);
    }

    /**
     * Show detailed milestone tracking
     */
    public function milestones()
    {
        $student = Auth::user();
        $analytics = $this->analyticsService->getStudentProgressAnalytics($student);
        
        return view('student.analytics.milestones', [
            'milestones' => $analytics['milestone_progress'],
            'recommendations' => $analytics['recommendations']
        ]);
    }

    /**
     * Prepare chart data for frontend visualization
     */
    private function prepareChartData($analytics)
    {
        return [
            'project_progress' => [
                'labels' => array_column($analytics['project_progress'] ?? [], 'project'),
                'completion' => array_column($analytics['project_progress'] ?? [], 'completion_percentage')
            ],
            'completion_trends' => [
                'labels' => array_column($analytics['completion_trends'] ?? [], 'week'),
                'completion_scores' => array_column($analytics['completion_trends'] ?? [], 'completion_score'),
                'activity_counts' => array_column($analytics['completion_trends'] ?? [], 'activity_count')
            ]
        ];
    }
}
