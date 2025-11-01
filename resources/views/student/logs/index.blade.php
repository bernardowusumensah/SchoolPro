<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Progress Logs - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: linear-gradient(180deg, #2c2c2c 10%, #1a1a1a 100%);
            z-index: 100;
            transition: all 0.3s;
            overflow-y: auto;
        }
        .sidebar .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            position: relative;
            transition: all 0.15s ease-in-out;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .content-wrapper {
            margin-left: 270px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        .topbar {
            height: 80px;
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0 1.5rem;
        }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-500 { color: #858796 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        .feedback-card {
            background-color: #e8f5e8;
            border-left: 4px solid #1cc88a;
            border-radius: 0.35rem;
        }
        .log-content {
            background-color: #f8f9fa;
            border-left: 4px solid #4e73df;
            border-radius: 0.35rem;
            white-space: pre-wrap;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
        <!-- Sidebar Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard/student">
            <div class="sidebar-brand-text mx-3">SchoolPro</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="/dashboard/student">
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - My Project -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProject"
                aria-expanded="true" aria-controls="collapseProject">
                <span>My Project</span>
            </a>
            <div id="collapseProject" class="collapse" aria-labelledby="headingProject" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Project Management:</h6>
                    <a class="collapse-item" href="{{ route('student.projects.index') }}">My Projects</a>
                    <a class="collapse-item" href="{{ route('student.projects.proposal') }}">New Proposal</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Weekly Logs -->
        <li class="nav-item">
            <a class="nav-link active collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLogs"
                aria-expanded="true" aria-controls="collapseLogs">
                <span>Weekly Progress Logs</span>
            </a>
            <div id="collapseLogs" class="collapse show" aria-labelledby="headingLogs" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Progress Tracking:</h6>
                    <a class="collapse-item active" href="{{ route('student.logs.index') }}">Log History</a>
                    <a class="collapse-item" href="{{ route('student.logs.create') }}">Upload New Log</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Analytics -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('student.analytics.progress') }}">
                <span>Progress Analytics</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Profile -->
        <li class="nav-item">
            <a class="nav-link" href="/profile">
                <span>My Profile</span>
            </a>
        </li>

        <!-- Nav Item - Logout -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Topbar -->
        <nav class="navbar navbar-expand topbar mb-4 static-top shadow">
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'Student' }}</span>
                        <img class="img-profile rounded-circle"
                            src="{{ auth()->user()->profile_picture_url }}" 
                            alt="Profile Picture"
                            style="width: 40px; height: 40px; object-fit: cover;">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="/profile">
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();">
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">My Progress Logs</h1>
                <div>
                    @if($currentProject)
                        <a href="{{ route('student.logs.create') }}" class="btn btn-primary">
                            Submit New Log
                        </a>
                    @elseif($revisionProject)
                        <a href="{{ route('student.projects.edit', $revisionProject->id) }}" class="btn btn-danger">
                            Edit Proposal
                        </a>
                    @elseif($draftProject)
                        <a href="{{ route('student.projects.edit', $draftProject->id) }}" class="btn btn-info">
                            Complete Draft
                        </a>
                    @elseif($pendingProject)
                        <span class="badge bg-warning text-dark">
                            Proposal Under Review
                        </span>
                    @else
                        <a href="{{ route('student.projects.proposal') }}" class="btn btn-outline-primary">
                            Create Project Proposal
                        </a>
                    @endif
                </div>
            </div>

            <!-- Hidden logout form for dropdown -->
            <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <!-- Current Project Info -->
            @if($currentProject)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Current Project
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="font-weight-bold">{{ $currentProject->title }}</h5>
                                        <p class="text-muted">{{ Str::limit($currentProject->description, 150) }}</p>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success mr-2">{{ $currentProject->status }}</span>
                                            <small class="text-muted">Supervisor: {{ $currentProject->supervisor->name ?? 'Not assigned' }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <div class="text-muted">
                                            <small>Started: {{ $currentProject->created_at->format('M j, Y') }}</small><br>
                                            <small>Expected Completion: {{ $currentProject->expected_completion_date ? $currentProject->expected_completion_date->format('M j, Y') : 'Not set' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Active Project Available for Logging -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-warning">
                                    Logging Status
                                </h6>
                            </div>
                            <div class="card-body text-center py-5">
                                @if($revisionProject)
                                    <!-- Icon removed for cleaner empty state -->
                                    <h5 class="text-danger">Proposal Needs Revision</h5>
                                    <p class="text-muted mb-4">
                                        Your project proposal "<strong>{{ $revisionProject->title }}</strong>" needs revisions based on your supervisor's feedback. 
                                        Please address the feedback and resubmit before logging can begin.
                                    </p>
                                    <div class="alert alert-warning">
                                        <strong>Next steps:</strong><br>
                                        • Review your supervisor's feedback<br>
                                        • Edit and improve your proposal<br>
                                        • Resubmit for approval<br>
                                        • Once approved, you can start weekly logging
                                    </div>
                                    <a href="{{ route('student.projects.edit', $revisionProject->id) }}" class="btn btn-danger">
                                        Edit Proposal
                                    </a>
                                @elseif($draftProject)
                                    <!-- Icon removed for cleaner empty state -->
                                    <h5 class="text-info">Draft Proposal Ready</h5>
                                    <p class="text-muted mb-4">
                                        You have a draft proposal "<strong>{{ $draftProject->title }}</strong>" ready to submit. 
                                        Complete and submit your proposal to start the review process.
                                    </p>
                                    <div class="alert alert-info">
                                        <strong>Next steps:</strong><br>
                                        • Review and finalize your draft proposal<br>
                                        • Submit it for supervisor review<br>
                                        • Once approved, you can start weekly logging
                                    </div>
                                    <a href="{{ route('student.projects.edit', $draftProject->id) }}" class="btn btn-info">
                                        Complete & Submit Proposal
                                    </a>
                                @elseif($pendingProject)
                                    <!-- Icon removed for cleaner empty state -->
                                    <h5 class="text-warning">Proposal Under Review</h5>
                                    <p class="text-muted mb-4">
                                        Your project proposal "<strong>{{ $pendingProject->title }}</strong>" is currently under review by your supervisor. 
                                        Weekly logging will be available once they approve it.
                                    </p>
                                    <div class="alert alert-info">
                                        <strong>What happens next:</strong><br>
                                        • Your supervisor will review your proposal<br>
                                        • If approved, you can start logging your weekly progress<br>
                                        • If revisions are needed, you'll be asked to edit your proposal
                                    </div>
                                @else
                                    <!-- Icon removed for cleaner empty state -->
                                    <h5 class="text-info">Ready to Get Started</h5>
                                    <p class="text-muted mb-4">
                                        Create your first project proposal to start tracking your weekly progress.
                                    </p>
                                    <a href="{{ route('student.projects.proposal') }}" class="btn btn-primary">
                                        Create Project Proposal
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Logs Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Progress Log History
                            </h6>
                            <span class="badge bg-info text-dark">{{ $logs->total() }} Total Logs</span>
                        </div>
                        <div class="card-body">
                            @if($logs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover" id="logsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Week</th>
                                                <th>Log Content</th>
                                                <th>Submitted</th>
                                                <th>Status</th>
                                                <th>Supervisor Feedback</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($logs as $index => $log)
                                            <tr>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="fw-bold">Week {{ $logs->count() - $index }}</div>
                                                        <small class="text-muted">{{ $log->created_at->format('M j') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $log->content }}">
                                                        {{ Str::limit($log->content, 100) }}
                                                    </div>
                                                    @if($log->file_path)
                                                        <small class="text-info">
                                                            Has attachment
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>{{ $log->created_at->format('M j, Y') }}</div>
                                                        <small class="text-muted">{{ $log->created_at->format('g:i A') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($log->supervisor_feedback)
                                                        <span class="badge bg-success">
                                                            Reviewed
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            Pending Review
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($log->supervisor_feedback)
                                                        <div class="feedback-card p-2">
                                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $log->supervisor_feedback }}">
                                                                {{ Str::limit($log->supervisor_feedback, 80) }}
                                                            </div>
                                                            <small class="text-muted">
                                                                {{ $log->feedback_date ? $log->feedback_date->format('M j, Y') : '' }}
                                                            </small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No feedback yet</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('student.logs.show', $log->id) }}" 
                                                           class="btn btn-outline-primary btn-sm" title="View Details">
                                                            View
                                                        </a>
                                                        @if($log->created_at->diffInHours(now()) <= 48 && !$log->supervisor_feedback)
                                                            <a href="{{ route('student.logs.edit', $log->id) }}" 
                                                               class="btn btn-outline-warning btn-sm" title="Edit Log">
                                                                Edit
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $logs->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <h5 class="text-gray-600">No Progress Logs Yet</h5>
                                    @if($currentProject)
                                        <p class="text-muted mb-4">You haven't submitted any weekly progress logs yet. Start tracking your project progress!</p>
                                        <a href="{{ route('student.logs.create') }}" class="btn btn-primary">
                                            Submit Your First Log
                                        </a>
                                    @elseif($revisionProject)
                                        <p class="text-muted mb-4">Your proposal needs revisions. Address the feedback and resubmit before logging can begin.</p>
                                        <a href="{{ route('student.projects.edit', $revisionProject->id) }}" class="btn btn-danger">
                                            Edit Proposal
                                        </a>
                                    @elseif($draftProject)
                                        <p class="text-muted mb-4">Complete and submit your draft proposal to start the approval process.</p>
                                        <a href="{{ route('student.projects.edit', $draftProject->id) }}" class="btn btn-info">
                                            Complete Draft Proposal
                                        </a>
                                    @elseif($pendingProject)
                                        <p class="text-muted mb-4">Your project proposal is under review. Logging will be available once your supervisor approves it.</p>
                                        <span class="badge bg-warning text-dark">Proposal Under Review</span>
                                    @else
                                        <p class="text-muted mb-4">Create your first project proposal to start tracking your progress.</p>
                                        <a href="{{ route('student.projects.proposal') }}" class="btn btn-outline-primary">
                                            Create Project Proposal
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Summary -->
            @if($logs->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Logs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $logs->total() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Reviewed Logs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $logs->where('supervisor_feedback', '!=', null)->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Review</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $logs->where('supervisor_feedback', null)->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable
            $(document).ready(function() {
                $('#logsTable').DataTable({
                    "order": [[ 2, "desc" ]], // Sort by Submitted column (index 2) descending
                    "pageLength": 10,
                    "responsive": true,
                    "language": {
                        "search": "Search logs:",
                        "lengthMenu": "Show _MENU_ logs per page",
                        "info": "Showing _START_ to _END_ of _TOTAL_ logs",
                        "emptyTable": "No logs found"
                    },
                    "columnDefs": [
                        { "orderable": false, "targets": 5 } // Disable sorting on Actions column
                    ]
                });
                
                // Sidebar toggle functionality
                $("#sidebarToggleTop").on('click', function() {
                    $(".sidebar").toggleClass("show");
                });
            });
        });
    </script>
</body>
</html>
