<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouncilController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 1. Student Routes (Middleware: auth, role:student,admin)
Route::middleware(['auth', 'role:student,admin'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    // Task Management terhubung ke TaskController:
    Route::prefix('tasks')->as('tasks.')->group(function () {
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::patch('/{task}/toggle', [TaskController::class, 'toggle'])->name('toggle');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    });
});

// 2. Teacher Routes (Middleware: auth, role:teacher)
Route::middleware(['auth', 'role:teacher,admin'])->prefix('teacher')->as('teachers.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::post('/tasks', fn() => abort(501, 'Penerbitan tugas guru belum diimplementasikan.'))->name('tasks.store');

    // Rute pengumuman bisa berada di dalam group ini jika khusus untuk guru
    Route::post('/announcements', fn() => abort(501, 'Pengumuman belum diimplementasikan.'))->name('announcements.store');
});

// 3. Council (OSIS) Routes (Middleware: auth, role:council)
Route::middleware(['auth', 'role:council,admin'])->group(function () {
    Route::get('/council', [CouncilController::class, 'index'])->name('council.index');

    // Event Management
    Route::prefix('school-events')->as('school-events.')->group(function () {
        Route::post('/', fn() => abort(501, 'Acara sekolah belum diimplementasikan.'))->name('store');
        Route::put('/{schoolEvent}', fn() => abort(501, 'Pembaruan acara belum diimplementasikan.'))->name('update');
        Route::delete('/{schoolEvent}', fn() => abort(501, 'Penghapusan acara belum diimplementasikan.'))->name('destroy');
    });
});

// 4. Admin Routes (Middleware: auth, role:admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // User Management
    Route::prefix('users')->as('users.')->group(function () {
        Route::post('/', fn() => abort(501, 'Manajemen pengguna belum diimplementasikan.'))->name('store');
        Route::put('/{user}', fn() => abort(501, 'Pembaruan pengguna belum diimplementasikan.'))->name('update');
        Route::delete('/{user}', fn() => abort(501, 'Penghapusan pengguna belum diimplementasikan.'))->name('destroy');
    });
});

// Calendar
Route::get('/api/calendar/month', function () {
    $year = (int) request('year', now()->year);
    $month = (int) request('month', now()->month);
    $date = now()->setDate($year, $month, 1)->startOfMonth();

    $monthNames = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    return response()->json([
        'year' => $year,
        'month' => $month,
        'month_name' => $monthNames[$month] ?? $date->format('F'),
        'first_day_of_week' => $date->dayOfWeek,
        'days_in_month' => $date->daysInMonth,
        'today_date' => now()->toDateString(),
        'agendas' => (object) [],
    ]);
})->name('calendar.month');
