@extends('layouts.student')

@section('title', 'My Grades')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">My Grades</h1>
            <p class="small text-muted">View your project grades and academic performance</p>
        </div>
    </div>

    <!-- Grade Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary bg-opacity-10 border-primary">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 text-primary">{{ $stats['total_projects'] }}</h4>
                    <p class="small mb-0 fw-bold text-primary">Total Projects</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success bg-opacity-10 border-success">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 text-success">{{ $stats['completed_projects'] }}</h4>
                    <p class="small mb-0 fw-bold text-success">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info bg-opacity-10 border-info">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 text-info">{{ $stats['average_grade'] }}%</h4>
                    <p class="small mb-0 fw-bold text-info">Average Grade</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning bg-opacity-10 border-warning">
                <div class="card-body p-3 text-center">
                    <h4 class="mb-0 text-warning">{{ $stats['highest_grade'] }}%</h4>
                    <p class="small mb-0 fw-bold text-warning">Highest Grade</p>
                </div>
            </div>
        </div>
    </div>

    @if($projects->count() > 0)
        <!-- Projects with Grades -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Project Grades</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Project</th>
                                <th>Deliverable</th>
                                <th>Submitted</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                @foreach($project->deliverables as $deliverable)
                                    @foreach($deliverable->submissions as $submission)
                                        <tr>
                                            <td>
                                                <div>
                                                    <p class="mb-0 fw-bold">{{ $project->title }}</p>
                                                    <p class="small text-muted mb-0">{{ $project->category }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $deliverable->title }}</p>
                                                <span class="badge bg-secondary small">{{ $deliverable->getTypeDisplayName() }}</span>
                                            </td>
                                            <td>
                                                <p class="small mb-0">{{ $submission->submitted_at->format('M j, Y') }}</p>
                                                <p class="small text-muted mb-0">{{ $submission->submitted_at->format('g:i A') }}</p>
                                            </td>
                                            <td>
                                                @if($submission->grade !== null)
                                                    <div class="d-flex align-items-center">
                                                        <h5 class="mb-0 me-2 text-{{ $submission->grade >= 70 ? 'success' : ($submission->grade >= 60 ? 'warning' : 'danger') }}">
                                                            {{ $submission->grade }}%
                                                        </h5>
                                                        @if($project->final_grade && $project->status === 'Completed')
                                                            <span class="badge bg-success small">Final</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">Not graded</span>
                                                @endif
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
                                                @if($submission->teacher_feedback)
                                                    <button class="btn btn-outline-primary btn-sm" type="button" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#feedback-{{ $submission->id }}" 
                                                            aria-expanded="false">
                                                        View Feedback
                                                    </button>
                                                    <div class="collapse mt-2" id="feedback-{{ $submission->id }}">
                                                        <div class="card card-body">
                                                            <p class="small mb-0">{{ $submission->teacher_feedback }}</p>
                                                            @if($submission->reviewed_at)
                                                                <p class="small text-muted mb-0 mt-2">
                                                                    Reviewed: {{ $submission->reviewed_at->format('M j, Y g:i A') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">No feedback</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- No Grades Yet -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <h5 class="text-muted">No Grades Available</h5>
                <p class="text-muted">You don't have any graded projects yet. Complete and submit your deliverables to see your grades here.</p>
                <a href="{{ route('student.deliverables.index') }}" class="btn btn-primary">
                    View My Deliverables
                </a>
            </div>
        </div>
    @endif

    <!-- Back to Dashboard -->
    <div class="mt-4">
        <a href="{{ route('dashboard.student') }}" class="btn btn-outline-secondary">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection