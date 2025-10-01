<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'category' => 'required|string|max:100',
            'objectives' => 'required|string|min:50',
            'methodology' => 'required|string|min:50',
            'expected_outcomes' => 'required|string|min:50',
            'expected_start_date' => 'required|date|after_or_equal:today',
            'expected_completion_date' => 'required|date|after:expected_start_date',
            'estimated_hours' => 'required|integer|min:40|max:500',
            'required_resources' => 'nullable|string|max:1000',
            'technology_stack' => 'required|array|min:1',
            'technology_stack.*' => 'string|max:50',
            'tools_and_software' => 'nullable|string|max:500',
            'supporting_documents' => 'nullable|array|max:5',
            'supporting_documents.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
            'supervisor_id' => 'required|exists:users,id',
            'is_draft' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Project title is required.',
            'description.required' => 'Project description is required.',
            'description.min' => 'Project description must be at least 100 characters.',
            'category.required' => 'Please select a project category.',
            'objectives.required' => 'Project objectives are required.',
            'objectives.min' => 'Project objectives must be at least 50 characters.',
            'methodology.required' => 'Project methodology is required.',
            'methodology.min' => 'Project methodology must be at least 50 characters.',
            'expected_outcomes.required' => 'Expected outcomes are required.',
            'expected_outcomes.min' => 'Expected outcomes must be at least 50 characters.',
            'expected_start_date.required' => 'Expected start date is required.',
            'expected_start_date.after_or_equal' => 'Start date cannot be in the past.',
            'expected_completion_date.required' => 'Expected completion date is required.',
            'expected_completion_date.after' => 'Completion date must be after start date.',
            'estimated_hours.required' => 'Estimated hours is required.',
            'estimated_hours.min' => 'Project must be at least 40 hours.',
            'estimated_hours.max' => 'Project cannot exceed 500 hours.',
            'technology_stack.required' => 'Please select at least one technology.',
            'supporting_documents.max' => 'You can upload a maximum of 5 supporting documents.',
            'supporting_documents.*.file' => 'Please upload valid files only.',
            'supporting_documents.*.mimes' => 'Only PDF, DOC, DOCX, TXT, JPG, JPEG, PNG files are allowed.',
            'supporting_documents.*.max' => 'Each file must be smaller than 10MB.',
            'supervisor_id.required' => 'Please select a supervisor.',
            'supervisor_id.exists' => 'Selected supervisor is invalid.',
        ];
    }
}