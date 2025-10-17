@extends('layouts.student')

@section('title', 'My Deliverables')

@section('content')
<div class="card shadow-lg">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">My Deliverables</h1>
                <p class="small text-muted">Track and submit your project deliverables</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-3 small text-muted">
                    <span class="fw-bold">{{ $deliverables->where('status', 'pending')->count() }}</span> pending
                </div>
                <div class="small text-muted">
                    <span class="fw-bold">{{ $deliverables->where('status', 'submitted')->count() }}</span> submitted
                </div>
            </div>
        </div>

        @if($deliverables->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                </div>
                <h5 class="text-muted mb-2">No deliverables yet</h5>
                <p class="small text-muted">Your supervisor will create deliverables for your project.</p>
            </div>
        @else
            <!-- Overview Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning bg-opacity-10 border-warning">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                </div>
                                <div>
                                    <p class="small mb-0 fw-bold text-warning">Pending</p>
                                    <h4 class="mb-0 text-warning">{{ $deliverables->where('status', 'pending')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-primary bg-opacity-10 border-primary">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                </div>
                                <div>
                                    <p class="small mb-0 fw-bold text-primary">Submitted</p>
                                    <h4 class="mb-0 text-primary">{{ $deliverables->where('status', 'submitted')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-success bg-opacity-10 border-success">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                </div>
                                <div>
                                    <p class="small mb-0 fw-bold text-success">Approved</p>
                                    <h4 class="mb-0 text-success">{{ $deliverables->where('status', 'approved')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-danger bg-opacity-10 border-danger">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                </div>
                                <div>
                                    <p class="small mb-0 fw-bold text-danger">Overdue</p>
                                    <h4 class="mb-0 text-danger">{{ $deliverables->filter(fn($d) => $d->isOverdue())->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deliverables List -->
            <div class="row g-3">
                @foreach($deliverables as $deliverable)
                    <div class="col-12">
                        <div class="card border h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">{{ $deliverable->title }}</h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-{{ $deliverable->status === 'pending' ? 'warning' : ($deliverable->status === 'approved' ? 'success' : 'primary') }} me-2">
                                                {{ ucfirst($deliverable->status) }}
                                            </span>
                                            <span class="badge bg-secondary me-2">
                                                {{ $deliverable->getTypeDisplayName() }}
                                            </span>
                                            @if($deliverable->status === 'approved' && $deliverable->project->final_grade)
                                                <span class="badge bg-info">Final Grade: {{ $deliverable->project->final_grade }}%</span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-muted small mb-2">{{ $deliverable->description }}</p>
                                        
                                        <div class="d-flex flex-wrap small text-muted mb-2">
                                            <div class="me-3">
                                                Due: {{ $deliverable->due_date->format('M j, Y g:i A') }}
                                            </div>
                                            <div class="me-3">
                                                Weight: {{ $deliverable->weight_percentage }}%
                                            </div>
                                            @if($deliverable->isOverdue())
                                                <div class="text-danger">
                                                    Overdue by {{ $deliverable->getDaysOverdue() }} day(s)
                                                </div>
                                            @else
                                                <div>
                                                    {{ $deliverable->getDaysUntilDue() }} day(s) remaining
                                                </div>
                                            @endif
                                        </div>

                                        @if($deliverable->latestSubmission)
                                            <div class="card bg-light mt-2">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <p class="small mb-0 fw-bold">Latest Submission</p>
                                                            <p class="small text-muted mb-0">
                                                                Submitted: {{ $deliverable->latestSubmission->submitted_at->format('M j, Y g:i A') }}
                                                                @if($deliverable->latestSubmission->grade)
                                                                    • Grade: {{ $deliverable->latestSubmission->grade }}% ({{ $deliverable->latestSubmission->getGradeLetter() }})
                                                                @endif
                                                            </p>
                                                        </div>
                                                        @if($deliverable->latestSubmission->teacher_feedback)
                                                            <button onclick="toggleFeedback('feedback-{{ $deliverable->id }}')" class="btn btn-sm btn-outline-primary">
                                                                View Feedback
                                                            </button>
                                                        @endif
                                                    </div>
                                                    @if($deliverable->latestSubmission->teacher_feedback)
                                                        <div id="feedback-{{ $deliverable->id }}" class="d-none mt-2 p-2 bg-white rounded border">
                                                            <p class="small fw-bold mb-1">Teacher Feedback:</p>
                                                            <p class="small mb-0">{{ $deliverable->latestSubmission->teacher_feedback }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ms-3 d-flex flex-column">
                                        <a href="{{ route('student.deliverables.show', $deliverable) }}" 
                                           class="btn btn-primary btn-sm mb-2">
                                            View Details
                                        </a>

                                        @if($deliverable->status === 'pending' || ($deliverable->status === 'rejected' && $deliverable->due_date > now()))
                                            <a href="{{ route('student.deliverables.submit', $deliverable) }}" 
                                               class="btn btn-success btn-sm">
                                                {{ $deliverable->latestSubmission ? 'Resubmit' : 'Submit' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function toggleFeedback(id) {
    const element = document.getElementById(id);
    element.classList.toggle('d-none');
}
</script>
@endsection