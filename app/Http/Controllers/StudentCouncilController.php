<?php

namespace App\Http\Controllers;

class StudentCouncilController extends Controller
{
    public function council()
    {
        $title = 'Agendaku - OSIS';

        return view('council.index', [
            'title' => $title,
        ]);
    }
}
