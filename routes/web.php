<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SchoolEventController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCouncilController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest)
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login-post');
Route::get('/register', [AuthController::class, 'registerView'])->name('register-view');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register-post');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Calendar JSON API
    Route::get('/api/calendar/month', [CalendarController::class, 'monthData'])->name('calendar.month');

    // Shared Task Routes
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // 1. Student Dashboard
    Route::get('/students', [StudentController::class, 'student'])->name('students.index');

    // 2. Teacher Panel & Actions
    Route::middleware('role:guru,admin')->group(function () {
        Route::get('/teacher', [TeacherController::class, 'teacher'])->name('teachers.index');
        Route::post('/teacher/tasks', [TaskController::class, 'guruStore'])->name('teachers.tasks.store');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    });

    // 3. OSIS / Council Panel & Actions
    Route::middleware('role:osis,admin')->group(function () {
        Route::get('/council', [StudentCouncilController::class, 'council'])->name('council.index');
        Route::post('/school-events', [SchoolEventController::class, 'store'])->name('school-events.store');
        Route::put('/school-events/{schoolEvent}', [SchoolEventController::class, 'update'])->name('school-events.update');
        Route::delete('/school-events/{schoolEvent}', [SchoolEventController::class, 'destroy'])->name('school-events.destroy');
    });

    // 4. Admin Panel & User Management
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'admin'])->name('admin.index');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    });
});
