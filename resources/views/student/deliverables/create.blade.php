@extends('layouts.student')

@section('title', 'Submit Deliverable')

@section('content')
<div class="card shadow-lg">
    <div class="card-body p-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.deliverables.index') }}" class="text-decoration-none">
                            Deliverables
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Submit</li>
                </ol>
            </nav>

            <h1 class="h3 mb-1 text-gray-800">Submit Deliverable</h1>
            <h2 class="h5 text-muted">{{ $deliverable->title }}</h2>
        </div>

        <!-- Deliverable Information -->
        <div class="card bg-primary bg-opacity-10 border-primary mb-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h3 class="h6 fw-bold text-primary mb-2">Deliverable Details</h3>
                        <p class="small text-primary mb-3">{{ $deliverable->description }}</p>
                        
                        <div class="row g-3 small">
                            <div class="col-md-4">
                                <span class="fw-bold text-primary">Due Date:</span>
                                <p class="text-primary mb-0">{{ $deliverable->due_date->format('M j, Y g:i A') }}</p>
                            </div>
                            <div class="col-md-4">
                                <span class="fw-bold text-primary">Weight:</span>
                                <p class="text-primary mb-0">{{ $deliverable->weight_percentage }}%</p>
                            </div>
                            <div class="col-md-4">
                                <span class="fw-bold text-primary">Type:</span>
                                <p class="text-primary mb-0">{{ $deliverable->getTypeDisplayName() }}</p>
                            </div>
                        </div>

                        @if($deliverable->requirements)
                            <div class="mt-3">
                                <span class="fw-bold text-primary small">Requirements:</span>
                                <p class="text-primary mt-1 small">{{ $deliverable->requirements }}</p>
                            </div>
                        @endif

                        <div class="mt-3">
                            <span class="fw-bold text-primary small">Allowed File Types:</span>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @foreach($deliverable->file_types_allowed as $type)
                                    <span class="badge bg-primary bg-opacity-25 text-primary">.{{ $type }}</span>
                                @endforeach
                            </div>
                            <p class="text-primary small mt-1 mb-0">Maximum file size: {{ $deliverable->max_file_size_mb }}MB</p>
                        </div>
                    </div>

                    <div class="ms-3">
                        @if($deliverable->isOverdue())
                            <div class="card bg-danger bg-opacity-10 border-danger">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="text-danger fw-bold small mb-0">Overdue</p>
                                            <p class="text-danger small mb-0">{{ $deliverable->getDaysOverdue() }} day(s) late</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card bg-success bg-opacity-10 border-success">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="text-success fw-bold small mb-0">On Time</p>
                                            <p class="text-success small mb-0">{{ $deliverable->getDaysUntilDue() }} day(s) remaining</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous Submissions (if any) -->
        @if($deliverable->submissions->count() > 0)
            <div class="card bg-light mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Previous Submissions</h5>
                </div>
                <div class="card-body">
                    @foreach($deliverable->submissions as $submission)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <p class="mb-0 fw-bold small">Version {{ $submission->version }}</p>
                                <p class="small text-muted mb-0">
                                    Submitted: {{ $submission->submitted_at->format('M j, Y g:i A') }}
                                    @if($submission->grade)
                                        • Grade: {{ $submission->grade }}% ({{ $submission->getGradeLetter() }})
                                    @endif
                                </p>
                                @if($submission->teacher_feedback)
                                    <p class="small text-muted mb-0">Feedback: {{ $submission->teacher_feedback }}</p>
                                @endif
                            </div>
                            <span class="badge bg-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Submission Form -->
        <form action="{{ route('student.deliverables.store', $deliverable) }}" method="POST" enctype="multipart/form-data" id="submissionForm">
            @csrf

            <!-- File Upload Area -->
            <div class="mb-4">
                <label class="form-label fw-bold">Files <span class="text-danger">*</span></label>
                <div class="card border-2 border-dashed border-primary bg-primary bg-opacity-5" 
                     id="dropZone" 
                     style="min-height: 200px; cursor: pointer;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">
                        <h5 class="text-primary mb-2">Drop files here or click to browse</h5>
                        <p class="text-muted small mb-3">
                            Allowed: {{ implode(', ', array_map(fn($t) => '.' . $t, $deliverable->file_types_allowed)) }}
                            <br>Maximum size: {{ $deliverable->max_file_size_mb }}MB per file
                        </p>
                        <input type="file" 
                               name="files[]" 
                               id="fileInput" 
                               multiple 
                               accept="{{ implode(',', array_map(fn($t) => '.' . $t, $deliverable->file_types_allowed)) }}" 
                               class="d-none" 
                               required>
                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('fileInput').click()">
                            Choose Files
                        </button>
                    </div>
                </div>

                <!-- Selected Files Display -->
                <div id="selectedFiles" class="mt-3"></div>
                
                @error('files')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                @error('files.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submission Notes -->
            <div class="mb-4">
                <label for="submission_notes" class="form-label fw-bold">Submission Notes</label>
                <textarea name="submission_notes" 
                          id="submission_notes" 
                          class="form-control @error('submission_notes') is-invalid @enderror" 
                          rows="4" 
                          placeholder="Optional notes about your submission...">{{ old('submission_notes') }}</textarea>
                <div class="form-text">Describe what you've included, any important details, or questions for your supervisor.</div>
                @error('submission_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submission Actions -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input type="checkbox" class="form-check-input" id="confirmSubmission" required>
                        <label class="form-check-label small" for="confirmSubmission">
                            I confirm that this work is my own and I understand the academic integrity policy
                        </label>
                    </div>
                </div>

                <div>
                    <a href="{{ route('student.deliverables.index') }}" class="btn btn-secondary me-2">
                        Back
                    </a>
                    <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                        Submit Deliverable
                    </button>
                </div>
            </div>

            <!-- Progress Bar (Hidden) -->
            <div class="mt-3" id="uploadProgress" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-muted">Uploading...</span>
                    <span class="small text-muted" id="progressText">0%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: 0%" 
                         id="progressBar"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const selectedFilesDiv = document.getElementById('selectedFiles');
    const confirmCheckbox = document.getElementById('confirmSubmission');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('submissionForm');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');

    let selectedFiles = [];
    const maxFileSize = {{ $deliverable->max_file_size_mb }} * 1024 * 1024; // Convert MB to bytes
    const allowedTypes = @json($deliverable->file_types_allowed);

    // Drag and drop functionality
    dropZone.addEventListener('click', () => fileInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-success');
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-success');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-success');
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        const newFiles = Array.from(files);
        
        // Validate files
        for (let file of newFiles) {
            if (file.size > maxFileSize) {
                alert(`File "${file.name}" is too large. Maximum size is {{ $deliverable->max_file_size_mb }}MB.`);
                return;
            }
            
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(extension)) {
                alert(`File "${file.name}" has an invalid type. Allowed types: ${allowedTypes.join(', ')}`);
                return;
            }
        }
        
        selectedFiles = [...selectedFiles, ...newFiles];
        updateSelectedFilesDisplay();
        updateSubmitButton();
    }

    function updateSelectedFilesDisplay() {
        if (selectedFiles.length === 0) {
            selectedFilesDiv.innerHTML = '';
            return;
        }

        let html = '<div class="card bg-light"><div class="card-body"><h6 class="card-title">Selected Files:</h6>';
        
        selectedFiles.forEach((file, index) => {
            const size = (file.size / (1024 * 1024)).toFixed(2);
            html += `
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <span class="fw-bold">${file.name}</span>
                        <span class="text-muted small">(${size} MB)</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                        ×
                    </button>
                </div>
            `;
        });
        
        html += '</div></div>';
        selectedFilesDiv.innerHTML = html;
    }

    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateSelectedFilesDisplay();
        updateSubmitButton();
        
        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    function updateSubmitButton() {
        const hasFiles = selectedFiles.length > 0;
        const isConfirmed = confirmCheckbox.checked;
        submitBtn.disabled = !(hasFiles && isConfirmed);
    }

    confirmCheckbox.addEventListener('change', updateSubmitButton);

    // Form submission with progress
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (selectedFiles.length === 0) {
            alert('Please select at least one file.');
            return;
        }
        
        // Update file input with selected files
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        
        // Show progress bar
        uploadProgress.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Uploading...';
        
        // Create FormData and submit
        const formData = new FormData(form);
        
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
                progressText.textContent = Math.round(percentComplete) + '%';
            }
        });
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.href = '{{ route("student.deliverables.index") }}';
            } else {
                alert('Upload failed. Please try again.');
                uploadProgress.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Deliverable';
            }
        };
        
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });
});
</script>
@endsection