<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function getMonthAgenda(Request $request): JsonResponse
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $calDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $startDate = $calDate->copy()->startOfMonth();
        $endDate = $calDate->copy()->endOfMonth();

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

        // 1. Ambil Tugas Sekolah (Akademik) bulan ini (tanpa duplikasi)
        $tasks = Task::whereBetween('deadline', [$startDate, $endDate])
            ->where('category', 'sekolah')
            ->get(['id', 'title', 'category', 'deadline'])
            ->unique(fn($t) => $t->title . Carbon::parse($t->deadline)->format('Y-m-d'))
            ->map(fn($t) => [
                'id' => $t->id,
                'title' => $t->title,
                'category' => 'sekolah',
                'date' => Carbon::parse($t->deadline)->format('Y-m-d'),
            ]);

        // 2. Ambil Acara OSIS / Sekolah bulan ini
        $events = SchoolEvent::whereBetween('event_date', [$startDate, $endDate])
            ->get(['id', 'title', 'event_date'])
            ->map(fn($e) => [
                'id' => $e->id,
                'title' => $e->title,
                'category' => 'acara',
                'date' => Carbon::parse($e->event_date)->format('Y-m-d'),
            ]);

        // 3. Gabungkan dan kelompokkan berdasarkan tanggal ('YYYY-MM-DD')
        $groupedAgendas = $tasks->concat($events)->groupBy('date');

        return response()->json([
            'year' => $year,
            'month' => $month,
            'month_name' => $monthNames[$month] ?? $calDate->format('F'),
            'first_day_of_week' => $calDate->dayOfWeek,
            'days_in_month' => $calDate->daysInMonth,
            'today_date' => now()->toDateString(),
            'agendas' => $groupedAgendas,
        ]);
    }
}