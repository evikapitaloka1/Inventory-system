@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')

@section('content')
<div class="card p-4" style="max-width:720px;">
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('products._form')
        <button class="btn btn-primary mt-3">Simpan Barang</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">Batal</a>
    </form>
</div>
@endsection
