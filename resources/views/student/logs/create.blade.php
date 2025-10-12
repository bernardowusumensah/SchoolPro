<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Weekly Log - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .border-left-primary { border-left: 4px solid #4e73df !important; }
        .border-left-success { border-left: 4px solid #1cc88a !important; }
        .border-left-warning { border-left: 4px solid #f6c23e !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-500 { color: #858796 !important; }
        .text-gray-300 { color: #dddfeb !important; }
    </style>
</head>
<body>
    <!-- Left Sidebar -->
    <nav class="sidebar">
        <div class="text-center py-3">
            <h5 class="text-white mb-0">SchoolPro</h5>
            <small class="text-white-50">Student Portal</small>
        </div>
        <hr class="sidebar-divider my-2" style="border-color: rgba(255,255,255,0.15);">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.student') }}">
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('student.projects.index') }}">
                    My Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('student.logs.index') }}">
                    Weekly Logs
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
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Create Weekly Log</span>
                
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'Student' }}</span>
                            <img class="img-profile rounded-circle"
                                src="{{ auth()->user()->profile_picture_url ?? '/images/default-avatar.png' }}" 
                                alt="Profile Picture"
                                style="width: 40px; height: 40px; object-fit: cover;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#profile">
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Weekly Progress Log</h1>
            <div>
                <a href="{{ route('student.logs.index') }}" class="btn btn-outline-primary">

                    View All Logs
                </a>
            </div>
        </div>

        <!-- Project Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            CURRENT PROJECT
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2">{{ $project->title }}</h5>
                                <p class="text-muted mb-2">
                                    Supervisor: {{ $project->supervisor->name }}
                                </p>
                                <p class="text-muted mb-0">
                                    {{ $project->expected_start_date->format('M j, Y') }} - {{ $project->expected_completion_date->format('M j, Y') }}
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="badge {{ $project->getStatusBadgeClass() }} mb-2" style="font-size: 1rem;">
                                    {{ $project->getStatusText() }}
                                </span>
                                @if($project->reviewed_at && in_array($project->status, ['approved', 'in_progress', 'completed']))
                                    <p class="text-success mb-0">
                                        Approved {{ $project->reviewed_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        @if($project->supervisor_feedback && in_array($project->status, ['approved', 'in_progress', 'completed']))
                            <div class="alert alert-success mt-3 mb-0">
                                <strong>Supervisor's Comment:</strong> {{ $project->supervisor_feedback }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Approval History (Collapsed) -->
        <div class="accordion mb-4" id="projectDetailsAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#projectDetails">
                        View Project Details
                    </button>
                </h2>
                <div id="projectDetails" class="accordion-collapse collapse" data-bs-parent="#projectDetailsAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Approval Details</h6>
                                @if($project->reviewed_at && in_array($project->status, ['approved', 'in_progress', 'completed']))
                                    <p class="mb-1"><strong>Approved:</strong> {{ $project->reviewed_at->format('M j, Y g:i A') }}</p>
                                    <p class="mb-1"><strong>By:</strong> {{ $project->supervisor->name }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Revision History</h6>
                                @if($project->reviewed_at)
                                    <p class="mb-1"><strong>Last Reviewed:</strong> {{ $project->reviewed_at->format('M j, Y g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Status Context -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert 
                    @if($project->status === 'Draft') alert-info
                    @elseif($project->status === 'Pending') alert-warning  
                    @elseif($project->status === 'Approved') alert-success
                    @elseif($project->status === 'Completed') alert-primary
                    @else alert-secondary
                    @endif
                " role="alert">
                    <h6 class="alert-heading mb-2">
                        @if($project->status === 'Draft')
                            Draft Project - Track Your Preparation Work
                        @elseif($project->status === 'Pending')
                            Pending Approval - Log Your Proposal Work
                        @elseif($project->status === 'Approved') 
                            Approved Project - Track Your Progress
                        @elseif($project->status === 'Completed')
                            Completed Project - Final Documentation
                        @else
                            Project Status: {{ ucfirst($project->status) }}
                        @endif
                    </h6>
                    <p class="mb-0">
                        @if($project->status === 'Draft')
                            You can log research activities, initial planning, literature review, and concept development while preparing your proposal.
                        @elseif($project->status === 'Pending')
                            Log your proposal preparation work, supervisor meetings, research activities, and any feedback incorporation while awaiting approval.
                        @elseif($project->status === 'Approved')
                            Track your weekly progress, milestones achieved, challenges faced, and next steps as you work on your approved project.
                        @elseif($project->status === 'Completed')
                            Document final testing, report writing, presentation preparation, and project wrap-up activities.
                        @else
                            Document your project-related activities and progress updates.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Weekly Log Warning -->
        @if($weeklyLogExists)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <div>
                        <strong>Weekly Log Already Submitted!</strong> You have already submitted a log for this week ({{ now()->startOfWeek()->format('M j') }} - {{ now()->endOfWeek()->format('M j') }}). 
                        You can still submit another log if needed.
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Log Creation Form -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">
                            Weekly Progress Log
                        </h6>
                        <div class="text-muted small">
                            Week of {{ now()->startOfWeek()->format('M j') }} - {{ now()->endOfWeek()->format('M j, Y') }}
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.logs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Log Content -->
                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold">
                                    Progress Summary <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" 
                                          name="content" 
                                          rows="8" 
                                          placeholder="Describe your progress this week, challenges faced, lessons learned, and plans for next week..."
                                          required>{{ old('content') }}</textarea>
                                <div class="form-text">
                                    Minimum 100 characters, maximum 5000 characters. 
                                    <span id="charCount" class="text-muted">0/5000</span>
                                </div>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Attachment -->
                            <div class="mb-4">
                                <label for="attachment" class="form-label fw-bold">
                                    Supporting Documents (Optional)
                                </label>
                                <input type="file" 
                                       class="form-control @error('attachment') is-invalid @enderror" 
                                       id="attachment" 
                                       name="attachment"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                                <div class="form-text">
                                    Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG, ZIP. Maximum file size: 10MB.
                                </div>
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Force Submit Checkbox (if weekly log exists) -->
                            @if($weeklyLogExists)
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="force_submit" name="force_submit" value="1">
                                    <label class="form-check-label" for="force_submit">
                                        Submit additional log for this week (I understand this is my second log this week)
                                    </label>
                                </div>
                            </div>
                            @endif

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('student.logs.index') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Submit Weekly Log
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Character counter for progress summary
            $('#content').on('input', function() {
                const length = $(this).val().length;
                const maxLength = 5000;
                const minLength = 100;
                
                $('#charCount').text(`${length}/${maxLength}`);
                
                if (length < minLength) {
                    $('#charCount').removeClass('text-success text-warning').addClass('text-danger');
                } else if (length > maxLength * 0.9) {
                    $('#charCount').removeClass('text-success text-danger').addClass('text-warning');
                } else {
                    $('#charCount').removeClass('text-warning text-danger').addClass('text-success');
                }
            });

            // Form validation
            $('form').on('submit', function(e) {
                const content = $('#content').val().trim();
                const minLength = 100;
                
                if (content.length < minLength) {
                    e.preventDefault();
                    alert(`Please provide at least ${minLength} characters in your progress summary.`);
                    $('#content').focus();
                    return false;
                }
            });

            // File size validation
            $('#attachment').on('change', function() {
                const file = this.files[0];
                const maxSize = 10 * 1024 * 1024; // 10MB
                
                if (file && file.size > maxSize) {
                    alert('File size must be less than 10MB.');
                    this.value = '';
                }
            });
        });
    </script>
</body>
</html>
           