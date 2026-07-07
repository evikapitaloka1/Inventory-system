@extends('layouts.app')

@section('title', 'Peminjaman')
@section('page-title', 'Peminjaman Barang')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <form method="GET" class="d-flex gap-2">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (['pending' => 'Menunggu', 'dipinjam' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan', 'ditolak' => 'Ditolak'] as $val => $label)
                <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('borrowings.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Ajukan Peminjaman</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($borrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->kode_peminjaman }}</td>
                        <td>{{ $borrowing->nama_peminjam }}</td>
                        <td>
                            @foreach ($borrowing->details as $detail)
                                <div class="small">{{ $detail->product->nama_barang }} &times;{{ $detail->jumlah }}</div>
                            @endforeach
                        </td>
                        <td>{{ $borrowing->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                        <td>{{ optional($borrowing->tanggal_kembali_rencana)->translatedFormat('d M Y') ?? '-' }}</td>
                        <td><span class="badge {{ $borrowing->statusBadgeClass() }}">{{ $borrowing->statusLabel() }}</span></td>
                        <td class="text-end">
                            @if ($borrowing->status === 'pending' && auth()->user()->hasRole('admin', 'manager'))
                                <form action="{{ route('borrowings.approve', $borrowing) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Setujui</button>
                                </form>
                                <form action="{{ route('borrowings.reject', $borrowing) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i> Tolak</button>
                                </form>
                           @elseif ($borrowing->status === 'dipinjam' && auth()->user()->hasRole('admin'))
                            <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi barang sudah dikembalikan?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-in-down"></i> Kembalikan</button>
                            </form>
                                <span class="text-secondary small">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $borrowings->links() }}</div>
</div>

@endsection
