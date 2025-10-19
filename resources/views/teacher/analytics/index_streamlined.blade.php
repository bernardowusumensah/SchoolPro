@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Teaching Analytics</h1>
                <div class="d-flex">
                    <form method="GET" action="{{ route('teacher.analytics.index') }}" class="d-flex gap-2">
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ $startDate ?? '' }}" placeholder="Start Date">
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ $endDate ?? '' }}" placeholder="End Date">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
            
            @if(isset($error))
                <div class="alert alert-warning">{{ $error }}</div>
            @endif
        </div>
    </div>

    <!-- Supervision Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Students Supervised
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['supervision_overview']['total_students'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <!-- Removed users icon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Projects
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['supervision_overview']['active_projects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <!-- Removed project diagram icon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['supervision_overview']['pending_reviews'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <!-- Removed clipboard check icon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Completion Rate
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $analytics['supervision_overview']['completion_rate'] ?? 0 }}%
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             style="width: {{ $analytics['supervision_overview']['completion_rate'] ?? 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <!-- Removed check circle icon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Progress Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Progress Overview</h6>
                </div>
                <div class="card-body">
                    @if(count($analytics['student_progress'] ?? []) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Project</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Total Logs</th>
                                        <th>Action Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['student_progress'] as $student)
                                    <tr>
                                        <td>{{ $student['student_name'] }}</td>
                                        <td>{{ Str::limit($student['project_title'], 40) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">{{ $student['completion_percentage'] }}%</span>
                                                <div class="progress flex-fill">
                                                    <div class="progress-bar {{ $student['on_track'] ? 'bg-success' : 'bg-warning' }}" 
                                                         style="width: {{ $student['completion_percentage'] }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $student['on_track'] ? 'badge-success' : 'badge-warning' }}">
                                                {{ $student['on_track'] ? 'On Track' : 'At Risk' }}
                                            </span>
                                        </td>
                                        <td>{{ $student['total_logs'] }}</td>
                                        <td>
                                            @if($student['needs_attention'])
                                                <span class="text-danger">Needs Feedback</span>
                                            @else
                                                <span class="text-success">Up to Date</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <!-- Removed info circle icon -->
                            <p class="text-muted">No student progress data available for the selected period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Metrics -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Weekly Feedback Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="feedbackTrendsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Feedback Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-muted">Total Logs Reviewed</div>
                        <div class="h5 font-weight-bold">{{ $analytics['feedback_metrics']['total_logs_reviewed'] ?? 0 }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Feedback Provided</div>
                        <div class="h5 font-weight-bold">{{ $analytics['feedback_metrics']['feedback_provided'] ?? 0 }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Feedback Rate</div>
                        <div class="h5 font-weight-bold text-success">{{ $analytics['feedback_metrics']['feedback_rate'] ?? 0 }}%</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Student Acknowledgment</div>
                        <div class="h5 font-weight-bold text-info">{{ $analytics['feedback_metrics']['acknowledgment_rate'] ?? 0 }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Feedback Trends Chart
    const feedbackCtx = document.getElementById('feedbackTrendsChart').getContext('2d');
    const chartData = @json($chartData['weekly_feedback'] ?? ['labels' => [], 'datasets' => []]);
    
    new Chart(feedbackCtx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endsection