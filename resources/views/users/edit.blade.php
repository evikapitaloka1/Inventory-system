@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required @disabled($user->id === auth()->id())>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->label ?? $role->name }}</option>
                @endforeach
            </select>
            @if ($user->id === auth()->id())
                <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                <div class="form-text">Anda tidak dapat mengubah role akun sendiri.</div>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Password Baru <span class="text-secondary small">(opsional, kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button class="btn btn-primary mt-2">Perbarui</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary mt-2">Batal</a>
    </form>
</div>
@endsection