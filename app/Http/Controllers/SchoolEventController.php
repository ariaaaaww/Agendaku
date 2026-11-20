<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use Illuminate\Http\Request;

class SchoolEventController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
        ]);

        $event = SchoolEvent::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'event_date' => $validated['event_date'],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'event' => $event]);
        }

        return back()->with('success', 'Acara OSIS berhasil ditambahkan.');
    }

    public function update(Request $request, SchoolEvent $schoolEvent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
        ]);

        $schoolEvent->update([
            'title' => $validated['title'],
            'event_date' => $validated['event_date'],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'event' => $schoolEvent]);
        }

        return back()->with('success', 'Acara OSIS berhasil diperbarui.');
    }

    public function destroy(Request $request, SchoolEvent $schoolEvent)
    {
        $schoolEvent->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Acara OSIS berhasil dihapus.');
    }
}