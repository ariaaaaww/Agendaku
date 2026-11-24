<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilan Dashboard Admin dengan Statistik Asli dari Database
     */
    public function index(Request $request): View
    {
        // 1. Statistik Kartu
        $totalUsers = User::count();
        $totalCompletedTasks = Task::where('is_completed', true)->count();
        $totalPendingTasks = Task::where('is_completed', false)->count();

        // 2. Progres Tugas Per Siswa
        $studentProgress = User::where('role', \App\Enums\UserRole::STUDENT)
            ->withCount([
                'tasks as completed_count' => fn($q) => $q->where('is_completed', true),
                'tasks as pending_count' => fn($q) => $q->where('is_completed', false),
                'tasks as total_count',
            ])
            ->get();

        // 3. Daftar Acara Sekolah / OSIS
        $allEvents = SchoolEvent::with('organizer')->orderBy('event_date', 'asc')->get();

        // 4. Daftar Seluruh Pengguna
        $allUsers = User::orderBy('id', 'desc')->get();

        return view('admin.index', array_merge($this->getCalendarData($request), [
            'title' => 'Agendaku - Admin',
            'announcement' => null,
            'alertData' => ['type' => 'empty', 'count' => 0, 'message' => 'Semuanya terkendali!'],
            'pendingTasks' => collect(),
            'schoolEvents' => $allEvents,
            'totalUsers' => $totalUsers,
            'totalCompletedTasks' => $totalCompletedTasks,
            'totalPendingTasks' => $totalPendingTasks,
            'studentProgress' => $studentProgress,
            'allEvents' => $allEvents,
            'allUsers' => $allUsers,
        ]));
    }

    /**
     * Tambah Akun Pengguna Baru
     */
    public function storeUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'role' => 'required|in:student,teacher,council,admin',
            'password' => 'required|string|min:4',
        ]);

        $user = User::create([
            'name' => $validated['fullname'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun pengguna berhasil ditambahkan.',
            'user' => $user,
        ]);
    }

    /**
     * Ubah Data Akun Pengguna
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'role' => 'required|in:student,teacher,council,admin',
            'password' => 'nullable|string|min:4',
        ]);

        $updateData = [
            'name' => $validated['fullname'],
            'username' => $validated['username'],
            'role' => $validated['role'],
        ];

        // Ubah password hanya jika field password diisi
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diperbarui.',
        ]);
    }

    /**
     * Hapus Akun Pengguna (Dengan Proteksi Anti-Self Delete)
     */
    public function destroyUser(User $user): JsonResponse
    {
        // Pastikan admin tidak bisa menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri!',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun pengguna berhasil dihapus.',
        ]);
    }
}