<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Student\StoreProjectProposalRequest;

class StudentProjectController extends Controller
{
    /**
     * Display the student's projects.
     */
    public function index(): View
    {
        $student = Auth::user();
        $projects = $student->projects()->with('supervisor', 'grades')->latest()->get();
        
        // Calculate active projects for UI display
        $activeProjectCount = $student->projects()
            ->whereIn('status', ['Pending', 'Approved', 'Needs Revision'])
            ->count();
        
        return view('student.projects.index', compact('projects', 'activeProjectCount'));
    }

    /**
     * Show the form for creating a new project proposal.
     */
    public function create(): View|RedirectResponse
    {
        $student = Auth::user();
        
        // ANTI-SPAM RULE: Maximum 3 ACTIVE projects (Pending + Approved + Needs Revision)
        // Completed and Rejected projects don't count toward this limit
        $activeProjectCount = $student->projects()
            ->whereIn('status', ['Pending', 'Approved', 'Needs Revision'])
            ->count();
            
        if ($activeProjectCount >= 3) {
            return redirect()->route('student.projects.index')
                ->with('warning', 'You have reached the maximum limit of 3 active projects. Please complete or receive a decision on your current projects before submitting new proposals.');
        }

        $supervisors = User::where('role', 'teacher')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('student.projects.create', compact('supervisors'));
    }

    /**
     * Store a newly created project proposal.
     */
    public function store(StoreProjectProposalRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $student = Auth::user();
        
        // Double-check active project limit (security measure)
        $activeProjectCount = $student->projects()
            ->whereIn('status', ['Pending', 'Approved', 'Needs Revision'])
            ->count();
            
        if ($activeProjectCount >= 3) {
            return redirect()->route('student.projects.index')
                ->with('error', 'Cannot create new project: Maximum limit of 3 active projects reached.');
        }
        
        $isDraft = $validatedData['is_draft'] ?? false;

        // Handle file uploads
        $supportingDocuments = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('projects/supporting_documents', $filename, 'public');
                $supportingDocuments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                    'uploaded_at' => now()->toISOString()
                ];
            }
        }

        $project = Project::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category' => $validatedData['category'],
            'objectives' => $validatedData['objectives'],
            'methodology' => $validatedData['methodology'],
            'expected_outcomes' => $validatedData['expected_outcomes'],
            'expected_start_date' => $validatedData['expected_start_date'],
            'expected_completion_date' => $validatedData['expected_completion_date'],
            'estimated_hours' => $validatedData['estimated_hours'],
            'required_resources' => $validatedData['required_resources'],
            'technology_stack' => $validatedData['technology_stack'],
            'tools_and_software' => $validatedData['tools_and_software'],
            'supporting_documents' => $supportingDocuments,
            'student_id' => $student->id,
            'supervisor_id' => $validatedData['supervisor_id'],
            'status' => $isDraft ? 'Draft' : 'Pending',
            'is_draft' => $isDraft,
            'draft_saved_at' => $isDraft ? now() : null,
        ]);

        $message = $isDraft 
            ? 'Project proposal saved as draft successfully!'
            : 'Project proposal submitted successfully! It is now pending supervisor approval.';

        return redirect()->route('student.projects.show', $project->id)
            ->with('success', $message);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): View
    {
        // Ensure student can only view their own projects
        if ($project->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project.');
        }

        $project->load('supervisor', 'logs', 'grades.teacher');

        return view('student.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the project (route without parameter).
     */
    public function edit(): View|RedirectResponse
    {
        $student = Auth::user();
        $editableProjects = $student->projects()->whereIn('status', ['Pending', 'Needs Revision'])->get();

        if ($editableProjects->isEmpty()) {
            return redirect()->route('student.projects.proposal')
                ->with('info', 'No editable project found. Create a new proposal instead.');
        }

        // If student has multiple editable projects, redirect to projects list for clarity
        if ($editableProjects->count() > 1) {
            return redirect()->route('student.projects.index')
                ->with('info', 'You have multiple editable proposals. Please select which one to edit from the list below.');
        }

        // Only if there's exactly one editable project, edit it directly
        $project = $editableProjects->first();

        $supervisors = User::where('role', 'teacher')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('student.projects.edit', compact('project', 'supervisors'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function editProject(Project $project): View|RedirectResponse
    {
        // Ensure student can only edit their own projects
        if ($project->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project.');
        }

        // Only allow editing if project is pending or needs revision
        if (!in_array($project->status, ['Pending', 'Needs Revision'])) {
            return redirect()->route('student.projects.show', $project->id)
                ->with('error', 'Cannot edit project after it has been approved, rejected, or completed.');
        }

        $supervisors = User::where('role', 'teacher')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('student.projects.edit', compact('project', 'supervisors'));
    }

    /**
     * Update the project (route without parameter).
     */
    public function update(Request $request): RedirectResponse
    {
        $student = Auth::user();
        $project = $student->projects()->whereIn('status', ['Pending', 'Needs Revision'])->latest()->first();

        if (!$project) {
            return redirect()->route('student.projects.proposal')
                ->with('error', 'No editable project found.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'supervisor_id' => 'required|exists:users,id',
        ], [
            'description.min' => 'Project description must be at least 100 characters.',
        ]);

        // Verify supervisor is actually a teacher
        $supervisor = User::findOrFail($request->supervisor_id);
        if ($supervisor->role !== 'teacher') {
            return back()->withErrors(['supervisor_id' => 'Selected supervisor must be a teacher.']);
        }

        // Check if this is a resubmission (updating a "Needs Revision" proposal)
        $isResubmission = $project->status === 'Needs Revision';

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'supervisor_id' => $request->supervisor_id,
            'status' => 'Pending', // Reset to pending when updated
            'resubmitted_at' => $isResubmission ? now() : null, // Track resubmission
        ]);

        return redirect()->route('student.projects.show', $project->id)
            ->with('success', 'Project proposal updated successfully!');
    }

    /**
     * Update the specified project.
     */
    public function updateProject(Request $request, Project $project): RedirectResponse
    {
        // Ensure student can only update their own projects
        if ($project->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to project.');
        }

        // Only allow updating if project is pending or needs revision
        if (!in_array($project->status, ['Pending', 'Needs Revision'])) {
            return redirect()->route('student.projects.show', $project->id)
                ->with('error', 'Cannot edit project after it has been approved, rejected, or completed.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'supervisor_id' => 'required|exists:users,id',
        ], [
            'description.min' => 'Project description must be at least 100 characters.',
        ]);

        // Verify supervisor is actually a teacher
        $supervisor = User::findOrFail($request->supervisor_id);
        if ($supervisor->role !== 'teacher') {
            return back()->withErrors(['supervisor_id' => 'Selected supervisor must be a teacher.']);
        }

        // Check if this is a resubmission (updating a "Needs Revision" proposal)
        $isResubmission = $project->status === 'Needs Revision';

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'supervisor_id' => $request->supervisor_id,
            'status' => 'Pending', // Reset to pending when updated
            'resubmitted_at' => $isResubmission ? now() : null, // Track resubmission
        ]);

        return redirect()->route('student.projects.show', $project->id)
            ->with('success', 'Project proposal updated successfully!');
    }

    /**
     * Show project status and progress.
     */
    public function status(): View
    {
        $student = Auth::user();
        $project = $student->projects()->with('supervisor', 'logs', 'grades.teacher')->latest()->first();

        return view('student.projects.status', compact('project'));
    }

    /**
     * Show final submission form.
     */
    public function finalSubmission(): View
    {
        $student = Auth::user();
        $project = $student->projects()->where('status', 'Approved')->latest()->first();

        if (!$project) {
            return redirect()->route('student.projects.index')
                ->with('error', 'No approved project found for final submission.');
        }

        return view('student.projects.final', compact('project'));
    }

    /**
     * Submit final project.
     */
    public function submitFinal(Request $request): RedirectResponse
    {
        $student = Auth::user();
        $project = $student->projects()->where('status', 'Approved')->latest()->first();

        if (!$project) {
            return redirect()->route('student.projects.index')
                ->with('error', 'No approved project found for final submission.');
        }

        $request->validate([
            'final_document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'presentation' => 'nullable|file|mimes:ppt,pptx,pdf|max:10240',
            'source_code' => 'nullable|file|mimes:zip,rar|max:50240', // 50MB max
            'submission_note' => 'nullable|string|max:1000',
        ]);

        // Store files
        $finalDocPath = null;
        $presentationPath = null;
        $sourceCodePath = null;

        if ($request->hasFile('final_document')) {
            $finalDocPath = $request->file('final_document')->store('projects/final-docs', 'public');
        }

        if ($request->hasFile('presentation')) {
            $presentationPath = $request->file('presentation')->store('projects/presentations', 'public');
        }

        if ($request->hasFile('source_code')) {
            $sourceCodePath = $request->file('source_code')->store('projects/source-code', 'public');
        }

        // Update project with submission details
        $project->update([
            'status' => 'Completed',
            'final_document_path' => $finalDocPath,
            'presentation_path' => $presentationPath,
            'source_code_path' => $sourceCodePath,
            'submission_note' => $request->submission_note,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.projects.show', $project->id)
            ->with('success', 'Final project submitted successfully! Your supervisor will review and grade your submission.');
    }


}