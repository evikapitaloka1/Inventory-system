@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman Barang')

@section('content')
<div class="card p-4" style="max-width:820px;">
    <form method="POST" action="{{ route('borrowings.store') }}" id="borrowForm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Peminjam</label>
                <input type="text" name="nama_peminjam" class="form-control" value="{{ old('nama_peminjam', auth()->user()->name) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Rencana Kembali</label>
                <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana') }}" required>
            </div>
        </div>

        <hr class="my-4">
        <h6>Pilih Barang</h6>
        <div id="itemRows">
            <div class="row g-2 mb-2 item-row">
                <div class="col-md-8">
                    <select name="products[]" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_barang }} ({{ $product->kode_barang }}) &mdash; stok {{ $product->stok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="quantities[]" class="form-control" min="1" value="1" placeholder="Jumlah" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-x"></i></button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="addRow"><i class="bi bi-plus-lg me-1"></i>Tambah Barang</button>

        <div class="mt-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control">{{ old('catatan') }}</textarea>
        </div>

        <button class="btn btn-primary mt-4">Ajukan Peminjaman</button>
        <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary mt-4">Batal</a>
    </form>
</div>

<script>
document.getElementById('addRow').addEventListener('click', function () {
    const rows = document.getElementById('itemRows');
    const clone = rows.querySelector('.item-row').cloneNode(true);
    clone.querySelectorAll('select, input').forEach(el => { if (el.tagName === 'SELECT') el.value = ''; else el.value = 1; });
    rows.appendChild(clone);
});
document.getElementById('itemRows').addEventListener('click', function (e) {
    if (e.target.closest('.remove-row')) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) e.target.closest('.item-row').remove();
    }
});
</script>
@endsection
