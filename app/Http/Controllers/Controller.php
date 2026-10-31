<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolEvent;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Get shared dashboard data (announcement, alerts, pending tasks, school events).
     *
     * @return array<string, mixed>
     */
    protected function getCommonDashboardData(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // 1. Latest active announcement
        $announcement = Announcement::where('is_active', true)->latest()->first();

        // 2. Active tasks for the user
        $tasksQuery = Task::where('is_completed', false);
        if ($user && $user->role === 'siswa') {
            $tasksQuery->where('user_id', $user->id);
        }
        $pendingTasks = $tasksQuery->orderBy('deadline', 'asc')->get();

        // 3. Calculate Alert Data via Carbon
        $overdueTasks = $pendingTasks->filter(fn ($t) => Carbon::parse($t->deadline)->lt($today));
        $todayTasks = $pendingTasks->filter(fn ($t) => Carbon::parse($t->deadline)->isSameDay($today));
        $tomorrowTasks = $pendingTasks->filter(fn ($t) => Carbon::parse($t->deadline)->isSameDay($tomorrow));

        $alertData = [
            'type' => 'empty',
            'count' => 0,
            'message' => 'Tidak ada tenggat waktu atau agenda esok hari. Semuanya terkendali!',
        ];

        if ($overdueTasks->isNotEmpty()) {
            $alertData['type'] = 'overdue';
            $alertData['count'] = $overdueTasks->count();
            $alertData['message'] = "Perhatian! Ada {$overdueTasks->count()} tugas yang telah melewati batas deadline!";
        } elseif ($todayTasks->isNotEmpty()) {
            $alertData['type'] = 'today';
            $alertData['count'] = $todayTasks->count();
            $alertData['message'] = "Hari ini ada {$todayTasks->count()} tugas yang harus diselesaikan: \"{$todayTasks->first()->title}\"";
        } elseif ($tomorrowTasks->isNotEmpty()) {
            $alertData['type'] = 'tomorrow';
            $alertData['count'] = $tomorrowTasks->count();
            $alertData['message'] = "Kamu memiliki {$tomorrowTasks->count()} agenda/tugas untuk esok hari: \"{$tomorrowTasks->first()->title}\"";
        }

        // 4. School Events
        $schoolEvents = SchoolEvent::orderBy('event_date', 'asc')->get();

        return [
            'announcement' => $announcement,
            'alertData' => $alertData,
            'pendingTasks' => $pendingTasks,
            'schoolEvents' => $schoolEvents,
        ];
    }
}
