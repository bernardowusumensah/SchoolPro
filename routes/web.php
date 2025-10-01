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
    Route::post('proposals/bulk-action', [TeacherDashboardController::class, 'bulkAction'])->name('proposals.bulk');
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
