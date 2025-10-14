<?php

namespace App\Services;

use App\Models\Log;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Generate streamlined student progress analytics
     */
    public function getStudentProgressAnalytics(User $student, $startDate = null, $endDate = null)
    {
        try {
            $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subMonths(3);
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

            $analytics = [
                'overview' => $this->getEssentialOverview($student, $startDate, $endDate),
                'project_progress' => $this->getProjectProgress($student),
                'completion_trends' => $this->getCompletionTrends($student, $startDate, $endDate)
            ];

            return $analytics;
        } catch (\Exception $e) {
            // Return safe defaults if there's an error
            return [
                'overview' => [
                    'active_projects' => 0,
                    'completed_projects' => 0,
                    'total_progress_logs' => 0,
                    'completion_rate' => 0
                ],
                'project_progress' => [],
                'completion_trends' => []
            ];
        }
    }

    /**
     * Essential overview metrics focused on project progress
     */
    private function getEssentialOverview(User $student, $startDate, $endDate)
    {
        $projects = Project::where('student_id', $student->id)->get();
        $activeLogs = Log::where('student_id', $student->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->get();

        return [
            'active_projects' => $projects->whereIn('status', ['approved', 'in_progress'])->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'total_progress_logs' => $activeLogs->count(),
            'completion_rate' => $this->calculateOverallCompletionRate($projects)
        ];
    }

    /**
     * Simplified project progress tracking
     */
    private function getProjectProgress(User $student)
    {
        $projects = Project::where('student_id', $student->id)
            ->whereIn('status', ['approved', 'in_progress'])
            ->get();

        $progress = [];
        foreach ($projects as $project) {
            $completionData = $this->calculateSimpleProjectProgress($project);
            
            $progress[] = [
                'project' => $project->title,
                'status' => $project->status,
                'completion_percentage' => $completionData['completion_percentage'],
                'expected_completion' => $project->expected_completion_date,
                'on_track' => $completionData['on_track'],
                'next_action' => $completionData['next_action']
            ];
        }

        return $progress;
    }

    /**
     * Simple project progress calculation
     */
    private function calculateSimpleProjectProgress(Project $project)
    {
        // Get project logs
        $logs = Log::where('project_id', $project->id)
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->get();

        // Simple completion calculation based on log content analysis
        $completionPercentage = $this->analyzeCompletionFromLogs($logs);
        
        // Timeline check
        $timelineProgress = $this->calculateTimelineProgress($project);
        $onTrack = $completionPercentage >= ($timelineProgress * 0.8);

        return [
            'completion_percentage' => $completionPercentage,
            'on_track' => $onTrack,
            'next_action' => $this->getNextAction($completionPercentage)
        ];
    }

    /**
     * Calculate overall completion rate for all projects
     */
    private function calculateOverallCompletionRate($projects)
    {
        if ($projects->count() === 0) return 0;
        
        $completedCount = $projects->where('status', 'completed')->count();
        return round(($completedCount / $projects->count()) * 100);
    }

    /**
     * Get completion trends over time
     */
    private function getCompletionTrends(User $student, $startDate, $endDate)
    {
        $logs = Log::where('student_id', $student->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('content')
            ->get();

        $weeklyProgress = [];
        $current = $startDate->copy()->startOfWeek();
        
        while ($current <= $endDate) {
            $weekEnd = $current->copy()->endOfWeek();
            $weekLogs = $logs->whereBetween('created_at', [$current, $weekEnd]);
            
            $completionScore = 0;
            foreach ($weekLogs as $log) {
                if (strpos(strtolower($log->content), 'completed') !== false ||
                    strpos(strtolower($log->content), 'finished') !== false) {
                    $completionScore += 20;
                }
            }
            
            $weeklyProgress[] = [
                'week' => $current->format('M j'),
                'completion_score' => min(100, $completionScore),
                'activity_count' => $weekLogs->count()
            ];
            
            $current->addWeek();
        }

        return $weeklyProgress;
    }

    /**
     * Analyze completion percentage from log content
     */
    private function analyzeCompletionFromLogs($logs)
    {
        if ($logs->count() === 0) return 0;

        $completionKeywords = ['completed', 'finished', 'done', 'implemented', 'delivered'];
        $progressKeywords = ['working', 'developing', 'progress', 'started'];
        
        $completionScore = 0;
        foreach ($logs as $log) {
            $content = strtolower($log->content);
            
            foreach ($completionKeywords as $keyword) {
                if (strpos($content, $keyword) !== false) {
                    $completionScore += 15;
                    break;
                }
            }
            
            foreach ($progressKeywords as $keyword) {
                if (strpos($content, $keyword) !== false) {
                    $completionScore += 5;
                    break;
                }
            }
        }
        
        return min(100, round($completionScore / $logs->count()));
    }

    /**
     * Calculate timeline-based progress
     */
    private function calculateTimelineProgress(Project $project)
    {
        if (!$project->expected_start_date || !$project->expected_completion_date) {
            return 50; // Default if dates not set
        }
        
        $totalDays = $project->expected_start_date->diffInDays($project->expected_completion_date);
        $elapsedDays = $project->expected_start_date->diffInDays(Carbon::now());
        
        if ($totalDays <= 0) return 100;
        
        return min(100, max(0, round(($elapsedDays / $totalDays) * 100)));
    }

    /**
     * Get next action based on completion percentage
     */
    private function getNextAction($completionPercentage)
    {
        if ($completionPercentage < 25) {
            return 'Start core development';
        } elseif ($completionPercentage < 50) {
            return 'Continue implementation';
        } elseif ($completionPercentage < 75) {
            return 'Complete remaining features';
        } else {
            return 'Finalize and test';
        }
    }

    /**
     * Generate streamlined teacher analytics for supervision oversight
     */
    public function getTeacherAnalytics(User $teacher, $startDate = null, $endDate = null)
    {
        try {
            $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subMonths(1);
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

            $analytics = [
                'supervision_overview' => $this->getSupervisionOverview($teacher, $startDate, $endDate),
                'student_progress' => $this->getStudentProgressSummary($teacher),
                'feedback_metrics' => $this->getFeedbackMetrics($teacher, $startDate, $endDate)
            ];

            return $analytics;
        } catch (\Exception $e) {
            // Return safe defaults if there's an error
            return [
                'supervision_overview' => [
                    'total_students' => 0,
                    'active_projects' => 0,
                    'pending_reviews' => 0,
                    'completion_rate' => 0
                ],
                'student_progress' => [],
                'feedback_metrics' => []
            ];
        }
    }

    /**
     * Get essential supervision overview for teachers
     */
    private function getSupervisionOverview(User $teacher, $startDate, $endDate)
    {
        // Get all projects supervised by this teacher
        $supervisedProjects = Project::where('supervisor_id', $teacher->id)->get();
        $activeProjects = $supervisedProjects->whereIn('status', ['approved', 'in_progress']);
        
        // Get students under supervision
        $studentIds = $supervisedProjects->pluck('student_id')->unique();
        
        // Get logs needing review (logs with no supervisor feedback)
        $pendingReviews = Log::whereIn('project_id', $supervisedProjects->pluck('id'))
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->whereNull('supervisor_feedback')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'total_students' => $studentIds->count(),
            'active_projects' => $activeProjects->count(),
            'pending_reviews' => $pendingReviews,
            'completion_rate' => $supervisedProjects->count() > 0 ? 
                round(($supervisedProjects->where('status', 'completed')->count() / $supervisedProjects->count()) * 100) : 0
        ];
    }

    /**
     * Get student progress summary for teacher oversight
     */
    private function getStudentProgressSummary(User $teacher)
    {
        $supervisedProjects = Project::where('supervisor_id', $teacher->id)
            ->whereIn('status', ['approved', 'in_progress'])
            ->with('student')
            ->get();

        $studentProgress = [];
        foreach ($supervisedProjects as $project) {
            if ($project->student) {
                $logs = Log::where('project_id', $project->id)
                    ->whereNotNull('content')
                    ->where('content', '!=', '')
                    ->get();

                $completionScore = $this->analyzeCompletionFromLogs($logs);
                $timelineProgress = $this->calculateTimelineProgress($project);

                $studentProgress[] = [
                    'student_name' => $project->student->name,
                    'project_title' => $project->title,
                    'completion_percentage' => $completionScore,
                    'on_track' => $completionScore >= ($timelineProgress * 0.8),
                    'total_logs' => $logs->count(),
                    'needs_attention' => $logs->whereNull('supervisor_feedback')->count() > 3
                ];
            }
        }

        return $studentProgress;
    }

    /**
     * Get feedback metrics for teacher performance
     */
    private function getFeedbackMetrics(User $teacher, $startDate, $endDate)
    {
        $supervisedProjects = Project::where('supervisor_id', $teacher->id)->get();
        
        $allLogs = Log::whereIn('project_id', $supervisedProjects->pluck('id'))
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $logsWithFeedback = $allLogs->whereNotNull('supervisor_feedback');
        $acknowledgedLogs = $allLogs->where('student_acknowledged', 1);

        $weeklyData = [];
        $current = $startDate->copy()->startOfWeek();
        
        while ($current <= $endDate) {
            $weekEnd = $current->copy()->endOfWeek();
            $weekLogs = $allLogs->whereBetween('created_at', [$current, $weekEnd]);
            $weekFeedback = $weekLogs->whereNotNull('supervisor_feedback');
            
            $weeklyData[] = [
                'week' => $current->format('M j'),
                'logs_received' => $weekLogs->count(),
                'feedback_given' => $weekFeedback->count(),
                'feedback_rate' => $weekLogs->count() > 0 ? 
                    round(($weekFeedback->count() / $weekLogs->count()) * 100) : 0
            ];
            
            $current->addWeek();
        }

        return [
            'total_logs_reviewed' => $allLogs->count(),
            'feedback_provided' => $logsWithFeedback->count(),
            'feedback_rate' => $allLogs->count() > 0 ? 
                round(($logsWithFeedback->count() / $allLogs->count()) * 100) : 0,
            'acknowledgment_rate' => $logsWithFeedback->count() > 0 ? 
                round(($acknowledgedLogs->count() / $logsWithFeedback->count()) * 100) : 0,
            'weekly_trends' => $weeklyData
        ];
    }
}