<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
     <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard/student">
                <i class="fas fa-graduation-cap me-2"></i>SchoolPro - Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="/dashboard/student">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
                <a class="nav-link text-white" href="{{ route('student.projects.proposal') }}">
                    <i class="fas fa-plus me-1"></i>New Proposal
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-primary">
                        <i class="fas fa-project-diagram me-2"></i>My Projects
                    </h2>
                    <a href="{{ route('student.projects.proposal') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Create New Proposal
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($projects->isEmpty())
                    <!-- No Projects State -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Projects Yet</h4>
                            <p class="text-muted mb-4">
                                You haven't created any project proposals yet. Get started by creating your first proposal!
                            </p>
                            <a href="{{ route('student.projects.proposal') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Create Your First Proposal
                            </a>
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
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                    @break
                                                @case('Approved')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Approved
                                                    </span>
                                                    @break
                                                @case('Rejected')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Rejected
                                                    </span>
                                                    @break
                                                @case('In Progress')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-cog fa-spin me-1"></i>In Progress
                                                    </span>
                                                    @break
                                                @case('Completed')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-flag me-1"></i>Completed
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
                                                <i class="fas fa-user-tie me-1"></i>
                                                <strong>Supervisor:</strong> {{ $project->supervisor->name ?? 'Not assigned' }}
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <strong>Submitted:</strong> {{ $project->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-white border-top-0">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('student.projects.show', $project->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            
                                            @if(in_array($project->status, ['Pending', 'Rejected']))
                                                <a href="{{ route('student.projects.editProject', $project->id) }}" 
                                                   class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                            @elseif($project->status === 'Approved')
                                                <span class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-check me-1"></i>Active
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Create New Proposal Button (Bottom) -->
                    <div class="text-center mt-4">
                        <a href="{{ route('student.projects.proposal') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i>Create Another Proposal
                        </a>
                        <p class="text-muted mt-2 small">
                            You can submit up to 3 pending proposals at once
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>