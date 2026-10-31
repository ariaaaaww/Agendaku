<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use Illuminate\View\View;

class StudentCouncilController extends Controller
{
    public function council(): View
    {
        $commonData = $this->getCommonDashboardData();

        $osisEvents = SchoolEvent::with('organizer')->latest()->get();

        return view('council.index', array_merge($commonData, [
            'title' => 'Agendaku - OSIS',
            'osisEvents' => $osisEvents,
        ]));
    }
}
