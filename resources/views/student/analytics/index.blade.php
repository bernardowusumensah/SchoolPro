@extends('layouts.student')

@section('title', 'Progress Analytics')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Progress Analytics</h1>
        <p class="text-muted">Track your learning journey and identify improvement opportunities</p>
    </div>

    <!-- Date Range Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date', $startDate) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date', $endDate) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Update Analytics</button>
                    <a href="{{ route('student.analytics.progress') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Overview Analytics Cards -->
    <div class="row">
        <!-- Active Projects -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card analytics-card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Projects
                            </div>
                            <div class="metric-value">{{ $analytics['overview']['active_projects'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card analytics-card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Projects
                            </div>
                            <div class="metric-value">{{ $analytics['overview']['completed_projects'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Logs -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card analytics-card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Progress Logs
                            </div>
                            <div class="metric-value">{{ $analytics['overview']['total_progress_logs'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card analytics-card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Completion Rate
                            </div>
                            <div class="metric-value">{{ $analytics['overview']['completion_rate'] }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Progress -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Project Progress</h6>
            <small class="text-muted">Track your project completion status</small>
        </div>
        <div class="card-body">
            @if(count($analytics['project_progress']) > 0)
                @foreach($analytics['project_progress'] as $project)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $project['project'] }}</h5>
                        <span class="badge {{ $project['on_track'] ? 'bg-success' : 'bg-warning' }}">
                            {{ $project['on_track'] ? 'On Track' : 'Behind Schedule' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Completion Progress</label>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-primary" 
                                         style="width: {{ $project['completion_percentage'] }}%">
                                        {{ $project['completion_percentage'] }}%
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Expected Completion</label>
                                <p class="mb-2">{{ $project['expected_completion'] ? \Carbon\Carbon::parse($project['expected_completion'])->format('M d, Y') : 'Not set' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $project['status'])) }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Next Action</label>
                                <p class="mb-0">{{ $project['next_action'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-muted">No active projects to display.</p>
            @endif
        </div>
    </div>

    <!-- Completion Trends Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Completion Trends</h6>
        </div>
        <div class="card-body">
            <canvas id="completionChart" width="400" height="200"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Completion Trends Chart
const completionCtx = document.getElementById('completionChart').getContext('2d');
const completionChart = new Chart(completionCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['completion_trends']['labels'] ?? []) !!},
        datasets: [{
            label: 'Completion Score',
            data: {!! json_encode($chartData['completion_trends']['completion_scores'] ?? []) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Activity Count',
            data: {!! json_encode($chartData['completion_trends']['activity_counts'] ?? []) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                title: {
                    display: true,
                    text: 'Completion Score'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Activity Count'
                }
            }
        }
    }
});
</script>

<style>
.analytics-card {
    border-left: 4px solid #4e73df;
}
.metric-value {
    font-size: 2rem;
    font-weight: 700;
    color: #5a5c69;
}
</style>
@endsection