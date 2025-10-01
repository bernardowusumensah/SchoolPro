<div class="proposal-details">
    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted">Project Title</h6>
            <p class="fw-bold">{{ $project->title }}</p>
        </div>
        <div class="col-md-6">
            <h6 class="text-muted">Student</h6>
            <p>{{ $project->student->name }}</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted">Status</h6>
            <span class="badge 
                @if($project->status === 'approved') bg-success
                @elseif($project->status === 'rejected') bg-danger
                @elseif($project->status === 'revision_requested') bg-warning
                @else bg-primary
                @endif">
                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
            </span>
        </div>
        <div class="col-md-6">
            <h6 class="text-muted">Submitted</h6>
            <p>{{ $project->created_at->format('M d, Y g:i A') }}</p>
        </div>
    </div>

    @if($project->description)
    <div class="mb-3">
        <h6 class="text-muted">Description</h6>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($project->description)) !!}
        </div>
    </div>
    @endif

    @if($project->objectives)
    <div class="mb-3">
        <h6 class="text-muted">Objectives</h6>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($project->objectives)) !!}
        </div>
    </div>
    @endif

    @if($project->scope)
    <div class="mb-3">
        <h6 class="text-muted">Scope</h6>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($project->scope)) !!}
        </div>
    </div>
    @endif

    @if($project->deliverables)
    <div class="mb-3">
        <h6 class="text-muted">Deliverables</h6>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($project->deliverables)) !!}
        </div>
    </div>
    @endif

    @if($project->timeline)
    <div class="mb-3">
        <h6 class="text-muted">Timeline</h6>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($project->timeline)) !!}
        </div>
    </div>
    @endif

    @if($project->resources)
    <div class="mb-3">
        <h6 class="text-muted">Resources Required</h6>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($project->resources)) !!}
        </div>
    </div>
    @endif

    @if($project->document_path)
    <div class="mb-3">
        <h6 class="text-muted">Attached Document</h6>
        <a href="{{ Storage::url($project->document_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-download me-2"></i>Download Document
        </a>
    </div>
    @endif

    @if($project->approval_comments || $project->rejection_reason)
    <div class="mb-3">
        <h6 class="text-muted">
            @if($project->status === 'approved') Approval Comments
            @elseif($project->status === 'rejected') Rejection Reason
            @else Review Comments
            @endif
        </h6>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($project->approval_comments ?? $project->rejection_reason)) !!}
        </div>
        @if($project->reviewed_at)
        <small class="text-muted">
            Reviewed on {{ $project->reviewed_at->format('M d, Y g:i A') }}
            @if($project->supervisor) by {{ $project->supervisor->name }} @endif
        </small>
        @endif
    </div>
    @endif

    <div class="mt-4 pt-3 border-top">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <strong>Created:</strong> {{ $project->created_at->format('M d, Y g:i A') }}
                </small>
            </div>
            <div class="col-md-6">
                <small class="text-muted">
                    <strong>Last Updated:</strong> {{ $project->updated_at->format('M d, Y g:i A') }}
                </small>
            </div>
        </div>
    </div>
</div>