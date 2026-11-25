<?php

use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\SchoolEvent;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function makeUser(UserRole $role, string $username, string $name = 'Test User', string $password = 'password123'): User
{
    return User::create([
        'name' => $name,
        'username' => $username,
        'password' => Hash::make($password),
        'role' => $role,
    ]);
}

/*
|--------------------------------------------------------------------------
| MODUL 1: GUEST (AUTENTIKASI & REGISTRASI)
|--------------------------------------------------------------------------
*/

it('TC-001: redirects root URL to login', function () {
    $this->get('/')->assertRedirect(route('login'));
});

it('TC-002: renders login page for guests', function () {
    $this->get('/login')
        ->assertSuccessful()
        ->assertSee('Masuk')
        ->assertSee('username');
});

it('TC-003: logs in student with valid credentials and redirects to /students', function () {
    $student = makeUser(UserRole::STUDENT, 'siswa01', 'Siswa Satu');

    $this->post('/login', [
        'username' => 'siswa01',
        'password' => 'password123',
    ])->assertRedirect(route('students.index'));

    $this->assertAuthenticatedAs($student);
});

it('TC-004: logs in admin with valid credentials and redirects to /admin', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin01', 'Admin Satu');

    $this->post('/login', [
        'username' => 'admin01',
        'password' => 'password123',
    ])->assertRedirect(route('admin.index'));

    $this->assertAuthenticatedAs($admin);
});

it('TC-005: rejects login with wrong password', function () {
    makeUser(UserRole::STUDENT, 'siswa02', 'Siswa Dua');

    $this->post('/login', [
        'username' => 'siswa02',
        'password' => 'wrongpass',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('TC-006: validates required fields on login', function () {
    $this->post('/login', [])
        ->assertSessionHasErrors(['username', 'password']);
});

it('TC-007: renders register page for guests', function () {
    $this->get('/register')
        ->assertSuccessful()
        ->assertSee('Daftar');
});

it('TC-008: registers new student account, enforces student role, and logs in', function () {
    $response = $this->post('/register', [
        'fullname' => 'Budi Santoso',
        'username' => 'budi_s',
        'password' => 'secret123',
        'role' => 'admin', // Manipulasi role harus diabaikan
    ]);

    $response->assertRedirect(route('students.index'));

    $user = User::where('username', 'budi_s')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe(UserRole::STUDENT);
    $this->assertAuthenticatedAs($user);
});

it('TC-009: rejects registration with duplicate username', function () {
    makeUser(UserRole::STUDENT, 'existing_user');

    $this->post('/register', [
        'fullname' => 'User Baru',
        'username' => 'existing_user',
        'password' => 'pass123',
    ])->assertSessionHasErrors('username');
});

it('TC-010: blocks guests from protected routes', function (string $url) {
    $this->get($url)->assertRedirect(route('login'));
})->with([
    '/students',
    '/teacher',
    '/council',
    '/admin',
]);

/*
|--------------------------------------------------------------------------
| MODUL 2: ADMIN PANEL
|--------------------------------------------------------------------------
*/

it('TC-011 & TC-012 & TC-013 & TC-014: renders admin dashboard with real DB stats, student progress, and user list', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin_main', 'Super Admin');
    $student = makeUser(UserRole::STUDENT, 'siswa_stat', 'Siswa Statistik');

    Task::create([
        'user_id' => $student->id,
        'title' => 'Tugas Selesai',
        'category' => 'sekolah',
        'deadline' => now()->addDays(2),
        'is_completed' => true,
    ]);
    Task::create([
        'user_id' => $student->id,
        'title' => 'Tugas Pending',
        'category' => 'pribadi',
        'deadline' => now()->addDays(3),
        'is_completed' => false,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertSuccessful()
        ->assertSee('Super Admin')
        ->assertSee('Siswa Statistik')
        ->assertViewHas('totalUsers', 2)
        ->assertViewHas('totalCompletedTasks', 1)
        ->assertViewHas('totalPendingTasks', 1);
});

it('TC-015: admin can create new user via JSON endpoint', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin_create');

    $this->actingAs($admin)
        ->postJson('/admin/users', [
            'fullname' => 'Guru Baru',
            'username' => 'guru_baru',
            'role' => 'teacher',
            'password' => 'pass1234',
        ])
        ->assertSuccessful()
        ->assertJson(['success' => true]);

    $created = User::where('username', 'guru_baru')->first();
    expect($created)->not->toBeNull();
    expect($created->role)->toBe(UserRole::TEACHER);
    expect(Hash::check('pass1234', $created->password))->toBeTrue();
});

it('TC-016: admin cannot create user with duplicate username', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin_dup');
    makeUser(UserRole::STUDENT, 'taken_user');

    $this->actingAs($admin)
        ->postJson('/admin/users', [
            'fullname' => 'Duplikat',
            'username' => 'taken_user',
            'role' => 'student',
            'password' => 'pass1234',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('username');
});

it('TC-017 & TC-018: admin can update user data and optional password', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin_edit');
    $target = makeUser(UserRole::STUDENT, 'target_u', 'Nama Lama');

    $this->actingAs($admin)
        ->putJson("/admin/users/{$target->id}", [
            'fullname' => 'Nama Baru',
            'username' => 'target_u_new',
            'role' => 'council',
            'password' => 'newpass99',
        ])
        ->assertSuccessful()
        ->assertJson(['success' => true]);

    $target->refresh();
    expect($target->name)->toBe('Nama Baru');
    expect($target->username)->toBe('target_u_new');
    expect($target->role)->toBe(UserRole::COUNCIL);
    expect(Hash::check('newpass99', $target->password))->toBeTrue();
});

it('TC-019: admin can delete another user', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin_del');
    $target = makeUser(UserRole::STUDENT, 'user_to_delete');

    $this->actingAs($admin)
        ->deleteJson("/admin/users/{$target->id}")
        ->assertSuccessful()
        ->assertJson(['success' => true]);

    expect(User::find($target->id))->toBeNull();
});

it('TC-020 & TC-021: admin cannot delete own account via backend and delete button hidden in view', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin_self');

    $this->actingAs($admin)
        ->deleteJson("/admin/users/{$admin->id}")
        ->assertForbidden()
        ->assertJson(['success' => false]);

    expect(User::find($admin->id))->not->toBeNull();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertDontSee("deleteUserAccount({$admin->id})");
});

it('TC-022: admin can access all role dashboards', function (string $url) {
    $admin = makeUser(UserRole::ADMIN, 'admin_super_' . md5($url));

    $this->actingAs($admin)->get($url)->assertSuccessful();
})->with([
    '/teacher',
    '/council',
    '/students',
    '/admin',
]);

it('TC-023: logout terminates session and prevents direct access afterwards', function () {
    $admin = makeUser(UserRole::ADMIN, 'admin_logout');

    $this->actingAs($admin)
        ->post('/logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
    $this->get('/admin')->assertRedirect(route('login'));
});

/*
|--------------------------------------------------------------------------
| MODUL 3: TEACHER (GURU)
|--------------------------------------------------------------------------
*/

it('TC-024: renders teacher dashboard', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru01', 'Pak Guru');

    $this->actingAs($teacher)
        ->get('/teacher')
        ->assertSuccessful()
        ->assertSee('Panel Pengelolaan Guru');
});

it('TC-025 & TC-026 & TC-027: teacher creates announcement replacing old active one, views banner, and deletes it', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru_ann');

    $old = Announcement::create([
        'user_id' => $teacher->id,
        'content' => 'Pengumuman Lama',
        'is_active' => true,
    ]);

    $this->actingAs($teacher)
        ->post('/announcements', ['content' => 'Pengumuman Baru Aktif'])
        ->assertRedirect();

    expect($old->fresh()->is_active)->toBeFalse();
    $new = Announcement::where('content', 'Pengumuman Baru Aktif')->first();
    expect($new)->not->toBeNull();
    expect($new->is_active)->toBeTrue();

    $this->actingAs($teacher)
        ->get('/teacher')
        ->assertSee('Pengumuman Baru Aktif');

    $this->actingAs($teacher)
        ->delete("/announcements/{$new->id}")
        ->assertRedirect();

    expect(Announcement::find($new->id))->toBeNull();
});

it('TC-028: teacher distributes school task to all students', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru_task');
    $s1 = makeUser(UserRole::STUDENT, 'murid1');
    $s2 = makeUser(UserRole::STUDENT, 'murid2');

    $this->actingAs($teacher)
        ->post('/teacher/tasks', [
            'title' => 'PR Matematika Bab 5',
            'category' => 'sekolah',
            'deadline' => now()->addDays(5)->toDateString(),
        ])
        ->assertRedirect();

    expect(Task::where('user_id', $s1->id)->where('title', 'PR Matematika Bab 5')->exists())->toBeTrue();
    expect(Task::where('user_id', $s2->id)->where('title', 'PR Matematika Bab 5')->exists())->toBeTrue();
    expect(Task::where('user_id', $teacher->id)->where('title', 'PR Matematika Bab 5')->exists())->toBeFalse();
});

it('TC-029: teacher publishing category acara stores in school_events', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru_event');

    $this->actingAs($teacher)
        ->post('/teacher/tasks', [
            'title' => 'Ujian Tengah Semester',
            'category' => 'acara',
            'deadline' => now()->addDays(7)->toDateString(),
        ])
        ->assertRedirect();

    expect(SchoolEvent::where('title', 'Ujian Tengah Semester')->exists())->toBeTrue();
});

it('TC-030: teacher can update and delete system task', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru_edit');
    $student = makeUser(UserRole::STUDENT, 'murid_edit');
    $task = Task::create([
        'user_id' => $student->id,
        'created_by_id' => $teacher->id,
        'title' => 'Tugas Awal',
        'category' => 'sekolah',
        'deadline' => now()->addDays(2),
    ]);

    $this->actingAs($teacher)
        ->put("/tasks/{$task->id}", [
            'title' => 'Tugas Diubah',
            'category' => 'sekolah',
            'deadline' => now()->addDays(4)->toDateString(),
        ])
        ->assertRedirect();

    expect($task->fresh()->title)->toBe('Tugas Diubah');

    $this->actingAs($teacher)
        ->delete("/tasks/{$task->id}")
        ->assertRedirect();

    expect(Task::find($task->id))->toBeNull();
});

it('TC-031: teacher calendar displays school tasks and events', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru_cal');
    $student = makeUser(UserRole::STUDENT, 'murid_cal');
    $targetDate = now()->startOfMonth()->addDays(10)->toDateString();

    Task::create([
        'user_id' => $student->id,
        'created_by_id' => $teacher->id,
        'title' => 'Kalender Tugas Guru',
        'category' => 'sekolah',
        'deadline' => $targetDate,
    ]);
    SchoolEvent::create([
        'user_id' => $teacher->id,
        'title' => 'Kalender Event Sekolah',
        'event_date' => $targetDate,
    ]);

    $this->actingAs($teacher)
        ->get('/teacher')
        ->assertSee('Kalender Tugas Guru')
        ->assertSee('Kalender Event Sekolah');
});

it('TC-032 & TC-033: teacher is forbidden from accessing admin and council panels', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru_auth');

    $this->actingAs($teacher)->get('/admin')->assertForbidden();
    $this->actingAs($teacher)->get('/council')->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| MODUL 4: STUDENT COUNCIL (OSIS)
|--------------------------------------------------------------------------
*/

it('TC-034 & TC-035 & TC-036 & TC-037: council manages school events (CRUD) and calendar displays them', function () {
    $council = makeUser(UserRole::COUNCIL, 'osis01', 'Ketua OSIS');
    $eventDate = now()->startOfMonth()->addDays(15)->toDateString();

    $this->actingAs($council)
        ->post('/school-events', [
            'title' => 'Pentas Seni 2026',
            'event_date' => $eventDate,
        ])
        ->assertRedirect();

    $event = SchoolEvent::where('title', 'Pentas Seni 2026')->first();
    expect($event)->not->toBeNull();
    expect($event->user_id)->toBe($council->id);

    $this->actingAs($council)
        ->get('/council')
        ->assertSuccessful()
        ->assertSee('Pentas Seni 2026');

    $this->actingAs($council)
        ->put("/school-events/{$event->id}", [
            'title' => 'Pentas Seni Akbar',
            'event_date' => $eventDate,
        ])
        ->assertRedirect();

    expect($event->fresh()->title)->toBe('Pentas Seni Akbar');

    $this->actingAs($council)
        ->delete("/school-events/{$event->id}")
        ->assertRedirect();

    expect(SchoolEvent::find($event->id))->toBeNull();
});

it('TC-038: validates required event_date on school event store', function () {
    $council = makeUser(UserRole::COUNCIL, 'osis_val');

    $this->actingAs($council)
        ->post('/school-events', ['title' => 'Acara Tanpa Tanggal'])
        ->assertSessionHasErrors('event_date');
});

it('TC-039 & TC-040: council is forbidden from accessing teacher and admin panels', function () {
    $council = makeUser(UserRole::COUNCIL, 'osis_auth');

    $this->actingAs($council)->get('/teacher')->assertForbidden();
    $this->actingAs($council)->get('/admin')->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| MODUL 5: STUDENT (SISWA)
|--------------------------------------------------------------------------
*/

it('TC-041 & TC-042 & TC-043: student views own pending tasks isolated from other students', function () {
    $s1 = makeUser(UserRole::STUDENT, 'siswa_a', 'Siswa A');
    $s2 = makeUser(UserRole::STUDENT, 'siswa_b', 'Siswa B');

    Task::create([
        'user_id' => $s1->id,
        'title' => 'Tugas Khusus A',
        'category' => 'pribadi',
        'deadline' => now()->addDays(2),
    ]);
    Task::create([
        'user_id' => $s2->id,
        'title' => 'Tugas Khusus B',
        'category' => 'pribadi',
        'deadline' => now()->addDays(2),
    ]);

    $this->actingAs($s1)
        ->get('/students')
        ->assertSuccessful()
        ->assertSee('Tugas Khusus A')
        ->assertDontSee('Tugas Khusus B');
});

it('TC-044: student can toggle task completion status', function () {
    $s1 = makeUser(UserRole::STUDENT, 'siswa_toggle');
    $task = Task::create([
        'user_id' => $s1->id,
        'title' => 'Tugas Toggle',
        'category' => 'pribadi',
        'deadline' => now()->addDays(1),
        'is_completed' => false,
    ]);

    $this->actingAs($s1)
        ->patch("/tasks/{$task->id}/toggle")
        ->assertRedirect();

    expect($task->fresh()->is_completed)->toBeTrue();
});

it('TC-045 & TC-046: student sees overdue and today alert banners accurately', function () {
    $s1 = makeUser(UserRole::STUDENT, 'siswa_alert');

    Task::create([
        'user_id' => $s1->id,
        'title' => 'Tugas Hari Ini',
        'category' => 'sekolah',
        'deadline' => Carbon::today(),
        'is_completed' => false,
    ]);

    $this->actingAs($s1)
        ->get('/students')
        ->assertSee('Tenggat Waktu Hari Ini');

    Task::create([
        'user_id' => $s1->id,
        'title' => 'Tugas Terlewat',
        'category' => 'sekolah',
        'deadline' => Carbon::yesterday(),
        'is_completed' => false,
    ]);

    $this->actingAs($s1)
        ->get('/students')
        ->assertSee('Tugas Melewati Batas Waktu!');
});

it('TC-047 & TC-048 & TC-049: student sees active announcement, school events, and integrated calendar', function () {
    $s1 = makeUser(UserRole::STUDENT, 'siswa_view');
    $teacher = makeUser(UserRole::TEACHER, 'guru_view');
    $eventDate = now()->startOfMonth()->addDays(12)->toDateString();

    Announcement::create([
        'user_id' => $teacher->id,
        'content' => 'Libur Nasional Besok',
        'is_active' => true,
    ]);
    SchoolEvent::create([
        'user_id' => $teacher->id,
        'title' => 'Upacara Bendera',
        'event_date' => $eventDate,
    ]);

    $this->actingAs($s1)
        ->get('/students')
        ->assertSee('Libur Nasional Besok')
        ->assertSee('Upacara Bendera');
});

it('TC-050 & TC-051 & TC-052: student is forbidden from accessing teacher, council, and admin panels', function () {
    $s1 = makeUser(UserRole::STUDENT, 'siswa_auth');

    $this->actingAs($s1)->get('/teacher')->assertForbidden();
    $this->actingAs($s1)->get('/council')->assertForbidden();
    $this->actingAs($s1)->get('/admin')->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| MODUL 6: SHARED CALENDAR API
|--------------------------------------------------------------------------
*/

it('TC-053 & TC-054 & TC-055 & TC-056: calendar API returns JSON grouped by date and handles empty months', function () {
    $teacher = makeUser(UserRole::TEACHER, 'guru_api');
    $student = makeUser(UserRole::STUDENT, 'siswa_api');

    Task::create([
        'user_id' => $student->id,
        'created_by_id' => $teacher->id,
        'title' => 'Tugas API Sekolah',
        'category' => 'sekolah',
        'deadline' => '2026-11-20',
    ]);
    SchoolEvent::create([
        'user_id' => $teacher->id,
        'title' => 'Acara API OSIS',
        'event_date' => '2026-11-20',
    ]);

    // Bulan ada agenda
    $this->getJson('/api/calendar/month?year=2026&month=11')
        ->assertSuccessful()
        ->assertJson([
            'year' => 2026,
            'month' => 11,
            'month_name' => 'November',
        ])
        ->assertJsonStructure([
            'year',
            'month',
            'month_name',
            'first_day_of_week',
            'days_in_month',
            'today_date',
            'agendas' => [
                '2026-11-20',
            ],
        ]);

    // Bulan kosong (tidak ada agenda)
    $this->getJson('/api/calendar/month?year=2027&month=1')
        ->assertSuccessful()
        ->assertJson([
            'year' => 2027,
            'month' => 1,
            'month_name' => 'Januari',
            'agendas' => [],
        ]);
});
