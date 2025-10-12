<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class StudentLogController extends Controller
{
    /**
     * Display a listing of the student's logs.
     */
    public function index(): View
    {
        $student = Auth::user();
        // Only show actual student weekly logs, not system audit logs
        $logs = $student->logs()
            ->whereNotNull('content') // Only student weekly logs
            ->where('content', '!=', '') // Ensure content is not empty
            ->with('project')
            ->latest()
            ->paginate(10);
        
        // Get current project for log creation (only approved projects)
        $currentProject = $student->projects()
            ->whereIn('status', ['Approved', 'In Progress'])
            ->latest()
            ->first();

        // Check if they have any pending proposals
        $pendingProject = $student->projects()
            ->where('status', 'Pending')
            ->latest()
            ->first();

        // Check if they have any projects needing revision
        $revisionProject = $student->projects()
            ->where('status', 'Needs Revision')
            ->latest()
            ->first();

        // Check if they have any draft projects (not submitted)
        $draftProject = $student->projects()
            ->where('status', 'Draft')
            ->latest()
            ->first();

        return view('student.logs.index', compact('logs', 'currentProject', 'pendingProject', 'revisionProject', 'draftProject'));
    }

    /**
     * Show the form for creating a new log entry.
     */
    public function create(): View|RedirectResponse
    {
        $student = Auth::user();
        
        // Get student's project that can receive logging (only approved projects)
        $project = $student->projects()
            ->whereIn('status', ['Approved', 'In Progress'])
            ->with('supervisor')
            ->latest()
            ->first();

        if (!$project) {
            // Check if they have any projects needing revision
            $revisionProject = $student->projects()
                ->where('status', 'Needs Revision')
                ->latest()
                ->first();

            // Check if they have any pending proposals
            $pendingProject = $student->projects()
                ->where('status', 'Pending')
                ->latest()
                ->first();

            if ($revisionProject) {
                return redirect()->route('student.projects.edit', $revisionProject->id)
                    ->with('info', 'Your project proposal needs revisions. Please address the feedback and resubmit before logging can begin.');
            } elseif ($pendingProject) {
                return redirect()->route('student.projects.index')
                    ->with('info', 'Your project proposal is under review by your supervisor. Weekly logging will be available once they approve it.');
            } else {
                return redirect()->route('student.projects.index')
                    ->with('info', 'Create your first project proposal to start tracking your weekly progress.');
            }
        }

        // Check if student already submitted a weekly log this week (only actual student logs, not system audit logs)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklyLogExists = Log::where('student_id', $student->id)
            ->where('project_id', $project->id)
            ->whereNotNull('content') // Only actual student weekly logs
            ->where('content', '!=', '') // Ensure content is not empty
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->exists();

        return view('student.logs.create', compact('project', 'weeklyLogExists'));
    }

    /**
     * Store a newly created log entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user();
        
        // Get student's project that can receive logging (only approved projects)
        $project = $student->projects()
            ->whereIn('status', ['Approved', 'In Progress'])
            ->with('supervisor')
            ->latest()
            ->first();

        if (!$project) {
            // Check if they have any projects needing revision
            $revisionProject = $student->projects()
                ->where('status', 'Needs Revision')
                ->latest()
                ->first();

            // Check if they have any pending proposals
            $pendingProject = $student->projects()
                ->where('status', 'Pending')
                ->latest()
                ->first();

            if ($revisionProject) {
                return redirect()->route('student.projects.edit', $revisionProject->id)
                    ->with('info', 'Your project proposal needs revisions. Please address the feedback and resubmit before logging can begin.');
            } elseif ($pendingProject) {
                return redirect()->route('student.projects.index')
                    ->with('info', 'Your project proposal is under review by your supervisor. Weekly logging will be available once they approve it.');
            } else {
                return redirect()->route('student.projects.index')
                    ->with('info', 'Create your first project proposal to start tracking your weekly progress.');
            }
        }

        $request->validate([
            'content' => 'required|string|min:100|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240', // 10MB max
        ], [
            'content.min' => 'Log content must be at least 100 characters long.',
            'content.max' => 'Log content cannot exceed 5000 characters.',
        ]);

        // Check if student already submitted a weekly log this week (only actual student logs, not system audit logs)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklyLogExists = Log::where('student_id', $student->id)
            ->where('project_id', $project->id)
            ->whereNotNull('content') // Only actual student weekly logs
            ->where('content', '!=', '') // Ensure content is not empty
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->exists();

        if ($weeklyLogExists && !$request->has('force_submit')) {
            return back()->with('warning', 'You have already submitted a log this week. You can still submit another one if needed.')
                ->withInput();
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('logs/attachments', 'public');
        }

        $log = Log::create([
            'project_id' => $project->id,
            'student_id' => $student->id,
            'content' => $request->content,
            'file_path' => $filePath,
        ]);

        return redirect()->route('student.logs.show', $log->id)
            ->with('success', 'Weekly log submitted successfully!');
    }

    /**
     * Display the specified log entry.
     */
    public function show(Log $log): View
    {
        // Ensure student can only view their own logs
        if ($log->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to log entry.');
        }

        $log->load('project');

        return view('student.logs.show', compact('log'));
    }

    /**
     * Show the form for editing the specified log entry.
     */
    public function edit(Log $log): View
    {
        // Ensure student can only edit their own logs
        if ($log->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to log entry.');
        }

        // Prevent editing after supervisor feedback has been provided
        if ($log->supervisor_feedback) {
            return redirect()->route('student.logs.show', $log->id)
                ->with('error', 'Log entries cannot be edited after supervisor feedback has been provided. This preserves the integrity of the feedback process.');
        }

        // Only allow editing within 48 hours of creation
        if ($log->created_at->diffInHours(now()) > 48) {
            return redirect()->route('student.logs.show', $log->id)
                ->with('error', 'Log entries can only be edited within 48 hours of submission.');
        }

        return view('student.logs.edit', compact('log'));
    }

    /**
     * Update the specified log entry.
     */
    public function update(Request $request, Log $log): RedirectResponse
    {
        // Ensure student can only update their own logs
        if ($log->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to log entry.');
        }

        // Prevent editing after supervisor feedback has been provided
        if ($log->supervisor_feedback) {
            return redirect()->route('student.logs.show', $log->id)
                ->with('error', 'Log entries cannot be edited after supervisor feedback has been provided. This preserves the integrity of the feedback process.');
        }

        // Only allow editing within 48 hours of creation
        if ($log->created_at->diffInHours(now()) > 48) {
            return redirect()->route('student.logs.show', $log->id)
                ->with('error', 'Log entries can only be edited within 48 hours of submission.');
        }

        $request->validate([
            'content' => 'required|string|min:100|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240',
            'remove_attachment' => 'nullable|boolean',
        ], [
            'content.min' => 'Log content must be at least 100 characters long.',
            'content.max' => 'Log content cannot exceed 5000 characters.',
        ]);

        // Handle file operations
        $filePath = $log->file_path;
        
        if ($request->has('remove_attachment') && $request->remove_attachment) {
            // Remove existing file
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
                $filePath = null;
            }
        }

        if ($request->hasFile('attachment')) {
            // Remove old file if exists
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            // Store new file
            $filePath = $request->file('attachment')->store('logs/attachments', 'public');
        }

        $log->update([
            'content' => $request->content,
            'file_path' => $filePath,
        ]);

        return redirect()->route('student.logs.show', $log->id)
            ->with('success', 'Log entry updated successfully!');
    }

    /**
     * Remove the specified log entry.
     */
    public function destroy(Log $log): RedirectResponse
    {
        // Ensure student can only delete their own logs
        if ($log->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to log entry.');
        }

        // Only allow deletion within 24 hours of creation
        if ($log->created_at->diffInHours(now()) > 24) {
            return redirect()->route('student.logs.show', $log->id)
                ->with('error', 'Log entries can only be deleted within 24 hours of submission.');
        }

        // Remove associated file if exists
        if ($log->file_path) {
            Storage::disk('public')->delete($log->file_path);
        }

        $log->delete();

        return redirect()->route('student.logs.index')
            ->with('success', 'Log entry deleted successfully!');
    }

    /**
     * Display log history with filters.
     */
    public function history(Request $request): View
    {
        $student = Auth::user();
        
        $query = $student->logs()->with('project');

        // Filter by month if specified
        if ($request->filled('month')) {
            $month = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereYear('created_at', $month->year)
                  ->whereMonth('created_at', $month->month);
        }

        // Filter by project if specified
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $logs = $query->latest()->paginate(15);
        
        // Get projects for filter dropdown
        $projects = $student->projects()->get();

        return view('student.logs.history', compact('logs', 'projects'));
    }

    /**
     * Download log attachment.
     */
    public function downloadAttachment(Log $log)
    {
        // Ensure student can only download their own log attachments
        if ($log->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to file.');
        }

        if (!$log->file_path || !Storage::disk('public')->exists($log->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($log->file_path);
    }

    /**
     * Show supervisor feedback on logs.
     */
    public function feedback(): View
    {
        $student = Auth::user();
        
        // Get logs with feedback (this would require adding feedback fields to logs table)
        $logsWithFeedback = $student->logs()
            ->with('project')
            ->whereNotNull('supervisor_feedback')
            ->latest()
            ->paginate(10);

        return view('student.logs.feedback', compact('logsWithFeedback'));
    }

    /**
     * Acknowledge teacher feedback on a log.
     */
    public function acknowledgeFeedback(Request $request, Log $log)
    {
        $student = Auth::user();
        
        // Ensure the log belongs to the current student
        if ($log->student_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this log.'
            ], 403);
        }

        // Validate request
        $request->validate([
            'student_question' => 'nullable|string|max:500',
        ]);

        // Update the log with acknowledgment
        $log->update([
            'student_acknowledged' => true,
            'student_question' => $request->student_question,
            'acknowledged_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->student_question ? 
                'Thank you for acknowledging the feedback and asking your question!' : 
                'Thank you for acknowledging the feedback!',
            'acknowledged' => true,
            'acknowledged_at' => $log->acknowledged_at->format('M j, Y g:i A'),
            'has_question' => !is_null($request->student_question)
        ]);
    }
}