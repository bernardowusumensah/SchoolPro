<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
     <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard/student">
                SchoolPro - Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="/dashboard/student">
                    Dashboard
                </a>
                <a class="nav-link text-white" href="{{ route('student.projects.proposal') }}">
                    New Proposal
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-primary">
                        My Projects ({{ $projects->count() }}/3)
                    </h2>
                    @if($projects->count() < 3)
                        <a href="{{ route('student.projects.proposal') }}" class="btn btn-success">
                            Create New Proposal
                        </a>
                    @else
                        <span class="btn btn-secondary" disabled>
                            Maximum Projects Reached
                        </span>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($projects->isEmpty())
                    <!-- No Projects State -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <svg width="40" height="40" fill="currentColor" class="text-muted" viewBox="0 0 16 16">
                                        <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                                        <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-muted mb-3">No Projects Yet</h4>
                            <p class="text-muted mb-4">
                                You haven't created any project proposals yet. Get started by creating your first proposal!
                            </p>
                            @if($projects->count() < 3)
                                <a href="{{ route('student.projects.proposal') }}" class="btn btn-primary btn-lg">
                                    Create Your First Proposal
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Projects List -->
                    <div class="row">
                        @foreach($projects as $project)
                            <div class="col-lg-6 col-xl-4 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom-0 pb-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="card-title text-truncate mb-2" title="{{ $project->title }}">
                                                {{ $project->title }}
                                            </h5>
                                            @switch($project->status)
                                                @case('Pending')
                                                    <span class="badge bg-warning text-dark">
                                                        Pending
                                                    </span>
                                                    @break
                                                @case('Approved')
                                                    <span class="badge bg-success">
                                                        Approved
                                                    </span>
                                                    @break
                                                @case('Rejected')
                                                    <span class="badge bg-danger">
                                                        Rejected
                                                    </span>
                                                    @break
                                                @case('In Progress')
                                                    <span class="badge bg-info">
                                                        In Progress
                                                    </span>
                                                    @break
                                                @case('Completed')
                                                    <span class="badge bg-primary">
                                                        Completed
                                                    </span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <p class="card-text text-muted small mb-3">
                                            {{ Str::limit($project->description, 120) }}
                                        </p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <strong>Supervisor:</strong> {{ $project->supervisor->name ?? 'Not assigned' }}
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <strong>Submitted:</strong> {{ $project->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-white border-top-0">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('student.projects.show', $project->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                View Details
                                            </a>
                                            
                                            @if(in_array($project->status, ['Pending', 'Rejected']))
                                                <a href="{{ route('student.projects.editProject', $project->id) }}" 
                                                   class="btn btn-outline-secondary btn-sm">
                                                    Edit
                                                </a>
                                            @elseif($project->status === 'Approved')
                                                <span class="btn btn-outline-success btn-sm">
                                                    Active
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Create New Proposal Button (Bottom) -->
                    @if($projects->count() < 3)
                        <div class="text-center mt-4">
                            <a href="{{ route('student.projects.proposal') }}" class="btn btn-success btn-lg">
                                Create Another Proposal
                            </a>
                            <p class="text-muted mt-2 small">
                                You can have a maximum of 3 projects total
                            </p>
                        </div>
                    @else
                        <div class="text-center mt-4">
                            <div class="alert alert-info">
                                <strong>Maximum Limit Reached:</strong> You have reached the maximum of 3 projects. 
                                Complete or have a supervisor delete an existing project to create new proposals.
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>