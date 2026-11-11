<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        return view('teachers.index', [
            'title' => 'Agendaku - Guru',
            'announcement' => null,
            'alertData' => ['type' => 'empty', 'count' => 0, 'message' => 'Semuanya terkendali!'],
            'pendingTasks' => collect(),
            'schoolEvents' => collect(),
            'allTasks' => collect(),
        ]);
    }
}
