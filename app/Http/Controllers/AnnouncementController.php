<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        // 1. Nonaktifkan seluruh pengumuman lama yang sedang aktif
        Announcement::where('is_active', true)->update(['is_active' => false]);

        // 2. Buat pengumuman baru sebagai pengumuman aktif
        $announcement = Announcement::create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'is_active' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'announcement' => $announcement]);
        }

        return back()->with('success', 'Pengumuman baru berhasil diterbitkan.');
    }
    public function destroy(Request $request, Announcement $announcement)
    {
        $announcement->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pengumuman berhasil dihapus.']);
        }

        return back()->with('success', 'Pengumuman sekolah berhasil dihapus.');
    }
}