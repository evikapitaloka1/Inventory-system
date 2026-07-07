@extends('layouts.app')

@section('title', 'Master Barang')
@section('page-title', 'Master Data Barang')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama, kode, atau lokasi..." style="min-width:240px;">
        <select name="category_id" class="form-select" style="min-width:180px;">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </form>

    @if (auth()->user()->hasRole('admin', 'staff'))
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Barang
        </a>
    @endif
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->kode_barang }}</td>
                        <td>
                            <div class="fw-semibold">{{ $product->nama_barang }}</div>
                            @if ($product->isLowStock())
                                <span class="badge text-bg-danger">Stok Menipis</span>
                            @endif
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->stok }}</td>
                        <td>{{ $product->lokasi_penyimpanan ?? '-' }}</td>
                        <td class="text-capitalize">{{ str_replace('_', ' ', $product->kondisi_barang) }}</td>
                        <td class="text-end">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            @if (auth()->user()->hasRole('admin', 'staff'))
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">Tidak ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">
        {{ $products->links() }}
    </div>
</div>

@endsection
