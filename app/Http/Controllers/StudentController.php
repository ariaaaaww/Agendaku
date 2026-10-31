<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StudentController extends Controller
{
    public function student(): View
    {
        $commonData = $this->getCommonDashboardData();

        return view('students.index', array_merge($commonData, [
            'title' => 'Agendaku - Siswa',
        ]));
    }
}
