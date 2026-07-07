@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User & Role')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau email..." style="min-width:240px;">
        <select name="role_id" class="form-select" style="min-width:180px;">
            <option value="">Semua Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>{{ $role->label ?? $role->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </form>

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            {{ $user->name }}
                            @if ($user->id === auth()->id())
                                <span class="badge text-bg-secondary">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-soft rounded-pill px-2 py-1 text-capitalize">
                                {{ $user->role->label ?? $user->role->name ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->translatedFormat('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->links() }}</div>
</div>

@endsection