<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouncilController extends Controller
{
    public function index(Request $request): View
    {
        $osisEvents = SchoolEvent::orderBy('event_date', 'asc')->get();

        return view('council.index', [
            'title' => 'Agendaku - Panel OSIS',
            'osisEvents' => $osisEvents,
            'schoolEvents' => $osisEvents, // Menampilkan acara OSIS di tab 'Acara Sekolah'
            'announcement' => null,
            'alertData' => ['type' => 'empty', 'count' => 0, 'message' => 'Semuanya terkendali!'],
            'pendingTasks' => collect(),
        ]);
    }
}