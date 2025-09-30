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
            'supervisor_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Project title is required.',
            'description.required' => 'Project description is required.',
            'description.min' => 'Project description must be at least 100 characters.',
            'supervisor_id.required' => 'Please select a supervisor.',
            'supervisor_id.exists' => 'Selected supervisor is invalid.',
        ];
    }
}