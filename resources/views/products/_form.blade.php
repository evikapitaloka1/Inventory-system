@php $product = $product ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Kode Barang</label>
        <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang', $product->kode_barang ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $product->nama_barang ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Stok</label>
        <input type="number" min="0" name="stok" class="form-control" value="{{ old('stok', $product->stok ?? 0) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Stok Minimum</label>
        <input type="number" min="0" name="stok_minimum" class="form-control" value="{{ old('stok_minimum', $product->stok_minimum ?? 5) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Lokasi Penyimpanan</label>
        <input type="text" name="lokasi_penyimpanan" class="form-control" value="{{ old('lokasi_penyimpanan', $product->lokasi_penyimpanan ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Kondisi Barang</label>
        <select name="kondisi_barang" class="form-select" required>
            @foreach (['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'] as $val => $label)
                <option value="{{ $val }}" @selected(old('kondisi_barang', $product->kondisi_barang ?? 'baik') == $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Gambar Barang</label>
        <input type="file" name="gambar" class="form-control" accept="image/*">
        @if (!empty($product?->gambar))
            <img src="{{ $product->imageUrl() }}" class="mt-2 rounded" style="height:80px;">
        @endif
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $product->deskripsi ?? '') }}</textarea>
    </div>
</div>
