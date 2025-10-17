<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DeliverableSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'deliverable_id',
        'student_id',
        'file_paths',
        'file_names',
        'file_sizes',
        'submission_note',
        'comments',
        'submitted_at',
        'status',
        'grade',
        'teacher_feedback',
        'feedback',
        'reviewed_at',
        'reviewed_by',
        'version'
    ];

    protected $casts = [
        'file_paths' => 'array',
        'file_names' => 'array',
        'file_sizes' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'grade' => 'decimal:2'
    ];

    /**
     * Get the deliverable this submission belongs to
     */
    public function deliverable(): BelongsTo
    {
        return $this->belongsTo(Deliverable::class);
    }

    /**
     * Get the student who made this submission
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the teacher who reviewed this submission
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if submission was late
     */
    public function isLate(): bool
    {
        return $this->submitted_at->isAfter($this->deliverable->due_date);
    }

    /**
     * Get days late (if late)
     */
    public function getDaysLate(): int
    {
        if (!$this->isLate()) {
            return 0;
        }
        return $this->deliverable->due_date->diffInDays($this->submitted_at);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'submitted' => $this->isLate() ? 'badge-warning' : 'badge-info',
            'late' => 'badge-warning',
            'reviewed' => 'badge-primary',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    /**
     * Get grade letter
     */
    public function getGradeLetter(): string
    {
        if (!$this->grade) return 'N/A';
        
        return match(true) {
            $this->grade >= 90 => 'A+',
            $this->grade >= 85 => 'A',
            $this->grade >= 80 => 'A-',
            $this->grade >= 77 => 'B+',
            $this->grade >= 73 => 'B',
            $this->grade >= 70 => 'B-',
            $this->grade >= 67 => 'C+',
            $this->grade >= 63 => 'C',
            $this->grade >= 60 => 'C-',
            $this->grade >= 57 => 'D+',
            $this->grade >= 53 => 'D',
            $this->grade >= 50 => 'D-',
            default => 'F'
        };
    }
}
