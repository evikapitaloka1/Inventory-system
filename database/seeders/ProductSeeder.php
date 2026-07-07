<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $elektronik = Category::where('name', 'Elektronik')->first()->id;
        $furniture = Category::where('name', 'Furniture')->first()->id;
        $atk = Category::where('name', 'Alat Tulis Kantor')->first()->id;
        $jaringan = Category::where('name', 'Peralatan Jaringan')->first()->id;
        $kendaraan = Category::where('name', 'Kendaraan Operasional')->first()->id;

        $products = [
            ['kode_barang' => 'ELK-001', 'nama_barang' => 'Laptop Dell Latitude 5420', 'category_id' => $elektronik, 'stok' => 12, 'stok_minimum' => 3, 'lokasi_penyimpanan' => 'Gudang IT Lt. 2', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'ELK-002', 'nama_barang' => 'Proyektor Epson EB-X500', 'category_id' => $elektronik, 'stok' => 4, 'stok_minimum' => 2, 'lokasi_penyimpanan' => 'Ruang Meeting Lt. 3', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'ELK-003', 'nama_barang' => 'Kamera Digital Sony A6000', 'category_id' => $elektronik, 'stok' => 2, 'stok_minimum' => 2, 'lokasi_penyimpanan' => 'Gudang Media Lt. 1', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'FUR-001', 'nama_barang' => 'Kursi Kantor Ergonomis', 'category_id' => $furniture, 'stok' => 25, 'stok_minimum' => 5, 'lokasi_penyimpanan' => 'Gudang Umum Lt. 1', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'FUR-002', 'nama_barang' => 'Meja Lipat Portable', 'category_id' => $furniture, 'stok' => 8, 'stok_minimum' => 3, 'lokasi_penyimpanan' => 'Gudang Umum Lt. 1', 'kondisi_barang' => 'rusak_ringan'],
            ['kode_barang' => 'ATK-001', 'nama_barang' => 'Proyektor Portable Mini', 'category_id' => $atk, 'stok' => 3, 'stok_minimum' => 2, 'lokasi_penyimpanan' => 'Gudang ATK Lt. 1', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'ATK-002', 'nama_barang' => 'Whiteboard Magnetik 120x90', 'category_id' => $atk, 'stok' => 6, 'stok_minimum' => 2, 'lokasi_penyimpanan' => 'Gudang ATK Lt. 1', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'JAR-001', 'nama_barang' => 'Router MikroTik RB750', 'category_id' => $jaringan, 'stok' => 5, 'stok_minimum' => 2, 'lokasi_penyimpanan' => 'Server Room Lt. 4', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'JAR-002', 'nama_barang' => 'Switch Hub 24 Port', 'category_id' => $jaringan, 'stok' => 1, 'stok_minimum' => 2, 'lokasi_penyimpanan' => 'Server Room Lt. 4', 'kondisi_barang' => 'baik'],
            ['kode_barang' => 'KND-001', 'nama_barang' => 'Motor Operasional Honda Beat', 'category_id' => $kendaraan, 'stok' => 3, 'stok_minimum' => 1, 'lokasi_penyimpanan' => 'Parkiran Basement', 'kondisi_barang' => 'baik'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['kode_barang' => $product['kode_barang']], $product);
        }
    }
}
