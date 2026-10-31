<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Store new personal/school task from student or general form.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sekolah,pribadi',
            'deadline' => 'required|date',
            'priority' => 'required|in:rendah,sedang,tinggi',
        ]);

        $task = Task::create([
            'user_id' => auth()->id(),
            'created_by_id' => auth()->id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'is_completed' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task]);
        }

        return back()->with('success', 'Agenda berhasil ditambahkan ke kalender!');
    }

    /**
     * Store mass task or school event from Teacher panel.
     */
    public function guruStore(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sekolah,acara',
            'deadline' => 'required|date',
        ]);

        if ($validated['category'] === 'acara') {
            $event = SchoolEvent::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'event_date' => $validated['deadline'],
                'description' => 'Acara / Ujian Sekolah dipublikasi oleh Guru',
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'event' => $event]);
            }
        } else {
            $siswas = User::where('role', 'siswa')->get();
            $tasks = [];

            if ($siswas->isNotEmpty()) {
                foreach ($siswas as $siswa) {
                    $tasks[] = Task::create([
                        'user_id' => $siswa->id,
                        'created_by_id' => auth()->id(),
                        'title' => $validated['title'],
                        'category' => 'sekolah',
                        'deadline' => $validated['deadline'],
                        'priority' => 'tinggi',
                        'is_completed' => false,
                    ]);
                }
            } else {
                // If no student yet, create one for current user
                $tasks[] = Task::create([
                    'user_id' => auth()->id(),
                    'created_by_id' => auth()->id(),
                    'title' => $validated['title'],
                    'category' => 'sekolah',
                    'deadline' => $validated['deadline'],
                    'priority' => 'tinggi',
                    'is_completed' => false,
                ]);
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'tasks' => $tasks]);
            }
        }

        return back()->with('success', 'Tugas/Acara massal berhasil diterbitkan ke seluruh sistem!');
    }

    /**
     * Update existing task (e.g. from Teacher or Student modal).
     */
    public function update(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sekolah,pribadi',
            'deadline' => 'required|date',
            'priority' => 'nullable|in:rendah,sedang,tinggi',
        ]);

        $task->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'] ?? $task->priority,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task]);
        }

        return back()->with('success', 'Agenda berhasil diperbarui!');
    }

    /**
     * Toggle completion status of a task.
     */
    public function toggle(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $task->update([
            'is_completed' => ! $task->is_completed,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $task->is_completed,
            ]);
        }

        return back()->with('success', 'Status tugas berhasil diperbarui!');
    }

    /**
     * Delete a task.
     */
    public function destroy(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $task->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}

