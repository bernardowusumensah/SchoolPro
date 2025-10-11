<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function create()
    {
        $supervisors = User::where('role', 'supervisor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('student.projects.create', compact('supervisors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string|min:100',
            'objectives' => 'required|string|min:50',
            'methodology' => 'required|string|min:50',
            'expected_outcomes' => 'required|string|min:50',
            'expected_start_date' => 'required|date|after_or_equal:today',
            'expected_completion_date' => 'required|date|after:expected_start_date',
            'estimated_hours' => 'required|integer|min:40|max:500',
            'supervisor_id' => 'required|exists:users,id',
            'technology_stack' => 'array|min:1',
            'technology_stack.*' => 'string',
            'required_resources' => 'nullable|string',
            'tools_and_software' => 'nullable|string',
            'supporting_documents.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png',
            'is_draft' => 'boolean'
        ]);

        $project = new Project();
        $project->student_id = Auth::id();
        $project->title = $validated['title'];
        $project->category = $validated['category'];
        $project->description = $validated['description'];
        $project->objectives = $validated['objectives'];
        $project->methodology = $validated['methodology'];
        $project->expected_outcomes = $validated['expected_outcomes'];
        $project->expected_start_date = $validated['expected_start_date'];
        $project->expected_completion_date = $validated['expected_completion_date'];
        $project->estimated_hours = $validated['estimated_hours'];
        $project->supervisor_id = $validated['supervisor_id'];
        $project->technology_stack = json_encode($validated['technology_stack'] ?? []);
        $project->required_resources = $validated['required_resources'];
        $project->tools_and_software = $validated['tools_and_software'];
        $project->status = $validated['is_draft'] ? 'draft' : 'pending_review';
        
        // Handle file uploads
        if ($request->hasFile('supporting_documents')) {
            $filePaths = [];
            foreach ($request->file('supporting_documents') as $file) {
                $path = $file->store('project-documents', 'public');
                $filePaths[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ];
            }
            $project->supporting_documents = json_encode($filePaths);
        }

        $project->save();

        $message = $validated['is_draft'] 
            ? 'Project proposal saved as draft successfully!'
            : 'Project proposal submitted successfully! It will be reviewed by your supervisor.';

        return redirect()->route('student.projects.index')->with('success', $message);
    }
}
