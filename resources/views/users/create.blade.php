@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->label ?? $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-primary mt-2">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary mt-2">Batal</a>
    </form>
</div>
@endsection