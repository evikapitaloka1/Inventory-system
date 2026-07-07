@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <img src="{{ $product->imageUrl() }}" class="rounded mb-3" style="width:100%; aspect-ratio:1; object-fit:cover;">
            <h5 class="mb-0">{{ $product->nama_barang }}</h5>
            <p class="text-secondary small">{{ $product->kode_barang }}</p>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-4">
            <dl class="row mb-0">
                <dt class="col-sm-4">Kategori</dt>
                <dd class="col-sm-8">{{ $product->category->name }}</dd>

                <dt class="col-sm-4">Stok</dt>
                <dd class="col-sm-8">{{ $product->stok }} unit @if($product->isLowStock()) <span class="badge text-bg-danger">Menipis</span> @endif</dd>

                <dt class="col-sm-4">Lokasi Penyimpanan</dt>
                <dd class="col-sm-8">{{ $product->lokasi_penyimpanan ?? '-' }}</dd>

                <dt class="col-sm-4">Kondisi</dt>
                <dd class="col-sm-8 text-capitalize">{{ str_replace('_', ' ', $product->kondisi_barang) }}</dd>

                <dt class="col-sm-4">Deskripsi</dt>
                <dd class="col-sm-8">{{ $product->deskripsi ?? '-' }}</dd>
            </dl>
        </div>

        <div class="card p-4 mt-3">
            <h6 class="mb-3">Riwayat Peminjaman</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Kode</th><th>Jumlah</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($product->borrowingDetails as $detail)
                            <tr>
                                <td>{{ $detail->borrowing->kode_peminjaman }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td><span class="badge {{ $detail->borrowing->statusBadgeClass() }}">{{ $detail->borrowing->statusLabel() }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-secondary text-center py-3">Belum pernah dipinjam.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
@endsection
