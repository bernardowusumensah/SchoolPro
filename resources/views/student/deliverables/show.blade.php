@extends('layouts.student')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.student') }}" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('student.deliverables.index') }}" class="text-decoration-none">Deliverables</a>
            </li>
            <li class="breadcrumb-item active">{{ $deliverable->title }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="mb-1">{{ $deliverable->title }}</h4>
                    <p class="text-muted mb-0 small">
                        Project: <strong>{{ $deliverable->project->title }}</strong>
                    </p>
                </div>
                <div class="text-end">
                    @php
                        $daysLeft = now()->diffInDays($deliverable->due_date, false);
                        $isOverdue = $daysLeft < 0;
                        $badgeClass = $isOverdue ? 'bg-danger' : ($daysLeft <= 3 ? 'bg-warning' : 'bg-success');
                    @endphp
                    <span class="badge {{ $badgeClass }} mb-2">
                        @if($isOverdue)
                            {{ abs($daysLeft) }} days overdue
                        @else
                            {{ $daysLeft }} days left
                        @endif
                    </span>
                    <div class="small text-muted">
                        Due: {{ $deliverable->due_date->format('M j, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Deliverable Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Deliverable Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted small">DESCRIPTION</h6>
                        <p class="mb-0">{{ $deliverable->description }}</p>
                    </div>

                    @if($deliverable->requirements)
                    <div class="mb-3">
                        <h6 class="text-muted small">REQUIREMENTS</h6>
                        <div class="small">
                            {!! nl2br(e($deliverable->requirements)) !!}
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <h6 class="text-muted small">TYPE</h6>
                            <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $deliverable->type)) }}</span>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <h6 class="text-muted small">WEIGHT</h6>
                            <span class="fw-bold">{{ $deliverable->weight_percentage }}%</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <h6 class="text-muted small">ALLOWED FILE TYPES</h6>
                            <div class="small">
                                @foreach($deliverable->file_types_allowed as $type)
                                    <span class="badge bg-light text-dark me-1">.{{ $type }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <h6 class="text-muted small">MAX FILE SIZE</h6>
                            <span class="small">{{ $deliverable->max_file_size_mb }} MB</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submission History -->
            @if($submissions->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Submission History</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Version</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                <tr>
                                    <td>
                                        <span class="fw-bold">v{{ $submission->version }}</span>
                                    </td>
                                    <td class="small">
                                        {{ $submission->submitted_at->format('M j, Y g:i A') }}
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'submitted' => 'bg-primary',
                                                'late' => 'bg-warning',
                                                'reviewed' => 'bg-info',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger'
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusColors[$submission->status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($submission->grade !== null)
                                            <span class="fw-bold">{{ $submission->grade }}%</span>
                                        @else
                                            <span class="text-muted small">Not graded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->file_paths && count($submission->file_paths) > 0)
                                            <div class="btn-group btn-group-sm">
                                                @foreach($submission->file_paths as $index => $filePath)
                                                    <a href="{{ route('student.deliverables.download', ['submission' => $submission->id, 'fileIndex' => $index]) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        File {{ $index + 1 }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Teacher Feedback -->
            @php
                $latestGradedSubmission = $submissions->whereNotNull('teacher_feedback')->first();
            @endphp
            @if($latestGradedSubmission)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info bg-opacity-10">
                    <h6 class="mb-0 text-info">Teacher Review</h6>
                </div>
                <div class="card-body">
                    @if($latestGradedSubmission->grade !== null)
                        <div class="mb-3">
                            <h6 class="text-muted small">FINAL GRADE</h6>
                            <h4 class="mb-0 text-{{ $latestGradedSubmission->grade >= 70 ? 'success' : ($latestGradedSubmission->grade >= 60 ? 'warning' : 'danger') }}">
                                {{ $latestGradedSubmission->grade }}%
                            </h4>
                        </div>
                    @endif
                    
                    @if($latestGradedSubmission->teacher_feedback)
                        <div class="mb-3">
                            <h6 class="text-muted small">FEEDBACK</h6>
                            <p class="mb-0">{{ $latestGradedSubmission->teacher_feedback }}</p>
                        </div>
                    @endif
                    
                    @if($latestGradedSubmission->reviewed_at)
                        <div class="small text-muted">
                            Reviewed on: {{ $latestGradedSubmission->reviewed_at->format('M j, Y g:i A') }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Current Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Current Status</h6>
                </div>
                <div class="card-body text-center">
                    @if($submissions->where('status', 'submitted')->count() > 0)
                        <div class="mb-3">
                            <span class="badge bg-primary fs-6 px-3 py-2">Submitted</span>
                        </div>
                        <p class="small text-muted mb-0">
                            Your submission is under review
                        </p>
                    @else
                        <div class="mb-3">
                            <span class="badge bg-warning fs-6 px-3 py-2">Pending</span>
                        </div>
                        <p class="small text-muted mb-3">
                            No submission yet
                        </p>
                        <a href="{{ route('student.deliverables.submit', $deliverable) }}" 
                           class="btn btn-primary btn-sm">
                            Submit Now
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.deliverables.submit', $deliverable) }}" 
                           class="btn btn-primary btn-sm">
                            {{ $submissions->count() > 0 ? 'Resubmit' : 'Submit' }} Deliverable
                        </a>
                        <a href="{{ route('student.deliverables.index') }}" 
                           class="btn btn-outline-secondary btn-sm">
                            Back to Deliverables
                        </a>
                        <a href="{{ route('student.projects.show', $deliverable->project) }}" 
                           class="btn btn-outline-info btn-sm">
                            View Project
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection