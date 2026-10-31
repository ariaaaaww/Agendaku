<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SchoolEventController extends Controller
{
    /**
     * Store a newly created school event (e.g. from OSIS panel).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $event = SchoolEvent::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'event_date' => $validated['event_date'],
            'description' => $validated['description'] ?? 'Acara kegiatan sekolah / OSIS',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'event' => $event]);
        }

        return back()->with('success', 'Acara OSIS berhasil diterbitkan ke kalender!');
    }

    /**
     * Update an existing school event.
     */
    public function update(Request $request, SchoolEvent $schoolEvent): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $schoolEvent->update([
            'title' => $validated['title'],
            'event_date' => $validated['event_date'],
            'description' => $validated['description'] ?? $schoolEvent->description,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'event' => $schoolEvent]);
        }

        return back()->with('success', 'Acara OSIS berhasil diperbarui!');
    }

    /**
     * Remove the specified school event.
     */
    public function destroy(Request $request, SchoolEvent $schoolEvent): RedirectResponse|JsonResponse
    {
        $schoolEvent->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Acara berhasil dihapus!');
    }
}

