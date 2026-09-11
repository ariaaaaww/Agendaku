<?php

namespace App\Http\Controllers;

class TeacherController extends Controller
{
    public function teacher()
    {
        $title = 'Agendaku - Guru';

        return view(
            'teachers.index',
            [
                'title' => $title,
            ]
        );
    }
}
