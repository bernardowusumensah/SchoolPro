<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherAnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show streamlined teacher analytics dashboard
     */
    public function index(Request $request)
    {
        try {
            $teacher = Auth::user();
            
            // Get date range from request or default to last month
            $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
            
            // Get streamlined teacher analytics
            $analytics = $this->analyticsService->getTeacherAnalytics($teacher, $startDate, $endDate);

            // Prepare chart data for feedback trends
            $chartData = $this->prepareChartData($analytics);

            return view('teacher.analytics.index', compact(
                'analytics', 
                'chartData', 
                'startDate', 
                'endDate'
            ));
        } catch (\Exception $e) {
            // Return safe defaults on error
            $analytics = [
                'supervision_overview' => [
                    'total_students' => 0,
                    'active_projects' => 0,
                    'pending_reviews' => 0,
                    'completion_rate' => 0
                ],
                'student_progress' => [],
                'feedback_metrics' => ['weekly_trends' => []]
            ];
            
            $chartData = ['weekly_feedback' => []];
            
            return view('teacher.analytics.index', compact('analytics', 'chartData'))
                ->with('error', 'Unable to load analytics data');
        }
    }

    /**
     * Prepare chart data for feedback trends visualization
     */
    private function prepareChartData($analytics)
    {
        try {
            // Extract weekly feedback trends for chart
            $weeklyTrends = $analytics['feedback_metrics']['weekly_trends'] ?? [];
            
            $chartData = [
                'weekly_feedback' => [
                    'labels' => [],
                    'datasets' => [
                        [
                            'label' => 'Logs Received',
                            'data' => [],
                            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                            'borderColor' => 'rgba(54, 162, 235, 1)',
                            'borderWidth' => 2
                        ],
                        [
                            'label' => 'Feedback Given',
                            'data' => [],
                            'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                            'borderColor' => 'rgba(255, 99, 132, 1)',
                            'borderWidth' => 2
                        ]
                    ]
                ]
            ];

            foreach ($weeklyTrends as $week) {
                $chartData['weekly_feedback']['labels'][] = $week['week'];
                $chartData['weekly_feedback']['datasets'][0]['data'][] = $week['logs_received'];
                $chartData['weekly_feedback']['datasets'][1]['data'][] = $week['feedback_given'];
            }

            return $chartData;
        } catch (\Exception $e) {
            return ['weekly_feedback' => ['labels' => [], 'datasets' => []]];
        }
    }
}
