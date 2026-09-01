<?php

namespace App\Http\Controllers;

class TeachersController extends Controller
{
    public function teachers()
    {
        $title = 'Agendaku - Guru';

        return view('teachers.index',
            [
                'title' => $title,
            ]);
    }
}
