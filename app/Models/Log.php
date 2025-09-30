<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'student_id',
        'content',
        'file_path',
        'supervisor_feedback',
        'feedback_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'feedback_date' => 'datetime',
    ];

    /**
     * Get the project that owns the log.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the student that owns the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the student that owns the log (alias for clarity).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
