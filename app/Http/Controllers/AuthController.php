<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login', ['title' => 'Agendaku - Login']);
    }

    public function register()
    {
        return view('auth.register', ['title' => 'Agendaku - Register']);
    }

    public function account()
    {
        return view('auth.login', ['title' => 'Agendaku - Login']);
    }
}
