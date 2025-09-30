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
        'student_id',
        'supervisor_id',
        'status',
        'final_document_path',
        'presentation_path',
        'source_code_path',
        'submission_note',
        'submitted_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'submitted_at' => 'datetime',
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
