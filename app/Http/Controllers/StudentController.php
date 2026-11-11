<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Ambil tahun dan bulan dari URL (?year=2026&month=11), default ke hari ini
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $calDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();

        // 2. Hitung komponen kalender via Carbon
        $daysInMonth = $calDate->daysInMonth;
        $firstDayOfWeek = $calDate->dayOfWeek; // 0 = Minggu, 1 = Senin, dst.
        $startOfMonth = $calDate->copy()->startOfMonth();
        $endOfMonth = $calDate->copy()->endOfMonth();

        // 3. Ambil tugas milik siswa di bulan tersebut dari MySQL, kelompokkan per tanggal
        $calAgendas = Task::where('user_id', auth()->id())
            ->whereBetween('deadline', [$startOfMonth, $endOfMonth])
            ->where('is_completed', false)
            ->get()
            ->groupBy(fn ($t) => Carbon::parse($t->deadline)->format('Y-m-d'));

        // 4. Kirim variabel ke view Blade
        return view('students.index', [
            'title' => 'Agendaku - Siswa',
            'calDate' => $calDate,
            'daysInMonth' => $daysInMonth,
            'firstDayOfWeek' => $firstDayOfWeek,
            'calAgendas' => $calAgendas,
            // Variabel dashboard lainnya
            'announcement' => null,
            'alertData' => ['type' => 'empty', 'count' => 0, 'message' => 'Semuanya terkendali!'],
            'pendingTasks' => collect(),
            'schoolEvents' => collect(),
        ]);
    }
}
