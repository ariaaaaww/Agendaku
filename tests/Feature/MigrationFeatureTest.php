<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('user can login successfully with username and password', function () {
    $response = $this->post('/login', [
        'username' => 'siswa',
        'password' => '123',
    ]);

    $response->assertRedirect('/students');
    $this->assertAuthenticated();
});

test('authenticated student can fetch calendar month json data', function () {
    $user = User::where('username', 'siswa')->first();

    $response = $this->actingAs($user)->getJson('/api/calendar/month?year=2026&month=10');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'year',
        'month',
        'month_name',
        'first_day_of_week',
        'days_in_month',
        'today_date',
        'agendas',
    ]);
});

test('student can create a new task into database', function () {
    $user = User::where('username', 'siswa')->first();

    $response = $this->actingAs($user)->postJson('/tasks', [
        'title' => 'Tugas Biologi Uji Lab',
        'category' => 'sekolah',
        'deadline' => now()->addDays(2)->toDateString(),
        'priority' => 'tinggi',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('tasks', [
        'title' => 'Tugas Biologi Uji Lab',
        'user_id' => $user->id,
        'category' => 'sekolah',
    ]);
});

test('task completion status can be toggled', function () {
    $user = User::where('username', 'siswa')->first();
    $task = Task::where('user_id', $user->id)->first();

    $initialStatus = $task->is_completed;

    $response = $this->actingAs($user)->patchJson("/tasks/{$task->id}/toggle");

    $response->assertStatus(200);
    expect($task->fresh()->is_completed)->toBe(! $initialStatus);
});

test('student is prohibited from accessing admin panel', function () {
    $student = User::where('username', 'siswa')->first();

    $response = $this->actingAs($student)->get('/admin');

    $response->assertRedirect('/students');
});

test('admin can access admin panel', function () {
    $admin = User::where('username', 'admin')->first();

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertStatus(200);
});
