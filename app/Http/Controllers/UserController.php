<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($search = $request->input('q')) {
            $keyword = '%'.strtolower($search).'%';
            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(name) LIKE ?', [$keyword])
                  ->orWhereRaw('LOWER(email) LIKE ?', [$keyword]);
            });
        }

        if ($roleId = $request->input('role_id')) {
            $query->where('role_id', $roleId);
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Admin tidak boleh menurunkan/mengubah role dirinya sendiri (mencegah admin terkunci dari akses).
        if ($user->id === $request->user()->id && (int) $data['role_id'] !== $user->role_id) {
            return back()->withErrors(['role_id' => 'Anda tidak dapat mengubah role akun Anda sendiri.'])->withInput();
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role_id'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        if ($user->isAdmin() && User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->count() <= 1) {
            return back()->withErrors(['user' => 'Tidak dapat menghapus admin terakhir di sistem.']);
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}