@extends('layouts.student')

@section('title', 'Submit Deliverable')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <!-- Header -->
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.deliverables.index') }}" class="text-decoration-none text-muted">
                                Deliverables
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Submit</li>
                    </ol>
                </nav>

                <h1 class="h3 mb-2 fw-bold">Submit Deliverable</h1>
                <p class="text-muted mb-0">{{ $deliverable->title }}</p>
            </div>

            <!-- Deliverable Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <h6 class="fw-bold mb-3">Description</h6>
                            <p class="text-muted mb-4">{{ $deliverable->description }}</p>
                            
                            @if($deliverable->requirements)
                                <h6 class="fw-bold mb-3">Requirements</h6>
                                <p class="text-muted mb-0">{{ $deliverable->requirements }}</p>
                            @endif
                        </div>

                        <!-- Right Column - Key Info -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Due Date</small>
                                <p class="mb-0 fw-semibold">{{ $deliverable->due_date->format('M j, Y') }}</p>
                                <small class="text-muted">{{ $deliverable->due_date->format('g:i A') }}</small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Weight</small>
                                <p class="mb-0 fw-semibold">{{ $deliverable->weight_percentage }}%</p>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Status</small>
                                @if($deliverable->isOverdue())
                                    <span class="badge bg-danger">Overdue by {{ $deliverable->getDaysOverdue() }} day(s)</span>
                                @else
                                    <span class="badge bg-success">{{ $deliverable->getDaysUntilDue() }} day(s) remaining</span>
                                @endif
                            </div>

                            <hr class="my-3">

                            <div class="mb-2">
                                <small class="text-muted d-block mb-2">Allowed File Types</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($deliverable->file_types_allowed as $type)
                                        <span class="badge bg-light text-dark border">.{{ $type }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <small class="text-muted">Max size: {{ $deliverable->max_file_size_mb }}MB per file</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Previous Submissions (if any) -->
            @if($deliverable->submissions->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Previous Submissions</h6>
                        <div class="list-group list-group-flush">
                            @foreach($deliverable->submissions as $submission)
                                <div class="list-group-item px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="mb-1 fw-semibold">Version {{ $submission->version }}</p>
                                            <p class="small text-muted mb-1">
                                                {{ $submission->submitted_at->format('M j, Y \a\t g:i A') }}
                                            </p>
                                            @if($submission->grade)
                                                <span class="badge bg-success">Grade: {{ $submission->grade }}%</span>
                                            @endif
                                            @if($submission->teacher_feedback)
                                                <p class="small text-muted mt-2 mb-0">
                                                    <strong>Feedback:</strong> {{ $submission->teacher_feedback }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="badge bg-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submission Form -->
            <form action="{{ route('student.deliverables.store', $deliverable) }}" method="POST" enctype="multipart/form-data" id="submissionForm">
                @csrf

                <!-- File Upload Area -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Upload Files <span class="text-danger">*</span></h6>
                        
                        <div class="border border-2 border-dashed rounded-3 bg-light" 
                             id="dropZone" 
                             style="min-height: 180px; cursor: pointer; transition: all 0.2s;">
                            <div class="d-flex flex-column justify-content-center align-items-center text-center p-4" style="min-height: 180px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-cloud-arrow-up text-muted mb-3" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z"/>
                                    <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                </svg>
                                <h6 class="mb-2">Drop files here or click to browse</h6>
                                <p class="text-muted small mb-3">
                                    Accepted: {{ implode(', ', array_map(fn($t) => strtoupper($t), $deliverable->file_types_allowed)) }} 
                                    • Max {{ $deliverable->max_file_size_mb }}MB per file
                                </p>
                                <input type="file" 
                                       name="files[]" 
                                       id="fileInput" 
                                       multiple 
                                       accept="{{ implode(',', array_map(fn($t) => '.' . $t, $deliverable->file_types_allowed)) }}" 
                                       class="d-none" 
                                       required>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('fileInput').click()">
                                    Choose Files
                                </button>
                            </div>
                        </div>

                        <!-- Selected Files Display -->
                        <div id="selectedFiles" class="mt-3"></div>
                        
                        @error('files')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        @error('files.*')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submission Notes -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Submission Notes <span class="text-muted small">(Optional)</span></h6>
                        <textarea name="submission_notes" 
                                  id="submission_notes" 
                                  class="form-control border-0 bg-light @error('submission_notes') is-invalid @enderror" 
                                  rows="4" 
                                  placeholder="Add any notes about your submission...">{{ old('submission_notes') }}</textarea>
                        <div class="form-text mt-2">Describe what you've included or any questions for your supervisor</div>
                        @error('submission_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submission Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="confirmSubmission" required>
                            <label class="form-check-label" for="confirmSubmission">
                                I confirm that this work is my own and I understand the academic integrity policy
                            </label>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('student.deliverables.index') }}" class="btn btn-light px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                                Submit Deliverable
                            </button>
                        </div>

                        <!-- Progress Bar (Hidden) -->
                        <div class="mt-3" id="uploadProgress" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Uploading...</span>
                                <span class="small text-muted fw-semibold" id="progressText">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: 0%" 
                                     id="progressBar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
    const maxFileSize = {{ $deliverable->max_file_size_mb }} * 1024 * 1024;
    const allowedTypes = @json($deliverable->file_types_allowed);

    // Drag and drop functionality
    dropZone.addEventListener('click', () => fileInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#0d6efd';
        dropZone.style.backgroundColor = '#f8f9fa';
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '';
        dropZone.style.backgroundColor = '';
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '';
        dropZone.style.backgroundColor = '';
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        const newFiles = Array.from(files);
        
        for (let file of newFiles) {
            if (file.size > maxFileSize) {
                alert(`File "${file.name}" is too large. Maximum size is {{ $deliverable->max_file_size_mb }}MB.`);
                return;
            }
            
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(extension)) {
                alert(`File "${file.name}" has an invalid type. Allowed: ${allowedTypes.join(', ')}`);
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

        let html = '<div class="mt-3"><small class="text-muted fw-semibold d-block mb-2">Selected Files:</small>';
        
        selectedFiles.forEach((file, index) => {
            const size = (file.size / (1024 * 1024)).toFixed(2);
            const extension = file.name.split('.').pop().toUpperCase();
            html += `
                <div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2 bg-light">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-secondary">${extension}</span>
                        <div>
                            <p class="mb-0 fw-semibold">${file.name}</p>
                            <small class="text-muted">${size} MB</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                        Remove
                    </button>
                </div>
            `;
        });
        
        html += '</div>';
        selectedFilesDiv.innerHTML = html;
    }

    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateSelectedFilesDisplay();
        updateSubmitButton();
        
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
        
        uploadProgress.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
        
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
            if (xhr.status === 200 || xhr.status === 302) {
                // Success - redirect to deliverables page
                window.location.href = '{{ route("student.deliverables.index") }}';
            } else {
                // Handle error
                let errorMsg = 'Upload failed. Please try again.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMsg = response.message;
                    }
                } catch(e) {
                    // If response is not JSON, use default error
                }
                alert(errorMsg);
                uploadProgress.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Deliverable';
            }
        };
        
        xhr.onerror = function() {
            alert('Network error. Please check your connection and try again.');
            uploadProgress.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Submit Deliverable';
        };
        
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        // Get CSRF token from form or meta tag
        const csrfToken = document.querySelector('input[name="_token"]').value || 
                         document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        }
        xhr.send(formData);
    });
});
</script>
@endsection