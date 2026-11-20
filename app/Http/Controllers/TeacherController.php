<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\SchoolEvent;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $allTasks = Task::with('creator')->latest()->get();
        $schoolEvents = SchoolEvent::orderBy('event_date', 'asc')->get();
        $announcement = Announcement::where('is_active', true)->latest()->first();

        return view('teachers.index', array_merge($this->getCalendarData($request), [
            'title' => 'Agendaku - Panel Guru',
            'allTasks' => $allTasks,
            'schoolEvents' => $schoolEvents,
            'announcement' => $announcement,
            'alertData' => ['type' => 'empty', 'count' => 0, 'message' => 'Semuanya terkendali!'],
            'pendingTasks' => collect(),
        ]));
    }

    public function storeTask(Request $request)
    {
        // Sesuaikan dengan kolom tabel tasks: 'category' dan 'deadline'
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sekolah,acara',
            'deadline' => 'required|date',
        ]);

        if ($validated['category'] === 'acara') {
            // Jika tipe acara sekolah, simpan ke tabel school_events
            SchoolEvent::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'event_date' => $validated['deadline'],
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Acara sekolah berhasil diterbitkan.']);
            }

            return back()->with('success', 'Acara sekolah berhasil ditambahkan ke kalender.');
        }

        // Jika kategori sekolah, bagikan ke SELURUH akun siswa
        $students = User::where('role', UserRole::STUDENT)->get();

        foreach ($students as $student) {
            Task::create([
                'user_id' => $student->id,
                'created_by_id' => auth()->id(),
                'title' => $validated['title'],
                'category' => 'sekolah',
                'deadline' => $validated['deadline'],
                'priority' => 'sedang',
                'is_completed' => false,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tugas berhasil dibagikan ke seluruh siswa.']);
        }

        return back()->with('success', 'Tugas massal berhasil dibagikan ke seluruh siswa.');
    }
}