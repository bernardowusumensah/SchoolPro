<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Proposal - SchoolPro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        }
        .auto-save-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/dashboard/student">
                <i class="fas fa-graduation-cap me-2"></i>SchoolPro - Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="/dashboard/student">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
                <a class="nav-link text-white" href="{{ route('student.projects.index') }}">
                    <i class="fas fa-list me-1"></i>My Projects
                </a>
            </div>
        </div>
    </nav>

    <!-- Auto-save indicator -->
    <div id="autoSaveIndicator" class="auto-save-indicator">
        <div class="alert alert-success d-none" role="alert">
            <i class="fas fa-check-circle me-2"></i>Draft saved automatically
        </div>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Create New Project Proposal
                        </h4>
                        <small class="opacity-75">Complete all sections to submit your proposal</small>
                    </div>
                    <div class="card-body">
                        @if(session('info'))
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                            </div>
                        @endif

                        @if(session('warning'))
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                            </div>
                        @endif

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
                                <div class="step-title">Review & Submit</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('student.projects.store') }}" id="proposalForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-1"></i>Project Title *
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Enter your project title" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Project Description *
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="6" 
                                          placeholder="Describe your project in detail (minimum 100 characters)" 
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Minimum 100 characters required. Current: <span id="charCount">0</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="supervisor_id" class="form-label">
                                    <i class="fas fa-user-tie me-1"></i>Preferred Supervisor *
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
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Choose a supervisor whose expertise matches your project
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Industry Standard Policy:</h6>
                                <ul class="mb-0">
                                    <li>You can submit up to <strong>3 pending proposals</strong> at once</li>
                                    <li>You can edit <strong>pending or rejected</strong> proposals</li>
                                    <li>Once approved, complete that project before starting new ones</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('student.projects.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Projects
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i>Submit Proposal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Character counter for description
        document.getElementById('description').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('charCount').textContent = count;
            
            if (count >= 100) {
                document.getElementById('charCount').style.color = 'green';
            } else {
                document.getElementById('charCount').style.color = 'red';
            }
        });
    </script>
</body>
</html>