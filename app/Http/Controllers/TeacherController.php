<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function teacher(): View
    {
        $commonData = $this->getCommonDashboardData();

        $allTasks = Task::with(['user', 'creator'])->latest()->get();

        return view('teachers.index', array_merge($commonData, [
            'title' => 'Agendaku - Guru',
            'allTasks' => $allTasks,
        ]));
    }
}
