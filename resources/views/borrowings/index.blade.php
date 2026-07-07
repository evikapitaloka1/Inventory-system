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
                        <td>
                            <span class="badge {{ $borrowing->statusBadgeClass() }}">{{ $borrowing->statusLabel() }}</span>

                            @if ($borrowing->status === 'ditolak' && $borrowing->alasan_penolakan)
                                <div>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-danger" data-bs-toggle="modal" data-bs-target="#alasanModal{{ $borrowing->id }}">
                                        <i class="bi bi-info-circle"></i> Lihat alasan
                                    </button>
                                </div>
                            @endif

                            @if ($borrowing->status === 'dikembalikan' && ($borrowing->catatan_pengembalian || $borrowing->foto_pengembalian))
                                <div>
                                    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#detailKembaliModal{{ $borrowing->id }}">
                                        <i class="bi bi-image"></i> Lihat detail
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($borrowing->status === 'pending' && auth()->user()->hasRole('admin', 'manager'))
                                <form action="{{ route('borrowings.approve', $borrowing) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Setujui</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#tolakModal{{ $borrowing->id }}">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            @elseif ($borrowing->status === 'dipinjam' && auth()->user()->hasRole('admin'))
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#kembaliModal{{ $borrowing->id }}">
                                    <i class="bi bi-box-arrow-in-down"></i> Kembalikan
                                </button>
                            @else
                                <span class="text-secondary small">-</span>
                            @endif
                        </td>
                    </tr>

                    @if ($borrowing->status === 'pending')
                        <div class="modal fade" id="tolakModal{{ $borrowing->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('borrowings.reject', $borrowing) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Peminjaman {{ $borrowing->kode_peminjaman }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                            <textarea name="alasan_penolakan" class="form-control" rows="3" required
                                                placeholder="Contoh: Barang sedang dalam perbaikan / stok dibutuhkan untuk keperluan lain."></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($borrowing->status === 'ditolak' && $borrowing->alasan_penolakan)
                        <div class="modal fade" id="alasanModal{{ $borrowing->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Penolakan &mdash; {{ $borrowing->kode_peminjaman }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-1 small text-secondary">Ditolak oleh: {{ $borrowing->approver->name ?? '-' }}</p>
                                        <p class="mb-0">{{ $borrowing->alasan_penolakan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($borrowing->status === 'dipinjam')
                        <div class="modal fade" id="kembaliModal{{ $borrowing->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Pengembalian &mdash; {{ $borrowing->kode_peminjaman }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kondisi Barang Saat Kembali <span class="text-danger">*</span></label>
                                                <select name="kondisi_saat_kembali" class="form-select" required>
                                                    <option value="baik">Baik</option>
                                                    <option value="rusak_ringan">Rusak Ringan</option>
                                                    <option value="rusak_berat">Rusak Berat</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Foto Kondisi Barang <span class="text-secondary small">(opsional)</span></label>
                                                <input type="file" name="foto_pengembalian" class="form-control" accept="image/*">
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label">Catatan <span class="text-secondary small">(opsional)</span></label>
                                                <textarea name="catatan_pengembalian" class="form-control" rows="2" placeholder="Contoh: ada goresan kecil di casing."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($borrowing->status === 'dikembalikan' && ($borrowing->catatan_pengembalian || $borrowing->foto_pengembalian))
                        <div class="modal fade" id="detailKembaliModal{{ $borrowing->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Pengembalian &mdash; {{ $borrowing->kode_peminjaman }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($borrowing->fotoPengembalianUrl())
                                            <img src="{{ $borrowing->fotoPengembalianUrl() }}" class="img-fluid rounded mb-3" alt="Foto pengembalian">
                                        @endif
                                        @if ($borrowing->catatan_pengembalian)
                                            <p class="mb-0">{{ $borrowing->catatan_pengembalian }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $borrowings->links() }}</div>
</div>

@endsection