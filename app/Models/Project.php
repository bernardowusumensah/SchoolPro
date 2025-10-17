<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id', 'supervisor_id', 'title', 'category', 'description',
        'objectives', 'methodology', 'expected_outcomes', 'expected_start_date',
        'expected_completion_date', 'estimated_hours', 'technology_stack',
        'required_resources', 'tools_and_software', 'supporting_documents',
        'status', 'supervisor_feedback', 'final_grade', 'submitted_at', 'reviewed_at', 'completed_at'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'technology_stack' => 'array',
        'supporting_documents' => 'array',
        'expected_start_date' => 'date',
        'expected_completion_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the student that owns the project.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the supervisor assigned to the project.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Get the grades for this project.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the logs for this project.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Get the deliverables for this project.
     */
    public function deliverables(): HasMany
    {
        return $this->hasMany(Deliverable::class);
    }

    /**
     * Determine if the project is a draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Determine if the project has been submitted.
     */
    public function isSubmitted(): bool
    {
        return !in_array($this->status, ['draft']);
    }

    /**
     * Determine if the project is pending review.
     */
    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    /**
     * Determine if the project is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Determine if the project is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Determine if the project is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Determine if the project is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Log an action performed on this project.
     */
    public function logAction(string $action, string $description, array $oldData = null, array $newData = null): ?Log
    {
        // Safety check - don't log if no authenticated user
        if (!Auth::check()) {
            return null;
        }

        return Log::createLog(
            $this->id,
            Auth::id(),
            $action,
            $description,
            $oldData,
            $newData
        );
    }

    /**
     * Override the save method to log updates automatically.
     */
    public function save(array $options = [])
    {
        $isUpdate = $this->exists;
        $originalAttributes = $isUpdate ? $this->getOriginal() : [];
        $changes = $isUpdate ? $this->getDirty() : $this->getAttributes();

        $saved = parent::save($options);

        // Log the action if save was successful and we have changes
        if ($saved && Auth::check() && !empty($changes)) {
            // Don't log if only timestamps changed
            $significantChanges = array_diff_key($changes, array_flip(['updated_at', 'created_at']));
            
            if (!empty($significantChanges)) {
                $action = $isUpdate ? 'updated' : 'created';
                $description = $isUpdate 
                    ? "Project details updated: " . implode(', ', array_keys($significantChanges))
                    : "Project created";

                $this->logAction(
                    $action,
                    $description,
                    $isUpdate ? array_intersect_key($originalAttributes, $significantChanges) : null,
                    $significantChanges
                );
            }
        }

        return $saved;
    }

    /**
     * Approve the project proposal.
     */
    public function approve(string $feedback = null): bool
    {
        $oldStatus = $this->status;
        $this->status = 'approved';
        $this->supervisor_feedback = $feedback;
        $this->reviewed_at = now();
        
        $saved = $this->save();
        
        if ($saved) {
            $this->logAction(
                'approved',
                "Project proposal approved by supervisor",
                ['status' => $oldStatus, 'feedback' => null],
                ['status' => 'approved', 'feedback' => $feedback]
            );
        }
        
        return $saved;
    }

    /**
     * Reject the project proposal.
     */
    public function reject(string $feedback): bool
    {
        $oldStatus = $this->status;
        $this->status = 'rejected';
        $this->supervisor_feedback = $feedback;
        $this->reviewed_at = now();
        
        $saved = $this->save();
        
        if ($saved) {
            $this->logAction(
                'rejected',
                "Project proposal rejected by supervisor",
                ['status' => $oldStatus, 'feedback' => null],
                ['status' => 'rejected', 'feedback' => $feedback]
            );
        }
        
        return $saved;
    }

    /**
     * Start the project (move from approved to in_progress).
     */
    public function start(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }
        
        $oldStatus = $this->status;
        $this->status = 'in_progress';
        
        $saved = $this->save();
        
        if ($saved) {
            $this->logAction(
                'started',
                "Project started and moved to in progress",
                ['status' => $oldStatus],
                ['status' => 'in_progress']
            );
        }
        
        return $saved;
    }

    /**
     * Complete the project.
     */
    public function complete(): bool
    {
        if ($this->status !== 'in_progress') {
            return false;
        }
        
        $oldStatus = $this->status;
        $this->status = 'completed';
        
        $saved = $this->save();
        
        if ($saved) {
            $this->logAction(
                'completed',
                "Project marked as completed",
                ['status' => $oldStatus],
                ['status' => 'completed']
            );
        }
        
        return $saved;
    }

    /**
     * Submit the project proposal.
     */
    public function submit(): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        $oldStatus = $this->status;
        $this->status = 'pending_review';
        $this->submitted_at = now();
        
        $saved = $this->save();
        
        if ($saved) {
            $this->logAction(
                'submitted',
                "Project proposal submitted for review",
                ['status' => $oldStatus, 'submitted_at' => null],
                ['status' => 'pending_review', 'submitted_at' => $this->submitted_at]
            );
        }
        
        return $saved;
    }

    /**
     * Get status badge class for display.
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'bg-secondary',
            'pending_review' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'in_progress' => 'bg-info',
            'completed' => 'bg-primary',
            default => 'bg-secondary'
        };
    }

    /**
     * Get formatted status text.
     */
    public function getStatusText(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending_review' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            default => 'Unknown'
        };
    }

    /**
     * Scope a query to only include projects of active users.
     */
    public function scopeActiveUsers($query)
    {
        return $query->whereHas('student', function ($q) {
            $q->where('status', 'active');
        });
    }

    /**
     * Scope a query to only include projects by role.
     */
    public function scopeByUserRole($query, $role)
    {
        return $query->whereHas('student', function ($q) use ($role) {
            $q->where('role', $role);
        });
    }

    /**
     * Scope a query to only include projects pending review.
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    /**
     * Scope a query to only include projects by supervisor.
     */
    public function scopeBySupervisor($query, $supervisorId)
    {
        return $query->where('supervisor_id', $supervisorId);
    }

    /**
     * Scope a query to only include projects by student.
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Project $project) {
            if (Auth::check()) {
                $project->logAction('created', 'Project proposal created');
            }
        });

        static::updated(function (Project $project) {
            if (Auth::check() && $project->wasChanged()) {
                $changes = $project->getChanges();
                
                // Handle specific status changes
                if (isset($changes['status'])) {
                    $oldStatus = $project->getOriginal('status');
                    $newStatus = $changes['status'];
                    
                    $statusActions = [
                        'pending_review' => ['submitted', 'Project submitted for review'],
                        'approved' => ['approved', 'Project approved by supervisor'],
                        'rejected' => ['rejected', 'Project rejected by supervisor'],
                        'in_progress' => ['started', 'Project started'],
                        'completed' => ['completed', 'Project completed']
                    ];
                    
                    if (isset($statusActions[$newStatus])) {
                        [$action, $description] = $statusActions[$newStatus];
                        $project->logAction($action, $description, 
                            ['status' => $oldStatus], 
                            ['status' => $newStatus]
                        );
                        return; // Don't log generic update
                    }
                }
                
                // Log generic update
                $project->logAction('updated', 
                    'Project updated: ' . implode(', ', array_keys($changes)),
                    array_intersect_key($project->getOriginal(), $changes),
                    $changes
                );
            }
        });
    }

    /**
     * Check if student can create logs for this project.
     * Students can only log on APPROVED or IN_PROGRESS projects.
     */
    public function canStudentLog(): bool
    {
        // Must be approved or in progress
        if (!in_array($this->status, ['approved', 'in_progress'])) {
            return false;
        }

        // Must be authenticated
        if (!Auth::check()) {
            return false;
        }

        // Must be the project owner
        if (Auth::id() !== $this->student_id) {
            return false;
        }

        return true;
    }

    /**
     * Check if student can view logs for this project.
     * Students can view logs on any submitted project (not draft).
     */
    public function canStudentViewLogs(): bool
    {
        return $this->isSubmitted() && 
               Auth::check() && 
               Auth::id() === $this->student_id;
    }

    /**
     * Get user-friendly message explaining why logging is disabled.
     */
    public function getLoggingStatusMessage(): string
    {
        if (!Auth::check() || Auth::id() !== $this->student_id) {
            return 'You do not have permission to log on this project.';
        }

        return match($this->status) {
            'draft' => 'Complete and submit your proposal first before you can start logging.',
            'pending_review' => 'Your proposal is under review. You can start logging once it is approved.',
            'rejected' => 'This proposal was not approved. You cannot log progress on rejected proposals.',
            'approved' => 'Your proposal is approved! You can now start logging your progress.',
            'in_progress' => 'Keep logging your progress as you work on the project.',
            'completed' => 'This project is completed. No new logs can be added.',
            default => 'Logging is currently not available for this project.'
        };
    }

    /**
     * Start the project and enable logging (move from approved to in_progress).
     * This should be called when student begins actual work.
     */
    public function startWork(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        if (Auth::id() !== $this->student_id) {
            return false;
        }
        
        $oldStatus = $this->status;
        $this->status = 'in_progress';
        
        $saved = $this->save();
        
        if ($saved) {
            $this->logAction(
                'started',
                "Student started working on the project",
                ['status' => $oldStatus],
                ['status' => 'in_progress']
            );
        }
        
        return $saved;
    }

    /**
     * Create a student progress log entry.
     */
    public function addStudentLog(string $title, string $description, int $hoursWorked = null, int $completionPercentage = null, array $attachments = null): ?Log
    {
        if (!$this->canStudentLog()) {
            return null;
        }

        return Log::createLog(
            $this->id,
            Auth::id(),
            'student_progress',
            $description,
            null,
            [
                'title' => $title,
                'hours_worked' => $hoursWorked,
                'completion_percentage' => $completionPercentage,
                'attachments' => $attachments
            ]
        );
    }

    /**
     * Add a milestone completion log.
     */
    public function logMilestone(string $milestone, string $description, int $hoursWorked = null, int $completionPercentage = null): ?Log
    {
        if (!$this->canStudentLog()) {
            return null;
        }

        return Log::createLog(
            $this->id,
            Auth::id(),
            'milestone_completed',
            $description,
            null,
            [
                'milestone' => $milestone,
                'hours_worked' => $hoursWorked,
                'completion_percentage' => $completionPercentage
            ]
        );
    }

    /**
     * Get student progress logs only.
     */
    public function studentLogs(): HasMany
    {
        return $this->logs()->whereIn('action', ['student_progress', 'milestone_completed', 'student_update']);
    }

    /**
     * Get system logs only.
     */
    public function systemLogs(): HasMany
    {
        return $this->logs()->whereNotIn('action', ['student_progress', 'milestone_completed', 'student_update']);
    }

    /**
     * Get total hours logged by student.
     */
    public function getTotalLoggedHours(): int
    {
        return $this->studentLogs()
            ->whereNotNull('new_data->hours_worked')
            ->get()
            ->sum(function ($log) {
                return $log->new_data['hours_worked'] ?? 0;
            });
    }

    /**
     * Get latest completion percentage from student logs.
     */
    public function getCompletionPercentage(): int
    {
        $latestLog = $this->studentLogs()
            ->whereNotNull('new_data->completion_percentage')
            ->latest()
            ->first();

        return $latestLog ? ($latestLog->new_data['completion_percentage'] ?? 0) : 0;
    }
}
