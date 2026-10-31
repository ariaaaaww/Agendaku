<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function loginView(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login', ['title' => 'Agendaku - Login']);
    }

    /**
     * Handle user login authentication.
     */
    public function loginPost(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()
            ->withErrors(['username' => 'Username atau Password salah!'])
            ->withInput($request->only('username'));
    }

    /**
     * Show registration form.
     */
    public function registerView(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.register', ['title' => 'Agendaku - Register']);
    }

    /**
     * Handle user registration.
     */
    public function registerPost(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:3',
            'role' => 'nullable|string|in:siswa,guru,osis,admin',
        ]);

        $user = User::create([
            'name' => $validated['fullname'],
            'username' => $validated['username'],
            'email' => $validated['username'].'@agendaku.test',
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'siswa',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user->role);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Helper to redirect user to their role base dashboard.
     */
    protected function redirectBasedOnRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.index'),
            'guru' => redirect()->route('teachers.index'),
            'osis' => redirect()->route('council.index'),
            default => redirect()->route('students.index'),
        };
    }
}
