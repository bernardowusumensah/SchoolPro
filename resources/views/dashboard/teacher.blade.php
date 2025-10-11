<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
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
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        .text-gray-500 {
            color: #858796 !important;
        }
        .text-gray-300 {
            color: #dddfeb !important;
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
                <a class="nav-link active" href="{{ route('dashboard.teacher') }}">
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
                    @if(isset($stats['unreviewed_logs']) && $stats['unreviewed_logs'] > 0)
                        <span class="badge bg-warning text-dark ms-2">{{ $stats['unreviewed_logs'] }}</span>
                    @endif
                </a>
                <a class="nav-link" href="{{ route('teacher.logs.analytics') }}">
                    Log Analytics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#grades">
                    Grades
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#reports">
                    Reports
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
                <span class="navbar-brand mb-0 h1">Teacher Dashboard</span>
                
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
                        <!-- Dropdown - User Information -->
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
            <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
        </div>
        <!-- Stats Cards Row -->
        <div class="row mb-4">
            <!-- Pending Proposals Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    Pending Proposals</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['pending_proposals'] ?? '0' }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Projects Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                    Approved Projects</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['approved_projects'] ?? '0' }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Needs Revision Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Needs Revision</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['needs_revision'] ?? '0' }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Projects Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                    Total Projects</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['total_projects'] ?? '0' }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('teacher.logs.unreviewed') }}" class="btn btn-warning w-100">
                                    Review Unreviewed Logs
                                    @if(isset($stats['unreviewed_logs']) && $stats['unreviewed_logs'] > 0)
                                        <span class="badge bg-light text-dark ms-2">{{ $stats['unreviewed_logs'] }}</span>
                                    @endif
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('teacher.logs.index') }}" class="btn btn-info w-100">
                                    View All Student Logs
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('teacher.logs.analytics') }}" class="btn btn-success w-100">
                                    Log Analytics
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('teacher.proposals.index') }}" class="btn btn-primary w-100">
                                    Review Proposals
                                    @if(isset($stats['pending_proposals']) && $stats['pending_proposals'] > 0)
                                        <span class="badge bg-light text-dark ms-2">{{ $stats['pending_proposals'] }}</span>
                                    @endif
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects DataTable -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">
                            Project Proposals
                        </h6>
                        <span class="badge bg-warning text-dark">{{ $stats['pending_proposals'] ?? 0 }} Pending Review</span>
                    </div>
                    <div class="card-body">
                        @if(isset($recentProposals) && count($recentProposals) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student</th>
                                            <th>Project Title</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentProposals as $proposal)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="rounded-circle me-2" 
                                                         src="{{ $proposal->student->profile_picture_url ?? '/images/default-avatar.png' }}" 
                                                         alt="Student" style="width: 30px; height: 30px;">
                                                    <div>
                                                        <div class="fw-bold">{{ $proposal->student->name }}</div>
                                                        <small class="text-muted">{{ $proposal->student->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ Str::limit($proposal->title, 40) }}</div>
                                                    <small class="text-muted">{{ Str::limit($proposal->description, 60) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($proposal->status === 'Pending')
                                                    <span class="badge bg-warning text-dark">Pending Review</span>
                                                @elseif($proposal->status === 'Needs Revision')
                                                    <span class="badge bg-info">Needs Revision</span>
                                                @elseif($proposal->status === 'Approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $proposal->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{ $proposal->created_at->format('M j, Y') }}</div>
                                                    <small class="text-muted">{{ $proposal->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            data-action="view-proposal"
                                                            data-proposal-id="{{ $proposal->id }}" 
                                                            title="View Details">
                                                        View
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" 
                                                            data-action="approve-proposal"
                                                            data-proposal-id="{{ $proposal->id }}" 
                                                            data-proposal-title="{{ $proposal->title }}"
                                                            title="Approve">
                                                        Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" 
                                                            data-action="revision-proposal"
                                                            data-proposal-id="{{ $proposal->id }}" 
                                                            data-proposal-title="{{ $proposal->title }}"
                                                            title="Request Revision">
                                                        Revise
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            data-action="reject-proposal"
                                                            data-proposal-id="{{ $proposal->id }}" 
                                                            data-proposal-title="{{ $proposal->title }}"
                                                            title="Reject">
                                                        Reject
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-dark" 
                                                            data-action="delete-proposal"
                                                            data-proposal-id="{{ $proposal->id }}" 
                                                            data-proposal-title="{{ $proposal->title }}"
                                                            title="Delete">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">

                                <h5 class="text-gray-500">No Project Proposals</h5>
                                <p class="text-muted">No project proposals have been submitted yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>



    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- View Proposal Modal -->
    <div class="modal fade" id="viewProposalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="proposalDetails">
                    <!-- Proposal details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Proposal Modal -->
    <div class="modal fade" id="approveProposalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="approveForm">
                    <div class="modal-body">
                        <p>Are you sure you want to approve the proposal: <strong id="approveProposalTitle"></strong>?</p>
                        <div class="mb-3">
                            <label for="approvalComments" class="form-label">Approval Comments (Optional)</label>
                            <textarea class="form-control" id="approvalComments" name="approval_comments" rows="3" placeholder="Add any comments or feedback..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            Approve Proposal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Proposal Modal -->
    <div class="modal fade" id="rejectProposalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm">
                    <div class="modal-body">
                        <p>Are you sure you want to reject the proposal: <strong id="rejectProposalTitle"></strong>?</p>
                        <div class="mb-3">
                            <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            Reject Proposal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Request Revision Modal -->
    <div class="modal fade" id="revisionProposalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Revision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="revisionForm">
                    <div class="modal-body">
                        <p>Request revision for the proposal: <strong id="revisionProposalTitle"></strong></p>
                        <div class="mb-3">
                            <label for="revisionComments" class="form-label">Revision Comments <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="revisionComments" name="revision_comments" rows="3" placeholder="Please specify what needs to be revised..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            Request Revision
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

        // View Proposal
        $(document).on('click', '[data-action="view-proposal"]', function() {
            const proposalId = $(this).data('proposal-id');
            
            $.ajax({
                url: `/teacher/proposals/${proposalId}`,
                method: 'GET',
                success: function(response) {
                    $('#proposalDetails').html(response);
                    $('#viewProposalModal').modal('show');
                },
                error: function(xhr) {
                    alert('Error loading proposal details');
                }
            });
        });

        // Approve Proposal
        $(document).on('click', '[data-action="approve-proposal"]', function() {
            const proposalId = $(this).data('proposal-id');
            const proposalTitle = $(this).data('proposal-title');
            
            $('#approveProposalTitle').text(proposalTitle);
            $('#approveForm').data('proposal-id', proposalId);
            $('#approveProposalModal').modal('show');
        });

        $('#approveForm').on('submit', function(e) {
            e.preventDefault();
            const proposalId = $(this).data('proposal-id');
            const comments = $('#approvalComments').val();
            
            $.ajax({
                url: `/teacher/proposals/${proposalId}/approve`,
                method: 'PATCH',
                data: { 
                    approval_comments: comments,
                    _method: 'PATCH'
                },
                success: function(response) {
                    $('#approveProposalModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error approving proposal');
                }
            });
        });

        // Reject Proposal
        $(document).on('click', '[data-action="reject-proposal"]', function() {
            const proposalId = $(this).data('proposal-id');
            const proposalTitle = $(this).data('proposal-title');
            
            $('#rejectProposalTitle').text(proposalTitle);
            $('#rejectForm').data('proposal-id', proposalId);
            $('#rejectProposalModal').modal('show');
        });

        $('#rejectForm').on('submit', function(e) {
            e.preventDefault();
            const proposalId = $(this).data('proposal-id');
            const reason = $('#rejectionReason').val();
            
            if (!reason.trim()) {
                alert('Please provide a rejection reason');
                return;
            }
            
            $.ajax({
                url: `/teacher/proposals/${proposalId}/reject`,
                method: 'PATCH',
                data: { 
                    rejection_reason: reason,
                    _method: 'PATCH'
                },
                success: function(response) {
                    $('#rejectProposalModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error rejecting proposal');
                }
            });
        });

        // Request Revision
        $(document).on('click', '[data-action="revision-proposal"]', function() {
            const proposalId = $(this).data('proposal-id');
            const proposalTitle = $(this).data('proposal-title');
            
            $('#revisionProposalTitle').text(proposalTitle);
            $('#revisionForm').data('proposal-id', proposalId);
            $('#revisionProposalModal').modal('show');
        });

        $('#revisionForm').on('submit', function(e) {
            e.preventDefault();
            const proposalId = $(this).data('proposal-id');
            const comments = $('#revisionComments').val();
            
            if (!comments.trim()) {
                alert('Please provide revision comments');
                return;
            }
            
            $.ajax({
                url: `/teacher/proposals/${proposalId}/revision`,
                method: 'PATCH',
                data: { 
                    revision_comments: comments,
                    _method: 'PATCH'
                },
                success: function(response) {
                    $('#revisionProposalModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error requesting revision');
                }
            });
        });

        // Delete Proposal
        $(document).on('click', '[data-action="delete-proposal"]', function() {
            const proposalId = $(this).data('proposal-id');
            const proposalTitle = $(this).data('proposal-title');
            
            if (confirm(`Are you sure you want to delete the proposal "${proposalTitle}"? This action cannot be undone.`)) {
                $.ajax({
                    url: `/teacher/proposals/${proposalId}`,
                    method: 'DELETE',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error deleting proposal');
                    }
                });
            }
        });

       
        

        // Reset modal forms when hidden
        $('.modal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();
            $(this).find('textarea').prop('required', false);
        });
    </script>
</body>
</html>
