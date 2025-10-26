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
                                                <a href="{{ route('teacher.proposals.show', $proposal) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                                @if($proposal->status === 'Pending')
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
                <a class="nav-link" href="{{ route('teacher.analytics.index') }}">
                    Analytics
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
                                                @if($proposal->status === 'Approved') bg-success
                                                @elseif($proposal->status === 'Rejected') bg-danger  
                                                @elseif($proposal->status === 'Needs Revision') bg-warning
                                                @elseif($proposal->status === 'Completed') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                {{ $proposal->status }}
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
                                                
                                                @if($proposal->status === 'Pending')
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
                                                @else
                                                    <span class="text-muted small">No actions available</span>
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
                        <!-- Removed clipboard emoji from empty state -->
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