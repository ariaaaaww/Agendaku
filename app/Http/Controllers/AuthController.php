<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

// use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView(): View
    {
        return view('auth.login', ['title' => 'Agendaku - Login']);
    }

    protected function redirectBasedOnRole($role)
    {
        $roleValue = $role instanceof UserRole ? $role->value : $role;

        return match ($roleValue) {
            'admin' => redirect()->route('admin.index'),
            'teacher' => redirect()->route('teachers.index'),
            'council' => redirect()->route('council.index'),
            default => redirect()->route('students.index'),
        };
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah!'])
            ->withInput($request->only('username'));
    }

    public function registerView(): View
    {
        return view('auth.register', ['title' => 'Agendaku - Register']);
    }

    public function registerPost(Request $request)
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:3'],
        ]);

        $user = User::create([
            'name' => $validated['fullname'],
            'username' => $validated['username'],
            'email' => $validated['username'].'@agendaku.test',
            'password' => $validated['password'],
            'role' => UserRole::STUDENT,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('students.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
