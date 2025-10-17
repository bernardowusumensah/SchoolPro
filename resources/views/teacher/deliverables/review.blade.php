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
            <li class="breadcrumb-item active">Review Submission</li>
        </ol>
    </nav>

    <!-- Submission Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">{{ $submission->deliverable->title }}</h4>
                            <p class="text-muted mb-1">
                                Student: <strong>{{ $submission->student->name }}</strong>
                            </p>
                            <p class="text-muted small mb-2">
                                Project: {{ $submission->deliverable->project->title }}
                            </p>
                            <span class="badge bg-primary me-2">Version {{ $submission->version }}</span>
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
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">
                                Submitted: {{ $submission->submitted_at->format('M j, Y g:i A') }}
                            </div>
                            @if($submission->due_date && $submission->submitted_at > $submission->due_date)
                                <div class="small text-danger">
                                    <strong>Late Submission</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Submission Details -->
        <div class="col-lg-8">
            <!-- Files -->
            @if($submission->file_paths && count($submission->file_paths) > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Submitted Files</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($submission->file_paths as $index => $fileInfo)
                                @php
                                    $fileName = is_array($fileInfo) ? ($fileInfo['original_name'] ?? basename($fileInfo['path'] ?? '')) : (is_object($fileInfo) ? ($fileInfo->original_name ?? basename($fileInfo->path ?? '')) : basename($fileInfo));
                                    $fileSize = is_array($fileInfo) ? ($fileInfo['size'] ?? 'Unknown') : (is_object($fileInfo) ? ($fileInfo->size ?? 'Unknown') : 'Unknown');
                                    $filePath = is_array($fileInfo) ? ($fileInfo['path'] ?? $fileInfo) : (is_object($fileInfo) ? ($fileInfo->path ?? $fileInfo) : $fileInfo);
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark fs-6">File {{ $index + 1 }}</span>
                                        </div>
                                        <h6 class="small mb-1">{{ $fileName }}</h6>
                                        <p class="text-muted small mb-2">{{ is_numeric($fileSize) ? number_format($fileSize / 1024, 1) . ' KB' : $fileSize }}</p>
                                        <a href="{{ route('teacher.deliverables.download', ['submission' => $submission->id, 'fileIndex' => $index]) }}" 
                                           class="btn btn-primary btn-sm">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Comments -->
            @if($submission->comments)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Student Comments</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $submission->comments }}</p>
                    </div>
                </div>
            @endif

            <!-- Current Review -->
            @if($submission->teacher_feedback || $submission->grade !== null)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="mb-0 text-info">Current Review</h6>
                    </div>
                    <div class="card-body">
                        @if($submission->grade !== null)
                            <div class="mb-3">
                                <h6 class="text-muted small">GRADE</h6>
                                <h4 class="mb-0 text-success">{{ $submission->grade }}%</h4>
                            </div>
                        @endif
                        
                        @if($submission->teacher_feedback)
                            <div class="mb-3">
                                <h6 class="text-muted small">FEEDBACK</h6>
                                <p class="mb-0">{{ $submission->teacher_feedback }}</p>
                            </div>
                        @endif
                        
                        @if($submission->reviewed_at)
                            <div class="small text-muted">
                                Reviewed on: {{ $submission->reviewed_at->format('M j, Y g:i A') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Review Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">{{ $submission->teacher_feedback ? 'Update Review' : 'Provide Review' }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.deliverables.review.store', $submission) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="grade" class="form-label">Grade (%)</label>
                                <input type="number" 
                                       name="grade" 
                                       id="grade" 
                                       class="form-control" 
                                       min="0" 
                                       max="100" 
                                       step="0.1"
                                       value="{{ old('grade', $submission->grade) }}">
                                @error('grade')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-8 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="reviewed" {{ old('status', $submission->status) === 'reviewed' ? 'selected' : '' }}>
                                        Reviewed
                                    </option>
                                    <option value="approved" {{ old('status', $submission->status) === 'approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>
                                    <option value="rejected" {{ old('status', $submission->status) === 'rejected' ? 'selected' : '' }}>
                                        Rejected (Needs Revision)
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="teacher_feedback" class="form-label">Feedback</label>
                            <textarea name="teacher_feedback" 
                                      id="teacher_feedback" 
                                      class="form-control" 
                                      rows="5" 
                                      placeholder="Provide detailed feedback about the submission...">{{ old('teacher_feedback', $submission->teacher_feedback) }}</textarea>
                            @error('teacher_feedback')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.deliverables.index') }}" 
                               class="btn btn-secondary">
                                Back to Deliverables
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ $submission->teacher_feedback ? 'Update Review' : 'Submit Review' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Deliverable Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Deliverable Information</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <strong>Title:</strong><br>
                            {{ $submission->deliverable->title }}
                        </div>
                        
                        <div class="mb-2">
                            <strong>Type:</strong><br>
                            <span class="badge bg-primary">
                                {{ ucfirst(str_replace('_', ' ', $submission->deliverable->type)) }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <strong>Due Date:</strong><br>
                            {{ $submission->deliverable->due_date->format('M j, Y') }}
                        </div>

                        <div class="mb-2">
                            <strong>Weight:</strong><br>
                            {{ $submission->deliverable->weight_percentage }}%
                        </div>

                        @if($submission->deliverable->description)
                            <div class="mb-2">
                                <strong>Description:</strong><br>
                                {{ $submission->deliverable->description }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Student Info -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <strong>Name:</strong><br>
                            {{ $submission->student->name }}
                        </div>
                        
                        <div class="mb-2">
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $submission->student->email }}" class="text-decoration-none">
                                {{ $submission->student->email }}
                            </a>
                        </div>

                        <div class="mb-2">
                            <strong>Project:</strong><br>
                            {{ $submission->deliverable->project->title }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection