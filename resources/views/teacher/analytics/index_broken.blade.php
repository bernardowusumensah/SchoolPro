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
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
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
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
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
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <i class="fas fa-info-circle fa-3x text-gray-300 mb-3"></i>
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
        .good { border-left-color: #1cc88a !important; }
        .warning { border-left-color: #f39c12 !important; }
        .info { border-left-color: #3498db !important; }
    </style>
</head>
<body>
    <!-- Left Sidebar -->
    <nav class="sidebar">
        <div class="text-center py-3">
            <h5 class="text-white mb-0">SchoolPro</h5>
            <small class="text-white-50">Teacher Portal</small>
        </div>
        <hr class="sidebar-divider my-2" style="border-color: rgba(255,255,255,0.15);">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.teacher') }}">
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.proposals.index') }}">
                    Proposals
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.logs.index') }}">
                    Student Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.logs.unreviewed') }}">
                    Unreviewed Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('teacher.analytics.index') }}">
                    Analytics Dashboard
                </a>
            </li>
        </ul>
        <hr class="sidebar-divider my-2" style="border-color: rgba(255,255,255,0.15);">
        <div class="px-3">
            <small class="text-white-50">ACCOUNT</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#profile">
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent text-start w-100" style="color: rgba(255, 255, 255, 0.8);">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Teacher Analytics Dashboard</h1>
                <p class="text-muted">Monitor student progress and optimize your supervision effectiveness</p>
            </div>
            <div>
                <a href="{{ route('teacher.analytics.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="btn btn-primary">
                    Export Report
                </a>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">Update Analytics</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Overview Metrics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card analytics-card shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Students Supervised
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analytics['supervision_overview']['total_students'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card analytics-card shadow h-100 py-2 {{ $analytics['supervision_overview']['review_rate'] >= 80 ? 'good' : ($analytics['supervision_overview']['review_rate'] >= 50 ? 'warning' : 'at-risk') }}">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Review Rate
                                </div>
                                <div class="metric-value">{{ $analytics['supervision_overview']['review_rate'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card analytics-card shadow h-100 py-2 {{ $analytics['supervision_overview']['avg_response_time'] <= 24 ? 'good' : ($analytics['supervision_overview']['avg_response_time'] <= 48 ? 'warning' : 'at-risk') }}">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Avg Response Time
                                </div>
                                <div class="metric-value">{{ round($analytics['supervision_overview']['avg_response_time'], 1) }}h</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card analytics-card shadow h-100 py-2 {{ count($analytics['at_risk_students']) == 0 ? 'good' : 'at-risk' }}">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    At-Risk Students
                                </div>
                                <div class="metric-value">{{ count($analytics['at_risk_students']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Supervision Workload</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="workloadChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Student Performance Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- At-Risk Students Alert -->
        @if(count($analytics['at_risk_students']) > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">⚠️ Students Requiring Attention</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($analytics['at_risk_students'] as $student)
                    <div class="col-md-6 mb-3">
                        <div class="card border-left-danger">
                            <div class="card-body">
                                <h6 class="card-title">{{ $student['name'] }}</h6>
                                <p class="card-text">{{ $student['risk_reason'] }}</p>
                                <small class="text-muted">Last activity: {{ $student['last_activity'] }}</small>
                                <div class="mt-2">
                                    <a href="{{ route('teacher.analytics.students', ['student_id' => $student['id']]) }}" 
                                       class="btn btn-sm btn-outline-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('teacher.logs.unreviewed') }}" class="btn btn-outline-primary">
                                Review Pending Logs ({{ $analytics['supervision_overview']['total_logs_received'] - $analytics['supervision_overview']['logs_reviewed'] }})
                            </a>
                            <a href="{{ route('teacher.analytics.students') }}" class="btn btn-outline-info">
                                Student Performance Analysis
                            </a>
                            <a href="{{ route('teacher.analytics.workload') }}" class="btn btn-outline-success">
                                Workload Optimization
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Teaching Insights</h6>
                    </div>
                    <div class="card-body">
                        @if(isset($analytics['teaching_insights']) && count($analytics['teaching_insights']) > 0)
                            @foreach($analytics['teaching_insights'] as $insight)
                            <div class="mb-3">
                                <strong>{{ $insight['title'] }}</strong>
                                <p class="mb-1">{{ $insight['description'] }}</p>
                                <small class="text-muted">{{ $insight['recommendation'] }}</small>
                            </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <strong>Great job!</strong> Your supervision metrics are excellent. Keep up the consistent feedback and engagement with your students.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Workload Chart
        const workloadCtx = document.getElementById('workloadChart').getContext('2d');
        new Chart(workloadCtx, {
            type: 'doughnut',
            data: {
                labels: ['Reviewed Logs', 'Pending Reviews'],
                datasets: [{
                    data: [
                        {{ $analytics['supervision_overview']['logs_reviewed'] }},
                        {{ $analytics['supervision_overview']['total_logs_received'] - $analytics['supervision_overview']['logs_reviewed'] }}
                    ],
                    backgroundColor: ['#1cc88a', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Performance Distribution Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        new Chart(performanceCtx, {
            type: 'pie',
            data: {
                labels: ['On Track', 'At Risk'],
                datasets: [{
                    data: [
                        {{ $analytics['supervision_overview']['students_supervised'] - count($analytics['at_risk_students']) }},
                        {{ count($analytics['at_risk_students']) }}
                    ],
                    backgroundColor: ['#1cc88a', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>