<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.index', [
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
        ]);
    }
}
