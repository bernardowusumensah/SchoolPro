<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'objectives',
        'methodology',
        'expected_outcomes',
        'expected_start_date',
        'expected_completion_date',
        'estimated_hours',
        'required_resources',
        'technology_stack',
        'tools_and_software',
        'supporting_documents',
        'is_draft',
        'draft_saved_at',
        'supervisor_feedback',
        'revision_notes',
        'revision_count',
        'student_id',
        'supervisor_id',
        'status',
        'final_document_path',
        'presentation_path',
        'source_code_path',
        'submission_note',
        'submitted_at',
        // Approval workflow fields
        'approval_comments',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'reviewed_at',
        'reviewed_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'submitted_at' => 'datetime',
        'expected_start_date' => 'date',
        'expected_completion_date' => 'date',
        'draft_saved_at' => 'datetime',
        'approved_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'technology_stack' => 'array',
        'supporting_documents' => 'array',
        'is_draft' => 'boolean',
    ];

    /**
     * Get the student that owns the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the student that owns the project (alias for clarity).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the supervisor assigned to the project.
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Get the grades for this project.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the logs for this project.
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Scope a query to only include projects of active users.
     */
    public function scopeActiveUsers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('status', 'active');
        });
    }

    /**
     * Scope a query to only include projects by role.
     */
    public function scopeByUserRole($query, $role)
    {
        return $query->whereHas('user', function ($q) use ($role) {
            $q->where('role', $role);
        });
    }
}
