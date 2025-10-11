<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard/student">
                SchoolPro - Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('student.projects.index') }}">
                    My Projects
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    {{ $project->title }}
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6>Description:</h6>
                        <p class="text-muted">{{ $project->description }}</p>
                        
                        <h6>Supervisor:</h6>
                        <p class="text-muted">{{ $project->supervisor->name ?? 'Not assigned' }}</p>
                        
                        <h6>Submitted:</h6>
                        <p class="text-muted">{{ $project->created_at->format('M d, Y at g:i A') }}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6>Project Status</h6>
                                @switch($project->status)
                                    @case('Pending')
                                        <span class="badge bg-warning text-dark fs-6">
                                            Pending Review
                                        </span>
                                        @break
                                    @case('Approved')
                                        <span class="badge bg-success fs-6">
                                            Approved
                                        </span>
                                        @break
                                    @case('Rejected')
                                        <span class="badge bg-danger fs-6">
                                            Rejected
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('student.projects.index') }}" class="btn btn-secondary">
                        ← Back to Projects
                    </a>
                    
                    @if(in_array($project->status, ['Pending', 'Rejected']))
                        <a href="{{ route('student.projects.editProject', $project->id) }}" class="btn btn-warning">
                            Edit Project
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>