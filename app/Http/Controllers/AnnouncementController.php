<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Store and publish a new school announcement.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        // Deactivate previous active announcements
        Announcement::where('is_active', true)->update(['is_active' => false]);

        $announcement = Announcement::create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'is_active' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'announcement' => $announcement,
            ]);
        }

        return back()->with('success', 'Pengumuman sekolah berhasil diperbarui!');
    }
}

