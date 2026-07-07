@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')

@section('content')
<div class="card p-4" style="max-width:720px;">
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('products._form')
        <button class="btn btn-primary mt-3">Perbarui Barang</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">Batal</a>
    </form>
</div>
@endsection
