<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolEvent;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // 1. To-Do List Siswa: Ambil tugas yang belum selesai, urutkan dari deadline terdekat
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('is_completed', false)
            ->orderBy('deadline', 'asc')
            ->get();

        // 2. Logika Alert Banner Deadline Otomatis
        $overdueTasks = $pendingTasks->filter(fn($t) => Carbon::parse($t->deadline)->lt($today));
        $todayTasks = $pendingTasks->filter(fn($t) => Carbon::parse($t->deadline)->isSameDay($today));
        $tomorrowTasks = $pendingTasks->filter(fn($t) => Carbon::parse($t->deadline)->isSameDay($tomorrow));

        $alertData = [
            'type' => 'empty',
            'count' => 0,
            'message' => 'Tidak ada tenggat waktu atau agenda esok hari. Semuanya terkendali!',
        ];

        if ($overdueTasks->isNotEmpty()) {
            $alertData = [
                'type' => 'overdue',
                'count' => $overdueTasks->count(),
                'message' => "Perhatian! Ada {$overdueTasks->count()} tugas yang telah melewati batas deadline!",
            ];
        } elseif ($todayTasks->isNotEmpty()) {
            $alertData = [
                'type' => 'today',
                'count' => $todayTasks->count(),
                'message' => "Hari ini ada {$todayTasks->count()} tugas yang harus diselesaikan: \"{$todayTasks->first()->title}\"",
            ];
        } elseif ($tomorrowTasks->isNotEmpty()) {
            $alertData = [
                'type' => 'tomorrow',
                'count' => $tomorrowTasks->count(),
                'message' => "Kamu memiliki {$tomorrowTasks->count()} agenda untuk esok hari: \"{$tomorrowTasks->first()->title}\"",
            ];
        }

        // 3. Pengumuman Aktif Terbaru
        $announcement = Announcement::where('is_active', true)->latest()->first();

        // 4. Acara Sekolah Mendatang
        $schoolEvents = SchoolEvent::orderBy('event_date', 'asc')->get();

        // 5. Gabungkan data kalender dari helper getCalendarData($request)
        return view('students.index', array_merge($this->getCalendarData($request), [
            'title' => 'Agendaku - Siswa',
            'pendingTasks' => $pendingTasks,
            'alertData' => $alertData,
            'announcement' => $announcement,
            'schoolEvents' => $schoolEvents,
        ]));
    }
}