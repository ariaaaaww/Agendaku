<?php

use Illuminate\Support\Facades\Route;

/**
 * Data kosong agar Blade dashboard tidak error sebelum backend dibangun ulang.
 *
 * @return array<string, mixed>
 */
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

/*
|--------------------------------------------------------------------------
| Halaman frontend Agendaku
|--------------------------------------------------------------------------
|
| Controller, autentikasi, dan middleware khusus aplikasi sudah dihapus
| agar bisa dibangun ulang. Route di bawah hanya menampilkan Blade yang
| sudah ada. Form POST dan aksi CRUD sengaja tidak diproses.
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login', ['title' => 'Agendaku - Login']);
})->name('login');

Route::get('/register', function () {
    return view('auth.register', ['title' => 'Agendaku - Register']);
})->name('register-view');

Route::get('/students', function () {
    return view('students.index', agendakuEmptyDashboard('Agendaku - Siswa'));
})->name('students.index');

Route::get('/teacher', function () {
    return view('teachers.index', agendakuEmptyDashboard('Agendaku - Guru'));
})->name('teachers.index');

Route::get('/council', function () {
    return view('council.index', agendakuEmptyDashboard('Agendaku - OSIS'));
})->name('council.index');

Route::get('/admin', function () {
    return view('admin.index', agendakuEmptyDashboard('Agendaku - Admin'));
})->name('admin.index');

/*
| Nama route lama dipertahankan agar Blade dan JavaScript tidak error
| saat memanggil route(). Handler tidak menyimpan data dan tidak login.
*/
Route::post('/login', fn () => abort(501, 'Proses login belum diimplementasikan.'))->name('login-post');
Route::post('/register', fn () => abort(501, 'Proses register belum diimplementasikan.'))->name('register-post');
Route::post('/logout', fn () => abort(501, 'Proses logout belum diimplementasikan.'))->name('logout');
Route::post('/tasks', fn () => abort(501, 'Penyimpanan tugas belum diimplementasikan.'))->name('tasks.store');
Route::put('/tasks/{task}', fn () => abort(501, 'Pembaruan tugas belum diimplementasikan.'))->name('tasks.update');
Route::patch('/tasks/{task}/toggle', fn () => abort(501, 'Toggle tugas belum diimplementasikan.'))->name('tasks.toggle');
Route::delete('/tasks/{task}', fn () => abort(501, 'Penghapusan tugas belum diimplementasikan.'))->name('tasks.destroy');
Route::post('/teacher/tasks', fn () => abort(501, 'Penerbitan tugas guru belum diimplementasikan.'))->name('teachers.tasks.store');
Route::post('/announcements', fn () => abort(501, 'Pengumuman belum diimplementasikan.'))->name('announcements.store');
Route::post('/school-events', fn () => abort(501, 'Acara sekolah belum diimplementasikan.'))->name('school-events.store');
Route::put('/school-events/{schoolEvent}', fn () => abort(501, 'Pembaruan acara belum diimplementasikan.'))->name('school-events.update');
Route::delete('/school-events/{schoolEvent}', fn () => abort(501, 'Penghapusan acara belum diimplementasikan.'))->name('school-events.destroy');
Route::post('/admin/users', fn () => abort(501, 'Manajemen pengguna belum diimplementasikan.'))->name('admin.users.store');
Route::put('/admin/users/{user}', fn () => abort(501, 'Pembaruan pengguna belum diimplementasikan.'))->name('admin.users.update');
Route::delete('/admin/users/{user}', fn () => abort(501, 'Penghapusan pengguna belum diimplementasikan.'))->name('admin.users.destroy');

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
