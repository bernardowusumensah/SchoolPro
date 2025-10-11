<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Details - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                    <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.proposals.index') }}">
                    <i class="fas fa-fw fa-clipboard-list me-2"></i>
                    Proposals
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('teacher.logs.index') }}">
                    <i class="fas fa-fw fa-calendar-week me-2"></i>
                    Student Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.logs.unreviewed') }}">
                    <i class="fas fa-fw fa-exclamation-circle me-2"></i>
                    Unreviewed Logs
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
                    <i class="fas fa-fw fa-user me-2"></i>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent text-start w-100" style="color: rgba(255, 255, 255, 0.8);">
                        <i class="fas fa-fw fa-sign-out-alt me-2"></i>
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
                <span class="navbar-brand mb-0 h1">Log Details</span>
                
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'Teacher' }}</span>
                            <img class="img-profile rounded-circle"
                                src="{{ auth()->user()->profile_picture_url }}" 
                                alt="Profile Picture"
                                style="width: 40px; height: 40px; object-fit: cover;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#profile">
                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.teacher') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teacher.logs.index') }}">Student Logs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Log Details</li>
            </ol>
        </nav>

        <!-- Log Details -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Log Content -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-calendar-week fa-fw"></i> Log Entry
                        </h6>
                        <div>
                            @if($log->supervisor_feedback)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Reviewed
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>Pending Review
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Student:</strong> {{ $log->student->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Project:</strong> {{ $log->project->title }}
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
                        
                        <h6 class="fw-bold mb-3">Log Content:</h6>
                        <div class="log-content">
                            {{ $log->content }}
                        </div>
                        
                        @if($log->file_path)
                            <div class="mt-3">
                                <h6 class="fw-bold mb-2">Attachment:</h6>
                                <a href="{{ Storage::url($log->file_path) }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-download me-2"></i>Download Attachment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Previous Logs Context -->
                @if($previousLogs->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-history fa-fw"></i> Previous Logs from {{ $log->student->name }}
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($previousLogs as $prevLog)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $prevLog->created_at->format('M j, Y') }}</h6>
                                        <small class="text-muted">{{ $prevLog->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-muted mb-2">{{ Str::limit($prevLog->content, 150) }}</p>
                                    @if($prevLog->supervisor_feedback)
                                        <div class="alert alert-success py-2">
                                            <small><strong>Your Feedback:</strong> {{ Str::limit($prevLog->supervisor_feedback, 100) }}</small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Student Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-user fa-fw"></i> Student Information
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <img class="rounded-circle mb-3" 
                             src="{{ $log->student->profile_picture_url ?? '/images/default-avatar.png' }}" 
                             alt="Student" style="width: 80px; height: 80px; object-fit: cover;">
                        <h5 class="fw-bold">{{ $log->student->name }}</h5>
                        <p class="text-muted">{{ $log->student->email }}</p>
                        <a href="{{ route('teacher.students.logs', $log->student->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-2"></i>View All Logs
                        </a>
                    </div>
                </div>

                <!-- Project Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-project-diagram fa-fw"></i> Project Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $log->project->title }}</h6>
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
                        
                        <a href="{{ route('teacher.projects.logs', $log->project->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-2"></i>View Project Logs
                        </a>
                    </div>
                </div>

                <!-- Feedback Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-comment fa-fw"></i> Feedback Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($log->supervisor_feedback)
                            <div class="alert alert-success">
                                <h6 class="fw-bold">Your Feedback:</h6>
                                <div class="feedback-content">
                                    {{ $log->supervisor_feedback }}
                                </div>
                                <small class="text-muted">
                                    Provided on {{ $log->feedback_date->format('M j, Y g:i A') }}
                                </small>
                            </div>
                            <button class="btn btn-warning w-100" data-action="update-feedback" data-log-id="{{ $log->id }}" data-student-name="{{ $log->student->name }}" data-current-feedback="{{ $log->supervisor_feedback }}">
                                <i class="fas fa-edit me-2"></i>Update Feedback
                            </button>
                        @else
                            <p class="text-muted mb-3">This log entry is waiting for your feedback.</p>
                            <button class="btn btn-success w-100 mb-2" data-action="provide-feedback" data-log-id="{{ $log->id }}" data-student-name="{{ $log->student->name }}">
                                <i class="fas fa-comment me-2"></i>Provide Feedback
                            </button>
                            <button class="btn btn-info w-100" data-action="mark-reviewed" data-log-id="{{ $log->id }}" data-student-name="{{ $log->student->name }}">
                                <i class="fas fa-check me-2"></i>Mark as Reviewed
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Provide Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Provide Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="feedbackForm">
                    <div class="modal-body">
                        <p>Provide feedback for <strong id="feedbackStudentName"></strong>'s log entry:</p>
                        <div class="mb-3">
                            <label for="supervisor_feedback" class="form-label">Feedback <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="supervisor_feedback" name="supervisor_feedback" rows="4" 
                                      placeholder="Provide constructive feedback on the student's progress..." required></textarea>
                            <div class="form-text">Minimum 10 characters, maximum 2000 characters.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-comment me-2"></i>Provide Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mark as Reviewed Modal -->
    <div class="modal fade" id="reviewedModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark as Reviewed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark <strong id="reviewedStudentName"></strong>'s log as reviewed?</p>
                    <p class="text-muted">This will add a generic "Reviewed" message without specific feedback.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="confirmReviewed">
                        <i class="fas fa-check me-2"></i>Mark as Reviewed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let currentLogId = null;

        // Provide Feedback
        $(document).on('click', '[data-action="provide-feedback"]', function() {
            const logId = $(this).data('log-id');
            const studentName = $(this).data('student-name');
            
            currentLogId = logId;
            $('#feedbackStudentName').text(studentName);
            $('#supervisor_feedback').val('');
            $('#feedbackModal').modal('show');
        });

        // Update Feedback
        $(document).on('click', '[data-action="update-feedback"]', function() {
            const logId = $(this).data('log-id');
            const studentName = $(this).data('student-name');
            const currentFeedback = $(this).data('current-feedback');
            
            currentLogId = logId;
            $('#feedbackStudentName').text(studentName);
            $('#supervisor_feedback').val(currentFeedback);
            $('#feedbackModal').modal('show');
        });

        $('#feedbackForm').on('submit', function(e) {
            e.preventDefault();
            const feedback = $('#supervisor_feedback').val();
            
            if (!feedback.trim()) {
                alert('Please provide feedback');
                return;
            }
            
            $.ajax({
                url: `/teacher/logs/${currentLogId}/feedback`,
                method: 'PATCH',
                data: { 
                    supervisor_feedback: feedback,
                    _method: 'PATCH'
                },
                success: function(response) {
                    $('#feedbackModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error providing feedback');
                }
            });
        });

        // Mark as Reviewed
        $(document).on('click', '[data-action="mark-reviewed"]', function() {
            const logId = $(this).data('log-id');
            const studentName = $(this).data('student-name');
            
            currentLogId = logId;
            $('#reviewedStudentName').text(studentName);
            $('#reviewedModal').modal('show');
        });

        $('#confirmReviewed').on('click', function() {
            $.ajax({
                url: `/teacher/logs/${currentLogId}/reviewed`,
                method: 'PATCH',
                data: { 
                    _method: 'PATCH'
                },
                success: function(response) {
                    $('#reviewedModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error marking log as reviewed');
                }
            });
        });

        // Reset modal forms when hidden
        $('.modal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();
        });
    </script>
</body>
</html>
