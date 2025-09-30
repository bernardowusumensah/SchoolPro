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
        
        return view('student.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project proposal.
     */
    public function create(): View|RedirectResponse
    {
        
         $student = Auth::user();
        
        // INDUSTRY STANDARD: Only block if student has ACTIVE work (Approved/In Progress)
        $activeProject = $student->projects()
            ->whereIn('status', ['Approved', 'In Progress'])
            ->first();
        
        if ($activeProject) {
            return redirect()->route('student.projects.show', $activeProject->id)
                ->with('info', 'Complete your current active project before starting a new proposal.');
        }

        // INDUSTRY STANDARD: Reasonable limit on pending proposals (prevents spam)
        $pendingCount = $student->projects()->where('status', 'Pending')->count();
        if ($pendingCount >= 3) {
            return redirect()->route('student.projects.index')
                ->with('warning', 'You have 3 pending proposals. Please wait for supervisor feedback before submitting more.');
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

        $project = Project::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'student_id' => $student->id,
            'supervisor_id' => $validatedData['supervisor_id'],
            'status' => 'Pending',
        ]);

        return redirect()->route('student.projects.show', $project->id)
            ->with('success', 'Project proposal submitted successfully! It is now pending supervisor approval.');
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
        $project = $student->projects()->whereIn('status', ['Pending', 'Rejected'])->latest()->first();

        if (!$project) {
            return redirect()->route('student.projects.proposal')
                ->with('info', 'No editable project found. Create a new proposal instead.');
        }

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

        // Only allow editing if project is still pending or rejected
        if (!in_array($project->status, ['Pending', 'Rejected'])) {
            return redirect()->route('student.projects.show', $project->id)
                ->with('error', 'Cannot edit project after it has been approved or completed.');
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
        $project = $student->projects()->whereIn('status', ['Pending', 'Rejected'])->latest()->first();

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

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'supervisor_id' => $request->supervisor_id,
            'status' => 'Pending', // Reset to pending when updated
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

        // Only allow updating if project is still pending or rejected
        if (!in_array($project->status, ['Pending', 'Rejected'])) {
            return redirect()->route('student.projects.show', $project->id)
                ->with('error', 'Cannot edit project after it has been approved or completed.');
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

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'supervisor_id' => $request->supervisor_id,
            'status' => 'Pending', // Reset to pending when updated
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