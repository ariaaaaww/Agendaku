<?php

namespace App\Http\Controllers;

class StudentController extends Controller
{
    public function student()
    {
        $title = 'Agendaku - Siswa';

        return view('students.index',
            [
                'title' => $title,
            ]);
    }
}
