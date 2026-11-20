<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Menyiapkan data kalender yang menggabungkan Tugas & Acara Sekolah
     * agar dapat dilihat oleh semua role.
     */
    protected function getCalendarData(Request $request): array
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $calDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $startDate = $calDate->copy()->startOfMonth();
        $endDate = $calDate->copy()->endOfMonth();

        $user = auth()->user();

        // 1. Ambil Tugas:
        // - Jika kategori 'sekolah' (tugas umum): tampilkan untuk semua orang
        // - Jika kategori 'pribadi': hanya tampilkan untuk pemiliknya
        // - Gunakan unique agar tugas massal guru tidak terduplikasi per siswa
        $tasks = Task::whereBetween('deadline', [$startDate, $endDate])
            ->where(function ($query) use ($user) {
                $query->where('category', 'sekolah')
                    ->orWhere('user_id', $user?->id)
                    ->orWhere('created_by_id', $user?->id);
            })
            ->get(['id', 'title', 'category', 'deadline'])
            ->unique(fn($t) => $t->title . $t->deadline->format('Y-m-d'));

        // 2. Ambil Acara Sekolah / OSIS (dari tabel school_events)
        $events = SchoolEvent::whereBetween('event_date', [$startDate, $endDate])
            ->get(['id', 'title', 'event_date'])
            ->map(function ($event) {
                return (object) [
                    'id' => $event->id,
                    'title' => $event->title,
                    'category' => 'acara', // Kategori khusus acara OSIS/Sekolah
                    'deadline' => $event->event_date,
                ];
            });

        // 3. Gabungkan tugas dan acara, lalu kelompokkan berdasarkan tanggal Y-m-d
        $calAgendas = $tasks->concat($events)
            ->groupBy(fn($item) => Carbon::parse($item->deadline)->format('Y-m-d'));

        return [
            'calDate' => $calDate,
            'daysInMonth' => $calDate->daysInMonth,
            'firstDayOfWeek' => $calDate->dayOfWeek,
            'calAgendas' => $calAgendas,
        ];
    }
}