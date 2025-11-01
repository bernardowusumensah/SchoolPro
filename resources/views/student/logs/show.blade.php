<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Details - SchoolPro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
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
        .log-content {
            background-color: #f8f9fa;
            border-left: 4px solid #4e73df;
            padding: 1.5rem;
            border-radius: 0.35rem;
            white-space: pre-wrap;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        .feedback-content {
            background-color: #e8f5e8;
            border-left: 4px solid #1cc88a;
            padding: 1.5rem;
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
                    <a class="collapse-item" href="{{ route('student.logs.index') }}">Log History</a>
                    <a class="collapse-item" href="{{ route('student.logs.create') }}">Upload New Log</a>
                </div>
            </div>
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
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
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
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.student') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.logs.index') }}">Progress Logs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Log Details</li>
                </ol>
            </nav>

            <!-- Log Details -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Log Content -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Progress Log Entry
                            </h6>
                            <div>
                                @if($log->supervisor_feedback)
                                    <span class="badge bg-success">
                                        Reviewed
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Pending Review
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Project:</strong> {{ $log->project->title }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Supervisor:</strong> {{ $log->project->supervisor->name ?? 'Not assigned' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Submitted:</strong> {{ $log->created_at->format('M j, Y g:i A') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Time Ago:</strong> {{ $log->created_at->diffForHumans() }}
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6 class="font-weight-bold mb-3">Log Content:</h6>
                            <div class="log-content">
                                {{ $log->content }}
                            </div>
                            
                            @if($log->file_path)
                                <div class="mt-3">
                                    <h6 class="font-weight-bold mb-2">Attachment:</h6>
                                    <a href="{{ Storage::url($log->file_path) }}" class="btn btn-outline-primary" target="_blank">
                                        Download Attachment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Supervisor Feedback -->
                    @if($log->supervisor_feedback)
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-success">
                                    Supervisor Feedback
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="feedback-content">
                                    {{ $log->supervisor_feedback }}
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Feedback provided on {{ $log->feedback_date->format('M j, Y g:i A') }}
                                        ({{ $log->feedback_date->diffForHumans() }})
                                    </small>
                                </div>
                                
                                <!-- Student Acknowledgment -->
                                <div class="mt-4 pt-3 border-top">
                                    @if($log->student_acknowledged)
                                        <div class="alert alert-success" role="alert">
                                            <strong>Acknowledged on {{ $log->acknowledged_at->format('M j, Y g:i A') }}</strong>
                                            @if($log->student_question)
                                                <div class="mt-2">
                                                    <strong>Your question:</strong> {{ $log->student_question }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="acknowledgment-section">
                                            <h6 class="text-primary mb-3">
                                                Please acknowledge this feedback
                                            </h6>
                                            <div class="d-flex gap-2 mb-3">
                                                <button type="button" class="btn btn-success btn-sm" onclick="acknowledgeFeedback('understood')">
                                                    Got it, thanks
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm" onclick="showQuestionForm()">
                                                    I have a question
                                                </button>
                                            </div>
                                            
                                            <!-- Question form (hidden by default) -->
                                            <div id="questionForm" class="d-none">
                                                <div class="mb-3">
                                                    <label for="studentQuestion" class="form-label">Your question:</label>
                                                    <textarea class="form-control" id="studentQuestion" rows="3" 
                                                              placeholder="What would you like to ask about this feedback?"></textarea>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="acknowledgeFeedback('question')">
                                                        Submit Question
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-sm" onclick="hideQuestionForm()">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-warning">
                                    Awaiting Supervisor Feedback
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center py-4">
                                    <h5 class="text-warning">Waiting for Feedback</h5>
                                    <p class="text-muted">Your supervisor will review this log entry and provide feedback soon.</p>
                                    <p class="text-muted">
                                        <small>Submitted {{ $log->created_at->diffForHumans() }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Project Info -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Project Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <h6 class="font-weight-bold">{{ $log->project->title }}</h6>
                            <p class="text-muted mb-3">{{ Str::limit($log->project->description, 100) }}</p>
                            
                            <div class="mb-2">
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $log->project->status == 'Approved' ? 'success' : 'warning' }}">
                                    {{ $log->project->status }}
                                </span>
                            </div>
                            
                            <div class="mb-2">
                                <strong>Category:</strong> {{ $log->project->category ?? 'N/A' }}
                            </div>
                            
                            <div class="mb-2">
                                <strong>Supervisor:</strong> {{ $log->project->supervisor->name ?? 'Not assigned' }}
                            </div>
                            
                            <a href="{{ route('student.projects.show', $log->project->id) }}" class="btn btn-outline-primary btn-sm">
                                View Project
                            </a>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.logs.index') }}" class="btn btn-outline-primary">
                                    Back to Log History
                                </a>
                                
                                @if($log->created_at->diffInHours(now()) <= 48 && !$log->supervisor_feedback)
                                    <a href="{{ route('student.logs.edit', $log->id) }}" class="btn btn-outline-warning">
                                        Edit This Log
                                    </a>
                                @elseif($log->supervisor_feedback)
                                    <button class="btn btn-outline-secondary" disabled title="Logs cannot be edited after supervisor feedback has been provided">
                                        Edit This Log
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary" disabled title="Logs can only be edited within 48 hours of submission">
                                        Edit This Log
                                    </button>
                                @endif
                                
                                <a href="{{ route('student.logs.create') }}" class="btn btn-success">
                                    Submit New Log
                                </a>
                            </div>
                            
                            @if($log->supervisor_feedback)
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Logs cannot be edited after supervisor feedback has been provided. This preserves the integrity of the feedback process.
                                    </small>
                                </div>
                            @elseif($log->created_at->diffInHours(now()) > 48)
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Logs can only be edited within 48 hours of submission.
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Log Statistics -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Log Statistics
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-right">
                                        <h4 class="font-weight-bold text-primary">{{ strlen($log->content) }}</h4>
                                        <small class="text-muted">Characters</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="font-weight-bold text-info">{{ str_word_count($log->content) }}</h4>
                                    <small class="text-muted">Words</small>
                                </div>
                            </div>
                            
                            @if($log->file_path)
                                <hr>
                                <div class="text-center">
                                    <small class="text-muted">Has attachment</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden logout form for dropdown -->
    <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle functionality
            $("#sidebarToggleTop").on('click', function() {
                $(".sidebar").toggleClass("show");
            });
        });

        function showQuestionForm() {
            document.getElementById('questionForm').classList.remove('d-none');
        }

        function hideQuestionForm() {
            document.getElementById('questionForm').classList.add('d-none');
            document.getElementById('studentQuestion').value = '';
        }

        function acknowledgeFeedback(type) {
            const logId = {{ $log->id }};
            let data = {
                type: type,
                _token: '{{ csrf_token() }}'
            };

            if (type === 'question') {
                const question = document.getElementById('studentQuestion').value.trim();
                if (!question) {
                    alert('Please enter your question before submitting.');
                    return;
                }
                data.question = question;
            }

            // Show loading state
            const buttons = document.querySelectorAll('.acknowledgment-section button');
            buttons.forEach(btn => btn.disabled = true);

            fetch(`/student/logs/${logId}/acknowledge`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show the acknowledgment
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong'));
                    // Re-enable buttons
                    buttons.forEach(btn => btn.disabled = false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                // Re-enable buttons
                buttons.forEach(btn => btn.disabled = false);
            });
        }
    </script>
</body>
</html>
