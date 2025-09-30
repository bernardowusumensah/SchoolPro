@extends('layouts.student')

@section('title', 'Edit Project Proposal')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-warning mr-2"></i>Edit Project Proposal
        </h1>
        <div>
            <a href="{{ route('student.projects.show', $project->id) }}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-eye mr-1"></i>View Current
            </a>
            <a href="{{ route('dashboard.student') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="row">
        <div class="col-12">
            @if($project->status === 'Pending')
                <div class="alert alert-warning border-0 shadow-sm">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-lg text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading mb-1">Editing Pending Proposal</h6>
                            <p class="mb-0">Your proposal is currently under review. Making changes will reset the review status to pending.</p>
                        </div>
                    </div>
                </div>
            @elseif($project->status === 'Rejected')
                <div class="alert alert-danger border-0 shadow-sm">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle fa-lg text-danger"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading mb-1">Revision Required</h6>
                            <p class="mb-0">Your supervisor has requested changes to your proposal. Please address their feedback and resubmit.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-warning py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-edit mr-2"></i>Edit Project Proposal
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.projects.update') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- Project Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold text-gray-800">
                                <i class="fas fa-heading text-primary mr-2"></i>Project Title *
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $project->title) }}" 
                                   placeholder="Enter a clear and descriptive title for your project"
                                   maxlength="255"
                                   required>
                            <div class="form-text">
                                <i class="fas fa-lightbulb text-warning mr-1"></i>
                                Choose a title that clearly represents your project idea
                            </div>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Project Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-gray-800">
                                <i class="fas fa-align-left text-primary mr-2"></i>Project Description *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="8"
                                      placeholder="Provide a detailed description of your project including objectives, methodology, expected outcomes, and technologies you plan to use..."
                                      minlength="100"
                                      maxlength="5000"
                                      required>{{ old('description', $project->description) }}</textarea>
                            <div class="form-text d-flex justify-content-between">
                                <span>
                                    <i class="fas fa-info-circle text-info mr-1"></i>
                                    Minimum 100 characters required. Address any supervisor feedback.
                                </span>
                                <span id="charCount" class="text-muted">0 / 5000</span>
                            </div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Supervisor Selection -->
                        <div class="mb-4">
                            <label for="supervisor_id" class="form-label fw-bold text-gray-800">
                                <i class="fas fa-user-tie text-primary mr-2"></i>Preferred Supervisor *
                            </label>
                            <select class="form-select @error('supervisor_id') is-invalid @enderror" 
                                    id="supervisor_id" 
                                    name="supervisor_id" 
                                    required>
                                <option value="">-- Select a Supervisor --</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" 
                                            {{ old('supervisor_id', $project->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="fas fa-user-graduate text-success mr-1"></i>
                                You can change your supervisor if needed
                            </div>
                            @error('supervisor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Change Summary (Optional) -->
                        <div class="mb-4">
                            <label for="change_summary" class="form-label fw-bold text-gray-800">
                                <i class="fas fa-list-ul text-info mr-2"></i>Summary of Changes (Optional)
                            </label>
                            <textarea class="form-control" 
                                      id="change_summary" 
                                      name="change_summary" 
                                      rows="3"
                                      placeholder="Briefly describe what changes you've made to address feedback or improve your proposal..."
                                      maxlength="1000">{{ old('change_summary') }}</textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info mr-1"></i>
                                Help your supervisor understand what you've changed (optional but recommended)
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="text-muted">
                                        <i class="fas fa-asterisk text-danger fa-xs mr-1"></i>
                                        <small>All fields marked with * are required</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('student.projects.show', $project->id) }}" 
                                           class="btn btn-outline-secondary me-2">
                                            <i class="fas fa-times mr-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save mr-1"></i>Update Proposal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow border-0">
                <div class="card-header bg-info py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle mr-2"></i>Current Proposal Info
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-gray-800">Status:</strong><br>
                        @if($project->status === 'Pending')
                            <span class="badge bg-warning text-dark">Pending Review</span>
                        @elseif($project->status === 'Rejected')
                            <span class="badge bg-danger">Needs Revision</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong class="text-gray-800">Current Supervisor:</strong><br>
                        <span class="text-muted">{{ $project->supervisor->name ?? 'Not assigned' }}</span>
                    </div>

                    <div class="mb-3">
                        <strong class="text-gray-800">Last Updated:</strong><br>
                        <span class="text-muted">{{ $project->updated_at->format('M j, Y \a\t g:i A') }}</span>
                    </div>

                    <div class="mb-3">
                        <strong class="text-gray-800">Originally Submitted:</strong><br>
                        <span class="text-muted">{{ $project->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-success py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-lightbulb mr-2"></i>Editing Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <small>Be specific about changes made</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <small>Address all supervisor feedback</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <small>Ensure description is detailed</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <small>Verify supervisor alignment</small>
                        </li>
                    </ul>
                    
                    <div class="alert alert-warning alert-sm mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <small>Saving changes will reset status to "Pending Review"</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    const descriptionTextarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const currentLength = descriptionTextarea.value.length;
        charCount.textContent = currentLength + ' / 5000';
        
        if (currentLength < 100) {
            charCount.className = 'text-danger';
        } else if (currentLength > 4500) {
            charCount.className = 'text-warning';
        } else {
            charCount.className = 'text-muted';
        }
    }
    
    descriptionTextarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
    
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endpush
@endsection