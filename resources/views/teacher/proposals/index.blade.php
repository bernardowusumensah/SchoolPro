<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposals - Teacher Portal</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0.5rem;
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
        .badge-status {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #5a5c69;
            font-size: 0.85rem;
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
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('teacher.proposals.index') }}">
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
                <a class="nav-link" href="{{ route('teacher.logs.analytics') }}">
                    Log Analytics
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Project Proposals</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.teacher') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Proposals</li>
                </ol>
            </nav>
        </div>

        <!-- Proposals List -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Project Proposals</h6>
            </div>
            <div class="card-body">
                @if($proposals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project Title</th>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proposals as $proposal)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $proposal->title }}</div>
                                            @if($proposal->description)
                                                <small class="text-muted">{{ Str::limit($proposal->description, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $proposal->student->name }}</div>
                                            <small class="text-muted">{{ $proposal->student->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-status
                                                @if($proposal->status === 'approved') bg-success
                                                @elseif($proposal->status === 'rejected') bg-danger  
                                                @elseif($proposal->status === 'revision_requested') bg-warning
                                                @else bg-primary
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $proposal->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $proposal->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.proposals.show', $proposal) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                                
                                                @if($proposal->status === 'pending')
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                                data-bs-toggle="dropdown">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form method="POST" action="{{ route('teacher.proposals.approve', $proposal) }}" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item text-success" 
                                                                            onclick="return confirm('Are you sure you want to approve this proposal?')">
                                                                        Approve
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="dropdown-item text-warning" 
                                                                        data-bs-toggle="modal" data-bs-target="#revisionModal{{ $proposal->id }}">
                                                                    Request Revision
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="dropdown-item text-danger" 
                                                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $proposal->id }}">
                                                                    Reject
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Revision Modal -->
                                    <div class="modal fade" id="revisionModal{{ $proposal->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Request Revision</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('teacher.proposals.revision', $proposal) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Revision Comments</label>
                                                            <textarea name="supervisor_feedback" class="form-control" rows="4" 
                                                                      placeholder="Please provide specific feedback on what needs to be revised..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Request Revision</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $proposal->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Proposal</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('teacher.proposals.reject', $proposal) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Rejection Reason</label>
                                                            <textarea name="supervisor_feedback" class="form-control" rows="4" 
                                                                      placeholder="Please provide reasons for rejection..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject Proposal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $proposals->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <div class="display-1 text-muted">📋</div>
                        </div>
                        <h5 class="text-muted">No Proposals Yet</h5>
                        <p class="text-muted">You haven't received any project proposals from students yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>