<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display Admin dashboard.
     */
    public function admin(): View
    {
        $commonData = $this->getCommonDashboardData();

        $totalUsers = User::count();
        $totalCompletedTasks = Task::where('is_completed', true)->count();
        $totalPendingTasks = Task::where('is_completed', false)->count();

        $studentProgress = User::where('role', 'siswa')->withCount([
            'tasks as completed_count' => fn ($q) => $q->where('is_completed', true),
            'tasks as pending_count' => fn ($q) => $q->where('is_completed', false),
            'tasks as total_count',
        ])->get();

        $allEvents = SchoolEvent::with('organizer')->latest()->get();
        $allUsers = User::latest()->get();

        return view('admin.index', array_merge($commonData, [
            'title' => 'Agendaku - Admin',
            'totalUsers' => $totalUsers,
            'totalCompletedTasks' => $totalCompletedTasks,
            'totalPendingTasks' => $totalPendingTasks,
            'studentProgress' => $studentProgress,
            'allEvents' => $allEvents,
            'allUsers' => $allUsers,
        ]));
    }

    /**
     * Store new user from Admin panel.
     */
    public function storeUser(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'role' => 'required|string|in:siswa,guru,osis,admin',
            'password' => 'required|string|min:3',
        ]);

        $user = User::create([
            'name' => $validated['fullname'],
            'username' => $validated['username'],
            'email' => $validated['username'].'@agendaku.test',
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'user' => $user]);
        }

        return back()->with('success', 'Akun berhasil ditambahkan!');
    }

    /**
     * Update user from Admin panel.
     */
    public function updateUser(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,'.$user->id,
            'role' => 'required|string|in:siswa,guru,osis,admin',
            'password' => 'nullable|string|min:3',
        ]);

        $updateData = [
            'name' => $validated['fullname'],
            'username' => $validated['username'],
            'role' => $validated['role'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'user' => $user]);
        }

        return back()->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Delete user from Admin panel.
     */
    public function destroyUser(Request $request, User $user): RedirectResponse|JsonResponse
    {
        // Prevent deleting oneself
        if (auth()->id() === $user->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri.'], 403);
            }

            return back()->withErrors(['message' => 'Tidak dapat menghapus akun sendiri.']);
        }

        $user->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Akun berhasil dihapus!');
    }
}
