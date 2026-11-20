<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.index', array_merge($this->getCalendarData($request), [
            'title' => 'Agendaku - Admin',
            'announcement' => null,
            'alertData' => ['type' => 'empty', 'count' => 0, 'message' => 'Semuanya terkendali!'],
            'pendingTasks' => collect(),
            'schoolEvents' => collect(),
            'totalUsers' => 0,
            'totalCompletedTasks' => 0,
            'totalPendingTasks' => 0,
            'studentProgress' => collect(),
            'allEvents' => collect(),
            'allUsers' => collect(),
        ]));
    }
}
