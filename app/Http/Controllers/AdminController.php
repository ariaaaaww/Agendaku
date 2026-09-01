<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function admin()
    {
        $title = 'Agendaku - Admin';

        return view('admin.index',
            [
                'title' => $title,
            ]);
    }
}
