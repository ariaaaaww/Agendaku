<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Menyimpan agenda baru yang dikirim dari form Blade
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sekolah,pribadi',
            'deadline' => 'required|date',
            'priority' => 'required|in:tinggi,sedang,rendah',
        ]);

        Task::create([
            'user_id' => auth()->id(), // ID siswa yang sedang login
            'created_by_id' => auth()->id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'is_completed' => false,
        ]);

        return back()->with('success', 'Agenda berhasil ditambahkan ke kalender!');
    }

    public function toggle(Task $task)
    {
        $task->update(['is_completed' => ! $task->is_completed]);

        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back()->with('success', 'Agenda berhasil dihapus!');
    }
}
