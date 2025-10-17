@extends('layouts.teacher')

@section('title', 'Deliverables Management')

@section('content')
<!-- Header -->
<div class="card shadow-lg mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Deliverables Management</h1>
                <p class="small text-muted">Each approved project gets one automatically-created deliverable • Review submissions and manage project progress</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="small text-muted">
                    <span class="fw-bold">{{ $overdueDeliverables->count() }}</span> overdue deliverables
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary bg-opacity-10 border-primary">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                    </div>
                    <div>
                        <p class="small mb-0 fw-bold text-primary">Total Projects</p>
                        <h4 class="mb-0 text-primary">{{ $projects->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-4">
        <div class="card bg-success bg-opacity-10 border-success">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                    </div>
                    <div>
                        <p class="small mb-0 fw-bold text-success">Total Deliverables</p>
                        <h4 class="mb-0 text-success">{{ $projects->sum(fn($p) => $p->deliverables->count()) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-danger bg-opacity-10 border-danger">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                    </div>
                    <div>
                        <p class="small mb-0 fw-bold text-danger">Overdue</p>
                        <h4 class="mb-0 text-danger">{{ $overdueDeliverables->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Projects Overview -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">Project Management Overview</h5>
                <p class="small text-muted mb-0">Single deliverable per project • Final submission and grading completes the project</p>
            </div>
            <div class="card-body">
                @if($projects->isEmpty())
                    <div class="text-center py-4">
                        <h5 class="text-muted">No projects assigned</h5>
                        <p class="small text-muted">Students will appear here once projects are assigned.</p>
                    </div>
                @else
                    <div class="accordion" id="projectsAccordion">
                        @foreach($projects as $project)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#project-{{ $project->id }}">
                                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                            <div>
                                                <p class="mb-0 fw-bold">{{ $project->title }}</p>
                                                <p class="small text-muted mb-0">Student: {{ $project->student->name }}</p>
                                            </div>
                                                            <div class="text-end">
                                                <span class="badge bg-{{ $project->status === 'active' ? 'success' : 'secondary' }} me-2">
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                                @if($project->deliverables->count() > 0)
                                                    <span class="badge bg-success">
                                                        Deliverable Created
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        Pending Setup
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="project-{{ $project->id }}" class="accordion-collapse collapse" 
                                     data-bs-parent="#projectsAccordion">
                                    <div class="accordion-body">
                                        @if($project->deliverables->isEmpty())
                                            <div class="text-center py-3">
                                                <p class="small text-muted mb-2">
                                                    @if($project->status === 'approved')
                                                        Deliverable will be created automatically
                                                    @else
                                                        Deliverable will be created when project is approved
                                                    @endif
                                                </p>
                                            </div>
                                        @else
                                            @foreach($project->deliverables as $deliverable)
                                                <div class="card bg-light border-0 mb-2">
                                                    <div class="card-body p-3">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-6">
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <span class="badge bg-{{ $deliverable->status === 'approved' ? 'success' : ($deliverable->status === 'submitted' ? 'warning' : 'secondary') }} small me-2">
                                                                        {{ ucfirst($deliverable->status) }}
                                                                    </span>
                                                                    @if($project->final_grade)
                                                                        <span class="badge bg-info small">Final Grade: {{ $project->final_grade }}%</span>
                                                                    @endif
                                                                </div>
                                                                <h6 class="mb-1">{{ $deliverable->title }}</h6>
                                                                <div class="d-flex gap-2 mb-2">
                                                                    <span class="badge bg-secondary small">{{ $deliverable->getTypeDisplayName() }}</span>
                                                                    <span class="badge bg-primary small">Weight: {{ $deliverable->weight_percentage }}%</span>
                                                                    @if($deliverable->isOverdue())
                                                                        <span class="badge bg-danger small">Overdue</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 text-center">
                                                                <p class="small mb-0 text-muted">Due Date</p>
                                                                <p class="mb-0 fw-bold">{{ $deliverable->due_date->format('M j, Y') }}</p>
                                                            </div>
                                                            <div class="col-md-3 text-end">
                                                                @if($deliverable->submissions->count() > 0)
                                                                    @php $latestSubmission = $deliverable->submissions->first(); @endphp
                                                                    @if($latestSubmission->grade)
                                                                        <div class="mb-1">
                                                                            <span class="badge bg-success">Graded: {{ $latestSubmission->grade }}%</span>
                                                                        </div>
                                                                    @else
                                                                        <div class="mb-1">
                                                                            <span class="badge bg-warning">Needs Review</span>
                                                                        </div>
                                                                    @endif
                                                                    <a href="{{ route('teacher.deliverables.review', $latestSubmission) }}" 
                                                                       class="btn btn-outline-primary btn-sm">
                                                                        View Submission
                                                                    </a>
                                                                @else
                                                                    <div class="mb-1">
                                                                        <span class="badge bg-secondary">No Submission</span>
                                                                    </div>
                                                                    <a href="{{ route('teacher.deliverables.project', $deliverable->project) }}" 
                                                                       class="btn btn-outline-secondary btn-sm">
                                                                        View Details
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Recent Activity -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">Recent Activity</h6>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @if($recentSubmissions->isEmpty())
                    <div class="text-center py-3">
                        <p class="small text-muted">No recent activity</p>
                    </div>
                @else
                    @foreach($recentSubmissions as $submission)
                        <div class="mb-3 pb-3 border-bottom">
                            <div>
                                <p class="small mb-0 fw-bold">{{ $submission->student->name }}</p>
                                <p class="small text-muted mb-0">submitted {{ $submission->deliverable->title }}</p>
                                <p class="small text-muted mb-0">{{ $submission->submitted_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.proposals.index') }}" class="btn btn-outline-primary btn-sm">
                        Manage Proposals
                    </a>
                    <a href="{{ route('teacher.logs.index') }}" class="btn btn-outline-secondary btn-sm">
                        View Logs
                    </a>
                    <div class="alert alert-info p-2 mb-0 small">
                        Deliverables are automatically created when projects are approved
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overdue Deliverables -->
@if($overdueDeliverables->count() > 0)
<div class="card shadow mt-4">
    <div class="card-header bg-danger bg-opacity-10">
        <h5 class="mb-0 text-danger">
            Overdue Deliverables ({{ $overdueDeliverables->count() }})
        </h5>
    </div>
    <div class="card-body">
        @foreach($overdueDeliverables as $deliverable)
            <div class="border border-danger bg-danger bg-opacity-10 rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-danger mb-1">{{ $deliverable->title }}</h6>
                        <div class="d-flex align-items-center small text-danger mb-2">
                            <i class="fas fa-user me-1"></i>
                            <span class="me-3">{{ $deliverable->project->student->name }}</span>
                            <i class="fas fa-calendar me-1"></i>
                            <span>Due: {{ $deliverable->due_date->format('M j, Y') }} ({{ $deliverable->getDaysOverdue() }} days ago)</span>
                        </div>
                        <p class="small text-muted mb-0">{{ $deliverable->description }}</p>
                    </div>
                    <a href="{{ route('teacher.deliverables.project', $deliverable->project) }}" 
                       class="btn btn-danger btn-sm">
                        View Project
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif


@endsection