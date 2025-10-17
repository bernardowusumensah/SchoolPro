<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Deliverable;
use App\Models\DeliverableSubmission;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentDeliverableController extends Controller
{
    /**
     * Display student's deliverables dashboard
     */
    public function index(): View
    {
        $student = Auth::user();
        
        // Get student's approved project
        $project = $student->projects()
            ->whereIn('status', ['approved', 'in_progress', 'completed'])
            ->latest()
            ->first();

        if (!$project) {
            return view('student.deliverables.no-project');
        }

        // Get all deliverables for the project with submissions
        $deliverables = $project->deliverables()
            ->with(['submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id)
                      ->orderBy('version', 'desc');
            }])
            ->orderBy('due_date')
            ->get();

        // Separate upcoming and overdue deliverables
        $upcoming = $deliverables->filter(function($deliverable) {
            return !$deliverable->isOverdue() && $deliverable->status === 'pending';
        });

        $overdue = $deliverables->filter(function($deliverable) {
            return $deliverable->isOverdue() && $deliverable->status === 'pending';
        });

        $completed = $deliverables->filter(function($deliverable) {
            return $deliverable->submissions->isNotEmpty();
        });

        return view('student.deliverables.index', compact(
            'project', 'deliverables', 'upcoming', 'overdue', 'completed'
        ));
    }

    /**
     * Show specific deliverable details
     */
    public function show(Deliverable $deliverable): View
    {
        $student = Auth::user();
        
        // Check if student has access to this deliverable
        if ($deliverable->project->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this deliverable.');
        }

        // Get student's submissions for this deliverable
        $submissions = $deliverable->submissions()
            ->where('student_id', $student->id)
            ->orderBy('version', 'desc')
            ->get();

        $latestSubmission = $submissions->first();

        return view('student.deliverables.show', compact(
            'deliverable', 'submissions', 'latestSubmission'
        ));
    }

    /**
     * Show submission form
     */
    public function create(Deliverable $deliverable): View
    {
        $student = Auth::user();
        
        // Check access and if submissions are allowed
        if ($deliverable->project->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this deliverable.');
        }

        if ($deliverable->isOverdue()) {
            return redirect()->route('student.deliverables.show', $deliverable)
                ->with('warning', 'This deliverable is overdue. Contact your supervisor for late submission approval.');
        }

        return view('student.deliverables.create', compact('deliverable'));
    }

    /**
     * Store a new submission
     */
    public function store(Request $request, Deliverable $deliverable): RedirectResponse
    {
        $student = Auth::user();
        
        // Validate access
        if ($deliverable->project->student_id !== $student->id) {
            abort(403);
        }

        // Validate file uploads
        $allowedTypes = $deliverable->file_types_allowed ?? ['pdf', 'docx', 'txt', 'zip'];
        $maxSize = ($deliverable->max_file_size_mb ?? 10) * 1024; // Convert to KB

        $request->validate([
            'files' => 'required|array|min:1|max:5',
            'files.*' => [
                'required',
                'file',
                'mimes:' . implode(',', $allowedTypes),
                'max:' . $maxSize
            ],
            'submission_note' => 'nullable|string|max:1000'
        ]);

        // Check if student already has a submission for this deliverable
        $existingSubmission = $deliverable->submissions()
            ->where('student_id', $student->id)
            ->first();

        // Determine version number
        $version = $existingSubmission ? $existingSubmission->version + 1 : 1;

        // Store files
        $filePaths = [];
        foreach ($request->file('files') as $file) {
            $path = $file->store("deliverables/{$deliverable->id}/student-{$student->id}/v{$version}", 'public');
            $filePaths[] = [
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ];
        }

        $submissionData = [
            'file_paths' => $filePaths,
            'submission_note' => $request->submission_note,
            'submitted_at' => now(),
            'status' => $deliverable->isOverdue() ? 'late' : 'submitted',
            'version' => $version
        ];

        if ($existingSubmission) {
            // Update existing submission
            $existingSubmission->update($submissionData);
        } else {
            // Create new submission
            DeliverableSubmission::create(array_merge($submissionData, [
                'deliverable_id' => $deliverable->id,
                'student_id' => $student->id,
            ]));
        }

        // Update deliverable status
        $deliverable->update(['status' => 'submitted']);

        return redirect()->route('student.deliverables.show', $deliverable)
            ->with('success', 'Deliverable submitted successfully!');
    }

    /**
     * Download submitted file
     */
    public function downloadFile(DeliverableSubmission $submission, $fileIndex)
    {
        $student = Auth::user();
        
        // Check access
        if ($submission->student_id !== $student->id) {
            abort(403);
        }

        $filePaths = $submission->file_paths;
        if (!isset($filePaths[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $file = $filePaths[$fileIndex];
        $filePath = $file['path'];

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return Storage::disk('public')->download($filePath, $file['original_name']);
    }
}
