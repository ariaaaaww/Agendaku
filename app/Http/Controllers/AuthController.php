<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

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
    public function storeRegister(Request $request)
{
    // Validasi
    $validatedRequest = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:users,username'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    // Simpan ke database (password otomatis di-hash oleh cast 'hashed')
    User::create($validatedRequest);

    // Handle If Success
    return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login');
}
}
