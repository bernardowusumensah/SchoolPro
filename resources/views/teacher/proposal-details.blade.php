@extends('layouts.teacher')

@section('title', 'Proposal Details')

@section('content')
<!-- Header -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1 text-gray-800">{{ $project->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.teacher') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.proposals.index') }}">Proposals</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('teacher.proposals.index') }}" class="btn btn-outline-secondary">
                ← Back to Proposals
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Overview Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="card-title mb-1">{{ $project->title }}</h5>
                        <p class="text-muted small mb-0">
                            Submitted {{ $project->created_at->diffForHumans() }}
                            @if($project->resubmitted_at)
                                <br><span class="text-info"><i class="fas fa-redo-alt"></i> Resubmitted {{ $project->resubmitted_at->diffForHumans() }}</span>
                            @endif
                        </p>
                    </div>
                    <span class="badge 
                        @if($project->status === 'Approved') bg-success
                        @elseif($project->status === 'Rejected') bg-danger
                        @elseif($project->status === 'Needs Revision') bg-warning text-dark
                        @else bg-primary
                        @endif fs-6 px-3 py-2">
                        {{ $project->status }}
                    </span>
                </div>

                @if($project->resubmitted_at && $project->rejection_reason && $project->status === 'Pending')
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-info-circle"></i> Your Previous Feedback
                    </h6>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $project->rejection_reason }}</p>
                    <small class="text-muted d-block mt-2">
                        Student has resubmitted this proposal. Review the updated content to see if your concerns were addressed.
                    </small>
                </div>
                @endif

                @if($project->description)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">
                        Description
                    </h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $project->description }}</p>
                    </div>
                </div>
                @endif

                @if($project->objectives)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">
                        Objectives
                    </h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $project->objectives }}</p>
                    </div>
                </div>
                @endif

                @if($project->scope)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">
                        Scope
                    </h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $project->scope }}</p>
                    </div>
                </div>
                @endif

                @if($project->deliverables)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">
                        Deliverables
                    </h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $project->deliverables }}</p>
                    </div>
                </div>
                @endif

                @if($project->timeline)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">
                        Timeline
                    </h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $project->timeline }}</p>
                    </div>
                </div>
                @endif

                @if($project->resources)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">
                        Resources Required
                    </h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $project->resources }}</p>
                    </div>
                </div>
                @endif

                @if($project->document_path)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">
                        Attached Document
                    </h6>
                    <a href="{{ Storage::url($project->document_path) }}" target="_blank" class="btn btn-outline-primary">
                        Download Proposal Document
                    </a>
                </div>
                @endif

                @if($project->supervisor_feedback || $project->rejection_reason)
                <div class="alert alert-{{ $project->status === 'Approved' ? 'success' : ($project->status === 'Rejected' ? 'danger' : 'warning') }} mb-0">
                    <h6 class="alert-heading mb-2">
                        @if($project->status === 'Approved') 
                            Approval Comments
                        @elseif($project->status === 'Rejected') 
                            Rejection Reason
                        @else 
                            Review Comments
                        @endif
                    </h6>
                    <p class="mb-2" style="white-space: pre-wrap;">{{ $project->supervisor_feedback ?? $project->rejection_reason }}</p>
                    @if($project->reviewed_at)
                    <small>
                        <strong>Reviewed:</strong> {{ $project->reviewed_at->format('M d, Y g:i A') }}
                        @if($project->supervisor) by {{ $project->supervisor->name }} @endif
                    </small>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Student Info Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-muted small fw-bold mb-3">Student Information</h6>
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <span class="fw-bold fs-5">{{ substr($project->student->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="fw-bold">{{ $project->student->name }}</div>
                        <small class="text-muted">{{ $project->student->email }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Meta Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-muted small fw-bold mb-3">Project Details</h6>
                
                <div class="mb-3">
                    <div class="small text-muted mb-1">Status</div>
                    <span class="badge 
                        @if($project->status === 'Approved') bg-success
                        @elseif($project->status === 'Rejected') bg-danger
                        @elseif($project->status === 'Needs Revision') bg-warning text-dark
                        @else bg-primary
                        @endif">
                        {{ $project->status }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="small text-muted mb-1">Submitted</div>
                    <div class="small">{{ $project->created_at->format('M d, Y') }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $project->created_at->format('g:i A') }}</div>
                </div>

                <div class="mb-3">
                    <div class="small text-muted mb-1">Last Updated</div>
                    <div class="small">{{ $project->updated_at->format('M d, Y') }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $project->updated_at->diffForHumans() }}</div>
                </div>

                @if($project->reviewed_at)
                <div class="mb-0">
                    <div class="small text-muted mb-1">Reviewed</div>
                    <div class="small">{{ $project->reviewed_at->format('M d, Y') }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $project->reviewed_at->format('g:i A') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection