<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Analytics - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 10%, #34495e 100%);
            z-index: 1000;
            padding-top: 1rem;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }
        .border-left-primary { border-left: 4px solid #4e73df !important; }
        .border-left-success { border-left: 4px solid #1cc88a !important; }
        .border-left-warning { border-left: 4px solid #f6c23e !important; }
        .border-left-danger { border-left: 4px solid #e74a3b !important; }
        .border-left-info { border-left: 4px solid #36b9cc !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-500 { color: #858796 !important; }
        .chart-container { position: relative; height: 300px; }
    </style>
</head>
<body>
    <!-- Left Sidebar -->
    <nav class="sidebar">
        <div class="text-center py-3">
            <h5 class="text-white mb-0">SchoolPro</h5>
            <small class="text-white-50">Teacher Portal</small>
        </div>
        <hr class="sidebar-divider">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.teacher') }}">
                    <i class="fas fa-tachometer-alt fa-fw"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.proposals.index') }}">
                    <i class="fas fa-file-alt fa-fw"></i>
                    Project Proposals
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.logs.index') }}">
                    <i class="fas fa-calendar-week fa-fw"></i>
                    Student Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.logs.unreviewed') }}">
                    <i class="fas fa-exclamation-triangle fa-fw"></i>
                    Unreviewed Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('teacher.logs.analytics') }}">
                    <i class="fas fa-chart-bar fa-fw"></i>
                    Log Analytics
                </a>
            </li>
        </ul>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Account</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start">
                        <i class="fas fa-sign-out-alt fa-fw"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar me-2"></i>Log Analytics Dashboard
            </h1>
            <div>
                <a href="{{ route('teacher.logs.export') }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Export Logs
                </a>
                <a href="{{ route('teacher.logs.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Logs
                </a>
            </div>
        </div>

        <!-- Overview Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Logs</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalLogs }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Reviewed Logs</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $reviewedLogs }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pending Review</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $unreviewedLogs }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-danger text-uppercase mb-1">Follow-up Required</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $followupLogs }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-flag fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Rating Distribution Chart -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-star me-2"></i>Rating Distribution
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="ratingChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline Chart -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-chart-line me-2"></i>Recent Activity (Last 30 Days)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Performance Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-users me-2"></i>Student Performance Overview
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Total Logs</th>
                                        <th>Average Rating</th>
                                        <th>Follow-up Needed</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($studentStats as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($student['name'], 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $student['name'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $student['total_logs'] }}</span>
                                        </td>
                                        <td>
                                            @if($student['avg_rating'] > 0)
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">{{ $student['avg_rating'] }}/4.0</span>
                                                    <div class="progress flex-grow-1" style="height: 8px;">
                                                        <div class="progress-bar 
                                                            @if($student['avg_rating'] >= 3.5) bg-success
                                                            @elseif($student['avg_rating'] >= 2.5) bg-warning
                                                            @else bg-danger @endif
                                                        " style="width: {{ ($student['avg_rating'] / 4) * 100 }}%"></div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">No ratings yet</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student['follow_up_needed'] > 0)
                                                <span class="badge bg-warning">{{ $student['follow_up_needed'] }}</span>
                                            @else
                                                <span class="badge bg-success">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student['avg_rating'] >= 3.5)
                                                <span class="badge bg-success">Excellent</span>
                                            @elseif($student['avg_rating'] >= 2.5)
                                                <span class="badge bg-warning">Good</span>
                                            @elseif($student['avg_rating'] > 0)
                                                <span class="badge bg-danger">Needs Attention</span>
                                            @else
                                                <span class="badge bg-secondary">Not Evaluated</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>No student data available yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Rating Distribution Pie Chart
        const ratingCtx = document.getElementById('ratingChart').getContext('2d');
        const ratingData = @json($ratingStats);
        
        new Chart(ratingCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(ratingData),
                datasets: [{
                    data: Object.values(ratingData),
                    backgroundColor: [
                        '#28a745', // Excellent - Green
                        '#007bff', // Good - Blue  
                        '#ffc107', // Satisfactory - Yellow
                        '#dc3545'  // Needs Improvement - Red
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
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

        // Activity Timeline Line Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityData = @json($recentActivity);
        
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: activityData.map(item => item.date),
                datasets: [{
                    label: 'Logs Submitted',
                    data: activityData.map(item => item.count),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>