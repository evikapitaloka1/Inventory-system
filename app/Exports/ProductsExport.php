<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with('category')->orderBy('nama_barang')->get();
    }

    public function headings(): array
    {
        return ['Kode Barang', 'Nama Barang', 'Kategori', 'Stok', 'Stok Minimum', 'Lokasi', 'Kondisi'];
    }

    public function map($product): array
    {
        return [
            $product->kode_barang,
            $product->nama_barang,
            $product->category->name,
            $product->stok,
            $product->stok_minimum,
            $product->lokasi_penyimpanan,
            $product->kondisi_barang,
        ];
    }
}
