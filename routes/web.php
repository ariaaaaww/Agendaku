<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouncilController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SchoolEventController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login-post');
Route::get('/register', [AuthController::class, 'registerView'])->name('register-view');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register-post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 1. Student Routes (Middleware: auth, role:student,admin)
Route::middleware(['auth', 'role:student,admin'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
});

// Task Management (Shared: Accessible by Student, Teacher, and Admin)
Route::middleware(['auth', 'role:student,teacher,admin'])->group(function () {
    Route::prefix('tasks')->as('tasks.')->group(function () {
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::patch('/{task}/toggle', [TaskController::class, 'toggle'])->name('toggle');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    });
});

// 2. Teacher Routes (Middleware: auth, role:teacher,admin)
Route::middleware(['auth', 'role:teacher,admin'])->prefix('teacher')->as('teachers.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::post('/tasks', [TeacherController::class, 'storeTask'])->name('tasks.store');
});

// Route Pengumuman Guru
Route::post('/announcements', [AnnouncementController::class, 'store'])
    ->middleware(['auth', 'role:teacher,admin'])
    ->name('announcements.store');

Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
    ->middleware(['auth', 'role:teacher,admin'])
    ->name('announcements.destroy');

// 3. Council (OSIS) Routes (Middleware: auth, role:council,admin)
Route::middleware(['auth', 'role:council,admin'])->group(function () {
    Route::get('/council', [CouncilController::class, 'index'])->name('council.index');
    // CRUD Acara Sekolah OSIS
    Route::prefix('school-events')->as('school-events.')->group(function () {
        Route::post('/', [SchoolEventController::class, 'store'])->name('store');
        Route::put('/{schoolEvent}', [SchoolEventController::class, 'update'])->name('update');
        Route::delete('/{schoolEvent}', [SchoolEventController::class, 'destroy'])->name('destroy');
    });
});

// 4. Admin Routes (Middleware: auth, role:admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // User Management CRUD
    Route::prefix('users')->as('users.')->group(function () {
        Route::post('/', [AdminController::class, 'storeUser'])->name('store');
        Route::put('/{user}', [AdminController::class, 'updateUser'])->name('update');
        Route::delete('/{user}', [AdminController::class, 'destroyUser'])->name('destroy');
    });
});

// Calendar API
Route::get('/api/calendar/month', [CalendarController::class, 'getMonthAgenda'])->name('calendar.month');