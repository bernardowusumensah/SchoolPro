<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Student\StudentProjectController;
use App\Http\Controllers\Student\StudentLogController;
use App\Http\Controllers\Teacher\TeacherLogController;

Route::get('/dashboard/student', [StudentDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:student'])
    ->name('dashboard.student');

Route::get('/dashboard/teacher', [TeacherDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:teacher'])
    ->name('dashboard.teacher');

Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('dashboard.admin');

// Student Project Management Routes
Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Project Proposal Routes
    Route::get('project/proposal', [StudentProjectController::class, 'create'])->name('projects.proposal');
    Route::post('project/proposal', [StudentProjectController::class, 'store'])->name('projects.store');
    Route::get('project/status', [StudentProjectController::class, 'status'])->name('projects.status');
    Route::get('project/edit', [StudentProjectController::class, 'edit'])->name('projects.edit');
    Route::put('project/edit', [StudentProjectController::class, 'update'])->name('projects.update');
    Route::get('/project/{project}/edit', [StudentProjectController::class, 'editProject'])->name('projects.editProject');
    // Full project resource for other operations
    Route::resource('projects', StudentProjectController::class)->except(['create', 'store', 'edit', 'update']);
    
    // Log Management Routes
    Route::get('logs', [StudentLogController::class, 'index'])->name('logs.index');
    Route::get('logs/create', [StudentLogController::class, 'create'])->name('logs.create');
    Route::post('logs', [StudentLogController::class, 'store'])->name('logs.store');
    Route::get('logs/{log}', [StudentLogController::class, 'show'])->name('logs.show');
    Route::get('logs/{log}/edit', [StudentLogController::class, 'edit'])->name('logs.edit');
    Route::put('logs/{log}', [StudentLogController::class, 'update'])->name('logs.update');
    Route::delete('logs/{log}', [StudentLogController::class, 'destroy'])->name('logs.destroy');
    Route::get('logs/{log}/download', [StudentLogController::class, 'downloadAttachment'])->name('logs.download');
    Route::get('logs/feedback', [StudentLogController::class, 'feedback'])->name('logs.feedback');
    Route::post('logs/{log}/acknowledge', [StudentLogController::class, 'acknowledgeFeedback'])->name('logs.acknowledge');
});

// Teacher Proposal Management Routes
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Proposal CRUD operations
    Route::get('proposals', [TeacherDashboardController::class, 'proposals'])->name('proposals.index');
    Route::get('proposals/{project}', [TeacherDashboardController::class, 'show'])->name('proposals.show');
    Route::patch('proposals/{project}/approve', [TeacherDashboardController::class, 'approve'])->name('proposals.approve');
    Route::patch('proposals/{project}/reject', [TeacherDashboardController::class, 'reject'])->name('proposals.reject');
    Route::patch('proposals/{project}/revision', [TeacherDashboardController::class, 'requestRevision'])->name('proposals.revision');
    Route::delete('proposals/{project}', [TeacherDashboardController::class, 'destroy'])->name('proposals.destroy');
    
    // Log Management Routes
    Route::get('logs', [TeacherLogController::class, 'index'])->name('logs.index');
    Route::get('logs/unreviewed', [TeacherLogController::class, 'unreviewed'])->name('logs.unreviewed');
    Route::get('logs/analytics', [TeacherLogController::class, 'analytics'])->name('logs.analytics');
    Route::get('logs/export', [TeacherLogController::class, 'exportLogs'])->name('logs.export');
    Route::get('logs/{log}', [TeacherLogController::class, 'show'])->name('logs.show');
    Route::patch('logs/{log}/feedback', [TeacherLogController::class, 'provideFeedback'])->name('logs.feedback');
    Route::patch('logs/{log}/feedback/update', [TeacherLogController::class, 'updateFeedback'])->name('logs.feedback.update');
    Route::patch('logs/{log}/rating', [TeacherLogController::class, 'rateLog'])->name('logs.rating');
    Route::patch('logs/{log}/notes', [TeacherLogController::class, 'updatePrivateNotes'])->name('logs.notes');
    Route::patch('logs/{log}/followup', [TeacherLogController::class, 'toggleFollowup'])->name('logs.followup');
    Route::patch('logs/{log}/reviewed', [TeacherLogController::class, 'markReviewed'])->name('logs.reviewed');
    Route::get('students/{student}/logs', [TeacherLogController::class, 'studentLogs'])->name('students.logs');
    Route::get('projects/{project}/logs', [TeacherLogController::class, 'projectLogs'])->name('projects.logs');
});

// Admin User Management Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::patch('users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');
    Route::patch('users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::get('projects', [AdminDashboardController::class, 'projects'])->name('projects.index');
});

// Optionally, remove or update the generic dashboard route
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
