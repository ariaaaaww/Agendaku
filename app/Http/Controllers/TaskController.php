<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sekolah,pribadi', // Sebelumnya 'sekolah,acara'
            'deadline' => 'required|date',
            'priority' => 'nullable|in:tinggi,sedang,rendah',
        ]);

        Task::create([
            'user_id' => auth()->id(),
            'created_by_id' => auth()->id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'] ?? 'sedang',
            'is_completed' => false,
        ]);

        return back()->with('success', 'Agenda berhasil ditambahkan ke kalender!');
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sekolah,pribadi',
            'deadline' => 'required|date',
        ]);

        $task->update($validated);

        return back()->with('success', 'Agenda berhasil diperbarui!');
    }

    public function toggle(Task $task): RedirectResponse
    {
        $task->update(['is_completed' => !$task->is_completed]);

        return back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return back()->with('success', 'Agenda berhasil dihapus!');
    }
}