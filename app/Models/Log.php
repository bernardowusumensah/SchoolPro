<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'student_id', // Add this field
        'content', // For weekly logs
        'file_path', // For weekly log attachments
        'supervisor_feedback', // Teacher feedback
        'feedback_date', // When feedback was provided
        'supervisor_rating', // Teacher rating (Excellent, Good, etc.)
        'private_notes', // Private teacher notes
        'requires_followup', // Flag for follow-up needed
        'feedback_updated_at', // Track feedback updates
        'student_acknowledged', // Student acknowledgment
        'student_question', // Student question
        'acknowledged_at', // When student acknowledged
        'action',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'feedback_date' => 'datetime',
        'feedback_updated_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the project that this log belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student who created this log.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Create a new log entry.
     */
    public static function createLog(
        int $projectId,
        int $userId,
        string $action,
        string $description,
        array $oldData = null,
        array $newData = null
    ): self {
        return self::create([
            'project_id' => $projectId,
            'student_id' => $userId, // Use userId as student_id since logs are created by students
            'action' => $action,
            'description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Get the action icon for display.
     */
    public function getActionIcon(): string
    {
        return match($this->action) {
            'created' => 'fas fa-plus-circle text-success',
            'updated' => 'fas fa-edit text-warning',
            'submitted' => 'fas fa-paper-plane text-info',
            'approved' => 'fas fa-check-circle text-success',
            'rejected' => 'fas fa-times-circle text-danger',
            'started' => 'fas fa-play-circle text-primary',
            'completed' => 'fas fa-flag-checkered text-success',
            'commented' => 'fas fa-comment text-info',
            'file_uploaded' => 'fas fa-upload text-info',
            'file_deleted' => 'fas fa-trash text-danger',
            'status_changed' => 'fas fa-exchange-alt text-warning',
            default => 'fas fa-info-circle text-secondary'
        };
    }

    /**
     * Get formatted action text.
     */
    public function getActionText(): string
    {
        return match($this->action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'submitted' => 'Submitted',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'started' => 'Started',
            'completed' => 'Completed',
            'commented' => 'Commented',
            'file_uploaded' => 'File Uploaded',
            'file_deleted' => 'File Deleted',
            'status_changed' => 'Status Changed',
            default => 'Action Performed'
        };
    }

    /**
     * Scope a query to only include logs for a specific project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to only include logs by a specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include logs of a specific action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to order logs by most recent first.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to only include logs with specific rating.
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('supervisor_rating', $rating);
    }

    /**
     * Scope a query to only include unreviewed logs.
     */
    public function scopeUnreviewed($query)
    {
        return $query->whereNull('supervisor_feedback');
    }

    /**
     * Scope a query to only include reviewed logs.
     */
    public function scopeReviewed($query)
    {
        return $query->whereNotNull('supervisor_feedback');
    }

    /**
     * Scope a query to only include logs requiring follow-up.
     */
    public function scopeRequiringFollowup($query)
    {
        return $query->where('requires_followup', true);
    }

    /**
     * Check if log has been reviewed by supervisor.
     */
    public function isReviewed(): bool
    {
        return !is_null($this->supervisor_feedback);
    }

    /**
     * Check if log requires follow-up.
     */
    public function requiresFollowup(): bool
    {
        return $this->requires_followup === true;
    }

    /**
     * Get rating badge color for display.
     */
    public function getRatingBadgeColor(): string
    {
        return match($this->supervisor_rating) {
            'Excellent' => 'success',
            'Good' => 'primary',
            'Satisfactory' => 'warning',
            'Needs Improvement' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get rating icon for display.
     */
    public function getRatingIcon(): string
    {
        return match($this->supervisor_rating) {
            'Excellent' => 'fas fa-star text-success',
            'Good' => 'fas fa-thumbs-up text-primary',
            'Satisfactory' => 'fas fa-check text-warning',
            'Needs Improvement' => 'fas fa-exclamation-triangle text-danger',
            default => 'fas fa-question text-secondary'
        };
    }

    /**
     * Check if feedback has been updated since initial submission.
     */
    public function hasFeedbackBeenUpdated(): bool
    {
        return !is_null($this->feedback_updated_at) && 
               $this->feedback_updated_at->gt($this->feedback_date);
    }

    /**
     * Check if student has acknowledged the feedback.
     */
    public function isAcknowledgedByStudent(): bool
    {
        return $this->student_acknowledged === true;
    }

    /**
     * Check if student has a question about the feedback.
     */
    public function hasStudentQuestion(): bool
    {
        return !is_null($this->student_question) && trim($this->student_question) !== '';
    }
}
