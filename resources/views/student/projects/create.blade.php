<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Proposal - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 1rem;
            position: relative;
        }
        .step.active {
            color: #0d6efd;
        }
        .step.completed {
            color: #198754;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            border: 2px solid #dee2e6;
        }
        .step.active .step-number {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .step.completed .step-number {
            background: #198754;
            color: white;
            border-color: #198754;
        }
        .character-counter {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .character-counter.warning {
            color: #dc3545;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
        .tech-tag {
            display: inline-block;
            background: #e9ecef;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            margin: 0.125rem;
            font-size: 0.875rem;
            cursor: pointer;
        }
        .tech-tag.selected {
            background: #0d6efd;
            color: white;
        }
        .auto-save-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .upload-area {
            border-color: #dee2e6 !important;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .upload-area:hover {
            border-color: #0d6efd !important;
            background-color: #f8f9ff;
        }
        .upload-area.dragover {
            border-color: #0d6efd !important;
            background-color: #e7f3ff;
        }
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
        }
        .file-info {
            display: flex;
            align-items: center;
        }
        .file-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }
        .file-details {
            display: flex;
            flex-direction: column;
        }
        .file-name {
            font-weight: 500;
            margin-bottom: 0.125rem;
        }
        .file-size {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .progress-bar-custom {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            margin-bottom: 2rem;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0d6efd, #198754);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard/student">
                SchoolPro - Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="/dashboard/student">
                    Dashboard
                </a>
            </div>
        </div>
    </nav>

    <!-- Auto-save indicator -->
    <div id="autoSaveIndicator" class="auto-save-indicator">
        <div class="alert alert-success d-none" role="alert">
            Draft saved automatically
        </div>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            Create New Project Proposal
                        </h4>
                        <small class="opacity-75">Complete all sections to submit your proposal</small>
                    </div>
                    <div class="card-body">
                        <!-- Progress Bar -->
                        <div class="progress-bar-custom">
                            <div class="progress-fill" id="progressFill" style="width: 20%"></div>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-title">Basic Info</div>
                            </div>
                            <div class="step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-title">Project Details</div>
                            </div>
                            <div class="step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-title">Timeline & Resources</div>
                            </div>
                            <div class="step" data-step="4">
                                <div class="step-number">4</div>
                                <div class="step-title">Technology</div>
                            </div>
                            <div class="step" data-step="5">
                                <div class="step-number">5</div>
                                <div class="step-title">Supporting Files</div>
                            </div>
                            <div class="step" data-step="6">
                                <div class="step-number">6</div>
                                <div class="step-title">Review & Submit</div>
                            </div>
                        </div>

                        <!-- Project Limits Information -->
                        <div class="alert alert-info border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="mb-1">Project Guidelines</h6>
                                    <p class="mb-0 small">
                                        Students can have a maximum of <strong>3 total projects</strong> at any time. You can work on multiple proposals 
                                        simultaneously, but only <strong>one active project</strong> at a time. Save drafts to continue later.
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if(session('info'))
                            <div class="alert alert-info">
                                {{ session('info') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('student.projects.store') }}" id="proposalForm" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Step 1: Basic Information -->
                            <div class="form-section active" id="step1">
                                <h5 class="mb-4">Basic Project Information</h5>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">
                                                Project Title *
                                            </label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" name="title" value="{{ old('title') }}" 
                                                   placeholder="Enter a descriptive project title" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <span class="character-counter" id="titleCounter">0/255</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">
                                                Project Category *
                                            </label>
                                            <select class="form-control @error('category') is-invalid @enderror" 
                                                    id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="Web Development" {{ old('category') == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                                <option value="Mobile App" {{ old('category') == 'Mobile App' ? 'selected' : '' }}>Mobile App</option>
                                                <option value="Data Science" {{ old('category') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                                <option value="Machine Learning" {{ old('category') == 'Machine Learning' ? 'selected' : '' }}>Machine Learning</option>
                                                <option value="Game Development" {{ old('category') == 'Game Development' ? 'selected' : '' }}>Game Development</option>
                                                <option value="IoT/Hardware" {{ old('category') == 'IoT/Hardware' ? 'selected' : '' }}>IoT/Hardware</option>
                                                <option value="Cybersecurity" {{ old('category') == 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                                                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                        <label for="description" class="form-label">
                                        Project Description *
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6" 
                                              placeholder="Provide a comprehensive description of your project, including the problem it solves and your approach" 
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <span class="character-counter" id="descCounter">0 characters (minimum 100 required)</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                        <label for="supervisor_id" class="form-label">
                                        Preferred Supervisor *
                                    </label>
                                    <select class="form-control @error('supervisor_id') is-invalid @enderror" 
                                            id="supervisor_id" name="supervisor_id" required>
                                        <option value="">Select a supervisor</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                                {{ $supervisor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supervisor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Choose a supervisor whose expertise aligns with your project
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Project Details -->
                            <div class="form-section" id="step2">
                                <h5 class="mb-4">Detailed Project Planning</h5>
                                
                                <div class="mb-3">
                                        <label for="objectives" class="form-label">
                                        Project Objectives *
                                    </label>
                                    <textarea class="form-control @error('objectives') is-invalid @enderror" 
                                              id="objectives" name="objectives" rows="4" 
                                              placeholder="List the main objectives and goals of your project"
                                              required>{{ old('objectives') }}</textarea>
                                    @error('objectives')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <span class="character-counter" id="objCounter">0 characters (minimum 50 required)</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                        <label for="methodology" class="form-label">
                                        Methodology & Approach *
                                    </label>
                                    <textarea class="form-control @error('methodology') is-invalid @enderror" 
                                              id="methodology" name="methodology" rows="4" 
                                              placeholder="Describe your approach, methods, and development process"
                                              required>{{ old('methodology') }}</textarea>
                                    @error('methodology')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <span class="character-counter" id="methCounter">0 characters (minimum 50 required)</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                        <label for="expected_outcomes" class="form-label">
                                        Expected Outcomes *
                                    </label>
                                    <textarea class="form-control @error('expected_outcomes') is-invalid @enderror" 
                                              id="expected_outcomes" name="expected_outcomes" rows="4" 
                                              placeholder="What deliverables and results do you expect from this project?"
                                              required>{{ old('expected_outcomes') }}</textarea>
                                    @error('expected_outcomes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <span class="character-counter" id="outCounter">0 characters (minimum 50 required)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Timeline & Resources -->
                            <div class="form-section" id="step3">
                                <h5 class="mb-4">Timeline & Resource Planning</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                                <label for="start_date" class="form-label">
                                                Expected Start Date *
                                            </label>
                                            <input type="date" class="form-control @error('expected_start_date') is-invalid @enderror" 
                                                   id="expected_start_date" name="expected_start_date" 
                                                   value="{{ old('expected_start_date') }}" required>
                                            @error('expected_start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                                                                            <label for="end_date" class="form-label">
                                                Expected Completion Date *
                                            </label>
                                            <input type="date" class="form-control @error('expected_completion_date') is-invalid @enderror" 
                                                   id="expected_completion_date" name="expected_completion_date" 
                                                   value="{{ old('expected_completion_date') }}" required>
                                            @error('expected_completion_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                        <label for="estimated_hours" class="form-label">
                                        Estimated Hours *
                                    </label>
                                    <input type="number" class="form-control @error('estimated_hours') is-invalid @enderror" 
                                           id="estimated_hours" name="estimated_hours" 
                                           value="{{ old('estimated_hours') }}" min="40" max="500" required>
                                    @error('estimated_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Total estimated hours for project completion (40-500 hours)
                                    </div>
                                </div>

                                <div class="mb-3">
                                        <label for="required_resources" class="form-label">
                                        Required Resources
                                    </label>
                                    <textarea class="form-control @error('required_resources') is-invalid @enderror" 
                                              id="required_resources" name="required_resources" rows="3" 
                                              placeholder="List any special resources, equipment, or access you might need">{{ old('required_resources') }}</textarea>
                                    @error('required_resources')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step 4: Technology Stack -->
                            <div class="form-section" id="step4">
                                <h5 class="mb-4">Technology Stack & Tools</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        Technology Stack *
                                    </label>
                                    <div class="border rounded p-3 mb-2">
                                        <div class="mb-2"><strong>Frontend:</strong></div>
                                        <div class="mb-3">
                                            <span class="tech-tag" data-value="HTML5">HTML5</span>
                                            <span class="tech-tag" data-value="CSS3">CSS3</span>
                                            <span class="tech-tag" data-value="JavaScript">JavaScript</span>
                                            <span class="tech-tag" data-value="React">React</span>
                                            <span class="tech-tag" data-value="Vue.js">Vue.js</span>
                                            <span class="tech-tag" data-value="Angular">Angular</span>
                                            <span class="tech-tag" data-value="Bootstrap">Bootstrap</span>
                                            <span class="tech-tag" data-value="Tailwind CSS">Tailwind CSS</span>
                                        </div>
                                        
                                        <div class="mb-2"><strong>Backend:</strong></div>
                                        <div class="mb-3">
                                            <span class="tech-tag" data-value="PHP">PHP</span>
                                            <span class="tech-tag" data-value="Laravel">Laravel</span>
                                            <span class="tech-tag" data-value="Node.js">Node.js</span>
                                            <span class="tech-tag" data-value="Python">Python</span>
                                            <span class="tech-tag" data-value="Django">Django</span>
                                            <span class="tech-tag" data-value="Flask">Flask</span>
                                            <span class="tech-tag" data-value="Java">Java</span>
                                            <span class="tech-tag" data-value="C#">C#</span>
                                        </div>
                                        
                                        <div class="mb-2"><strong>Database:</strong></div>
                                        <div class="mb-3">
                                            <span class="tech-tag" data-value="MySQL">MySQL</span>
                                            <span class="tech-tag" data-value="PostgreSQL">PostgreSQL</span>
                                            <span class="tech-tag" data-value="MongoDB">MongoDB</span>
                                            <span class="tech-tag" data-value="SQLite">SQLite</span>
                                            <span class="tech-tag" data-value="Redis">Redis</span>
                                        </div>
                                        
                                        <div class="mb-2"><strong>Mobile:</strong></div>
                                        <div class="mb-3">
                                            <span class="tech-tag" data-value="React Native">React Native</span>
                                            <span class="tech-tag" data-value="Flutter">Flutter</span>
                                            <span class="tech-tag" data-value="Swift">Swift</span>
                                            <span class="tech-tag" data-value="Kotlin">Kotlin</span>
                                            <span class="tech-tag" data-value="Xamarin">Xamarin</span>
                                        </div>
                                        
                                        <div class="mb-2"><strong>Other:</strong></div>
                                        <div>
                                            <span class="tech-tag" data-value="Docker">Docker</span>
                                            <span class="tech-tag" data-value="Git">Git</span>
                                            <span class="tech-tag" data-value="AWS">AWS</span>
                                            <span class="tech-tag" data-value="Azure">Azure</span>
                                            <span class="tech-tag" data-value="Firebase">Firebase</span>
                                        </div>
                                    </div>
                                    <div id="selectedTechnologies" class="mb-2">
                                        <strong>Selected Technologies:</strong>
                                        <div id="selectedTechList" class="mt-2"></div>
                                    </div>
                                    <div class="form-text">
                                        Click on technologies you plan to use in your project
                                    </div>
                                    @error('technology_stack')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tools_and_software" class="form-label">
                                        Development Tools & Software
                                    </label>
                                    <textarea class="form-control @error('tools_and_software') is-invalid @enderror" 
                                              id="tools_and_software" name="tools_and_software" rows="3" 
                                              placeholder="List IDEs, design tools, testing frameworks, etc.">{{ old('tools_and_software') }}</textarea>
                                    @error('tools_and_software')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step 5: Supporting Documents -->
                            <div class="form-section" id="step5">
                                <h5 class="mb-4">Supporting Documents (Optional)</h5>
                                
                                <div class="alert alert-info">
                                    <h6>File Upload Guidelines:</h6>
                                    <ul class="mb-0">
                                        <li><strong>Maximum 5 files</strong> per proposal</li>
                                        <li><strong>File size limit:</strong> 10MB per file</li>
                                        <li><strong>Accepted formats:</strong> PDF, DOC, DOCX, TXT, JPG, JPEG, PNG</li>
                                        <li><strong>Examples:</strong> Research papers, mockups, diagrams, references</li>
                                    </ul>
                                </div>

                                <div class="mb-3">
                                        <label for="supporting_documents" class="form-label">
                                        Upload Supporting Documents
                                    </label>
                                    <div class="upload-area border-2 border-dashed rounded p-4 text-center" 
                                         id="uploadArea" 
                                         ondrop="handleDrop(event)" 
                                         ondragover="handleDragOver(event)" 
                                         ondragleave="handleDragLeave(event)">
                                        <div class="upload-content">
                                            <div class="mb-3 text-muted">
                                                <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M.5 3l.04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09l-.636 7A1 1 0 0 1 13.174 13H9v1h4.174a2 2 0 0 0 1.991-1.823l.637-7A2 2 0 0 0 13.81 3H.5zM2.19 4a1 1 0 0 1 .996 1.09l.636 7A1 1 0 0 1 2.826 13H2.19a1 1 0 0 1-.996-1.09l-.636-7A1 1 0 0 1 1.554 4h.636z"/>
                                                    <path d="M6.5 6a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6zM8.5 6a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6zM10.5 6a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                </svg>
                                            </div>
                                            <p class="mb-2">Drag and drop files here or</p>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('supporting_documents').click()">
                                                Browse Files
                                            </button>
                                            <input type="file" 
                                                   class="form-control d-none @error('supporting_documents') is-invalid @enderror" 
                                                   id="supporting_documents" 
                                                   name="supporting_documents[]" 
                                                   multiple 
                                                   accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"
                                                   onchange="handleFileSelect(this)">
                                        </div>
                                    </div>
                                    @error('supporting_documents')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @error('supporting_documents.*')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="fileList" class="mt-3" style="display: none;">
                                    <h6>Selected Files:</h6>
                                    <div id="fileItems"></div>
                                </div>

                                <div class="form-text">
                                    <strong>Tip:</strong> Supporting documents help supervisors better understand your project. 
                                    Consider uploading research papers, wireframes, or technical specifications.
                                </div>
                            </div>

                            <!-- Step 6: Review & Submit -->
                            <div class="form-section" id="step6">
                                <h5 class="mb-4">Review & Submit</h5>
                                
                                <div class="alert alert-info">
                                    <h6>Before Submitting:</h6>
                                    <ul class="mb-0">
                                        <li>Review all information for accuracy</li>
                                        <li>Ensure all required fields are completed</li>
                                        <li>Double-check your technology stack selection</li>
                                        <li>Verify timeline is realistic</li>
                                    </ul>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h6>Proposal Summary</h6>
                                    </div>
                                    <div class="card-body" id="proposalSummary">
                                        <!-- Summary will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                                    ← Previous
                                </button>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" id="saveDraftBtn">
                                        Save as Draft
                                    </button>
                                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                                        Next →
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                        Submit Proposal
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden inputs for technology stack -->
                            <div id="technologyInputs"></div>
                            <input type="hidden" name="is_draft" id="isDraft" value="0">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 6;
        let selectedTechnologies = [];

        // Character counters
        function setupCharacterCounters() {
            const counters = [
                { input: 'title', counter: 'titleCounter', max: 255 },
                { input: 'description', counter: 'descCounter', min: 100 },
                { input: 'objectives', counter: 'objCounter', min: 50 },
                { input: 'methodology', counter: 'methCounter', min: 50 },
                { input: 'expected_outcomes', counter: 'outCounter', min: 50 }
            ];

            counters.forEach(({ input, counter, max, min }) => {
                const inputEl = document.getElementById(input);
                const counterEl = document.getElementById(counter);
                
                inputEl.addEventListener('input', function() {
                    const length = this.value.length;
                    if (max) {
                        counterEl.textContent = `${length}/${max}`;
                        counterEl.className = length > max ? 'character-counter warning' : 'character-counter';
                    } else if (min) {
                        counterEl.textContent = `${length} characters (minimum ${min} required)`;
                        counterEl.className = length < min ? 'character-counter warning' : 'character-counter';
                    }
                });
            });
        }

        // Technology selection
        function setupTechnologySelection() {
            document.querySelectorAll('.tech-tag').forEach(tag => {
                tag.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        selectedTechnologies = selectedTechnologies.filter(tech => tech !== value);
                    } else {
                        this.classList.add('selected');
                        selectedTechnologies.push(value);
                    }
                    updateSelectedTechnologies();
                });
            });
        }

        function updateSelectedTechnologies() {
            const container = document.getElementById('selectedTechList');
            const inputsContainer = document.getElementById('technologyInputs');
            
            container.innerHTML = '';
            inputsContainer.innerHTML = '';
            
            selectedTechnologies.forEach(tech => {
                // Display selected tech
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-1 mb-1';
                badge.textContent = tech;
                container.appendChild(badge);
                
                // Add hidden input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'technology_stack[]';
                input.value = tech;
                inputsContainer.appendChild(input);
            });
        }

        // Step navigation
        function changeStep(direction) {
            if (direction === 1 && !validateCurrentStep()) {
                return;
            }

            const currentSection = document.getElementById(`step${currentStep}`);
            currentSection.classList.remove('active');
            
            document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');
            if (direction === 1) {
                document.querySelector(`[data-step="${currentStep}"]`).classList.add('completed');
            }

            currentStep += direction;
            
            const newSection = document.getElementById(`step${currentStep}`);
            newSection.classList.add('active');
            
            document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');

            updateProgress();
            updateButtons();
            
            if (currentStep === 6) {
                updateSummary();
            }
        }

        function validateCurrentStep() {
            const step = document.getElementById(`step${currentStep}`);
            const requiredInputs = step.querySelectorAll('input[required], textarea[required], select[required]');
            let isValid = true;

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Special validation for technology stack
            if (currentStep === 4 && selectedTechnologies.length === 0) {
                alert('Please select at least one technology for your project.');
                isValid = false;
            }

            return isValid;
        }

        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progressFill').style.width = `${progress}%`;
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
            nextBtn.style.display = currentStep < totalSteps ? 'block' : 'none';
            submitBtn.style.display = currentStep === totalSteps ? 'block' : 'none';
        }

        function updateSummary() {
            const summary = document.getElementById('proposalSummary');
            const formData = new FormData(document.getElementById('proposalForm'));
            
            summary.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Information</h6>
                        <p><strong>Title:</strong> ${formData.get('title') || 'Not specified'}</p>
                        <p><strong>Category:</strong> ${formData.get('category') || 'Not specified'}</p>
                        <p><strong>Supervisor:</strong> ${document.getElementById('supervisor_id').selectedOptions[0]?.text || 'Not selected'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Timeline</h6>
                        <p><strong>Start Date:</strong> ${formData.get('expected_start_date') || 'Not specified'}</p>
                        <p><strong>Completion Date:</strong> ${formData.get('expected_completion_date') || 'Not specified'}</p>
                        <p><strong>Estimated Hours:</strong> ${formData.get('estimated_hours') || 'Not specified'}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Technology Stack</h6>
                    <p>${selectedTechnologies.join(', ') || 'None selected'}</p>
                </div>
                <div class="mt-3">
                    <h6>Supporting Documents</h6>
                    <p>${selectedFiles.length > 0 ? selectedFiles.map(f => f.name).join(', ') : 'No files uploaded'}</p>
                </div>
            `;
        }

        // Save as draft
        document.getElementById('saveDraftBtn').addEventListener('click', function() {
            document.getElementById('isDraft').value = '1';
            document.getElementById('proposalForm').submit();
        });

        // Auto-save functionality (every 2 minutes)
        setInterval(function() {
            // Auto-save logic would go here
            // For now, just show the indicator
            const indicator = document.querySelector('#autoSaveIndicator .alert');
            indicator.classList.remove('d-none');
            setTimeout(() => indicator.classList.add('d-none'), 3000);
        }, 120000);

        // File Upload Functions
        let selectedFiles = [];

        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('uploadArea').classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('uploadArea').classList.remove('dragover');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('uploadArea').classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            handleFiles(files);
        }

        function handleFileSelect(input) {
            handleFiles(input.files);
        }

        function handleFiles(files) {
            // Check file limit
            if (selectedFiles.length + files.length > 5) {
                alert('You can only upload a maximum of 5 files.');
                return;
            }

            for (let file of files) {
                // Check file size (10MB limit)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
                    continue;
                }

                // Check file type
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert(`File "${file.name}" has an unsupported format.`);
                    continue;
                }

                selectedFiles.push(file);
            }

            updateFileList();
            updateFileInput();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
            updateFileInput();
        }

        function updateFileList() {
            const fileList = document.getElementById('fileList');
            const fileItems = document.getElementById('fileItems');
            
            if (selectedFiles.length === 0) {
                fileList.style.display = 'none';
                return;
            }

            fileList.style.display = 'block';
            fileItems.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                
                const fileIcon = getFileIcon(file.type);
                const fileSize = formatFileSize(file.size);
                
                fileItem.innerHTML = `
                    <div class="file-info">
                        <span class="file-icon">${fileIcon}</span>
                        <div class="file-details">
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${fileSize}</div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                        Remove
                    </button>
                `;
                
                fileItems.appendChild(fileItem);
            });
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            document.getElementById('supporting_documents').files = dataTransfer.files;
        }

        function getFileIcon(fileType) {
            if (fileType.includes('pdf')) return 'PDF';
            if (fileType.includes('word') || fileType.includes('document')) return 'DOC';
            if (fileType.includes('text')) return 'TXT';
            if (fileType.includes('image')) return 'IMG';
            return 'FILE';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupCharacterCounters();
            setupTechnologySelection();
            updateProgress();
            updateButtons();
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('expected_start_date').min = today;
            
            // Update completion date minimum when start date changes
            document.getElementById('expected_start_date').addEventListener('change', function() {
                document.getElementById('expected_completion_date').min = this.value;
            });
        });
    </script>
</body>
</html>