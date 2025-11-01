<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unreviewed Logs - SchoolPro</title>
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
        .border-left-warning { border-left: 4px solid #f6c23e !important; }
        .border-left-success { border-left: 4px solid #1cc88a !important; }
        .border-left-info { border-left: 4px solid #36b9cc !important; }
        .border-left-primary { border-left: 4px solid #4e73df !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-500 { color: #858796 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        .urgency-high { border-left: 4px solid #e74a3b !important; }
        .urgency-medium { border-left: 4px solid #f6c23e !important; }
        .urgency-low { border-left: 4px solid #1cc88a !important; }
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
                <a class="nav-link active" href="{{ route('teacher.logs.unreviewed') }}">
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
                <span class="navbar-brand mb-0 h1">Unreviewed Logs</span>
                
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
            <h1 class="h3 mb-0 text-gray-800">Unreviewed Student Logs</h1>
            <div>
                <a href="{{ route('teacher.logs.index') }}" class="btn btn-outline-primary">
                    View All Logs
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    Unreviewed Logs</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $unreviewedLogs->total() }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unreviewed Logs Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">
                            Logs Requiring Review
                        </h6>
                        <div>
                            <button class="btn btn-success btn-sm" id="bulkFeedbackBtn" disabled>
                                Bulk Feedback
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($unreviewedLogs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                            <th>Student</th>
                                            <th>Project</th>
                                            <th>Log Content</th>
                                            <th>Submitted</th>
                                            <th>Urgency</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unreviewedLogs as $log)
                                            @php
                                                $daysSinceSubmission = $log->created_at->diffInDays(now());
                                                $urgencyClass = $daysSinceSubmission >= 7 ? 'urgency-high' : ($daysSinceSubmission >= 3 ? 'urgency-medium' : 'urgency-low');
                                                $urgencyText = $daysSinceSubmission >= 7 ? 'High' : ($daysSinceSubmission >= 3 ? 'Medium' : 'Low');
                                            @endphp
                                        <tr class="{{ $urgencyClass }}">
                                            <td>
                                                <input type="checkbox" class="form-check-input log-select" value="{{ $log->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="rounded-circle me-2" 
                                                         src="{{ $log->student->profile_picture_url ?? '/images/default-avatar.png' }}" 
                                                         alt="Student" style="width: 30px; height: 30px;">
                                                    <div>
                                                        <div class="fw-bold">{{ $log->student->name }}</div>
                                                        <small class="text-muted">{{ $log->student->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ Str::limit($log->project->title, 30) }}</div>
                                                    <small class="text-muted">{{ $log->project->status }}</small>
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
                                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($urgencyText == 'High')
                                                    <span class="badge bg-danger">{{ $urgencyText }}</span>
                                                @elseif($urgencyText == 'Medium')
                                                    <span class="badge bg-warning text-dark">{{ $urgencyText }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ $urgencyText }}</span>
                                                @endif
                                                <br><small class="text-muted">{{ $daysSinceSubmission }} days ago</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('teacher.logs.show', $log->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                                        View
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-success" 
                                                            data-action="provide-feedback"
                                                            data-log-id="{{ $log->id }}" 
                                                            data-student-name="{{ $log->student->name }}"
                                                            title="Provide Feedback">
                                                        Feedback
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            data-action="mark-reviewed"
                                                            data-log-id="{{ $log->id }}" 
                                                            data-student-name="{{ $log->student->name }}"
                                                            title="Mark as Reviewed">
                                                        Mark Reviewed
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $unreviewedLogs->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">

                                <h5 class="text-success">All Caught Up!</h5>
                                <p class="text-muted">No unreviewed logs at the moment. Great job staying on top of student feedback!</p>
                                <a href="{{ route('teacher.logs.index') }}" class="btn btn-primary">
                                    View All Logs
                                </a>
                            </div>
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
                            Provide Feedback
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
                        Mark as Reviewed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Feedback Modal -->
    <div class="modal fade" id="bulkFeedbackModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bulkFeedbackForm">
                    <div class="modal-body">
                        <p>Provide the same feedback for <strong id="bulkLogCount"></strong> selected log(s):</p>
                        <div class="mb-3">
                            <label for="bulk_supervisor_feedback" class="form-label">Feedback <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="bulk_supervisor_feedback" name="supervisor_feedback" rows="4" 
                                      placeholder="Provide constructive feedback that applies to all selected logs..." required></textarea>
                            <div class="form-text">Minimum 10 characters, maximum 2000 characters.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            Provide Bulk Feedback
                        </button>
                    </div>
                </form>
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

        // Individual Feedback
        $(document).on('click', '[data-action="provide-feedback"]', function() {
            const logId = $(this).data('log-id');
            const studentName = $(this).data('student-name');
            
            currentLogId = logId;
            $('#feedbackStudentName').text(studentName);
            $('#supervisor_feedback').val('');
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

        // Bulk Actions
        $('.log-select').on('change', function() {
            updateBulkActionButton();
        });

        $('#selectAll').on('change', function() {
            $('.log-select').prop('checked', this.checked);
            updateBulkActionButton();
        });

        function updateBulkActionButton() {
            const selectedCount = $('.log-select:checked').length;
            if (selectedCount > 0) {
                $('#bulkFeedbackBtn').prop('disabled', false);
            } else {
                $('#bulkFeedbackBtn').prop('disabled', true);
            }
        }

        $('#bulkFeedbackBtn').on('click', function() {
            const selectedIds = $('.log-select:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                alert('Please select at least one log');
                return;
            }

            $('#bulkLogCount').text(selectedIds.length);
            $('#bulk_supervisor_feedback').val('');
            $('#bulkFeedbackModal').modal('show');
        });

        $('#bulkFeedbackForm').on('submit', function(e) {
            e.preventDefault();
            const feedback = $('#bulk_supervisor_feedback').val();
            const selectedIds = $('.log-select:checked').map(function() {
                return $(this).val();
            }).get();
            
            if (!feedback.trim()) {
                alert('Please provide feedback');
                return;
            }
            
            $.ajax({
                url: '/teacher/logs/bulk-feedback',
                method: 'POST',
                data: {
                    log_ids: selectedIds,
                    supervisor_feedback: feedback
                },
                success: function(response) {
                    $('#bulkFeedbackModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error providing bulk feedback');
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
