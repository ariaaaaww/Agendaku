<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const ROLES = ['admin', 'guru', 'osis', 'siswa'];

    private function pastikanAdmin(): void
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403, 'Akses khusus admin.');
        }
    }

    public function store(Request $request)
    {
        $this->pastikanAdmin();

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|min:3|max:30|alpha_dash|unique:users,username',
            'password' => 'required|string|min:8',
            'role'     => ['required', Rule::in(self::ROLES)],
        ], [
            'username.unique'     => 'Username sudah dipakai, coba yang lain.',
            'username.min'        => 'Username minimal 3 karakter.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan underscore.',
            'password.min'        => 'Password minimal 8 karakter.',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return response()->json(['message' => 'Akun berhasil ditambahkan.']);
    }

    public function update(Request $request, User $user)
    {
        $this->pastikanAdmin();

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'min:3', 'max:30', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role'     => ['required', Rule::in(self::ROLES)],
        ], [
            'username.unique' => 'Username sudah dipakai, coba yang lain.',
            'password.min'    => 'Password minimal 8 karakter.',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json(['message' => 'Akun berhasil diperbarui.']);
    }

    public function destroy(User $user)
    {
        $this->pastikanAdmin();

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Kamu tidak bisa menghapus akunmu sendiri.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'Akun berhasil dihapus.']);
    }
}