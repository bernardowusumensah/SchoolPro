@extends('layouts.teacher')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.teacher') }}" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.deliverables.index') }}" class="text-decoration-none">Deliverables</a>
            </li>
            <li class="breadcrumb-item active">{{ $project->title }}</li>
        </ol>
    </nav>

    <!-- Project Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">{{ $project->title }}</h4>
                            <p class="text-muted mb-2">
                                Student: <strong>{{ $project->student->name }}</strong>
                            </p>
                            <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'approved' ? 'primary' : 'warning') }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>
                        <div class="text-end">
                            <p class="small text-muted mb-0">
                                Created: {{ $project->created_at->format('M j, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deliverables -->
    <div class="row">
        <div class="col-lg-8">
            @if($deliverables->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Project Deliverable</h5>
                    </div>
                    <div class="card-body">
                        @foreach($deliverables as $deliverable)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $deliverable->title }}</h6>
                                        <p class="text-muted small mb-1">{{ $deliverable->description }}</p>
                                        <span class="badge bg-{{ $deliverable->type === 'final_project' ? 'success' : 'primary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $deliverable->type)) }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="small text-muted">
                                            Due: {{ $deliverable->due_date->format('M j, Y') }}
                                        </div>
                                        <div class="small text-muted">
                                            Weight: {{ $deliverable->weight_percentage }}%
                                        </div>
                                    </div>
                                </div>

                                @if($deliverable->requirements)
                                    <div class="small bg-light p-2 rounded mb-2">
                                        <strong>Requirements:</strong> {{ $deliverable->requirements }}
                                    </div>
                                @endif

                                <!-- Submissions -->
                                @if($deliverable->submissions->count() > 0)
                                    <div class="mt-3">
                                        <h6 class="small text-muted mb-2">SUBMISSIONS</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Student</th>
                                                        <th>Version</th>
                                                        <th>Submitted</th>
                                                        <th>Status</th>
                                                        <th>Grade</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($deliverable->submissions as $submission)
                                                        <tr>
                                                            <td class="small">{{ $submission->student->name }}</td>
                                                            <td><span class="badge bg-light text-dark">v{{ $submission->version }}</span></td>
                                                            <td class="small">{{ $submission->submitted_at->format('M j, g:i A') }}</td>
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
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{ route('teacher.deliverables.review', $submission) }}" 
                                                                       class="btn btn-outline-primary btn-sm">
                                                                        Review
                                                                    </a>
                                                                    @if($submission->file_paths && count($submission->file_paths) > 0)
                                                                        @foreach($submission->file_paths as $index => $filePath)
                                                                            <a href="{{ route('teacher.deliverables.download', ['submission' => $submission->id, 'fileIndex' => $index]) }}" 
                                                                               class="btn btn-outline-secondary btn-sm">
                                                                                File {{ $index + 1 }}
                                                                            </a>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3 bg-light rounded">
                                        <p class="text-muted small mb-0">No submissions yet</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <h5 class="text-muted">No Deliverables</h5>
                        <p class="text-muted small">
                            @if($project->status === 'approved')
                                Deliverable will be created automatically
                            @else
                                Deliverable will be created when project is approved
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Project Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Project Information</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <strong>Student:</strong><br>
                            {{ $project->student->name }}<br>
                            <a href="mailto:{{ $project->student->email }}" class="text-decoration-none">
                                {{ $project->student->email }}
                            </a>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'approved' ? 'primary' : 'warning') }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <strong>Created:</strong><br>
                            {{ $project->created_at->format('M j, Y g:i A') }}
                        </div>

                        @if($project->description)
                            <div class="mb-2">
                                <strong>Description:</strong><br>
                                {{ Str::limit($project->description, 150) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.deliverables.index') }}" 
                           class="btn btn-outline-secondary btn-sm">
                            Back to All Deliverables
                        </a>
                        <a href="{{ route('teacher.logs.index') }}?project={{ $project->id }}" 
                           class="btn btn-outline-info btn-sm">
                            View Project Logs
                        </a>
                        @if($project->proposal_file_path)
                            <a href="{{ Storage::url($project->proposal_file_path) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                View Proposal
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection