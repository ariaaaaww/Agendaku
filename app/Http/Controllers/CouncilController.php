<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CouncilController extends Controller
{
    public function index(): View
    {
        return view('council.index', [
            'title' => 'Agendaku - OSIS',
            'announcement' => null,
            'alertData' => ['type' => 'empty', 'count' => 0, 'message' => 'Semuanya terkendali!'],
            'pendingTasks' => collect(),
            'schoolEvents' => collect(),
            'osisEvents' => collect(),
        ]);
    }
}
