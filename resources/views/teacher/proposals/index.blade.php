<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposals - Teacher Portal</title>
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
            bottom: 0;
            left: 0;
            width: 250px;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            padding: 20px;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
            display: block;
            text-decoration: none;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .badge-status {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
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
                                            @if($proposal->resubmitted_at && $proposal->status === 'Pending')
                                                <br><small class="text-info"><i class="fas fa-redo-alt"></i> Resubmitted</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $proposal->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $proposal->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher.proposals.show', $proposal) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
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
            </div> <!-- end .card-body -->
        </div> <!-- end .card -->
    </div> <!-- end .main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>