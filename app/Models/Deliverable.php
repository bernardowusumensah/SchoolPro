<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Deliverable extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'type',
        'status',
        'due_date',
        'weight',
        'weight_percentage',
        'requirements',
        'file_types_allowed',
        'max_file_size',
        'max_file_size_mb',
        'allowed_file_types',
        'instructions'
    ];

    protected $casts = [
        'due_date' => 'date',
        'file_types_allowed' => 'array'
    ];

    /**
     * Get the project this deliverable belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all submissions for this deliverable
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(DeliverableSubmission::class);
    }

    /**
     * Get the latest submission for a specific student
     */
    public function getLatestSubmissionForStudent($studentId)
    {
        return $this->submissions()
            ->where('student_id', $studentId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Check if deliverable is overdue
     */
    public function isOverdue(): bool
    {
        return Carbon::now()->isAfter($this->due_date);
    }

    /**
     * Get days until due date
     */
    public function getDaysUntilDue(): int
    {
        return Carbon::now()->diffInDays($this->due_date, false);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'Pending' => 'badge-warning',
            'Submitted' => 'badge-info',
            'Reviewed' => 'badge-primary',
            'Approved' => 'badge-success',
            'Rejected' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    /**
     * Get type display name
     */
    public function getTypeDisplayName(): string
    {
        return match($this->type) {
            'milestone' => 'Milestone',
            'document' => 'Document',
            'presentation' => 'Presentation',
            'code' => 'Source Code',
            'final_project' => 'Final Project',
            default => ucfirst($this->type)
        };
    }

    /**
     * Check if this is a project deliverable (main project work)
     */
    public function isProjectDeliverable(): bool
    {
        return $this->type === 'final_project';
    }

    /**
     * Check if this is a presentation deliverable
     */
    public function isPresentationDeliverable(): bool
    {
        return $this->type === 'presentation';
    }

    /**
     * Get the phase number (1 for project, 2 for presentation)
     */
    public function getPhaseNumber(): int
    {
        return match($this->type) {
            'final_project' => 1,
            'presentation' => 2,
            default => 0
        };
    }

    /**
     * Get phase display name
     */
    public function getPhaseDisplayName(): string
    {
        return match($this->type) {
            'final_project' => 'Phase 1: Project Development',
            'presentation' => 'Phase 2: Project Presentation',
            default => $this->getTypeDisplayName()
        };
    }
}
