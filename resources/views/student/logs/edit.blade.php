<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Weekly Log - SchoolPro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
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
                    Progress Logs
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.student') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.logs.index') }}">Progress Logs</a></li>
                        <li class="breadcrumb-item active">Edit Log</li>
                    </ol>
                </nav>
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#profile">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Project Information -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            Project: {{ $log->project->title }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="text-gray-800 mb-1">{{ $log->project->title }}</h5>
                                <p class="text-muted mb-1">
                                    Supervisor: {{ $log->project->supervisor->name }}
                                </p>
                                <p class="text-muted mb-0">
                                    {{ $log->project->expected_start_date->format('M j, Y') }} - {{ $log->project->expected_completion_date->format('M j, Y') }}
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="badge {{ $log->project->getStatusBadgeClass() }} mb-2" style="font-size: 1rem;">
                                    {{ $log->project->getStatusText() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-warning">
                            Edit Weekly Log
                        </h6>
                        <div class="text-muted small">
                            Originally submitted: {{ $log->created_at->format('M j, Y g:i A') }}
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Warning about feedback -->
                        @if($log->supervisor_feedback)
                            <div class="alert alert-danger">
                                <strong>Edit Not Allowed:</strong> This log cannot be edited because supervisor feedback has already been provided. 
                                Editing after feedback would invalidate the supervisor's review.
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.logs.show', $log->id) }}" class="btn btn-primary">
                                    View Log Details
                                </a>
                                <a href="{{ route('student.logs.index') }}" class="btn btn-outline-secondary">
                                    Back to Log History
                                </a>
                            </div>
                        @elseif($log->created_at->diffInHours(now()) > 48)
                            <div class="alert alert-warning">
                                <strong>Edit Time Expired:</strong> This log can no longer be edited as the 48-hour edit window has passed.
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('student.logs.show', $log->id) }}" class="btn btn-primary">
                                    View Log Details
                                </a>
                                <a href="{{ route('student.logs.index') }}" class="btn btn-outline-secondary">
                                    Back to Log History
                                </a>
                            </div>
                        @else
                            <form action="{{ route('student.logs.update', $log->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
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
                                              required>{{ old('content', $log->content) }}</textarea>
                                    <div class="form-text">
                                        Minimum 100 characters, maximum 5000 characters. 
                                        <span id="charCount" class="text-muted">{{ strlen($log->content) }}/5000</span>
                                    </div>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Attachment Display -->
                                @if($log->file_path)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Current Attachment</label>
                                        <div class="alert alert-info">
                                            <strong>File:</strong> {{ basename($log->file_path) }}
                                            <a href="{{ Storage::url($log->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                                View File
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <!-- File Attachment -->
                                <div class="mb-4">
                                    <label for="attachment" class="form-label fw-bold">
                                        @if($log->file_path)
                                            Replace Supporting Document (Optional)
                                        @else
                                            Add Supporting Document (Optional)
                                        @endif
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('attachment') is-invalid @enderror" 
                                           id="attachment" 
                                           name="attachment"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                                    <div class="form-text">
                                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG, ZIP. Maximum file size: 10MB.
                                        @if($log->file_path)
                                            Leave blank to keep current file.
                                        @endif
                                    </div>
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Edit Warning -->
                                <div class="alert alert-warning">
                                    <strong>Important:</strong> You have {{ 48 - $log->created_at->diffInHours(now()) }} hours remaining to edit this log. 
                                    Once supervisor feedback is provided or the 48-hour window expires, no further edits will be possible.
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('student.logs.show', $log->id) }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        Update Log
                                    </button>
                                </div>
                            </form>
                        @endif
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
            // Character count for content
            function updateCharCount() {
                var content = $('#content').val();
                var charCount = content.length;
                $('#charCount').text(charCount + '/5000');
                
                if (charCount < 100) {
                    $('#charCount').removeClass('text-success').addClass('text-danger');
                } else if (charCount > 4500) {
                    $('#charCount').removeClass('text-success').addClass('text-warning');
                } else {
                    $('#charCount').removeClass('text-danger text-warning').addClass('text-success');
                }
            }

            // Update character count on load and input
            updateCharCount();
            $('#content').on('input', updateCharCount);
        });
    </script>
</body>
</html>