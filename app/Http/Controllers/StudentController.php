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
        // 1. Data Kalender
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $calDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $daysInMonth = $calDate->daysInMonth;
        $firstDayOfWeek = $calDate->dayOfWeek;
        // Ambil agenda bulan ini untuk kalender
        $calAgendas = Task::where('user_id', $user->id)
            ->whereBetween('deadline', [$calDate->copy()->startOfMonth(), $calDate->copy()->endOfMonth()])
            ->where('is_completed', false)
            ->get()
            ->groupBy(fn($t) => Carbon::parse($t->deadline)->format('Y-m-d'));
        // 2. To-Do List Siswa: Ambil tugas yang belum selesai, urutkan dari deadline terdekat
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('is_completed', false)
            ->orderBy('deadline', 'asc')
            ->get();
        // 3. Logika Alert Banner Deadline Otomatis
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
        // 4. Pengumuman Aktif Terbaru
        $announcement = Announcement::where('is_active', true)->latest()->first();
        // 5. Acara Sekolah Mendatang
        $schoolEvents = SchoolEvent::orderBy('event_date', 'asc')->get();
        return view('students.index', [
            'title' => 'Agendaku - Siswa',
            'calDate' => $calDate,
            'daysInMonth' => $daysInMonth,
            'firstDayOfWeek' => $firstDayOfWeek,
            'calAgendas' => $calAgendas,
            'pendingTasks' => $pendingTasks,
            'alertData' => $alertData,
            'announcement' => $announcement,
            'schoolEvents' => $schoolEvents,
        ]);
    }
}
