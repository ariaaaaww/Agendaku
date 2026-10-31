<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Return JSON calendar data for a specific year and month.
     */
    public function monthData(Request $request): JsonResponse
    {
        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        $date = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $user = Auth::user();

        // 1. Fetch user tasks within this month range
        $tasksQuery = Task::whereBetween('deadline', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->where('is_completed', false);

        if ($user && $user->role === 'siswa') {
            $tasksQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('category', 'sekolah');
            });
        }

        $tasks = $tasksQuery->get(['id', 'title', 'category', 'deadline', 'priority'])
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'category' => $task->category,
                    'deadline' => Carbon::parse($task->deadline)->format('Y-m-d'),
                    'priority' => $task->priority,
                ];
            });

        // 2. Fetch school events within this month range
        $events = SchoolEvent::whereBetween('event_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get(['id', 'title', 'event_date'])
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'category' => 'acara',
                    'deadline' => Carbon::parse($event->event_date)->format('Y-m-d'),
                    'priority' => 'sedang',
                ];
            });

        // 3. Merge and group by date 'YYYY-MM-DD'
        $allAgendas = $tasks->concat($events)->groupBy('deadline');

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return response()->json([
            'year' => $year,
            'month' => $month,
            'month_name' => $monthNames[$month] ?? $date->format('F'),
            'first_day_of_week' => $startOfMonth->dayOfWeek, // 0 = Sunday, 1 = Monday, etc.
            'days_in_month' => $startOfMonth->daysInMonth,
            'today_date' => Carbon::now()->format('Y-m-d'),
            'agendas' => $allAgendas,
        ]);
    }
}
