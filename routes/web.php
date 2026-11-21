<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;

if (! function_exists('agendakuEmptyDashboard')) {
    function agendakuEmptyDashboard(string $title): array
    {
        return [
            'title' => $title,
            'announcement' => null,
            'alertData' => [
                'type' => 'empty',
                'count' => 0,
                'message' => 'Tidak ada tenggat waktu atau agenda esok hari. Semuanya terkendali!',
            ],
            'pendingTasks' => collect(),
            'schoolEvents' => collect(),
            'allTasks' => collect(),
            'osisEvents' => collect(),
            'allEvents' => collect(),
            'allUsers' => collect(),
            'studentProgress' => collect(),
            'totalUsers' => 0,
            'totalCompletedTasks' => 0,
            'totalPendingTasks' => 0,
        ];
    }
}

/**
 * Cek hak akses role. Return null kalau boleh, atau redirect kalau tidak boleh.
 * Admin selalu boleh. Array kosong = hanya admin.
 */
if (! function_exists('agendakuGuard')) {
    function agendakuGuard(array $roles = [])
    {
        $user = auth()->user();

        if ($user->role === 'admin' || in_array($user->role, $roles, true)) {
            return null;
        }

        return match ($user->role) {
            'guru'  => redirect()->route('teachers.index'),
            'osis'  => redirect()->route('council.index'),
            default => redirect()->route('students.index'),
        };
    }
}

// ===== Halaman publik (guest boleh) =====
Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login-post');
Route::get('/register', [AuthController::class, 'registerView'])->name('register-view');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register-post');

// ===== Halaman yang butuh login (guest otomatis ke /login) =====
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/students', function () {
        if ($r = agendakuGuard(['siswa'])) return $r;
        return view('students.index', agendakuEmptyDashboard('Agendaku - Siswa'));
    })->name('students.index');

    Route::get('/teacher', function () {
        if ($r = agendakuGuard(['guru'])) return $r;
        return view('teachers.index', agendakuEmptyDashboard('Agendaku - Guru'));
    })->name('teachers.index');

    Route::get('/council', function () {
        if ($r = agendakuGuard(['osis'])) return $r;
        return view('council.index', agendakuEmptyDashboard('Agendaku - OSIS'));
    })->name('council.index');

    Route::get('/admin', function () {
        if ($r = agendakuGuard()) return $r; // hanya admin

        $data = agendakuEmptyDashboard('Agendaku - Admin');
        $data['allUsers']   = User::latest()->get();
        $data['totalUsers'] = $data['allUsers']->count();

        return view('admin.index', $data);
    })->name('admin.index');

    // CRUD pengguna (pengecekan admin ada di dalam controller)
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Route lama dipertahankan agar Blade tidak error saat memanggil route()
    Route::post('/tasks', fn () => abort(501, 'Penyimpanan tugas belum diimplementasikan.'))->name('tasks.store');
    Route::put('/tasks/{task}', fn () => abort(501, 'Pembaruan tugas belum diimplementasikan.'))->name('tasks.update');
    Route::patch('/tasks/{task}/toggle', fn () => abort(501, 'Toggle tugas belum diimplementasikan.'))->name('tasks.toggle');
    Route::delete('/tasks/{task}', fn () => abort(501, 'Penghapusan tugas belum diimplementasikan.'))->name('tasks.destroy');
    Route::post('/teacher/tasks', fn () => abort(501, 'Penerbitan tugas guru belum diimplementasikan.'))->name('teachers.tasks.store');
    Route::post('/announcements', fn () => abort(501, 'Pengumuman belum diimplementasikan.'))->name('announcements.store');
    Route::post('/school-events', fn () => abort(501, 'Acara sekolah belum diimplementasikan.'))->name('school-events.store');
    Route::put('/school-events/{schoolEvent}', fn () => abort(501, 'Pembaruan acara belum diimplementasikan.'))->name('school-events.update');
    Route::delete('/school-events/{schoolEvent}', fn () => abort(501, 'Penghapusan acara belum diimplementasikan.'))->name('school-events.destroy');

    Route::get('/api/calendar/month', function () {
        $year = (int) request('year', now()->year);
        $month = (int) request('month', now()->month);
        $date = now()->setDate($year, $month, 1)->startOfMonth();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
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
});